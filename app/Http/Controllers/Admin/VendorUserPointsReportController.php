<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class VendorUserPointsReportController extends Controller
{
    /**
     * Show the Vendor by User Points Report page
     */
    public function index()
    {
        return view('admin.points.vendor_user_report.index');
    }

    /**
     * DataTables endpoint for Vendor by User Points Report
     */
    public function data(Request $request)
    {
        $table = $this->detectVendorOrderTable();
        if (! $table) {
            return DataTables::of(collect())->with(['summary' => [
                'credit' => 0,
                'debit' => 0,
                'available_points' => 0,
            ]])->make(true);
        }

        $qb = DB::table($table);

        // Join vendor (seller)
        if (Schema::hasTable('vendors') && Schema::hasColumn($table, 'seller_id')) {
            $qb->leftJoin('vendors as v', $table.'.seller_id', '=', 'v.id');
        }

        // Join user (buyer)
        if (Schema::hasTable('users') && Schema::hasColumn($table, 'buyer_id')) {
            $qb->leftJoin('users as u', $table.'.buyer_id', '=', 'u.id');
        } elseif (Schema::hasTable('users') && Schema::hasColumn($table, 'user_id')) {
            $qb->leftJoin('users as u', $table.'.user_id', '=', 'u.id');
        }

        // Select base fields
        $selects = [];
        foreach ([
            'id', 'order_no', 'order_date', 'give_points_status', 'give_points_no', 'apply_points_status', 'apply_points_no', 'total_points',
        ] as $col) {
            if (Schema::hasColumn($table, $col)) {
                $selects[] = $table.'.'.$col;
            }
        }

        // Vendor display fields
        $selects[] = DB::raw('v.id as vendor_id');
        $selects[] = DB::raw('COALESCE(v.business_name, v.contact_person) as vendor_name');
        $selects[] = DB::raw('COALESCE(v.mobile_no, v.contact_mobile_no) as vendor_mobile');

        // User display fields
        $selects[] = DB::raw('u.id as user_id');
        $selects[] = DB::raw('u.name as user_name');

        // Credit / Debit calculations
        $creditExpr = '0';
        if (Schema::hasColumn($table, 'give_points_status') && Schema::hasColumn($table, 'give_points_no')) {
            $creditExpr = "CASE WHEN $table.give_points_status IN ('Yes','Points','points') THEN $table.give_points_no ELSE 0 END";
        }
        $debitExpr = '0';
        if (Schema::hasColumn($table, 'apply_points_status') && Schema::hasColumn($table, 'apply_points_no')) {
            $debitExpr = "CASE WHEN $table.apply_points_status IN ('Yes','Points','points') THEN $table.apply_points_no ELSE 0 END";
        }
        $selects[] = DB::raw($creditExpr.' as credit');
        $selects[] = DB::raw($debitExpr.' as debit');

        $qb->select($selects);

        // Date range filters
        if ($request->filled('from_date')) {
            $qb->where($table.'.order_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $qb->where($table.'.order_date', '<=', $request->input('to_date'));
        }

        // Summary totals
        $summaryQb = clone $qb;
        $totals = $summaryQb->select([
            DB::raw('SUM('.$creditExpr.') as total_credit'),
            DB::raw('SUM('.$debitExpr.') as total_debit'),
        ])->first();

        $summary = [
            'credit' => (float) ($totals->total_credit ?? 0),
            'debit' => (float) ($totals->total_debit ?? 0),
            'available_points' => (float) ($totals->total_credit ?? 0) - (float) ($totals->total_debit ?? 0),
        ];

        return DataTables::of($qb)
            ->addIndexColumn() // Sr. No.
            ->editColumn('order_date', function ($row) {
                return $row->order_date ?? null;
            })
            ->addColumn('vendor_ref', function ($row) {
                $vid = $row->vendor_id ? ('VID'.$row->vendor_id) : '';
                $name = $row->vendor_name ?? '';

                return trim($vid.' ('.$name.')');
            })
            ->addColumn('vendor_mobile', function ($row) {
                return $row->vendor_mobile ?? '';
            })
            ->addColumn('user_ref', function ($row) {
                $uid = $row->user_id ? ('UID'.$row->user_id) : '';
                $name = $row->user_name ?? '';

                return trim($uid.' ('.$name.')');
            })
            ->addColumn('mode', function ($row) {
                $modes = [];
                if (! empty($row->credit)) {
                    $modes[] = 'Give Points';
                }
                if (! empty($row->debit)) {
                    $modes[] = 'Apply Points';
                }

                return implode(' / ', $modes);
            })
            ->addColumn('narration', function ($row) {
                if (! empty($row->credit) && ! empty($row->debit)) {
                    return 'Points given and applied';
                } elseif (! empty($row->credit)) {
                    return 'Points given';
                } elseif (! empty($row->debit)) {
                    return 'Points applied';
                }

                return '';
            })
            ->with(['summary' => $summary])
            ->make(true);
    }

    private function detectVendorOrderTable(): ?string
    {
        try {
            if (Schema::hasTable('vendor_orders')) {
                return 'vendor_orders';
            }
            if (Schema::hasTable('orders')) {
                return 'orders';
            }
            if (Schema::hasTable('user_orders')) {
                return 'user_orders';
            }
        } catch (\Exception $e) {
            // ignore
        }

        return null;
    }
}
