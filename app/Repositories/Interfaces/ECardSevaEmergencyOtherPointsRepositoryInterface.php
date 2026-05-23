<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface ECardSevaEmergencyOtherPointsRepositoryInterface
{
    /**
     * Return base query for DataTables with applied filters.
     *
     * Supported filters: from_date, to_date, status, search.
     */
    public function getForDataTable(array $filters = []): Builder;
}
