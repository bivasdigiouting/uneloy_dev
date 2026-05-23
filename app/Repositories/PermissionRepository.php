<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionRepository implements PermissionRepositoryInterface
{
    protected Permission $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * Get all permissions
     */
    public function all(): Collection
    {
        return $this->model->orderBy('module')->orderBy('name')->get();
    }

    /**
     * Get paginated permissions
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('roles')
            ->orderBy('module')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Find permission by ID
     */
    public function find(int $id): ?Permission
    {
        return $this->model->with('roles')->find($id);
    }

    /**
     * Create new permission
     */
    public function create(array $data): Permission
    {
        return $this->model->create($data);
    }

    /**
     * Update permission
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete permission
     */
    public function delete(int $id): bool
    {
        $permission = $this->find($id);
        if ($permission) {
            $permission->roles()->detach();

            return $permission->delete();
        }

        return false;
    }

    /**
     * Find permission by name
     */
    public function findByName(string $name): ?Permission
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Get active permissions
     */
    public function getActive(): Collection
    {
        return $this->model->active()
            ->orderBy('module')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get permissions by module
     */
    public function getByModule(string $module): Collection
    {
        return $this->model->byModule($module)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all modules
     */
    public function getModules(): Collection
    {
        return $this->model->select('module')
            ->distinct()
            ->whereNotNull('module')
            ->orderBy('module')
            ->pluck('module');
    }

    /**
     * Get permissions with roles
     */
    public function withRoles(): Collection
    {
        return $this->model->with('roles')
            ->orderBy('module')
            ->orderBy('name')
            ->get();
    }
}
