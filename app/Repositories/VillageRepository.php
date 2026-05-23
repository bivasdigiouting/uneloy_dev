<?php

namespace App\Repositories;

use App\Models\Village;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VillageRepository implements VillageRepositoryInterface
{
    protected Village $model;

    public function __construct(Village $model)
    {
        $this->model = $model;
    }

    public function getPaginatedVillages(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['state', 'district', 'city'])->ordered()->paginate($perPage);
    }

    public function getActiveVillages(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    public function findVillage(int $id): ?Village
    {
        return $this->model->with(['state', 'district', 'city'])->find($id);
    }

    public function createVillage(array $data): Village
    {
        return $this->model->create($data);
    }

    public function updateVillage(int $id, array $data): bool
    {
        $village = $this->findVillage($id);
        if (! $village) {
            return false;
        }

        return $village->update($data);
    }

    public function deleteVillage(int $id): bool
    {
        $village = $this->findVillage($id);
        if (! $village) {
            return false;
        }

        return $village->delete();
    }

    public function getForDataTables()
    {
        return $this->model->select(['id', 'village_name', 'state_id', 'district_id', 'city_id', 'status', 'created_at'])
            ->with(['state:id,state_name', 'district:id,district_name', 'city:id,city_name'])
            ->ordered();
    }

    public function toggleStatus(int $id): bool
    {
        $village = $this->findVillage($id);
        if (! $village) {
            return false;
        }
        $newStatus = $village->status === 'active' ? 'inactive' : 'active';

        return $village->update(['status' => $newStatus]);
    }

    public function getVillagesByCity(int $cityId): Collection
    {
        return $this->model->where('city_id', $cityId)->active()->ordered()->get();
    }

    public function findByNameAndCity(string $name, int $cityId): ?Village
    {
        return $this->model->where('city_id', $cityId)
            ->whereRaw('LOWER(village_name) = ?', [mb_strtolower(trim($name))])
            ->first();
    }
}
