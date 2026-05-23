<?php

namespace App\Repositories\Interfaces;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleRepositoryInterface
{
    /**
     * Get all roles
     */
    public function all(): Collection;

    /**
     * Get paginated roles
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find role by ID
     */
    public function find(int $id): ?Role;

    /**
     * Create new role
     */
    public function create(array $data): Role;

    /**
     * Update role
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete role
     */
    public function delete(int $id): bool;

    /**
     * Find role by name
     */
    public function findByName(string $name): ?Role;

    /**
     * Get active roles
     */
    public function getActive(): Collection;

    /**
     * Sync permissions to role
     */
    public function syncPermissions(int $roleId, array $permissionIds): void;

    /**
     * Get roles with permissions
     */
    public function withPermissions(): Collection;
}
