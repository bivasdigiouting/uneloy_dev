<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Query\Builder;

interface ProductStockTransferRepositoryInterface
{
    /**
     * Create a product stock transfer record.
     */
    public function create(array $data);

    /**
     * Get transfer records for DataTables.
     */
    public function getForDataTable();

    /**
     * Build filtered report query for DataTables.
     */
    public function getReportQuery(array $filters): Builder;
}
