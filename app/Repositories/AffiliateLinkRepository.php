<?php

namespace App\Repositories;

use App\Models\AffiliateLink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AffiliateLinkRepository implements AffiliateLinkRepositoryInterface
{
    public function __construct(private AffiliateLink $model) {}

    public function queryForDataTable(): Builder
    {
        return $this->model->newQuery()
            ->with(['affiliate:id,service_name'])
            ->select('affiliate_links.*');
    }

    public function createAffiliateLink(array $data): AffiliateLink
    {
        if (empty($data['code'])) {
            do {
                $data['code'] = Str::lower(Str::random(10));
            } while ($this->model->newQuery()->where('code', $data['code'])->exists());
        }

        return $this->model->create($data);
    }

    public function deleteAffiliateLink(int $id): bool
    {
        $link = $this->model->find($id);
        if (! $link) {
            return false;
        }

        return (bool) $link->delete();
    }

    public function findByCode(string $code): ?AffiliateLink
    {
        return $this->model->newQuery()->where('code', $code)->first();
    }
}
