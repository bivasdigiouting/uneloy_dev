<?php

namespace App\Repositories\Interfaces;

use App\Models\ProductStockTransaction;
use Illuminate\Database\Eloquent\Builder;

interface ProductStockRepositoryInterface
{
    /** Create a new stock transaction */
    public function create(array $data): ProductStockTransaction;

    /** Base query for DataTables listing */
    public function getForDataTable(): Builder;
}
