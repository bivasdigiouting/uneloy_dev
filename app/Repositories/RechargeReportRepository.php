<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RechargeReportRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RechargeReportRepository implements RechargeReportRepositoryInterface
{
    /**
     * Determine the recharge transactions table name if available.
     */
    protected function resolveTransactionsTable(): ?string
    {
        $candidates = [
            'recharge_transactions',
            'recharges',
            'utility_recharges',
        ];
        foreach ($candidates as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    public function paginateSummary(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $table = $this->resolveTransactionsTable();
        if (! $table) {
            // Return an empty paginator gracefully if no transactions table exists
            return new LengthAwarePaginator([], 0, $perPage, Paginator::resolveCurrentPage(), [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        $amountCol = Schema::hasColumn($table, 'amount') ? 'amount'
            : (Schema::hasColumn($table, 'total_amount') ? 'total_amount' : null);
        $commissionCol = Schema::hasColumn($table, 'commission_amount') ? 'commission_amount'
            : (Schema::hasColumn($table, 'commission') ? 'commission' : null);

        $query = DB::table($table.' as rt')
            ->join('users as u', 'u.id', '=', 'rt.user_id')
            ->leftJoin('recharge_operators as ro', 'ro.id', '=', 'rt.operator_id')
            ->leftJoin('recharge_services as rs', 'rs.id', '=', 'ro.recharge_service_id')
            ->select([
                'u.id as user_id',
                'u.name as user_name',
                DB::raw('COALESCE(ro.operator_name, "-") as operator_name'),
                DB::raw(($amountCol ? "SUM(rt.$amountCol)" : 'SUM(0)').' as total_recharge_amt'),
                DB::raw(($commissionCol ? "SUM(rt.$commissionCol)" : 'SUM(0)').' as total_commission_amt'),
            ])
            ->groupBy('u.id', 'u.name', 'ro.operator_name');

        // Filters
        $serviceId = isset($filters['service_id']) && $filters['service_id'] !== '' ? (int) $filters['service_id'] : null;
        $operatorId = isset($filters['operator_id']) && $filters['operator_id'] !== '' ? (int) $filters['operator_id'] : null;
        $search = isset($filters['search']) ? trim((string) $filters['search']) : null;

        if ($serviceId) {
            $query->where('ro.recharge_service_id', $serviceId);
        }
        if ($operatorId) {
            $query->where('rt.operator_id', $operatorId);
        }
        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                // ID exact match if numeric
                if (is_numeric($search)) {
                    $q->orWhere('u.id', (int) $search);
                }
                // Name and email like filters
                $q->orWhere('u.name', 'like', '%'.$search.'%')
                    ->orWhere('u.email', 'like', '%'.$search.'%');
                // Mobile filter if column exists
                if (Schema::hasColumn('users', 'mobile')) {
                    $q->orWhere('u.mobile', 'like', '%'.$search.'%');
                } elseif (Schema::hasColumn('users', 'phone')) {
                    $q->orWhere('u.phone', 'like', '%'.$search.'%');
                }
            });
        }

        return $query->paginate($perPage);
    }

    public function paginateTransactions(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $table = $this->resolveTransactionsTable();
        if (! $table) {
            return new LengthAwarePaginator([], 0, $perPage, Paginator::resolveCurrentPage(), [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        $firstExisting = function (string $tbl, array $candidates): ?string {
            foreach ($candidates as $col) {
                if (Schema::hasColumn($tbl, $col)) {
                    return $col;
                }
            }

            return null;
        };

        $userIdCol = $firstExisting($table, ['user_id', 'customer_id', 'member_id']);
        $operatorIdCol = $firstExisting($table, ['operator_id', 'recharge_operator_id']);
        $amountCol = Schema::hasColumn($table, 'amount') ? 'amount'
            : (Schema::hasColumn($table, 'total_amount') ? 'total_amount' : null);
        $rechargeNoCol = $firstExisting($table, ['recharge_no', 'mobile', 'mobile_no', 'consumer_number', 'account', 'account_no', 'number']);
        $statusCol = $firstExisting($table, ['status', 'recharge_status', 'status_code', 'txn_status']);
        $dateCol = $firstExisting($table, ['recharge_date', 'txn_date', 'transaction_date', 'created_at', 'date']);
        $txnIdCol = $firstExisting($table, ['transaction_id', 'txn_id', 'order_id', 'reference_id', 'ref_id']);

        $query = DB::table($table.' as rt');

        if ($userIdCol) {
            $query->join('users as u', 'u.id', '=', 'rt.'.$userIdCol);
        }
        if ($operatorIdCol) {
            $query->leftJoin('recharge_operators as ro', 'ro.id', '=', 'rt.'.$operatorIdCol);
            $query->leftJoin('recharge_services as rs', 'rs.id', '=', 'ro.recharge_service_id');
        }

        $select = [];
        $select[] = $userIdCol ? DB::raw('u.name as customer_name') : DB::raw('"-" as customer_name');
        $select[] = $operatorIdCol ? DB::raw('COALESCE(ro.operator_name, "-") as operator_name') : DB::raw('"-" as operator_name');
        $select[] = $rechargeNoCol ? DB::raw('rt.'.$rechargeNoCol.' as recharge_no') : DB::raw('NULL as recharge_no');
        $select[] = $amountCol ? DB::raw('rt.'.$amountCol.' as amount') : DB::raw('0 as amount');
        $select[] = $statusCol ? DB::raw('rt.'.$statusCol.' as recharge_status') : DB::raw('NULL as recharge_status');
        $select[] = $dateCol ? DB::raw('rt.'.$dateCol.' as recharge_date') : DB::raw('NULL as recharge_date');
        $select[] = $txnIdCol ? DB::raw('rt.'.$txnIdCol.' as transaction_id') : DB::raw('NULL as transaction_id');

        $query->select($select);

        // Filters
        $fromDate = isset($filters['from_date']) ? trim((string) $filters['from_date']) : null;
        $toDate = isset($filters['to_date']) ? trim((string) $filters['to_date']) : null;
        $serviceId = isset($filters['service_id']) && $filters['service_id'] !== '' ? (int) $filters['service_id'] : null;
        $operatorId = isset($filters['operator_id']) && $filters['operator_id'] !== '' ? (int) $filters['operator_id'] : null;
        $status = isset($filters['status']) ? strtolower(trim((string) $filters['status'])) : 'all';
        $search = isset($filters['search']) ? trim((string) $filters['search']) : null;

        if ($dateCol) {
            if (! empty($fromDate)) {
                $query->whereDate('rt.'.$dateCol, '>=', $fromDate);
            }
            if (! empty($toDate)) {
                $query->whereDate('rt.'.$dateCol, '<=', $toDate);
            }
        }

        if ($serviceId && $operatorIdCol) {
            $query->where('ro.recharge_service_id', $serviceId);
        }
        if ($operatorId && $operatorIdCol) {
            $query->where('rt.'.$operatorIdCol, $operatorId);
        }

        if ($statusCol && $status && $status !== 'all') {
            $query->whereRaw('LOWER(rt.'.$statusCol.') = ?', [$status]);
        }

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search, $userIdCol) {
                if (is_numeric($search)) {
                    if ($userIdCol) {
                        $q->orWhere('rt.'.$userIdCol, (int) $search);
                    }
                }
                if ($userIdCol) {
                    $q->orWhere('u.name', 'like', '%'.$search.'%')
                        ->orWhere('u.email', 'like', '%'.$search.'%');
                    if (Schema::hasColumn('users', 'mobile')) {
                        $q->orWhere('u.mobile', 'like', '%'.$search.'%');
                    } elseif (Schema::hasColumn('users', 'phone')) {
                        $q->orWhere('u.phone', 'like', '%'.$search.'%');
                    }
                }
            });
        }

        if ($dateCol) {
            $query->orderBy('rt.'.$dateCol, 'desc');
        } else {
            $query->orderBy('rt.id', 'desc');
        }

        return $query->paginate($perPage);
    }
}
