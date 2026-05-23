<?php

namespace App\Repositories;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface StateRepositoryInterface
{
    /**
     * Get paginated states
     */
    public function getPaginatedStates(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active states
     */
    public function getActiveStates(): Collection;

    /**
     * Find state by ID
     */
    public function findState(int $id): ?State;

    /**
     * Create new state
     */
    public function createState(array $data): State;

    /**
     * Update state
     */
    public function updateState(int $id, array $data): bool;

    /**
     * Delete state
     */
    public function deleteState(int $id): bool;

    /**
     * Get states for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle state status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get state count
     */
    public function getStateCount(): int;

    /**
     * Get active state count
     */
    public function getActiveStateCount(): int;
}
