<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class AdminUserPointsReportController extends Controller
{
    /**
     * Show the Admin by User Point Report page.
     */
    public function index(Request $request)
    {
        return view('admin.points.admin_user_report.index');
    }

    /**
     * DataTables endpoint: Points report by order with filters and summary.
     */
    public function data(Request $request)
    {
        $table = $this->detectOrderTable();

        // If no orders table exists, return empty dataset with zero summary
        if (! $table) {
            $emptySummary = [
                'available_total_points' => 0,
                'sum_credit' => 0,
                'sum_debit' => 0,
            ];

            return DataTables::of(collect([]))
                ->addIndexColumn()
                ->with('summary', $emptySummary)
                ->make(true);
        }

        $query = $this->buildBaseQuery($table);
        $query = $this->applyFilters($query, $table, $request);

        $summary = $this->computeSummary($table, $request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('order_date', function ($row) {
                $date = $row->order_date ?? null;
                if (! $date) {
                    return '';
                }
                try {
                    return date('d-M-Y', strtotime($date));
                } catch (\Exception $e) {
                    return (string) $date;
                }
            })
            ->addColumn('credit', function ($row) {
                $status = (string) ($row->give_points_status ?? '');
                $no = (float) ($row->give_points_no ?? 0);
                if (strtolower($status) === 'yes' || $status === '1') {
                    return $no;
                }

                return 0;
            })
            ->addColumn('debit', function ($row) {
                $status = (string) ($row->apply_points_status ?? '');
                $no = (float) ($row->apply_points_no ?? 0);
                if (strtolower($status) === 'yes' || $status === '1') {
                    return $no;
                }

                return 0;
            })
            ->addColumn('mode', function ($row) {
                $credit = (float) ($row->give_points_no ?? 0);
                $debit = (float) ($row->apply_points_no ?? 0);
                if ($credit > 0) {
                    return 'Credit';
                }
                if ($debit > 0) {
                    return 'Debit';
                }

                return '-';
            })
            ->addColumn('narration', function ($row) {
                $orderNo = $row->order_no ?? '';
                $credit = (float) ($row->give_points_no ?? 0);
                $debit = (float) ($row->apply_points_no ?? 0);
                if ($credit > 0) {
                    return 'Points credited for order '.$orderNo;
                }
                if ($debit > 0) {
                    return 'Points debited for order '.$orderNo;
                }

                return 'No points activity';
            })
            ->with('summary', $summary)
            ->make(true);
    }

    /**
     * Detect the available orders table.
     */
    private function detectOrderTable(): ?string
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('orders')) {
                return 'orders';
            }
            if (DB::getSchemaBuilder()->hasTable('user_orders')) {
                return 'user_orders';
            }
        } catch (\Exception $e) {
            // ignore
        }

        return null;
    }

    /**
     * Build a base query selecting fields relevant to points report.
     */
    private function buildBaseQuery(string $table)
    {
        $qb = DB::table($table);

        // Select identifiers and dates
        $selects = [];
        $selects[] = "$table.id as id";
        $selects[] = $this->selectCol($table, 'order_no', 'order_no');
        $selects[] = $this->selectCol($table, 'order_number', 'order_no'); // alt mapping
        $selects[] = $this->selectCol($table, 'order_date', 'order_date');

        // Points-related columns
        $selects[] = $this->selectCol($table, 'give_points_status', 'give_points_status');
        $selects[] = $this->selectCol($table, 'give_points_no', 'give_points_no');
        $selects[] = $this->selectCol($table, 'apply_points_status', 'apply_points_status');
        $selects[] = $this->selectCol($table, 'apply_points_no', 'apply_points_no');
        $selects[] = $this->selectCol($table, 'total_points', 'total_points');

        foreach ($selects as $sel) {
            if ($sel) {
                $qb->addSelect($sel);
            }
        }

        return $qb;
    }

    /**
     * Apply filters from request.
     */
    private function applyFilters($qb, string $table, Request $request)
    {
        // Date range filters (order_date)
        if ($request->filled('from_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date') && Schema::hasColumn($table, 'order_date')) {
            $qb->whereDate("$table.order_date", '<=', $request->input('to_date'));
        }

        return $qb;
    }

    /**
     * Compute totals for points across filtered dataset.
     */
    private function computeSummary(string $table, Request $request): array
    {
        $base = $this->buildBaseQuery($table);
        $base = $this->applyFilters($base, $table, $request);

        $colGive = $this->resolveCol($table, ['give_points_no']);
        $colApply = $this->resolveCol($table, ['apply_points_no']);
        $colTotal = $this->resolveCol($table, ['total_points']);

        $summary = [
            'available_total_points' => 0,
            'sum_credit' => 0,
            'sum_debit' => 0,
        ];

        try {
            $selects = [];
            if ($colGive) {
                $selects[] = "COALESCE(SUM($table.$colGive),0) as sum_credit";
            }
            if ($colApply) {
                $selects[] = "COALESCE(SUM($table.$colApply),0) as sum_debit";
            }
            if (! empty($selects)) {
                $row = (clone $base)->selectRaw(implode(', ', $selects))->first();
                $summary['sum_credit'] = isset($row->sum_credit) ? (float) $row->sum_credit : 0.0;
                $summary['sum_debit'] = isset($row->sum_debit) ? (float) $row->sum_debit : 0.0;
                $summary['available_total_points'] = $summary['sum_credit'] - $summary['sum_debit'];
            } elseif ($colTotal) {
                $row = (clone $base)->selectRaw("COALESCE(SUM($table.$colTotal),0) as available_total_points")->first();
                $summary['available_total_points'] = isset($row->available_total_points) ? (float) $row->available_total_points : 0.0;
            }
        } catch (\Exception $e) {
            // ignore, keep zeros
        }

        return $summary;
    }

    /**
     * Helper: Resolve first available column from list.
     */
    private function resolveCol(string $table, array $candidates): ?string
    {
        foreach ($candidates as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }

        return null;
    }

    /**
     * Helper: Build select string or NULL alias if missing.
     */
    private function selectCol(string $table, string $column, string $alias)
    {
        if (Schema::hasColumn($table, $column)) {
            return "$table.$column as $alias";
        }

        return DB::raw("NULL as $alias");
    }
}
