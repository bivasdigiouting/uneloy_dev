<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CityRepository implements CityRepositoryInterface
{
    protected City $model;

    public function __construct(City $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated cities
     */
    public function getPaginatedCities(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['state', 'district'])->ordered()->paginate($perPage);
    }

    /**
     * Get active cities
     */
    public function getActiveCities(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Find city by ID
     */
    public function findCity(int $id): ?City
    {
        return $this->model->with(['state', 'district'])->find($id);
    }

    /**
     * Create new city
     */
    public function createCity(array $data): City
    {
        return $this->model->create($data);
    }

    /**
     * Update city
     */
    public function updateCity(int $id, array $data): bool
    {
        $city = $this->findCity($id);

        if (! $city) {
            return false;
        }

        return $city->update($data);
    }

    /**
     * Delete city
     */
    public function deleteCity(int $id): bool
    {
        $city = $this->findCity($id);

        if (! $city) {
            return false;
        }

        return $city->delete();
    }

    /**
     * Get cities for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select(['id', 'city_name', 'district_id', 'state_id', 'status', 'created_at'])
            ->with(['state:id,state_name', 'district:id,district_name'])
            ->ordered();
    }

    /**
     * Toggle city status
     */
    public function toggleStatus(int $id): bool
    {
        $city = $this->findCity($id);

        if (! $city) {
            return false;
        }

        $newStatus = $city->status === 'active' ? 'inactive' : 'active';

        return $city->update(['status' => $newStatus]);
    }

    /**
     * Get city count
     */
    public function getCityCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get active city count
     */
    public function getActiveCityCount(): int
    {
        return $this->model->active()->count();
    }

    /**
     * Get cities by state
     */
    public function getCitiesByState(int $stateId): Collection
    {
        return $this->model->where('state_id', $stateId)->active()->ordered()->get();
    }

    /**
     * Get cities by district
     */
    public function getCitiesByDistrict(int $districtId): Collection
    {
        return $this->model->where('district_id', $districtId)->active()->ordered()->get();
    }

    public function getCitiesByStateAndDistrict(int $stateId, int $districtId): Collection
    {
        return $this->model
            ->where('state_id', $stateId)
            ->where('district_id', $districtId)
            ->active()
            ->ordered()
            ->get();
    }
}
