<?php

namespace App\Repositories;

use App\Models\AdvertisementRequest;
use App\Repositories\Interfaces\AdvertisementRequestRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementRequestRepository implements AdvertisementRequestRepositoryInterface
{
    protected AdvertisementRequest $model;

    public function __construct(AdvertisementRequest $model)
    {
        $this->model = $model;
    }

    public function getForReport(array $filters = []): Builder
    {
        $query = $this->model->newQuery()->select([
            'id',
            'campaign_name',
            'business_category_id',
            'lead_id',
            'location',
            'advertisement_type',
            'from_date',
            'to_date',
            'request_status',
            'requester_type',
            'requester_id',
            'requester_name',
            'requester_email',
            'created_at',
        ]);

        if (! empty($filters['from_date'])) {
            $query->whereDate('from_date', '>=', $filters['from_date']);
        }
        if (! empty($filters['to_date'])) {
            $query->whereDate('to_date', '<=', $filters['to_date']);
        }
        if (! empty($filters['type'])) {
            $query->where('requester_type', $filters['type']);
        }
        if (! empty($filters['department'])) {
            $query->where('advertisement_type', $filters['department']);
        }
        if (! empty($filters['status'])) {
            $query->where('request_status', $filters['status']);
        }
        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search)
                        ->orWhere('requester_id', (int) $search);
                }
                $q->orWhere('requester_name', 'LIKE', "%$search%")
                    ->orWhere('requester_email', 'LIKE', "%$search%")
                    ->orWhere('campaign_name', 'LIKE', "%$search%");
            });
        }

        return $query;
    }
}
