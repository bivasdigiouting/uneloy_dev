<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LevelCommissionReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.level-commission.index');
    }

    public function data(Request $request)
    {
        // Detect base orders table
        $orderTableCandidates = ['user_orders', 'orders', 'recharge_orders', 'transactions'];
        $orderTable = null;
        foreach ($orderTableCandidates as $table) {
            if (Schema::hasTable($table)) {
                $orderTable = $table;
                break;
            }
        }
        if (! $orderTable) {
            return response()->json([
                'data' => [],
                'summary' => [
                    'total' => 0,
                    'average' => 0,
                    'count' => 0,
                ],
                'message' => 'No order table found.',
            ]);
        }

        // Primary date column detection
        $dateColumns = ['order_date', 'created_at', 'date', 'transaction_date'];
        $dateColumn = 'created_at';
        foreach ($dateColumns as $col) {
            if (Schema::hasColumn($orderTable, $col)) {
                $dateColumn = $col;
                break;
            }
        }

        // Commission amount detection
        $commissionCandidates = [
            'level_commission_amount',
            'level_commission',
            'commission_level_amount',
            'commission_level',
            'l_commission',
            'level_comm_amt',
            'commission_level_rate',
            'commission_amount',
        ];

        $commissionExprParts = [];
        foreach ($commissionCandidates as $col) {
            if (Schema::hasColumn($orderTable, $col)) {
                $commissionExprParts[] = "IFNULL($orderTable.$col, 0)";
            }
        }
        // Fallback: 0 if none found
        $commissionExprSql = count($commissionExprParts) ? '('.implode(' + ', $commissionExprParts).')' : '0';

        $query = DB::table($orderTable.' as o');

        // Selects
        $selects = [
            DB::raw('o.id as order_id'),
            DB::raw("o.$dateColumn as order_date"),
            DB::raw($commissionExprSql.' as level_commission_amount'),
        ];

        // Optional columns for display
        if (Schema::hasColumn($orderTable, 'order_number')) {
            $selects[] = DB::raw('o.order_number');
        }
        if (Schema::hasColumn($orderTable, 'seller_id')) {
            $selects[] = DB::raw('o.seller_id');
        }
        if (Schema::hasColumn($orderTable, 'buyer_id')) {
            $selects[] = DB::raw('o.buyer_id');
        }
        if (Schema::hasColumn($orderTable, 'user_mobile')) {
            $selects[] = DB::raw('o.user_mobile');
        }
        if (Schema::hasColumn($orderTable, 'user_name')) {
            $selects[] = DB::raw('o.user_name');
        }

        $query->select($selects);

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

        // Numeric filters
        if ($request->filled('min_commission')) {
            $query->whereRaw($commissionExprSql.' >= ?', [(float) $request->input('min_commission')]);
        }
        if ($request->filled('max_commission')) {
            $query->whereRaw($commissionExprSql.' <= ?', [(float) $request->input('max_commission')]);
        }

        // Free text search
        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $query->where(function ($q) use ($orderTable, $term) {
                if (Schema::hasColumn($orderTable, 'order_number')) {
                    $q->orWhere('o.order_number', 'like', $term);
                }
                if (Schema::hasColumn($orderTable, 'user_name')) {
                    $q->orWhere('o.user_name', 'like', $term);
                }
                if (Schema::hasColumn($orderTable, 'user_mobile')) {
                    $q->orWhere('o.user_mobile', 'like', $term);
                }
            });
        }

        // Ordering
        $query->orderBy("o.$dateColumn", 'desc');

        // Pagination for DataTables
        $length = (int) ($request->input('length', 10));
        $start = (int) ($request->input('start', 0));
        $page = (int) floor($start / max($length, 1)) + 1;

        $paginator = $query->paginate($length > 0 ? $length : 10, ['*'], 'page', $page);
        $rows = $paginator->items();

        // Summary
        $summaryQuery = DB::table($orderTable.' as o');
        $summaryQuery->selectRaw('SUM('.$commissionExprSql.') as total, AVG('.$commissionExprSql.') as average, COUNT(*) as count');
        // Apply same filters to summary
        if ($startDate) {
            $summaryQuery->whereDate("o.$dateColumn", '>=', $startDate);
        }
        if ($endDate) {
            $summaryQuery->whereDate("o.$dateColumn", '<=', $endDate);
        }
        if ($request->filled('order_number') && Schema::hasColumn($orderTable, 'order_number')) {
            $summaryQuery->where('o.order_number', $request->input('order_number'));
        }
        if ($request->filled('min_commission')) {
            $summaryQuery->whereRaw($commissionExprSql.' >= ?', [(float) $request->input('min_commission')]);
        }
        if ($request->filled('max_commission')) {
            $summaryQuery->whereRaw($commissionExprSql.' <= ?', [(float) $request->input('max_commission')]);
        }
        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $summaryQuery->where(function ($q) use ($orderTable, $term) {
                if (Schema::hasColumn($orderTable, 'order_number')) {
                    $q->orWhere('o.order_number', 'like', $term);
                }
                if (Schema::hasColumn($orderTable, 'user_name')) {
                    $q->orWhere('o.user_name', 'like', $term);
                }
                if (Schema::hasColumn($orderTable, 'user_mobile')) {
                    $q->orWhere('o.user_mobile', 'like', $term);
                }
            });
        }

        $summary = $summaryQuery->first();

        // Format for DataTables
        $data = array_map(function ($row) {
            return [
                'order_id' => $row->order_id ?? null,
                'order_number' => $row->order_number ?? '-/-',
                'order_date' => isset($row->order_date) ? (string) $row->order_date : null,
                'seller_id' => $row->seller_id ?? null,
                'buyer_id' => $row->buyer_id ?? null,
                'user_name' => $row->user_name ?? null,
                'user_mobile' => $row->user_mobile ?? null,
                'level_commission_amount' => number_format((float) ($row->level_commission_amount ?? 0), 2),
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
