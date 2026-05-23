<?php

namespace App\Repositories;

use App\Models\AffiliateLink;
use Illuminate\Database\Eloquent\Builder;

interface AffiliateLinkRepositoryInterface
{
    public function queryForDataTable(): Builder;

    public function createAffiliateLink(array $data): AffiliateLink;

    public function deleteAffiliateLink(int $id): bool;

    public function findByCode(string $code): ?AffiliateLink;
}
