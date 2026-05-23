<?php

namespace App\Repositories\Interfaces;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DepartmentRepositoryInterface
{
    /**
     * Get all departments
     */
    public function all(): Collection;

    /**
     * Get paginated departments
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find department by ID
     */
    public function find(int $id): ?Department;

    /**
     * Create new department
     */
    public function create(array $data): Department;

    /**
     * Update department
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete department
     */
    public function delete(int $id): bool;

    /**
     * Find department by name
     */
    public function findByName(string $name): ?Department;

    /**
     * Get active departments
     */
    public function getActive(): Collection;

    /**
     * Toggle department status
     */
    public function toggleStatus(int $id): bool;
}
