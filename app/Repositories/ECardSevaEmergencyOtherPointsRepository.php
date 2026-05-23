<?php

namespace App\Repositories;

use App\Models\ECardSevaEmergencyOtherPoint;
use App\Repositories\Interfaces\ECardSevaEmergencyOtherPointsRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ECardSevaEmergencyOtherPointsRepository implements ECardSevaEmergencyOtherPointsRepositoryInterface
{
    protected ECardSevaEmergencyOtherPoint $model;

    public function __construct(ECardSevaEmergencyOtherPoint $model)
    {
        $this->model = $model;
    }

    public function getForDataTable(array $filters = []): Builder
    {
        $query = $this->model->newQuery();

        // Date range filtering
        $fromDate = trim((string) ($filters['from_date'] ?? ''));
        $toDate = trim((string) ($filters['to_date'] ?? ''));
        if ($fromDate !== '' && $toDate !== '') {
            try {
                $start = Carbon::parse($fromDate)->startOfDay();
                $end = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('request_date', [$start, $end]);
            } catch (\Throwable $e) {
            }
        } elseif ($fromDate !== '') {
            try {
                $start = Carbon::parse($fromDate)->startOfDay();
                $query->where('request_date', '>=', $start);
            } catch (\Throwable $e) {
            }
        } elseif ($toDate !== '') {
            try {
                $end = Carbon::parse($toDate)->endOfDay();
                $query->where('request_date', '<=', $end);
            } catch (\Throwable $e) {
            }
        }

        // Status filtering (All | Pending | Approved | Send Point)
        $status = trim((string) ($filters['status'] ?? ''));
        if ($status !== '' && strtoupper($status) !== 'ALL') {
            $query->where('status', $status);
        }

        // Search by name/mobile
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->orWhere('name', 'like', "%$search%");
                $q->orWhere('mobile_no', 'like', "%$search%");
            });
        }

        return $query->orderBy('request_date', 'desc')->orderBy('created_at', 'desc');
    }
}
