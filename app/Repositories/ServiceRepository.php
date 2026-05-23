<?php

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected Service $model;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    public function getForDataTable()
    {
        return $this->model->select(['id', 'service_name', 'state_id', 'district_id', 'city_id', 'icon', 'created_at'])
            ->with(['state:id,state_name', 'district:id,district_name', 'city:id,city_name']);
    }

    public function findById(int $id): ?Service
    {
        return $this->model->find($id);
    }

    public function create(array $data): Service
    {
        if (isset($data['icon']) && $data['icon']) {
            $data['icon'] = $this->uploadIcon($data['icon']);
        }

        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $service = $this->findById($id);
        if (! $service) {
            return false;
        }

        if (isset($data['icon']) && $data['icon']) {
            if ($service->icon && Storage::disk('public')->exists($service->icon)) {
                Storage::disk('public')->delete($service->icon);
            }
            $data['icon'] = $this->uploadIcon($data['icon']);
        } else {
            unset($data['icon']);
        }

        return $service->update($data);
    }

    public function delete(int $id): bool
    {
        $service = $this->findById($id);
        if (! $service) {
            return false;
        }
        if ($service->icon && Storage::disk('public')->exists($service->icon)) {
            Storage::disk('public')->delete($service->icon);
        }

        return (bool) $service->delete();
    }

    private function uploadIcon($file): string
    {
        return $file->store('services/icons', 'public');
    }
}
