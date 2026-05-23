<?php

namespace App\Repositories;

use App\Models\Municipality;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MunicipalityRepository implements MunicipalityRepositoryInterface
{
    public function __construct(private Municipality $model) {}

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['state', 'district', 'city'])->ordered()->paginate($perPage);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    public function find(int $id): ?Municipality
    {
        return $this->model->with(['state', 'district', 'city'])->find($id);
    }

    public function create(array $data): Municipality
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $row = $this->find($id);

        return $row ? $row->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $row = $this->find($id);

        return $row ? (bool) $row->delete() : false;
    }

    public function getForDataTables()
    {
        return $this->model->select(['id', 'municipality_name', 'state_id', 'district_id', 'city_id', 'status', 'created_at'])
            ->with(['state:id,state_name', 'district:id,district_name', 'city:id,city_name'])
            ->ordered();
    }

    public function toggleStatus(int $id): bool
    {
        $row = $this->find($id);
        if (! $row) {
            return false;
        }
        $new = $row->status === 'active' ? 'inactive' : 'active';

        return $row->update(['status' => $new]);
    }

    public function getByCity(int $cityId): Collection
    {
        return $this->model->where('city_id', $cityId)->active()->ordered()->get();
    }
}
