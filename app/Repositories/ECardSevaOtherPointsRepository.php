<?php

namespace App\Repositories;

use App\Models\ECardSevaOtherPoint;
use App\Repositories\Interfaces\ECardSevaOtherPointsRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ECardSevaOtherPointsRepository implements ECardSevaOtherPointsRepositoryInterface
{
    protected ECardSevaOtherPoint $model;

    public function __construct(ECardSevaOtherPoint $model)
    {
        $this->model = $model;
    }

    public function getForDataTable(array $filters = []): Builder
    {
        $query = $this->model->newQuery()->select([
            'id',
            'points',
            'approved_id_no',
            'approved_name',
            'approved_date',
            'name',
            'mobile_no',
            'age',
            'gender',
            'blood_group',
            'hospital_name',
            'hospital_address',
            'request_date',
            'image',
            'status',
            'send_points',
            'remarks',
            'send_points_date',
            'created_at',
        ]);

        // Date range filtering by request_date
        $fromDate = trim((string) ($filters['from_date'] ?? ''));
        $toDate = trim((string) ($filters['to_date'] ?? ''));
        if ($fromDate !== '' && $toDate !== '') {
            try {
                $start = Carbon::parse($fromDate)->startOfDay();
                $end = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('request_date', [$start, $end]);
            } catch (\Throwable $e) {
                // ignore parse error
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

        // Blood group filtering
        $bloodGroup = trim((string) ($filters['blood_group'] ?? ''));
        if ($bloodGroup !== '' && strtoupper($bloodGroup) !== 'ALL') {
            $query->where('blood_group', $bloodGroup);
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
