<?php

namespace App\Repositories;

use App\Models\AffiliateApi;
use Illuminate\Database\Eloquent\Builder;

interface AffiliateApiRepositoryInterface
{
    public function queryForDataTable(): Builder;

    public function create(array $data): AffiliateApi;

    public function delete(int $id): bool;
}
