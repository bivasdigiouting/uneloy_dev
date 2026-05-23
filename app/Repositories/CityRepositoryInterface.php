<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CityRepositoryInterface
{
    /**
     * Get paginated cities
     */
    public function getPaginatedCities(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active cities
     */
    public function getActiveCities(): Collection;

    /**
     * Find city by ID
     */
    public function findCity(int $id): ?City;

    /**
     * Create new city
     */
    public function createCity(array $data): City;

    /**
     * Update city
     */
    public function updateCity(int $id, array $data): bool;

    /**
     * Delete city
     */
    public function deleteCity(int $id): bool;

    /**
     * Get cities for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle city status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get city count
     */
    public function getCityCount(): int;

    /**
     * Get active city count
     */
    public function getActiveCityCount(): int;

    /**
     * Get cities by state
     */
    public function getCitiesByState(int $stateId): Collection;

    /**
     * Get cities by district
     */
    public function getCitiesByDistrict(int $districtId): Collection;

    /**
     * Get cities by state and district
     */
    public function getCitiesByStateAndDistrict(int $stateId, int $districtId): Collection;
}
