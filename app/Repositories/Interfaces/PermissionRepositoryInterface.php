<?php

namespace App\Repositories\Interfaces;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionRepositoryInterface
{
    /**
     * Get all permissions
     */
    public function all(): Collection;

    /**
     * Get paginated permissions
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find permission by ID
     */
    public function find(int $id): ?Permission;

    /**
     * Create new permission
     */
    public function create(array $data): Permission;

    /**
     * Update permission
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete permission
     */
    public function delete(int $id): bool;

    /**
     * Find permission by name
     */
    public function findByName(string $name): ?Permission;

    /**
     * Get active permissions
     */
    public function getActive(): Collection;

    /**
     * Get permissions by module
     */
    public function getByModule(string $module): Collection;

    /**
     * Get all modules
     */
    public function getModules(): Collection;

    /**
     * Get permissions with roles
     */
    public function withRoles(): Collection;
}
