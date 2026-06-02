<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VendorStockAlertsApiController extends Controller
{
    private function vendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

    public function remaining(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $select = ['id', 'name', 'category', 'stock', 'price'];
        if (Schema::hasColumn('products', 'distributor_price')) {
            $select[] = 'distributor_price';
        }

        $productsQuery = Product::query()->where('vendor_id', $vendor->id);

        $products = $productsQuery
            ->select($select)
            ->latest()
            ->get();

        $totalStock = (int) $productsQuery->sum('stock');
        $lowStockCount = (int) (clone $productsQuery)
            ->where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_stock' => $totalStock,
                'low_stock_count' => $lowStockCount,
                'products' => $products,
            ],
        ]);
    }

    public function reminders(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $threshold = (int) $request->query('threshold', 5);
        if ($threshold < 0) {
            $threshold = 5;
        }

        $reminders = Product::query()
            ->where('vendor_id', $vendor->id)
            ->where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'threshold' => $threshold,
                'reminders' => $reminders,
            ],
        ]);
    }

    public function restockAlerts(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $threshold = (int) $request->query('threshold', 5);
        $minToRestock = (int) $request->query('min_restock_qty', 10);

        $alerts = Product::query()
            ->where('vendor_id', $vendor->id)
            ->where('stock', '<=', $threshold)
            ->select(['id', 'name', 'category', 'stock'])
            ->get()
            ->map(function ($p) use ($threshold, $minToRestock) {
                $current = (int) $p->stock;
                $suggested = max($minToRestock, $threshold + 1 - $current);

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'category' => $p->category,
                    'current_stock' => $current,
                    'suggested_restock_qty' => (int) $suggested,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'threshold' => $threshold,
                'min_restock_qty' => $minToRestock,
                'restock_alerts' => $alerts,
            ],
        ]);
    }

    public function quantityBreakdown(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $q = Product::query()->where('vendor_id', $vendor->id);

        $b0 = (clone $q)->where('stock', '=', 0)->count();
        $b1 = (clone $q)->whereBetween('stock', [1, 5])->count();
        $b2 = (clone $q)->whereBetween('stock', [6, 20])->count();
        $b3 = (clone $q)->where('stock', '>=', 21)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'breakdown' => [
                    ['label' => '0', 'count' => (int) $b0],
                    ['label' => '1-5', 'count' => (int) $b1],
                    ['label' => '6-20', 'count' => (int) $b2],
                    ['label' => '21+', 'count' => (int) $b3],
                ],
                'total_products' => (int) ($q->count() ?? 0),
            ],
        ]);
    }
}

