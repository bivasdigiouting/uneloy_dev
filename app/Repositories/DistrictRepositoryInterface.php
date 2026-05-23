<?php

namespace App\Repositories;

use App\Models\District;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DistrictRepositoryInterface
{
    /**
     * Get paginated districts
     */
    public function getPaginatedDistricts(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active districts
     */
    public function getActiveDistricts(): Collection;

    /**
     * Get districts by state
     */
    public function getDistrictsByState(int $stateId): Collection;

    /**
     * Find district by ID
     */
    public function findDistrict(int $id): ?District;

    /**
     * Create new district
     */
    public function createDistrict(array $data): District;

    /**
     * Update district
     */
    public function updateDistrict(int $id, array $data): bool;

    /**
     * Delete district
     */
    public function deleteDistrict(int $id): bool;

    /**
     * Get districts for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle district status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get district count
     */
    public function getDistrictCount(): int;

    /**
     * Get active district count
     */
    public function getActiveDistrictCount(): int;
}
