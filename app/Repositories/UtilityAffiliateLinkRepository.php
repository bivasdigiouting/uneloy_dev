<?php

namespace App\Repositories;

use App\Models\UtilityAffiliateLink;
use App\Repositories\Interfaces\UtilityAffiliateLinkRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UtilityAffiliateLinkRepository implements UtilityAffiliateLinkRepositoryInterface
{
    public function __construct(private UtilityAffiliateLink $model) {}

    public function queryForDataTable(): Builder
    {
        return $this->model->newQuery()
            ->with(['states:id,state_name', 'district:id,district_name', 'city:id,city_name'])
            ->select('utility_affiliate_links.*');
    }

    public function create(array $attributes, array $stateIds): UtilityAffiliateLink
    {
        return DB::transaction(function () use ($attributes, $stateIds) {
            $link = $this->model->create($attributes);
            $link->states()->sync($stateIds);

            return $link;
        });
    }

    public function delete(int $id): bool
    {
        $link = $this->model->find($id);
        if (! $link) {
            return false;
        }

        return (bool) $link->delete();
    }

    public function find(int $id): ?UtilityAffiliateLink
    {
        return $this->model->find($id);
    }
}
