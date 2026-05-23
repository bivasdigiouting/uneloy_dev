<?php

namespace App\Repositories;

use App\Models\District;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DistrictRepository implements DistrictRepositoryInterface
{
    protected District $model;

    public function __construct(District $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated districts
     */
    public function getPaginatedDistricts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('state')->ordered()->paginate($perPage);
    }

    /**
     * Get active districts
     */
    public function getActiveDistricts(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Get districts by state
     */
    public function getDistrictsByState(int $stateId): Collection
    {
        return $this->model->where('state_id', $stateId)->active()->ordered()->get();
    }

    /**
     * Find district by ID
     */
    public function findDistrict(int $id): ?District
    {
        return $this->model->with('state')->find($id);
    }

    /**
     * Create new district
     */
    public function createDistrict(array $data): District
    {
        return $this->model->create($data);
    }

    /**
     * Update district
     */
    public function updateDistrict(int $id, array $data): bool
    {
        $district = $this->model->find($id);

        if (! $district) {
            return false;
        }

        return $district->update($data);
    }

    /**
     * Delete district
     */
    public function deleteDistrict(int $id): bool
    {
        $district = $this->model->find($id);

        if (! $district) {
            return false;
        }

        return $district->delete();
    }

    /**
     * Get districts for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select(['id', 'district_name', 'state_id', 'status', 'created_at'])
            ->with('state:id,state_name')
            ->ordered();
    }

    /**
     * Toggle district status
     */
    public function toggleStatus(int $id): bool
    {
        $district = $this->model->find($id);

        if (! $district) {
            return false;
        }

        $newStatus = $district->status === 'active' ? 'inactive' : 'active';

        return $district->update(['status' => $newStatus]);
    }

    /**
     * Get district count
     */
    public function getDistrictCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get active district count
     */
    public function getActiveDistrictCount(): int
    {
        return $this->model->active()->count();
    }
}
