<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface RechargeReportRepositoryInterface
{
    /**
     * Paginate summary of recharges grouped by user and operator.
     * Filters: service_id, operator_id, search (id/name/email/mobile)
     */
    public function paginateSummary(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Paginate detailed recharge transactions with filters.
     * Filters: from_date, to_date, service_id, operator_id, status, search
     */
    public function paginateTransactions(array $filters, int $perPage = 15): LengthAwarePaginator;
}
