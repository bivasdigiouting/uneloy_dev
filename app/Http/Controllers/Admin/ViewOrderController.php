<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class ViewOrderController extends Controller
{
    /**
     * Show the View Order page.
     */
    public function index(Request $request)
    {
        return view('admin.orders.index');
    }

    /**
     * DataTables endpoint: Orders list with filters and totals summary.
     */
    public function data(Request $request)
    {
        $table = $this->detectOrderTable();

        // If no orders table exists, return empty dataset with zero summary
        if (! $table) {
            $emptySummary = [
                'billing_amount' => 0,
                'discount_amount' => 0,
                'cashback_amount' => 0,
                'apply_coupon_amount' => 0,
                'give_voucher_amount' => 0,
                'apply_voucher_amount' => 0,
                'total_points' => 0,
            ];

            return DataTables::of(collect([]))
                ->addIndexColumn()
                ->with('summary', $emptySummary)
                ->make(true);
        }

        $query = $this->buildBaseQuery($table);
        $query = $this->applyFilters($query, $table, $request);

        $summary = $this->computeSummary($table, $request);

        return DataTables::of($query)
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
            ->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-primary" disabled>View</button>';
            })
            ->rawColumns(['action'])
            ->with('summary', $summary)
            ->make(true);
    }

    /**
     * Detect the available orders table.
     */
    private function detectOrderTable(): ?string
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('orders')) {
                return 'orders';
            }
            if (DB::getSchemaBuilder()->hasTable('user_orders')) {
                return 'user_orders';
            }
        } catch (\Exception $e) {
            // ignore
        }

        return null;
    }

    /**
     * Build a base query selecting common fields as best-effort.
     */
    private function buildBaseQuery(string $table)
    {
        $qb = DB::table($table);

        // Select core identifiers
        $selects = [];
        $selects[] = "$table.id as id";
        $selects[] = $this->selectCol($table, 'order_no', 'order_no');
        $selects[] = $this->selectCol($table, 'order_number', 'order_no'); // alt mapping
        $selects[] = $this->selectCol($table, 'order_date', 'order_date');

        // Seller & Purchase IDs
        $selects[] = $this->selectCol($table, 'seller_id', 'seller_id');
        $selects[] = $this->selectCol($table, 'buyer_id', 'purchase_id');
        $selects[] = $this->selectCol($table, 'purchase_id', 'purchase_id');

        // Basic metrics
        $selects[] = $this->selectCol($table, 'item_count', 'item_count');
        $selects[] = $this->selectCol($table, 'no_of_items', 'item_count');
        $selects[] = $this->selectCol($table, 'total_qty', 'total_qty');

        // Amount columns
        $selects[] = $this->selectCol($table, 'billing_amount', 'billing_amount');
        $selects[] = $this->selectCol($table, 'discount_amount', 'discount_amount');
        $selects[] = $this->selectCol($table, 'discount_amt', 'discount_amount');
        $selects[] = $this->selectCol($table, 'cashback_amount', 'cashback_amount');
        $selects[] = $this->selectCol($table, 'cashback_amt', 'cashback_amount');

        // Coupons
        $selects[] = $this->selectCol($table, 'give_coupon_status', 'give_coupon_status');
        $selects[] = $this->selectCol($table, 'give_coupon_no', 'give_coupon_no');
        $selects[] = $this->selectCol($table, 'apply_coupon_status', 'apply_coupon_status');
        $selects[] = $this->selectCol($table, 'apply_coupon_no', 'apply_coupon_no');
        $selects[] = $this->selectCol($table, 'apply_coupon_amount', 'apply_coupon_amount');
        $selects[] = $this->selectCol($table, 'apply_coupon_amt', 'apply_coupon_amount');

        // Vouchers
        $selects[] = $this->selectCol($table, 'give_voucher_status', 'give_voucher_status');
        $selects[] = $this->selectCol($table, 'give_voucher_no', 'give_voucher_no');
        $selects[] = $this->selectCol($table, 'give_voucher_amount', 'give_voucher_amount');
        $selects[] = $this->selectCol($table, 'give_voucher_amt', 'give_voucher_amount');
        $selects[] = $this->selectCol($table, 'give_voucher_expiry_date', 'give_voucher_expiry_date');
        $selects[] = $this->selectCol($table, 'give_voucher_ex_date', 'give_voucher_expiry_date');
        $selects[] = $this->selectCol($table, 'apply_voucher_status', 'apply_voucher_status');
        $selects[] = $this->selectCol($table, 'apply_voucher_no', 'apply_voucher_no');
        $selects[] = $this->selectCol($table, 'apply_voucher_amount', 'apply_voucher_amount');
        $selects[] = $this->selectCol($table, 'apply_voucher_amt', 'apply_voucher_amount');

        // Points
        $selects[] = $this->selectCol($table, 'give_points_status', 'give_points_status');
        $selects[] = $this->selectCol($table, 'give_points_no', 'give_points_no');
        $selects[] = $this->selectCol($table, 'apply_points_status', 'apply_points_status');
        $selects[] = $this->selectCol($table, 'apply_points_no', 'apply_points_no');
        $selects[] = $this->selectCol($table, 'total_points', 'total_points');

        // Apply selects to QB
        foreach ($selects as $sel) {
            if ($sel) {
                $qb->addSelect($sel);
            }
        }

        // Join users table for seller and purchase names if available
        try {
            $hasUsers = DB::getSchemaBuilder()->hasTable('users');
        } catch (\Exception $e) {
            $hasUsers = false;
        }
        if ($hasUsers) {
            if (Schema::hasColumn($table, 'seller_id')) {
                $qb->leftJoin('users as seller', "$table.seller_id", '=', 'seller.id')
                    ->addSelect(DB::raw('seller.name as seller_name'));
            }
            if (Schema::hasColumn($table, 'buyer_id')) {
                $qb->leftJoin('users as buyer', "$table.buyer_id", '=', 'buyer.id')
                    ->addSelect(DB::raw('buyer.name as purchase_name'));
            } elseif (Schema::hasColumn($table, 'purchase_id')) {
                $qb->leftJoin('users as buyer', "$table.purchase_id", '=', 'buyer.id')
                    ->addSelect(DB::raw('buyer.name as purchase_name'));
            }
        } else {
            // Still provide name aliases to avoid undefined properties
            $qb->addSelect(DB::raw('NULL as seller_name'));
            $qb->addSelect(DB::raw('NULL as purchase_name'));
        }

        return $qb;
    }

    /**
     * Apply filters from request.
     */
    private function applyFilters($qb, string $table, Request $request)
    {
        // Date range filters (order_date)
        if ($request->filled('from_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '<=', $request->input('to_date'));
        }

        // Give Option (assumed give_coupon_status)
        $giveOption = $request->input('give_option');
        if ($giveOption && $giveOption !== 'All' && Schema::hasColumn($table, 'give_coupon_status')) {
            $qb->where("$table.give_coupon_status", $giveOption);
        }

        // Give Voucher status
        $giveVoucher = $request->input('give_voucher');
        if ($giveVoucher && $giveVoucher !== 'All' && Schema::hasColumn($table, 'give_voucher_status')) {
            $qb->where("$table.give_voucher_status", $giveVoucher);
        }

        // Give Points status
        $givePoints = $request->input('give_points');
        if ($givePoints && $givePoints !== 'All' && Schema::hasColumn($table, 'give_points_status')) {
            $qb->where("$table.give_points_status", $givePoints);
        }

        // Search by id/name/email
        $search = trim((string) $request->input('search_text'));
        if ($search !== '') {
            $qb->where(function ($q) use ($search, $table) {
                // ID exact match
                if (Schema::hasColumn($table, 'id') && is_numeric($search)) {
                    $q->orWhere("$table.id", (int) $search);
                }
                // Order number
                if (Schema::hasColumn($table, 'order_no')) {
                    $q->orWhere("$table.order_no", 'like', "%$search%");
                } elseif (Schema::hasColumn($table, 'order_number')) {
                    $q->orWhere("$table.order_number", 'like', "%$search%");
                }
                // Seller name
                $q->orWhere('seller.name', 'like', "%$search%");
                // Buyer name
                $q->orWhere('buyer.name', 'like', "%$search%");
                // Buyer email
                $q->orWhere('buyer.email', 'like', "%$search%");
                // Seller email
                $q->orWhere('seller.email', 'like', "%$search%");
            });
        }

        return $qb;
    }

    /**
     * Compute totals for monetary columns across filtered dataset.
     */
    private function computeSummary(string $table, Request $request): array
    {
        $base = $this->buildBaseQuery($table);
        $base = $this->applyFilters($base, $table, $request);

        // Build dynamic SUM selects
        $sumSelects = [];
        $colBilling = $this->resolveCol($table, ['billing_amount']);
        $colDiscount = $this->resolveCol($table, ['discount_amount', 'discount_amt']);
        $colCashback = $this->resolveCol($table, ['cashback_amount', 'cashback_amt']);
        $colApplyCouponAmt = $this->resolveCol($table, ['apply_coupon_amount', 'apply_coupon_amt']);
        $colGiveVoucherAmt = $this->resolveCol($table, ['give_voucher_amount', 'give_voucher_amt']);
        $colApplyVoucherAmt = $this->resolveCol($table, ['apply_voucher_amount', 'apply_voucher_amt']);
        $colTotalPoints = $this->resolveCol($table, ['total_points']);

        if ($colBilling) {
            $sumSelects[] = "COALESCE(SUM($table.$colBilling),0) as billing_amount";
        }
        if ($colDiscount) {
            $sumSelects[] = "COALESCE(SUM($table.$colDiscount),0) as discount_amount";
        }
        if ($colCashback) {
            $sumSelects[] = "COALESCE(SUM($table.$colCashback),0) as cashback_amount";
        }
        if ($colApplyCouponAmt) {
            $sumSelects[] = "COALESCE(SUM($table.$colApplyCouponAmt),0) as apply_coupon_amount";
        }
        if ($colGiveVoucherAmt) {
            $sumSelects[] = "COALESCE(SUM($table.$colGiveVoucherAmt),0) as give_voucher_amount";
        }
        if ($colApplyVoucherAmt) {
            $sumSelects[] = "COALESCE(SUM($table.$colApplyVoucherAmt),0) as apply_voucher_amount";
        }
        if ($colTotalPoints) {
            $sumSelects[] = "COALESCE(SUM($table.$colTotalPoints),0) as total_points";
        }

        $summary = [
            'billing_amount' => 0,
            'discount_amount' => 0,
            'cashback_amount' => 0,
            'apply_coupon_amount' => 0,
            'give_voucher_amount' => 0,
            'apply_voucher_amount' => 0,
            'total_points' => 0,
        ];

        if (! empty($sumSelects)) {
            try {
                $row = (clone $base)->selectRaw(implode(', ', $sumSelects))->first();
                foreach ($summary as $k => $v) {
                    if (isset($row->$k)) {
                        $summary[$k] = (float) $row->$k;
                    }
                }
            } catch (\Exception $e) {
                // ignore, keep zeros
            }
        }

        return $summary;
    }

    /**
     * Helper: Resolve first available column from list.
     */
    private function resolveCol(string $table, array $candidates): ?string
    {
        foreach ($candidates as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }

        return null;
    }

    /**
     * Helper: Build select string or NULL alias if missing.
     */
    private function selectCol(string $table, string $column, string $alias)
    {
        if (Schema::hasColumn($table, $column)) {
            return "$table.$column as $alias";
        }

        return DB::raw("NULL as $alias");
    }
}
