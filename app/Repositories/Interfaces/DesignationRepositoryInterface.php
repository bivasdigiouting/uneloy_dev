<?php

namespace App\Repositories\Interfaces;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DesignationRepositoryInterface
{
    /**
     * Get all designations
     */
    public function all(): Collection;

    /**
     * Get paginated designations
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find designation by ID
     */
    public function find(int $id): ?Designation;

    /**
     * Create new designation
     */
    public function create(array $data): Designation;

    /**
     * Update designation
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete designation
     */
    public function delete(int $id): bool;

    /**
     * Find designation by name
     */
    public function findByName(string $name): ?Designation;

    /**
     * Get active designations
     */
    public function getActive(): Collection;

    /**
     * Toggle designation status
     */
    public function toggleStatus(int $id): bool;
}
