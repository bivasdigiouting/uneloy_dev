<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class VoucherDetailsReportController extends Controller
{
    /**
     * Show the Voucher Details Report page.
     */
    public function index(Request $request)
    {
        return view('admin.reports.voucher-details.index');
    }

    /**
     * Data endpoint: voucher-focused order list with filters and totals.
     */
    public function data(Request $request)
    {
        $table = $this->detectOrderTable();

        if (! $table) {
            $emptySummary = [
                'give_voucher_amount' => 0,
                'apply_voucher_amount' => 0,
            ];

            return DataTables::of(collect([]))
                ->addIndexColumn()
                ->with('summary', $emptySummary)
                ->make(true);
        }

        $qb = $this->buildBaseQuery($table);
        $qb = $this->applyFilters($qb, $table, $request);
        $summary = $this->computeSummary($table, $request);

        return DataTables::of($qb)
            ->addIndexColumn()
            ->editColumn('order_date', function ($row) {
                $date = $row->order_date ?? null;
                if (! $date) {
                    return '';
                }
                try {
                    return date('d-M-Y', strtotime($date));
                } catch (\Exception $e) {
                    return (string) $date;
                }
            })
            ->addColumn('seller_display', function ($row) {
                $id = $row->seller_id ?? '';
                $name = $row->seller_name ?? '';
                if (! $id && ! $name) {
                    return '';
                }

                return trim($id.($name ? ' ('.$name.')' : ''));
            })
            ->addColumn('purchase_display', function ($row) {
                $id = $row->purchase_id ?? '';
                $name = $row->purchase_name ?? '';
                if (! $id && ! $name) {
                    return '';
                }

                return trim($id.($name ? ' ('.$name.')' : ''));
            })
            ->rawColumns(['seller_display', 'purchase_display'])
            ->with('summary', $summary)
            ->make(true);
    }

    private function detectOrderTable(): ?string
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('orders')) {
                return 'orders';
            }
            if (DB::getSchemaBuilder()->hasTable('user_orders')) {
                return 'user_orders';
            }
        } catch (\Exception $e) { /* ignore */
        }

        return null;
    }

    private function buildBaseQuery(string $table)
    {
        $qb = DB::table($table);

        // Core identifiers
        $qb->select([DB::raw("$table.id as id")]);
        $this->addSelect($qb, $table, 'order_no', 'order_no');
        $this->addSelect($qb, $table, 'order_number', 'order_no');
        $this->addSelect($qb, $table, 'order_date', 'order_date');
        $this->addSelect($qb, $table, 'seller_id', 'seller_id');
        $this->addSelect($qb, $table, 'buyer_id', 'purchase_id');
        $this->addSelect($qb, $table, 'purchase_id', 'purchase_id');

        // Voucher fields
        foreach ([
            'give_voucher_status', 'give_voucher_no', 'give_voucher_amount', 'give_voucher_expiry_date',
            'apply_voucher_status', 'apply_voucher_no', 'apply_voucher_amount',
            // Alternative column names
            'give_voucher_amt', 'give_voucher_ex_date', 'apply_voucher_amt',
        ] as $col) {
            if (Schema::hasColumn($table, $col)) {
                $qb->addSelect(DB::raw("$table.$col as $col"));
            }
        }

        // Join users for seller/purchase names
        try {
            $hasUsers = DB::getSchemaBuilder()->hasTable('users');
        } catch (\Exception $e) {
            $hasUsers = false;
        }
        if ($hasUsers) {
            if (Schema::hasColumn($table, 'seller_id')) {
                $qb->leftJoin('users as seller', "$table.seller_id", '=', 'seller.id')
                    ->addSelect(DB::raw('seller.name as seller_name'))
                    ->addSelect(DB::raw('seller.email as seller_email'));
            }
            if (Schema::hasColumn($table, 'buyer_id')) {
                $qb->leftJoin('users as buyer', "$table.buyer_id", '=', 'buyer.id')
                    ->addSelect(DB::raw('buyer.name as purchase_name'))
                    ->addSelect(DB::raw('buyer.email as purchase_email'));
            } elseif (Schema::hasColumn($table, 'purchase_id')) {
                $qb->leftJoin('users as buyer', "$table.purchase_id", '=', 'buyer.id')
                    ->addSelect(DB::raw('buyer.name as purchase_name'))
                    ->addSelect(DB::raw('buyer.email as purchase_email'));
            }
        } else {
            $qb->addSelect(DB::raw('NULL as seller_name'));
            $qb->addSelect(DB::raw('NULL as purchase_name'));
            $qb->addSelect(DB::raw('NULL as seller_email'));
            $qb->addSelect(DB::raw('NULL as purchase_email'));
        }

        return $qb;
    }

    private function addSelect($qb, string $table, string $col, string $alias)
    {
        if (Schema::hasColumn($table, $col)) {
            $qb->addSelect(DB::raw("$table.$col as $alias"));
        }
    }

    private function applyFilters($qb, string $table, Request $request)
    {
        // Date range on order_date
        if ($request->filled('from_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '<=', $request->input('to_date'));
        }

        // Give voucher status
        $giveVoucher = $request->input('give_voucher');
        if ($giveVoucher && $giveVoucher !== 'All' && Schema::hasColumn($table, 'give_voucher_status')) {
            $qb->where("$table.give_voucher_status", $giveVoucher);
        }

        // Apply voucher status
        $applyVoucher = $request->input('apply_voucher');
        if ($applyVoucher && $applyVoucher !== 'All' && Schema::hasColumn($table, 'apply_voucher_status')) {
            $qb->where("$table.apply_voucher_status", $applyVoucher);
        }

        // Voucher number search (matches both give/apply numbers)
        $voucherNo = trim((string) $request->input('voucher_no'));
        if ($voucherNo !== '') {
            $qb->where(function ($q) use ($voucherNo, $table) {
                if (Schema::hasColumn($table, 'give_voucher_no')) {
                    $q->orWhere("$table.give_voucher_no", 'like', "%$voucherNo%");
                }
                if (Schema::hasColumn($table, 'apply_voucher_no')) {
                    $q->orWhere("$table.apply_voucher_no", 'like', "%$voucherNo%");
                }
            });
        }

        // Text search across names/emails
        $search = trim((string) $request->input('search_text'));
        if ($search !== '') {
            $qb->where(function ($q) use ($search) {
                $q->orWhere('seller.name', 'like', "%$search%")
                    ->orWhere('seller.email', 'like', "%$search%")
                    ->orWhere('buyer.name', 'like', "%$search%")
                    ->orWhere('buyer.email', 'like', "%$search%");
            });
        }

        return $qb;
    }

    private function resolveCol(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }

        return null;
    }

    private function computeSummary(string $table, Request $request): array
    {
        $colGiveVoucherAmt = $this->resolveCol($table, ['give_voucher_amount', 'give_voucher_amt']);
        $colApplyVoucherAmt = $this->resolveCol($table, ['apply_voucher_amount', 'apply_voucher_amt']);

        $selects = [DB::raw('0 as give_voucher_amount'), DB::raw('0 as apply_voucher_amount')];
        if ($colGiveVoucherAmt) {
            $selects[0] = DB::raw("COALESCE(SUM($table.$colGiveVoucherAmt),0) as give_voucher_amount");
        }
        if ($colApplyVoucherAmt) {
            $selects[1] = DB::raw("COALESCE(SUM($table.$colApplyVoucherAmt),0) as apply_voucher_amount");
        }

        $qb = DB::table($table)->select($selects);

        // Apply identical date/status filters to summary
        if ($request->filled('from_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '<=', $request->input('to_date'));
        }
        $giveVoucher = $request->input('give_voucher');
        if ($giveVoucher && $giveVoucher !== 'All' && Schema::hasColumn($table, 'give_voucher_status')) {
            $qb->where("$table.give_voucher_status", $giveVoucher);
        }
        $applyVoucher = $request->input('apply_voucher');
        if ($applyVoucher && $applyVoucher !== 'All' && Schema::hasColumn($table, 'apply_voucher_status')) {
            $qb->where("$table.apply_voucher_status", $applyVoucher);
        }

        $res = $qb->first();

        return [
            'give_voucher_amount' => (float) ($res->give_voucher_amount ?? 0),
            'apply_voucher_amount' => (float) ($res->apply_voucher_amount ?? 0),
        ];
    }
}
