<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedeemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class UserRedeemReportController extends Controller
{
    /**
     * Show the User Redeem Report page.
     */
    public function index(Request $request)
    {
        return view('admin.redeem.user_redeem_report.index');
    }

    /**
     * DataTables endpoint: Aggregated monthly purchases and distribute value per user.
     */
    public function data(Request $request)
    {
        $table = $this->detectOrderTable();
        if (! $table) {
            return DataTables::of(collect([]))->addIndexColumn()->make(true);
        }

        $colBuyer = $this->resolveCol($table, ['buyer_id', 'purchase_id']);
        $colDate = $this->resolveCol($table, ['order_date', 'created_at']);
        $colBilling = $this->resolveCol($table, ['billing_amount']);
        $colGiveStatus = $this->resolveCol($table, ['give_points_status']);
        $colGiveNo = $this->resolveCol($table, ['give_points_no']);
        $colApplyStatus = $this->resolveCol($table, ['apply_points_status']);
        $colApplyNo = $this->resolveCol($table, ['apply_points_no']);

        // If critical columns are missing, return empty
        if (! $colBuyer || ! $colDate || ! $colBilling) {
            return DataTables::of(collect([]))->addIndexColumn()->make(true);
        }

        $qb = DB::table($table)
            ->leftJoin('users as buyer', "$table.$colBuyer", '=', 'buyer.id');

        // Apply month filter
        $filterMonth = trim((string) $request->input('filter_month')); // expected YYYY-MM
        if ($filterMonth !== '' && preg_match('/^\d{4}-\d{2}$/', $filterMonth)) {
            [$year, $month] = explode('-', $filterMonth);
            $qb->whereYear("$table.$colDate", (int) $year)
                ->whereMonth("$table.$colDate", (int) $month);
        }

        // Apply search filter (ID/Name/Email)
        $search = trim((string) $request->input('search_text'));
        if ($search !== '') {
            $qb->where(function ($q) use ($search, $table, $colBuyer) {
                if (is_numeric($search)) {
                    $q->orWhere("$table.$colBuyer", (int) $search);
                    $q->orWhere('buyer.id', (int) $search);
                }
                $q->orWhere('buyer.name', 'like', "%$search%");
                $q->orWhere('buyer.email', 'like', "%$search%");
            });
        }

        // Build aggregated selects
        $monthFormat = "DATE_FORMAT($table.$colDate, '%b %Y')";
        $selects = [];
        $selects[] = "$table.$colBuyer as user_id";
        $selects[] = DB::raw('buyer.name as user_name');
        $selects[] = DB::raw("$monthFormat as month_name");
        $selects[] = DB::raw("COALESCE(SUM($table.$colBilling),0) as total_purchase");

        // Points aggregation if columns exist
        $creditExpr = null;
        $debitExpr = null;
        if ($colGiveNo) {
            if ($colGiveStatus) {
                $creditExpr = "SUM(CASE WHEN LOWER($table.$colGiveStatus) = 'yes' OR $table.$colGiveStatus = '1' THEN $table.$colGiveNo ELSE 0 END)";
            } else {
                $creditExpr = "SUM(COALESCE($table.$colGiveNo,0))"; // fallback without status
            }
            $selects[] = DB::raw("COALESCE(($creditExpr),0) as sum_credit_points");
        } else {
            $selects[] = DB::raw('0 as sum_credit_points');
        }
        if ($colApplyNo) {
            if ($colApplyStatus) {
                $debitExpr = "SUM(CASE WHEN LOWER($table.$colApplyStatus) = 'yes' OR $table.$colApplyStatus = '1' THEN $table.$colApplyNo ELSE 0 END)";
            } else {
                $debitExpr = "SUM(COALESCE($table.$colApplyNo,0))";
            }
            $selects[] = DB::raw("COALESCE(($debitExpr),0) as sum_debit_points");
        } else {
            $selects[] = DB::raw('0 as sum_debit_points');
        }

        foreach ($selects as $sel) {
            $qb->addSelect($sel);
        }

        $qb->groupBy("$table.$colBuyer")
            ->groupBy(DB::raw($monthFormat))
            ->orderByRaw("MIN($table.$colDate) DESC");

        $setting = RedeemSetting::query()->first();
        $redeemValue = $setting ? (float) $setting->redeem_value : 0.0;

        return DataTables::of($qb)
            ->addIndexColumn()
            ->addColumn('user_display', function ($row) {
                $id = $row->user_id ?? '';
                $name = $row->user_name ?? '';
                if ($id && $name) {
                    return $id.' ('.$name.')';
                }
                if ($id) {
                    return (string) $id;
                }

                return (string) $name;
            })
            ->addColumn('distribute_value', function ($row) use ($redeemValue) {
                $credit = (float) ($row->sum_credit_points ?? 0);
                $debit = (float) ($row->sum_debit_points ?? 0);
                $available = $credit - $debit;
                $value = $available * $redeemValue;

                return number_format($value, 2);
            })
            ->editColumn('total_purchase', function ($row) {
                return number_format((float) ($row->total_purchase ?? 0), 2);
            })
            ->rawColumns(['user_display'])
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
}
