<?php

namespace App\Repositories;

use App\Models\Camp;
use App\Repositories\Interfaces\CampRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CampRepository implements CampRepositoryInterface
{
    protected Camp $model;

    public function __construct(Camp $model)
    {
        $this->model = $model;
    }

    public function getForDataTable()
    {
        return $this->model->select(['id', 'camp_name', 'icon', 'is_active', 'created_at']);
    }

    public function findById(int $id): ?Camp
    {
        return $this->model->find($id);
    }

    public function create(array $data): Camp
    {
        if (isset($data['icon']) && $data['icon']) {
            $data['icon'] = $this->uploadIcon($data['icon']);
        }

        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $camp = $this->findById($id);
        if (! $camp) {
            return false;
        }
        if (isset($data['icon']) && $data['icon']) {
            if ($camp->icon && Storage::disk('public')->exists($camp->icon)) {
                Storage::disk('public')->delete($camp->icon);
            }
            $data['icon'] = $this->uploadIcon($data['icon']);
        } else {
            unset($data['icon']);
        }

        return $camp->update($data);
    }

    public function delete(int $id): bool
    {
        $camp = $this->findById($id);
        if (! $camp) {
            return false;
        }
        if ($camp->icon && Storage::disk('public')->exists($camp->icon)) {
            Storage::disk('public')->delete($camp->icon);
        }

        return (bool) $camp->delete();
    }

    public function toggleStatus(int $id): bool
    {
        $camp = $this->findById($id);
        if (! $camp) {
            return false;
        }
        $camp->is_active = ! $camp->is_active;

        return $camp->save();
    }

    private function uploadIcon($file): string
    {
        return $file->store('camps/icons', 'public');
    }
}
