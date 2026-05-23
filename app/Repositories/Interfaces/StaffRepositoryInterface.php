<?php

namespace App\Repositories\Interfaces;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface StaffRepositoryInterface
{
    /**
     * Get all staff
     */
    public function all(): Collection;

    /**
     * Get paginated staff
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find staff by ID
     */
    public function find(int $id): ?Staff;

    /**
     * Create new staff
     */
    public function create(array $data): Staff;

    /**
     * Update staff
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete staff
     */
    public function delete(int $id): bool;

    /**
     * Find staff by user ID
     */
    public function findByUserId(string $userId): ?Staff;

    /**
     * Find staff by email
     */
    public function findByEmail(string $email): ?Staff;

    /**
     * Get active staff
     */
    public function getActive(): Collection;

    /**
     * Toggle staff status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get staff with designation
     */
    public function getAllWithDesignation(): Collection;

    /**
     * Get staff by designation
     */
    public function getByDesignation(int $designationId): Collection;
}
