<?php

namespace App\Repositories;

use App\Models\Helpline;
use App\Repositories\Interfaces\HelplineRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class HelplineRepository implements HelplineRepositoryInterface
{
    protected Helpline $model;

    public function __construct(Helpline $model)
    {
        $this->model = $model;
    }

    public function getForDataTable()
    {
        return $this->model->select(['id', 'helpline_name', 'helpline_number', 'state_id', 'district_id', 'city_id', 'icon', 'created_at'])
            ->with(['state:id,state_name', 'district:id,district_name', 'city:id,city_name']);
    }

    public function findById(int $id): ?Helpline
    {
        return $this->model->find($id);
    }

    public function create(array $data): Helpline
    {
        if (isset($data['icon']) && $data['icon']) {
            $data['icon'] = $this->uploadIcon($data['icon']);
        }

        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $helpline = $this->findById($id);
        if (! $helpline) {
            return false;
        }

        if (isset($data['icon']) && $data['icon']) {
            if ($helpline->icon && Storage::disk('public')->exists($helpline->icon)) {
                Storage::disk('public')->delete($helpline->icon);
            }
            $data['icon'] = $this->uploadIcon($data['icon']);
        } else {
            unset($data['icon']);
        }

        return $helpline->update($data);
    }

    public function delete(int $id): bool
    {
        $helpline = $this->findById($id);
        if (! $helpline) {
            return false;
        }
        if ($helpline->icon && Storage::disk('public')->exists($helpline->icon)) {
            Storage::disk('public')->delete($helpline->icon);
        }

        return (bool) $helpline->delete();
    }

    private function uploadIcon($file): string
    {
        return $file->store('helplines/icons', 'public');
    }
}
