<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ECardSale;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class VendorNewBillApiController extends Controller
{
    private function vendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

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

        // Best-effort based on EcardSale (has customer_name).
        // If you later add a dedicated customer table, we can switch this logic.
        if (!Schema::hasTable('ecard_sales')) {
            return response()->json(['success' => false, 'message' => 'Sales table not found (ecard_sales)'], 500);
        }

        $customers = ECardSale::query()
            ->select(['customer_name'])
            ->where('customer_name', '!=', '')
            ->where('customer_name', 'like', "%{$q}%")
            ->where('user_id', '=', $vendor->id)
            ->distinct()
            ->limit(20)
            ->get()
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

    public function getPurchasedProductsForCustomer(Request $request, string $customerName)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $q = trim($customerName);
        if ($q === '') {
            return response()->json(['success' => false, 'message' => 'customer_name is required'], 422);
        }

        $from = $request->query('from');
        $to = $request->query('to');

        $salesQ = ECardSale::query()
            ->with(['items'])
            ->where('customer_name', $q)
            ->where('user_id', '=', $vendor->id);

        if ($from) {
            $salesQ->whereDate('billing_date', '>=', Carbon::parse($from)->toDateString());
        }
        if ($to) {
            $salesQ->whereDate('billing_date', '<=', Carbon::parse($to)->toDateString());
        }

        $sales = $salesQ
            ->orderByDesc('billing_date')
            ->limit(200)
            ->get();

        $lines = [];
        $totals = [
            'subtotal' => 0.0,
            'tax' => 0.0,
            'total' => 0.0,
        ];

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $line = [
                    'sale_id' => $sale->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name ?? null,
                    'quantity' => (int) $item->quantity,
                    'price' => (float) $item->price,
                    'tax_amount' => (float) $item->tax_amount,
                    'total_amount' => (float) $item->total_amount,
                ];

                // If item.product relation is not available, we still return product_id + computed totals.
                $lines[] = $line;

                $totals['subtotal'] += (float) $item->total_amount - (float) $item->tax_amount;
                $totals['tax'] += (float) $item->tax_amount;
                $totals['total'] += (float) $item->total_amount;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => [
                    'name' => $q,
                ],
                'filters' => [
                    'from' => $from,
                    'to' => $to,
                ],
                'bill' => [
                    'lines' => $lines,
                    'totals' => [
                        'subtotal' => (float) round($totals['subtotal'], 2),
                        'tax' => (float) round($totals['tax'], 2),
                        'total' => (float) round($totals['total'], 2),
                    ],
                ],
            ],
        ]);
    }
}

