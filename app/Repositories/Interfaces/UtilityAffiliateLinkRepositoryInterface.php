<?php

namespace App\Repositories\Interfaces;

use App\Models\UtilityAffiliateLink;
use Illuminate\Database\Eloquent\Builder;

interface UtilityAffiliateLinkRepositoryInterface
{
    public function queryForDataTable(): Builder;

    public function create(array $attributes, array $stateIds): UtilityAffiliateLink;

    public function delete(int $id): bool;

    public function find(int $id): ?UtilityAffiliateLink;
}
