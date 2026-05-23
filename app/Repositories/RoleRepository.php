<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository implements RoleRepositoryInterface
{
    protected Role $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * Get all roles
     */
    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    /**
     * Get paginated roles
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('permissions')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Find role by ID
     */
    public function find(int $id): ?Role
    {
        return $this->model->with('permissions')->find($id);
    }

    /**
     * Create new role
     */
    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    /**
     * Update role
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete role
     */
    public function delete(int $id): bool
    {
        $role = $this->find($id);
        if ($role) {
            $role->permissions()->detach();

            return $role->delete();
        }

        return false;
    }

    /**
     * Find role by name
     */
    public function findByName(string $name): ?Role
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Get active roles
     */
    public function getActive(): Collection
    {
        return $this->model->with('permissions')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Sync permissions to role
     */
    public function syncPermissions(int $roleId, array $permissionIds): void
    {
        $role = $this->find($roleId);
        if ($role) {
            $role->permissions()->sync($permissionIds);
        }
    }

    /**
     * Get roles with permissions
     */
    public function withPermissions(): Collection
    {
        return $this->model->with('permissions')
            ->orderBy('name')
            ->get();
    }
}
