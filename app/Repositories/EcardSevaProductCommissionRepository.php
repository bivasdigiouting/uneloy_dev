<?php

namespace App\Repositories;

use App\Models\EcardSevaProductCommission;
use App\Repositories\Interfaces\EcardSevaProductCommissionRepositoryInterface;

class EcardSevaProductCommissionRepository implements EcardSevaProductCommissionRepositoryInterface
{
    protected $model;

    public function __construct(EcardSevaProductCommission $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('product')->get();
    }

    public function getForDataTable()
    {
        return $this->model->with('product')->select('ecard_seva_product_commissions.*');
    }

    public function find($id)
    {
        return $this->model->with('product')->find($id);
    }

    public function findByInhouseProductId($inhouseProductId)
    {
        return $this->model->where('inhouse_product_id', $inhouseProductId)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $commission = $this->model->findOrFail($id);
        $commission->update($data);
        return $commission;
    }

    public function delete($id)
    {
        $commission = $this->model->findOrFail($id);
        return $commission->delete();
    }

    public function toggleStatus($id)
    {
        $commission = $this->model->findOrFail($id);
        $commission->is_active = !$commission->is_active;
        $commission->save();
        return $commission;
    }

    public function existsForInhouseProduct($inhouseProductId, $excludeId = null)
    {
        $query = $this->model->where('inhouse_product_id', $inhouseProductId);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }
}
