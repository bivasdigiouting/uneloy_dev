<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface ECardSevaOtherPointsRepositoryInterface
{
    /**
     * Return base query for DataTables with applied filters.
     *
     * Supported filters: from_date, to_date, status, blood_group, search.
     */
    public function getForDataTable(array $filters = []): Builder;
}
