<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected Department $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    /**
     * Get all departments
     */
    public function all(): Collection
    {
        return $this->model->orderBy('department_name')->get();
    }

    /**
     * Get paginated departments
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('department_name')
            ->paginate($perPage);
    }

    /**
     * Find department by ID
     */
    public function find(int $id): ?Department
    {
        return $this->model->find($id);
    }

    /**
     * Create new department
     */
    public function create(array $data): Department
    {
        return $this->model->create($data);
    }

    /**
     * Update department
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete department
     */
    public function delete(int $id): bool
    {
        $department = $this->find($id);
        if ($department && $department->canBeDeleted()) {
            return $department->delete();
        }

        return false;
    }

    /**
     * Find department by name
     */
    public function findByName(string $name): ?Department
    {
        return $this->model->where('department_name', $name)->first();
    }

    /**
     * Get active departments
     */
    public function getActive(): Collection
    {
        return $this->model->active()
            ->orderBy('department_name')
            ->get();
    }

    /**
     * Toggle department status
     */
    public function toggleStatus(int $id): bool
    {
        $department = $this->find($id);
        if ($department) {
            return $this->update($id, ['is_active' => ! $department->is_active]);
        }

        return false;
    }
}
