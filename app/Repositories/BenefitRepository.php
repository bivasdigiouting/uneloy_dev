<?php

namespace App\Repositories;

use App\Models\Benefit;
use App\Repositories\Interfaces\BenefitRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BenefitRepository implements BenefitRepositoryInterface
{
    protected Benefit $model;

    public function __construct(Benefit $model)
    {
        $this->model = $model;
    }

    public function getForDataTable()
    {
        return $this->model->select(['id', 'benefit_name', 'icon', 'schema_type', 'schema_type_name', 'is_active', 'created_at']);
    }

    public function findById(int $id): ?Benefit
    {
        return $this->model->find($id);
    }

    public function create(array $data): Benefit
    {
        if (isset($data['icon']) && $data['icon']) {
            $data['icon'] = $this->uploadIcon($data['icon']);
        }

        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $benefit = $this->findById($id);
        if (! $benefit) {
            return false;
        }

        if (isset($data['icon']) && $data['icon']) {
            if ($benefit->icon && Storage::disk('public')->exists($benefit->icon)) {
                Storage::disk('public')->delete($benefit->icon);
            }
            $data['icon'] = $this->uploadIcon($data['icon']);
        } else {
            unset($data['icon']);
        }

        return $benefit->update($data);
    }

    public function delete(int $id): bool
    {
        $benefit = $this->findById($id);
        if (! $benefit) {
            return false;
        }
        if ($benefit->icon && Storage::disk('public')->exists($benefit->icon)) {
            Storage::disk('public')->delete($benefit->icon);
        }

        return (bool) $benefit->delete();
    }

    private function uploadIcon($file): string
    {
        return $file->store('benefits/icons', 'public');
    }
}
