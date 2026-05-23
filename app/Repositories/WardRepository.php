<?php

namespace App\Repositories;

use App\Models\Ward;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class WardRepository implements WardRepositoryInterface
{
    public function __construct(private Ward $model) {}

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['state', 'district', 'city', 'municipality'])->ordered()->paginate($perPage);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    public function find(int $id): ?Ward
    {
        return $this->model->with(['state', 'district', 'city', 'municipality'])->find($id);
    }

    public function create(array $data): Ward
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
        return $this->model->select(['id', 'ward_no', 'state_id', 'district_id', 'city_id', 'municipality_id', 'status', 'created_at'])
            ->with(['state:id,state_name', 'district:id,district_name', 'city:id,city_name', 'municipality:id,municipality_name'])
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

    public function getByMunicipality(int $municipalityId): Collection
    {
        return $this->model->where('municipality_id', $municipalityId)->active()->ordered()->get();
    }
}
