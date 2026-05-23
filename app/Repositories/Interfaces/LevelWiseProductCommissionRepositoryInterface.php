<?php

namespace App\Repositories\Interfaces;

use App\Models\LevelWiseProductCommission;
use Illuminate\Database\Eloquent\Collection;

interface LevelWiseProductCommissionRepositoryInterface
{
    /**
     * Get all commissions
     */
    public function all(): Collection;

    /**
     * Find commission by ID
     */
    public function find(int $id): ?LevelWiseProductCommission;

    /**
     * Find commission by product category ID
     */
    public function findByProductCategoryId(int $productCategoryId): ?LevelWiseProductCommission;

    /**
     * Create new commission
     */
    public function create(array $data): LevelWiseProductCommission;

    /**
     * Update commission
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete commission
     */
    public function delete(int $id): bool;

    /**
     * Get active commissions
     */
    public function getActive(): Collection;

    /**
     * Get commissions for DataTables
     */
    public function getForDataTable();

    /**
     * Toggle commission status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Check if commission exists for product category
     */
    public function existsForProductCategory(int $productCategoryId, ?int $excludeId = null): bool;
}
