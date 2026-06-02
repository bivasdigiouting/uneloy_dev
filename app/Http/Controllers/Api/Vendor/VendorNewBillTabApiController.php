<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ECardSale;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorNewBillTabApiController extends Controller
{
    private function vendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

    /**
     * GET /vendor/billing/new-bill/customers/search?q=...
     *
     * Uses ECardSale.customer_name as "customer" source (best-effort).
     */
    public function searchCustomers(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $q = trim((string) $request->query('q', ''));
        if ($q === '') {
            return response()->json([
                'success' => true,
                'data' => ['customers' => [], 'query' => $q],
            ]);
        }

        if (! Schema::hasTable('ecard_sales')) {
            return response()->json(['success' => false, 'message' => 'Sales table ecard_sales not found'], 500);
        }

        $customers = ECardSale::query()
            ->select('customer_name')
            ->where('customer_name', '!=', '')
            ->where('customer_name', 'like', "%{$q}%")
            ->where('user_id', $vendor->id)
            ->distinct()
            ->limit(20)
            ->pluck('customer_name')
            ->values()
            ->map(fn ($name, $idx) => [
                'id' => (string) $idx,
                'name' => $name,
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'customers' => $customers,
                'query' => $q,
            ],
        ]);
    }

    /**
     * GET /vendor/billing/new-bill/purchased-products?customer_name=...
     * Optional:
     *  - from=YYYY-MM-DD
     *  - to=YYYY-MM-DD
     *
     * Returns bill-ready lines + totals.
     */
    public function purchasedProducts(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $customerName = trim((string) $request->query('customer_name', ''));
        if ($customerName === '') {
            return response()->json(['success' => false, 'message' => 'customer_name is required'], 422);
        }

        $from = $request->query('from');
        $to = $request->query('to');

        if (! Schema::hasTable('ecard_sales') || ! Schema::hasTable('ecard_sale_items')) {
            return response()->json(['success' => false, 'message' => 'Sales tables not found (ecard_sales / ecard_sale_items)'], 500);
        }

        $q = DB::table('ecard_sales')
            ->join('ecard_sale_items', 'ecard_sale_items.ecard_sale_id', '=', 'ecard_sales.id')
            ->where('ecard_sales.customer_name', $customerName)
            ->where('ecard_sales.user_id', $vendor->id);

        if ($from) {
            $q->whereDate('ecard_sales.billing_date', '>=', Carbon::parse($from)->toDateString());
        }
        if ($to) {
            $q->whereDate('ecard_sales.billing_date', '<=', Carbon::parse($to)->toDateString());
        }

        // Fetch limited number of lines to avoid heavy responses.
        $rows = $q
            ->orderByDesc('ecard_sales.billing_date')
            ->limit(1000)
            ->select([
                'ecard_sales.id as sale_id',
                'ecard_sale_items.product_id',
                'ecard_sale_items.quantity',
                'ecard_sale_items.price',
                'ecard_sale_items.tax_amount',
                'ecard_sale_items.total_amount',
            ])
            ->get();

        $lines = [];
        $subtotal = 0.0;
        $tax = 0.0;
        $total = 0.0;

        foreach ($rows as $r) {
            $lineTotal = (float) ($r->total_amount ?? 0);
            $taxAmt = (float) ($r->tax_amount ?? 0);
            $sub = $lineTotal - $taxAmt;

            $subtotal += $sub;
            $tax += $taxAmt;
            $total += $lineTotal;

            $lines[] = [
                'sale_id' => (int) $r->sale_id,
                'product_id' => (int) $r->product_id,
                'quantity' => (int) $r->quantity,
                'price' => (float) $r->price,
                'tax_amount' => (float) $r->tax_amount,
                'total_amount' => (float) $r->total_amount,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => ['name' => $customerName],
                'filters' => ['from' => $from, 'to' => $to],
                'bill' => [
                    'lines' => $lines,
                    'totals' => [
                        'subtotal' => (float) round($subtotal, 2),
                        'tax' => (float) round($tax, 2),
                        'total' => (float) round($total, 2),
                    ],
                ],
            ],
        ]);
    }
}

