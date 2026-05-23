<?php

namespace App\Repositories;

use App\Models\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffRepository implements StaffRepositoryInterface
{
    protected Staff $model;

    public function __construct(Staff $model)
    {
        $this->model = $model;
    }

    /**
     * Get all staff
     */
    public function all(): Collection
    {
        return $this->model->with('designation')->orderBy('staff_name')->get();
    }

    /**
     * Get paginated staff
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('designation')
            ->orderBy('staff_name')
            ->paginate($perPage);
    }

    /**
     * Find staff by ID
     */
    public function find(int $id): ?Staff
    {
        return $this->model->with('designation')->find($id);
    }

    /**
     * Create new staff
     */
    public function create(array $data): Staff
    {
        return $this->model->create($data);
    }

    /**
     * Update staff
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete staff
     */
    public function delete(int $id): bool
    {
        $staff = $this->find($id);
        if ($staff && $staff->canBeDeleted()) {
            return $staff->delete();
        }

        return false;
    }

    /**
     * Find staff by user ID
     */
    public function findByUserId(string $userId): ?Staff
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Find staff by email
     */
    public function findByEmail(string $email): ?Staff
    {
        return $this->model->where('email_id', $email)->first();
    }

    /**
     * Get active staff
     */
    public function getActive(): Collection
    {
        return $this->model->active()
            ->with('designation')
            ->orderBy('staff_name')
            ->get();
    }

    /**
     * Toggle staff status
     */
    public function toggleStatus(int $id): bool
    {
        $staff = $this->find($id);
        if ($staff) {
            return $this->update($id, ['is_active' => ! $staff->is_active]);
        }

        return false;
    }

    /**
     * Get staff with designation
     */
    public function getAllWithDesignation(): Collection
    {
        return $this->model->with('designation')
            ->orderBy('staff_name')
            ->get();
    }

    /**
     * Get staff by designation
     */
    public function getByDesignation(int $designationId): Collection
    {
        return $this->model->where('designation_id', $designationId)
            ->with('designation')
            ->orderBy('staff_name')
            ->get();
    }
}
