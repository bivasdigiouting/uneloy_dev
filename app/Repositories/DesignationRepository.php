<?php

namespace App\Repositories;

use App\Models\Designation;
use App\Repositories\Interfaces\DesignationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DesignationRepository implements DesignationRepositoryInterface
{
    protected Designation $model;

    public function __construct(Designation $model)
    {
        $this->model = $model;
    }

    /**
     * Get all designations
     */
    public function all(): Collection
    {
        return $this->model->orderBy('designation_name')->get();
    }

    /**
     * Get paginated designations
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('designation_name')
            ->paginate($perPage);
    }

    /**
     * Find designation by ID
     */
    public function find(int $id): ?Designation
    {
        return $this->model->find($id);
    }

    /**
     * Create new designation
     */
    public function create(array $data): Designation
    {
        return $this->model->create($data);
    }

    /**
     * Update designation
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete designation
     */
    public function delete(int $id): bool
    {
        $designation = $this->find($id);
        if ($designation && $designation->canBeDeleted()) {
            return $designation->delete();
        }

        return false;
    }

    /**
     * Find designation by name
     */
    public function findByName(string $name): ?Designation
    {
        return $this->model->where('designation_name', $name)->first();
    }

    /**
     * Get active designations
     */
    public function getActive(): Collection
    {
        return $this->model->active()
            ->orderBy('designation_name')
            ->get();
    }

    /**
     * Toggle designation status
     */
    public function toggleStatus(int $id): bool
    {
        $designation = $this->find($id);
        if ($designation) {
            return $this->update($id, ['is_active' => ! $designation->is_active]);
        }

        return false;
    }
}
