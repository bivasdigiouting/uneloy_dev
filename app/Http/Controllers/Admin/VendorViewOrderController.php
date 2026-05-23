<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\DataTables;

class VendorViewOrderController extends Controller
{
    public function index()
    {
        return view('admin.vendor_orders.index');
    }

    public function data(Request $request)
    {
        $table = $this->detectOrderTable();
        if (! $table) {
            return DataTables::of(collect())->make(true);
        }

        $query = $this->buildBaseQuery($table);
        $query = $this->applyFilters($query, $table, $request);

        // Clone query for summary before pagination
        $summaryQuery = clone $query;
        $summary = $this->computeSummary($summaryQuery, $table);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('order_date', function ($row) {
                return isset($row->order_date) ? date('Y-m-d H:i', strtotime($row->order_date)) : '';
            })
            ->addColumn('seller_display', function ($row) {
                $sellerId = $row->seller_id ?? null;
                $sellerName = $row->seller_name ?? '';

                return $sellerId ? ($sellerId.' ('.$sellerName.')') : '';
            })
            ->addColumn('purchase_display', function ($row) {
                $buyerId = $row->buyer_id ?? null;
                $buyerName = $row->buyer_name ?? '';

                return $buyerId ? ($buyerId.' ('.$buyerName.')') : '';
            })
            ->addColumn('action', function ($row) {
                return '';
            })
            ->with(['summary' => $summary])
            ->make(true);
    }

    private function detectOrderTable(): ?string
    {
        // Prefer vendor-specific orders table if present
        if (Schema::hasTable('vendor_orders')) {
            return 'vendor_orders';
        }
        if (Schema::hasTable('orders')) {
            return 'orders';
        }
        if (Schema::hasTable('user_orders')) {
            return 'user_orders';
        }

        return null;
    }

    private function buildBaseQuery(string $table)
    {
        $query = DB::table($table);

        // Base selects that commonly exist across tables
        $selects = [];
        foreach ([
            'id', 'order_no', 'order_date', 'seller_id', 'buyer_id', 'item_count', 'total_qty',
            'billing_amount', 'discount_amount', 'cashback_amount',
            'give_coupon_status', 'give_coupon_no', 'apply_coupon_status', 'apply_coupon_no', 'apply_coupon_amount',
            'give_voucher_status', 'give_voucher_no', 'give_voucher_amount', 'give_voucher_expiry_date',
            'apply_voucher_status', 'apply_voucher_no', 'apply_voucher_amount',
            'give_points_status', 'give_points_no', 'apply_points_status', 'apply_points_no', 'total_points',
        ] as $col) {
            if (Schema::hasColumn($table, $col)) {
                $selects[] = $table.'.'.$col;
            }
        }

        // Join buyer (users)
        if (Schema::hasTable('users') && Schema::hasColumn($table, 'buyer_id')) {
            $query->leftJoin('users as buyer', $table.'.buyer_id', '=', 'buyer.id');
            $selects[] = DB::raw('buyer.id as buyer_id');
            $selects[] = DB::raw('buyer.name as buyer_name');
            $selects[] = DB::raw('buyer.email as buyer_email');
        }

        // Join seller as vendor; require vendor presence to ensure vendor-only orders
        if (Schema::hasTable('vendors') && Schema::hasColumn($table, 'seller_id')) {
            $query->join('vendors as seller_v', $table.'.seller_id', '=', 'seller_v.id');
            $selects[] = DB::raw('seller_v.id as seller_id');
            // Prefer business_name, fall back to contact_person
            $selects[] = DB::raw('COALESCE(seller_v.business_name, seller_v.contact_person) as seller_name');
        } elseif (Schema::hasTable('users') && Schema::hasColumn($table, 'seller_id')) {
            // Fallback to users if vendors table is not present
            $query->leftJoin('users as seller', $table.'.seller_id', '=', 'seller.id');
            $selects[] = DB::raw('seller.id as seller_id');
            $selects[] = DB::raw('seller.name as seller_name');
        }

        $query->select($selects);

        return $query;
    }

    private function applyFilters($query, string $table, Request $request)
    {
        if ($request->filled('from_date') && Schema::hasColumn($table, 'order_date')) {
            $query->whereDate($table.'.order_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date') && Schema::hasColumn($table, 'order_date')) {
            $query->whereDate($table.'.order_date', '<=', $request->input('to_date'));
        }

        // Give Option (coupon) filter
        if ($request->filled('give_option') && Schema::hasColumn($table, 'give_coupon_status')) {
            $val = $request->input('give_option');
            if ($val === 'Yes') {
                $query->where($table.'.give_coupon_status', '=', 'Yes');
            } elseif ($val === 'No') {
                $query->where($table.'.give_coupon_status', '=', 'No');
            }
        }

        // Give Voucher filter
        if ($request->filled('give_voucher') && Schema::hasColumn($table, 'give_voucher_status')) {
            $val = $request->input('give_voucher');
            if ($val === 'Yes') {
                $query->where($table.'.give_voucher_status', '=', 'Yes');
            } elseif ($val === 'No') {
                $query->where($table.'.give_voucher_status', '=', 'No');
            }
        }

        // Give Points filter
        if ($request->filled('give_points') && Schema::hasColumn($table, 'give_points_status')) {
            $val = $request->input('give_points');
            if ($val === 'Yes') {
                $query->where($table.'.give_points_status', '=', 'Yes');
            } elseif ($val === 'No') {
                $query->where($table.'.give_points_status', '=', 'No');
            }
        }

        // Text search across IDs, names, emails
        if ($request->filled('search_text')) {
            $text = trim($request->input('search_text'));
            $query->where(function ($q) use ($text, $table) {
                // Order ID
                if (Schema::hasColumn($table, 'id') && is_numeric($text)) {
                    $q->orWhere($table.'.id', intval($text));
                }
                // Seller (vendor) name
                if (Schema::hasTable('vendors')) {
                    $q->orWhere('seller_v.business_name', 'like', "%$text%")
                        ->orWhere('seller_v.contact_person', 'like', "%$text%");
                } else {
                    // Fallback to users table if seller joined as users
                    $q->orWhere('seller.name', 'like', "%$text%")
                        ->orWhere('seller.email', 'like', "%$text%");
                }
                // Buyer name/email
                if (Schema::hasTable('users')) {
                    $q->orWhere('buyer.name', 'like', "%$text%")
                        ->orWhere('buyer.email', 'like', "%$text%");
                }
            });
        }

        return $query;
    }

    private function computeSummary($query, string $table): array
    {
        $summary = [
            'billing_amount' => 0,
            'discount_amount' => 0,
            'cashback_amount' => 0,
            'apply_coupon_amount' => 0,
            'give_voucher_amount' => 0,
            'apply_voucher_amount' => 0,
            'total_points' => 0,
        ];

        $row = $query->cloneWithout(['columns', 'orders', 'limit', 'offset'])->select([
            Schema::hasColumn($table, 'billing_amount') ? DB::raw("SUM($table.billing_amount) as billing_amount") : DB::raw('0 as billing_amount'),
            Schema::hasColumn($table, 'discount_amount') ? DB::raw("SUM($table.discount_amount) as discount_amount") : DB::raw('0 as discount_amount'),
            Schema::hasColumn($table, 'cashback_amount') ? DB::raw("SUM($table.cashback_amount) as cashback_amount") : DB::raw('0 as cashback_amount'),
            Schema::hasColumn($table, 'apply_coupon_amount') ? DB::raw("SUM($table.apply_coupon_amount) as apply_coupon_amount") : DB::raw('0 as apply_coupon_amount'),
            Schema::hasColumn($table, 'give_voucher_amount') ? DB::raw("SUM($table.give_voucher_amount) as give_voucher_amount") : DB::raw('0 as give_voucher_amount'),
            Schema::hasColumn($table, 'apply_voucher_amount') ? DB::raw("SUM($table.apply_voucher_amount) as apply_voucher_amount") : DB::raw('0 as apply_voucher_amount'),
            Schema::hasColumn($table, 'total_points') ? DB::raw("SUM($table.total_points) as total_points") : DB::raw('0 as total_points'),
        ])->first();

        if ($row) {
            foreach ($summary as $key => $val) {
                $summary[$key] = (float) ($row->$key ?? 0);
            }
        }

        return $summary;
    }
}
