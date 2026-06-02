<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class VendorInventoryApiController extends Controller
{
    private function getVendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

    public function summary(Request $request)
    {
        $vendor = $this->getVendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Only compute from products table; use schema checks for safety.
        $base = Product::query()->where('vendor_id', $vendor->id);

        $totalProducts = (int) $base->clone()->count();
        $totalItemsQuantity = (int) ($base->clone()->sum('stock') ?? 0);

        // Valuations: both price and distributor_price, if those columns exist.
        $totalValueSelling = 0.0;
        $totalValueDistributor = 0.0;

        if (Schema::hasColumn('products', 'price')) {
            $totalValueSelling = (float) $base
                ->clone()
                ->select(DB::raw('SUM(stock * price) as total'))
                ->value('total') ?? 0;
        }

        if (Schema::hasColumn('products', 'distributor_price')) {
            $totalValueDistributor = (float) $base
                ->clone()
                ->select(DB::raw('SUM(stock * distributor_price) as total'))
                ->value('total') ?? 0;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => $totalProducts,
                'total_items_quantity' => $totalItemsQuantity,
                'total_value' => [
                    'selling_price' => (float) $totalValueSelling,
                    'distributor_price' => (float) $totalValueDistributor,
                ],
            ],
        ]);
    }

    /**
     * Stock list of all products for vendor with full product details + per-product stock.
     *
     * Query params:
     * - q (string): search by name/detail/category/brand
     * - category (string)
     * - brand (string)
     * - min_stock / max_stock (int)
     * - page (int)
     * - per_page (int)
     */
    public function stockList(Request $request)
    {
        $vendor = $this->getVendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $q = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $brand = trim((string) $request->query('brand', ''));

        $minStock = $request->query('min_stock');
        $maxStock = $request->query('max_stock');

        $page = (int) $request->query('page', 1);
        if ($page < 1) {
            $page = 1;
        }

        $perPage = (int) $request->query('per_page', 20);
        if ($perPage < 1) {
            $perPage = 20;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $select = ['id', 'name', 'detail', 'description', 'price', 'mrp', 'distributor_price', 'stock', 'image', 'vendor_id', 'category', 'brand', 'sku', 'weight', 'dimensions', 'attributes', 'is_active', 'is_featured', 'admin_status', 'created_at', 'updated_at'];

        // Keep only columns that exist.
        $columnsToKeep = [];
        foreach ($select as $col) {
            if (Schema::hasColumn('products', $col)) {
                $columnsToKeep[] = $col;
            }
        }

        $query = Product::query()->where('vendor_id', $vendor->id);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('detail', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        if ($brand !== '') {
            $query->where('brand', $brand);
        }

        if ($minStock !== null && $minStock !== '') {
            $query->where('stock', '>=', (int) $minStock);
        }

        if ($maxStock !== null && $maxStock !== '') {
            $query->where('stock', '<=', (int) $maxStock);
        }

        $totalProducts = (int) $query->clone()->count();
        $totalItemsQuantity = (int) ($query->clone()->sum('stock') ?? 0);

        $paginator = $query
            ->select($columnsToKeep)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => [
                'filters' => [
                    'q' => $q,
                    'category' => $category,
                    'brand' => $brand,
                    'min_stock' => $minStock !== null && $minStock !== '' ? (int) $minStock : null,
                    'max_stock' => $maxStock !== null && $maxStock !== '' ? (int) $maxStock : null,
                ],
                'totals' => [
                    'total_products' => $totalProducts,
                    'total_items_quantity' => $totalItemsQuantity,
                ],
                'pagination' => [
                    'page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ],
                'products' => $paginator->items(),
            ],
        ]);
    }
}


