<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class CashBackReportController extends Controller
{
    /**
     * Show the Cashback Report page.
     */
    public function index(Request $request)
    {
        return view('admin.reports.cashback.index');
    }

    /**
     * Data endpoint: order-centric cashback list with filters and totals.
     */
    public function data(Request $request)
    {
        $table = $this->detectOrderTable();

        if (! $table) {
            $emptySummary = [
                'total_cashback' => 0,
                'avg_cashback' => 0,
                'cashback_count' => 0,
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
            ->editColumn('cashback_amount', function ($row) {
                $v = $row->cashback_amount ?? 0;
                if ($v === null) {
                    $v = 0;
                }

                return number_format((float) $v, 2);
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

        // Cashback fields (prefer a normalized alias: cashback_amount)
        $cashbackCol = $this->resolveCol($table, ['cashback_amount', 'cashback', 'cashback_amt']);
        if ($cashbackCol) {
            $qb->addSelect(DB::raw("$table.$cashbackCol as cashback_amount"));
        } else {
            $qb->addSelect(DB::raw('NULL as cashback_amount'));
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

        // Order number search
        $orderNo = trim((string) $request->input('order_no'));
        if ($orderNo !== '') {
            $qb->where(function ($q) use ($orderNo, $table) {
                if (Schema::hasColumn($table, 'order_no')) {
                    $q->orWhere("$table.order_no", 'like', "%$orderNo%");
                }
                if (Schema::hasColumn($table, 'order_number')) {
                    $q->orWhere("$table.order_number", 'like', "%$orderNo%");
                }
            });
        }

        // Cashback min/max
        $cashbackCol = $this->resolveCol($table, ['cashback_amount', 'cashback', 'cashback_amt']);
        if ($cashbackCol) {
            if ($request->filled('min_cashback')) {
                $qb->where("$table.$cashbackCol", '>=', (float) $request->input('min_cashback'));
            }
            if ($request->filled('max_cashback')) {
                $qb->where("$table.$cashbackCol", '<=', (float) $request->input('max_cashback'));
            }
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
        $colCashbackAmt = $this->resolveCol($table, ['cashback_amount', 'cashback', 'cashback_amt']);

        // Fallback detection tables for total/avg/count when order table lacks columns
        $fallbackTable = null;
        $fallbackCol = null;
        try {
            if (! $colCashbackAmt && DB::getSchemaBuilder()->hasTable('user_cashbacks')) {
                $fallbackTable = 'user_cashbacks';
                $fallbackCol = 'cashback_amount';
            } elseif (! $colCashbackAmt && DB::getSchemaBuilder()->hasTable('cashbacks')) {
                $fallbackTable = 'cashbacks';
                $fallbackCol = 'amount';
            }
        } catch (\Exception $e) { /* ignore */
        }

        if ($colCashbackAmt) {
            $qb = DB::table($table)->select([
                DB::raw("COALESCE(SUM($table.$colCashbackAmt),0) as total_cashback"),
                DB::raw("COALESCE(AVG(NULLIF($table.$colCashbackAmt,0)),0) as avg_cashback"),
                DB::raw("SUM(CASE WHEN $table.$colCashbackAmt > 0 THEN 1 ELSE 0 END) as cashback_count"),
            ]);

            if ($request->filled('from_date') && Schema::hasColumn($table, 'order_date')) {
                $qb->whereDate("$table.order_date", '>=', $request->input('from_date'));
            }
            if ($request->filled('to_date') && Schema::hasColumn($table, 'order_date')) {
                $qb->whereDate("$table.order_date", '<=', $request->input('to_date'));
            }
            if ($request->filled('min_cashback')) {
                $qb->where("$table.$colCashbackAmt", '>=', (float) $request->input('min_cashback'));
            }
            if ($request->filled('max_cashback')) {
                $qb->where("$table.$colCashbackAmt", '<=', (float) $request->input('max_cashback'));
            }

            $res = $qb->first();

            return [
                'total_cashback' => (float) ($res->total_cashback ?? 0),
                'avg_cashback' => (float) ($res->avg_cashback ?? 0),
                'cashback_count' => (int) ($res->cashback_count ?? 0),
            ];
        }

        if ($fallbackTable && $fallbackCol) {
            $res = DB::table($fallbackTable)->select([
                DB::raw("COALESCE(SUM($fallbackTable.$fallbackCol),0) as total_cashback"),
                DB::raw("COALESCE(AVG(NULLIF($fallbackTable.$fallbackCol,0)),0) as avg_cashback"),
                DB::raw("SUM(CASE WHEN $fallbackTable.$fallbackCol > 0 THEN 1 ELSE 0 END) as cashback_count"),
            ])->first();

            return [
                'total_cashback' => (float) ($res->total_cashback ?? 0),
                'avg_cashback' => (float) ($res->avg_cashback ?? 0),
                'cashback_count' => (int) ($res->cashback_count ?? 0),
            ];
        }

        return [
            'total_cashback' => 0,
            'avg_cashback' => 0,
            'cashback_count' => 0,
        ];
    }
}
