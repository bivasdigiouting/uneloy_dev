<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    /** Get paginated products */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::orderBy('created_at', 'desc')->paginate($perPage);
    }

    /** Get active products */
    public function getActive(): Collection
    {
        return Product::where('is_active', true)->orderBy('name')->get();
    }

    /** Find product by ID */
    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    /** Create new product */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /** Update product */
    public function update(int $id, array $data): bool
    {
        $product = $this->find($id);
        if (! $product) {
            return false;
        }

        return $product->update($data);
    }

    /** Delete product */
    public function delete(int $id): bool
    {
        $product = $this->find($id);
        if (! $product) {
            return false;
        }

        return (bool) $product->delete();
    }

    /** Get products for DataTables */
    public function getForDataTable()
    {
        return Product::select([
            'id', 'name', 'category', 'price', 'mrp', 'distributor_price', 'image', 'gst_tax_id', 'is_active', 'created_at',
        ]);
    }

    /** Toggle product status */
    public function toggleStatus(int $id): bool
    {
        $product = $this->find($id);
        if (! $product) {
            return false;
        }
        $product->is_active = ! $product->is_active;

        return $product->save();
    }

    /** Get active products by category id */
    public function getByCategoryId(int $categoryId): Collection
    {
        return Product::query()
            ->leftJoin('product_categories', 'products.category', '=', 'product_categories.name')
            ->where('product_categories.id', $categoryId)
            ->where('products.is_active', true)
            ->select('products.*')
            ->orderBy('products.name')
            ->get();
    }
}
