<?php

namespace App\Repositories;

use App\Models\Advertisement;
use App\Repositories\Interfaces\AdvertisementRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AdvertisementRepository implements AdvertisementRepositoryInterface
{
    protected Advertisement $model;

    public function __construct(Advertisement $model)
    {
        $this->model = $model;
    }

    public function getForDataTable(): Builder
    {
        return $this->model->select(['id', 'name', 'price_per_day', 'is_active', 'created_at']);
    }

    public function findById(int $id): ?Advertisement
    {
        return $this->model->find($id);
    }

    public function create(array $data): Advertisement
    {
        return $this->model->create([
            'name' => $data['name'],
            'price_per_day' => $data['price_per_day'],
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $adv = $this->findById($id);
        if (! $adv) {
            return false;
        }

        return $adv->update([
            'name' => $data['name'],
            'price_per_day' => $data['price_per_day'],
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : $adv->is_active,
        ]);
    }

    public function delete(int $id): bool
    {
        $adv = $this->findById($id);
        if (! $adv) {
            return false;
        }

        return (bool) $adv->delete();
    }

    public function toggleStatus(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $adv = $this->findById($id);
            if (! $adv) {
                return false;
            }
            $adv->is_active = ! $adv->is_active;

            return $adv->save();
        });
    }
}
