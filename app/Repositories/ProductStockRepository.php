<?php

namespace App\Repositories;

use App\Models\ProductStockTransaction;
use App\Repositories\Interfaces\ProductStockRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductStockRepository implements ProductStockRepositoryInterface
{
    protected ProductStockTransaction $model;

    public function __construct(ProductStockTransaction $model)
    {
        $this->model = $model;
    }

    /** Create a new stock transaction */
    public function create(array $data): ProductStockTransaction
    {
        return $this->model->create($data);
    }

    /** Base query for DataTables listing */
    public function getForDataTable(): Builder
    {
        return $this->model
            ->query()
            ->leftJoin('products', 'product_stock_transactions.product_id', '=', 'products.id')
            ->leftJoin('product_categories', 'product_stock_transactions.product_category_id', '=', 'product_categories.id')
            ->select([
                'product_stock_transactions.id',
                'product_stock_transactions.product_id',
                'product_stock_transactions.product_category_id',
                'product_stock_transactions.quantity',
                'product_stock_transactions.type',
                'product_stock_transactions.remarks',
                'product_stock_transactions.created_at',
                DB::raw('products.name as product_name'),
                DB::raw('product_categories.name as category_name'),
            ])
            ->orderBy('product_stock_transactions.created_at', 'desc');
    }
}
