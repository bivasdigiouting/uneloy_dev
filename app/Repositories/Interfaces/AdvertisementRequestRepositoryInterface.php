<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface AdvertisementRequestRepositoryInterface
{
    /**
     * Build base query for Approve/Reject Advertisement Report with applied filters
     */
    public function getForReport(array $filters = []): Builder;
}
