<?php

namespace App\Repositories;

use App\Models\Affiliate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AffiliateRepository implements AffiliateRepositoryInterface
{
    protected Affiliate $model;

    public function __construct(Affiliate $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated affiliates
     */
    public function getPaginatedAffiliates(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->ordered()->paginate($perPage);
    }

    /**
     * Get active affiliates
     */
    public function getActiveAffiliates(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Find affiliate by ID
     */
    public function findAffiliate(int $id): ?Affiliate
    {
        return $this->model->find($id);
    }

    /**
     * Create new affiliate
     */
    public function createAffiliate(array $data): Affiliate
    {
        return $this->model->create($data);
    }

    /**
     * Update affiliate
     */
    public function updateAffiliate(int $id, array $data): bool
    {
        $affiliate = $this->findAffiliate($id);

        if (! $affiliate) {
            return false;
        }

        return $affiliate->update($data);
    }

    /**
     * Delete affiliate
     */
    public function deleteAffiliate(int $id): bool
    {
        $affiliate = $this->findAffiliate($id);

        if (! $affiliate) {
            return false;
        }

        return $affiliate->delete();
    }

    /**
     * Get affiliates for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select(['id', 'service_name', 'icon', 'status', 'created_at'])
            ->ordered();
    }

    /**
     * Toggle affiliate status
     */
    public function toggleStatus(int $id): bool
    {
        $affiliate = $this->findAffiliate($id);

        if (! $affiliate) {
            return false;
        }

        $newStatus = $affiliate->status === 'active' ? 'inactive' : 'active';

        return $affiliate->update(['status' => $newStatus]);
    }

    /**
     * Get all affiliates ordered by service name
     */
    public function getAllOrdered(): Collection
    {
        return $this->model->ordered()->get();
    }
}
