<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    protected $model;

    public function __construct(ProductCategory $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('sequence', 'asc')->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Throwable $e) {
            if (stripos($e->getMessage(), "Field 'id' doesn't have a default value") !== false) {
                $nextId = (int) (DB::table('product_categories')->max('id') ?? 0) + 1;
                $payload = $data;
                $payload['id'] = $nextId;
                $payload['created_at'] = now();
                $payload['updated_at'] = now();
                $columns = Schema::getColumnListing('product_categories');
                $payload = array_intersect_key($payload, array_flip($columns));
                DB::table('product_categories')->insert($payload);

                return $this->model->find($nextId);
            }
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        $productCategory = $this->find($id);
        $productCategory->update($data);

        return $productCategory;
    }

    public function delete($id)
    {
        $productCategory = $this->find($id);

        return $productCategory->delete();
    }

    public function getActive()
    {
        return $this->model->active()->orderBy('sequence', 'asc')->get();
    }

    public function getInactive()
    {
        return $this->model->inactive()->orderBy('sequence', 'asc')->get();
    }

    public function updateStatus($id, $status)
    {
        $productCategory = $this->find($id);
        $productCategory->update(['status' => $status]);

        return $productCategory;
    }

    public function getForDataTable()
    {
        // Do not select removed columns; compute via accessors on the model
        return $this->model->with('levelWiseCommission')
            ->select(['id', 'name', 'icon', 'sequence', 'status', 'created_at'])
            ->orderBy('sequence', 'asc');
    }

    public function getTotalCount()
    {
        return $this->model->count();
    }

    public function getActiveCount()
    {
        return $this->model->active()->count();
    }

    public function getInactiveCount()
    {
        return $this->model->inactive()->count();
    }

    public function getAverageCommission()
    {
        // Prefer legacy column if present on the SAME connection as the model
        $connection = $this->model->getConnectionName();
        if (Schema::connection($connection)->hasColumn('product_categories', 'commission')) {
            return (float) ($this->model->avg('commission') ?? 0);
        }

        return (float) (\App\Models\LevelWiseProductCommission::avg('customer_commission') ?? 0);
    }
}
