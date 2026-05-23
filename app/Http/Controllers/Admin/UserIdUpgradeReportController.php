<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserIdUpgradeReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.user-id-upgrade.index');
    }

    public function data(Request $request)
    {
        // Detect a plausible orders/transactions table
        $orderTables = ['user_orders', 'orders', 'recharge_orders', 'transactions'];
        $orderTable = null;
        foreach ($orderTables as $t) {
            if (Schema::hasTable($t)) {
                $orderTable = $t;
                break;
            }
        }
        if (! $orderTable) {
            return response()->json([
                'data' => [],
                'summary' => ['total' => 0, 'average' => 0, 'count' => 0],
                'message' => 'No base order/transaction table found.',
            ]);
        }

        // Date column detection
        $dateCandidates = ['order_date', 'created_at', 'date', 'transaction_date', 'updated_at'];
        $dateColumn = 'created_at';
        foreach ($dateCandidates as $col) {
            if (Schema::hasColumn($orderTable, $col)) {
                $dateColumn = $col;
                break;
            }
        }

        // Upgrade amount detection (sum of possible columns)
        $upgradeCols = [
            'upgrade_amount',
            'user_upgrade_amount',
            'upgrade_fee',
            'upgrade_charge',
            'upgrade_commission',
            'upgrade_amt',
        ];
        $exprParts = [];
        foreach ($upgradeCols as $col) {
            if (Schema::hasColumn($orderTable, $col)) {
                $exprParts[] = "IFNULL($orderTable.$col, 0)";
            }
        }
        $upgradeExprSql = count($exprParts) ? '('.implode(' + ', $exprParts).')' : '0';

        $query = DB::table($orderTable.' as o');

        $selects = [
            DB::raw('o.id as order_id'),
            DB::raw("o.$dateColumn as order_date"),
            DB::raw($upgradeExprSql.' as upgrade_amount'),
        ];
        foreach (['order_number', 'user_name', 'user_mobile', 'old_user_id', 'new_user_id', 'previous_level', 'new_level'] as $opt) {
            if (Schema::hasColumn($orderTable, $opt)) {
                $selects[] = DB::raw("o.$opt");
            }
        }
        $query->select($selects);

        // Optional: only upgrades if there is a boolean flag present
        if (Schema::hasColumn($orderTable, 'is_upgrade')) {
            $query->where('o.is_upgrade', 1);
        }

        // Filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate("o.$dateColumn", '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate("o.$dateColumn", '<=', $endDate);
        }

        if ($request->filled('order_number') && Schema::hasColumn($orderTable, 'order_number')) {
            $query->where('o.order_number', $request->input('order_number'));
        }
        if ($request->filled('min_upgrade')) {
            $query->whereRaw($upgradeExprSql.' >= ?', [(float) $request->input('min_upgrade')]);
        }
        if ($request->filled('max_upgrade')) {
            $query->whereRaw($upgradeExprSql.' <= ?', [(float) $request->input('max_upgrade')]);
        }

        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $query->where(function ($q) use ($orderTable, $term) {
                foreach (['order_number', 'user_name', 'user_mobile', 'old_user_id', 'new_user_id'] as $col) {
                    if (Schema::hasColumn($orderTable, $col)) {
                        $q->orWhere("o.$col", 'like', $term);
                    }
                }
            });
        }

        $query->orderBy("o.$dateColumn", 'desc');

        // DataTables pagination
        $length = (int) ($request->input('length', 10));
        $start = (int) ($request->input('start', 0));
        $page = (int) floor($start / max($length, 1)) + 1;
        $paginator = $query->paginate($length > 0 ? $length : 10, ['*'], 'page', $page);
        $rows = $paginator->items();

        // Summary mirrors filters
        $sumQ = DB::table($orderTable.' as o')
            ->selectRaw('SUM('.$upgradeExprSql.') as total, AVG('.$upgradeExprSql.') as average, COUNT(*) as count');
        if (Schema::hasColumn($orderTable, 'is_upgrade')) {
            $sumQ->where('o.is_upgrade', 1);
        }
        if ($startDate) {
            $sumQ->whereDate("o.$dateColumn", '>=', $startDate);
        }
        if ($endDate) {
            $sumQ->whereDate("o.$dateColumn", '<=', $endDate);
        }
        if ($request->filled('order_number') && Schema::hasColumn($orderTable, 'order_number')) {
            $sumQ->where('o.order_number', $request->input('order_number'));
        }
        if ($request->filled('min_upgrade')) {
            $sumQ->whereRaw($upgradeExprSql.' >= ?', [(float) $request->input('min_upgrade')]);
        }
        if ($request->filled('max_upgrade')) {
            $sumQ->whereRaw($upgradeExprSql.' <= ?', [(float) $request->input('max_upgrade')]);
        }
        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $sumQ->where(function ($q) use ($orderTable, $term) {
                foreach (['order_number', 'user_name', 'user_mobile', 'old_user_id', 'new_user_id'] as $col) {
                    if (Schema::hasColumn($orderTable, $col)) {
                        $q->orWhere("o.$col", 'like', $term);
                    }
                }
            });
        }
        $summary = $sumQ->first();

        $data = array_map(function ($row) {
            return [
                'order_number' => $row->order_number ?? '-/-',
                'order_date' => isset($row->order_date) ? (string) $row->order_date : null,
                'user_name' => $row->user_name ?? null,
                'user_mobile' => $row->user_mobile ?? null,
                'old_user_id' => $row->old_user_id ?? null,
                'new_user_id' => $row->new_user_id ?? null,
                'upgrade_amount' => number_format((float) ($row->upgrade_amount ?? 0), 2),
            ];
        }, $rows);

        return response()->json([
            'draw' => (int) ($request->input('draw', 1)),
            'recordsTotal' => (int) $paginator->total(),
            'recordsFiltered' => (int) $paginator->total(),
            'data' => $data,
            'summary' => [
                'total' => number_format((float) ($summary->total ?? 0), 2),
                'average' => number_format((float) ($summary->average ?? 0), 2),
                'count' => (int) ($summary->count ?? 0),
            ],
        ]);
    }
}
