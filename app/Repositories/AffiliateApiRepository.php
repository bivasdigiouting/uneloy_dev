<?php

namespace App\Repositories;

use App\Models\AffiliateApi;
use Illuminate\Database\Eloquent\Builder;

class AffiliateApiRepository implements AffiliateApiRepositoryInterface
{
    public function __construct(private AffiliateApi $model) {}

    public function queryForDataTable(): Builder
    {
        return $this->model
            ->newQuery()
            ->with(['affiliate:id,service_name'])
            ->select('affiliate_apis.*');
    }

    public function create(array $data): AffiliateApi
    {
        return $this->model->create($data);
    }

    public function delete(int $id): bool
    {
        $api = $this->model->find($id);
        if (! $api) {
            return false;
        }

        return (bool) $api->delete();
    }
}
