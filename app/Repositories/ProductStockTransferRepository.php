<?php

namespace App\Repositories;

use App\Models\ProductStockTransfer;
use App\Repositories\Interfaces\ProductStockTransferRepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ProductStockTransferRepository implements ProductStockTransferRepositoryInterface
{
    protected ProductStockTransfer $model;

    public function __construct(ProductStockTransfer $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function getForDataTable()
    {
        return $this->model
            ->select([
                'product_stock_transfers.*',
                'products.name as product_name',
                'product_categories.name as category_name',
            ])
            ->leftJoin('products', 'products.id', '=', 'product_stock_transfers.product_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_stock_transfers.product_category_id')
            ->orderByDesc('product_stock_transfers.created_at');
    }

    /**
     * Build filtered report query for DataTables.
     */
    public function getReportQuery(array $filters): Builder
    {
        $query = $this->model
            ->select([
                'product_stock_transfers.*',
                DB::raw('products.name as product_name'),
                DB::raw('product_categories.name as category_name'),
            ])
            ->leftJoin('products', 'products.id', '=', 'product_stock_transfers.product_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_stock_transfers.product_category_id');

        // Date range
        if (! empty($filters['from_date'])) {
            $query->whereDate('product_stock_transfers.created_at', '>=', $filters['from_date']);
        }
        if (! empty($filters['to_date'])) {
            $query->whereDate('product_stock_transfers.created_at', '<=', $filters['to_date']);
        }

        // Level filtering (assumed to apply to "to" side for reporting)
        if (! empty($filters['level'])) {
            $level = $filters['level']; // state|district|city|panchayat|village
            $query->where('product_stock_transfers.to_level_type', $level);

            // Member filter
            if (in_array($level, ['state', 'district', 'city'])) {
                if (! empty($filters['member_id'])) {
                    $col = [
                        'state' => 'to_state_id',
                        'district' => 'to_district_id',
                        'city' => 'to_city_id',
                    ][$level];
                    $query->where('product_stock_transfers.'.$col, $filters['member_id']);
                }
            } elseif (in_array($level, ['panchayat', 'village'])) {
                if (! empty($filters['member_name'])) {
                    $col = $level === 'panchayat' ? 'to_panchayat_name' : 'to_village_name';
                    $query->where('product_stock_transfers.'.$col, 'like', '%'.$filters['member_name'].'%');
                }
            }
        }

        return $query->orderByDesc('product_stock_transfers.created_at');
    }
}
