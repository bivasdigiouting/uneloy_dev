<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /** Get paginated products */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /** Get active products */
    public function getActive(): Collection;

    /** Find product by ID */
    public function find(int $id): ?Product;

    /** Create new product */
    public function create(array $data): Product;

    /** Update product */
    public function update(int $id, array $data): bool;

    /** Delete product */
    public function delete(int $id): bool;

    /** Get products for DataTables */
    public function getForDataTable();

    /** Toggle product status */
    public function toggleStatus(int $id): bool;

    /** Get active products by category id */
    public function getByCategoryId(int $categoryId): Collection;
}
