<?php

namespace App\Repositories;

use App\Models\GstTax;
use App\Repositories\Interfaces\GstTaxRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GstTaxRepository implements GstTaxRepositoryInterface
{
    protected GstTax $model;

    public function __construct(GstTax $model)
    {
        $this->model = $model;
    }

    public function getForDataTable(): Builder
    {
        return $this->model->select(['id', 'tax_name', 'rate_percent', 'is_active', 'created_at']);
    }

    public function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('is_active', true)->orderBy('tax_name')->get();
    }

    public function findById(int $id): ?GstTax
    {
        return $this->model->find($id);
    }

    public function create(array $data): GstTax
    {
        return $this->model->create([
            'tax_name' => $data['tax_name'],
            'rate_percent' => $data['rate_percent'],
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $tax = $this->findById($id);
        if (! $tax) {
            return false;
        }

        return $tax->update([
            'tax_name' => $data['tax_name'],
            'rate_percent' => $data['rate_percent'],
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : $tax->is_active,
        ]);
    }

    public function delete(int $id): bool
    {
        $tax = $this->findById($id);
        if (! $tax) {
            return false;
        }

        return (bool) $tax->delete();
    }

    public function toggleStatus(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $tax = $this->findById($id);
            if (! $tax) {
                return false;
            }
            $tax->is_active = ! $tax->is_active;

            return $tax->save();
        });
    }
}
