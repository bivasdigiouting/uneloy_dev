<?php

namespace App\Repositories;

use App\Models\LevelWiseProductCommission;
use App\Repositories\Interfaces\LevelWiseProductCommissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LevelWiseProductCommissionRepository implements LevelWiseProductCommissionRepositoryInterface
{
    protected $model;

    public function __construct(LevelWiseProductCommission $model)
    {
        $this->model = $model;
    }

    /**
     * Get all commissions
     */
    public function all(): Collection
    {
        return $this->model->with('productCategory')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Find commission by ID
     */
    public function find(int $id): ?LevelWiseProductCommission
    {
        return $this->model->with('productCategory')->find($id);
    }

    /**
     * Find commission by product category ID
     */
    public function findByProductCategoryId(int $productCategoryId): ?LevelWiseProductCommission
    {
        return $this->model->where('product_category_id', $productCategoryId)->first();
    }

    /**
     * Create new commission
     */
    public function create(array $data): LevelWiseProductCommission
    {
        return $this->model->create($data);
    }

    /**
     * Update commission
     */
    public function update(int $id, array $data): bool
    {
        $commission = $this->find($id);
        if (! $commission) {
            return false;
        }

        return $commission->update($data);
    }

    /**
     * Delete commission
     */
    public function delete(int $id): bool
    {
        $commission = $this->find($id);
        if (! $commission) {
            return false;
        }

        return $commission->delete();
    }

    /**
     * Get active commissions
     */
    public function getActive(): Collection
    {
        return $this->model->active()->with('productCategory')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get commissions for DataTables
     */
    public function getForDataTable()
    {
        return $this->model->with('productCategory')
            ->select([
                'id',
                'product_category_id',
                'state_member_commission',
                'district_member_commission',
                'block_member_commission',
                'panchayat_member_commission',
                'village_member_commission',
                'customer_commission',
                'is_active',
                'created_at',
            ])
            ->orderBy('created_at', 'desc');
    }

    /**
     * Toggle commission status
     */
    public function toggleStatus(int $id): bool
    {
        $commission = $this->find($id);
        if (! $commission) {
            return false;
        }

        return $commission->update(['is_active' => ! $commission->is_active]);
    }

    /**
     * Check if commission exists for product category
     */
    public function existsForProductCategory(int $productCategoryId, ?int $excludeId = null): bool
    {
        $query = $this->model->where('product_category_id', $productCategoryId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
