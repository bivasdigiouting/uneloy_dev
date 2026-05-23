<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VendorGlobalFundReportController extends Controller
{
    /** Show Vendor Global Disburs. Fund Report page */
    public function index()
    {
        return view('admin.vendor-global-fund-report.index');
    }

    /** DataTables endpoint: List vendors with latest and total distributed fund within date range and search */
    public function data(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $search = trim((string) $request->input('search_text'));

        // Base vendor query
        $query = Vendor::query()
            ->select([
                'vendors.id',
                'vendors.business_name',
                'vendors.contact_person',
                'vendors.gmail_id',
                'vendors.mobile_no',
                DB::raw('latest_row.created_at as latest_date'),
                DB::raw('latest_row.amount as latest_amount'),
                DB::raw('sum_vgd.total_amount as total_amount'),
            ]);

        // Sum subquery within date range
        $sumSub = DB::table('vendor_global_vendor_distributions as vgd')
            ->select('vgd.vendor_id', DB::raw('SUM(vgd.amount) as total_amount'))
            ->when($fromDate, function ($q) use ($fromDate) {
                $q->whereDate('vgd.created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($q) use ($toDate) {
                $q->whereDate('vgd.created_at', '<=', $toDate);
            })
            ->groupBy('vgd.vendor_id');

        // Latest id subquery within date range
        $latestIdSub = DB::table('vendor_global_vendor_distributions as vgd2')
            ->select('vgd2.vendor_id', DB::raw('MAX(vgd2.id) as latest_id'))
            ->when($fromDate, function ($q) use ($fromDate) {
                $q->whereDate('vgd2.created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($q) use ($toDate) {
                $q->whereDate('vgd2.created_at', '<=', $toDate);
            })
            ->groupBy('vgd2.vendor_id');

        $query->leftJoinSub($sumSub, 'sum_vgd', function ($join) {
            $join->on('sum_vgd.vendor_id', '=', 'vendors.id');
        });

        $query->leftJoinSub($latestIdSub, 'latest_vgd', function ($join) {
            $join->on('latest_vgd.vendor_id', '=', 'vendors.id');
        });

        $query->leftJoin('vendor_global_vendor_distributions as latest_row', 'latest_row.id', '=', 'latest_vgd.latest_id');

        // Search across ID / Name / Email (and mobile for convenience)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->orWhere('vendors.id', 'like', "%$search%")
                    ->orWhere('vendors.business_name', 'like', "%$search%")
                    ->orWhere('vendors.contact_person', 'like', "%$search%")
                    ->orWhere('vendors.gmail_id', 'like', "%$search%")
                    ->orWhere('vendors.mobile_no', 'like', "%$search%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('vendor_id', function ($row) {
                return $row->id;
            })
            ->addColumn('vendor_name', function ($row) {
                return $row->business_name ?: ($row->contact_person ?: '');
            })
            ->addColumn('date', function ($row) {
                return $row->latest_date ? date('Y-m-d', strtotime($row->latest_date)) : '';
            })
            ->addColumn('distributed_fund', function ($row) {
                return number_format((float) ($row->latest_amount ?? 0), 2);
            })
            ->addColumn('total_distributed_fund', function ($row) {
                return number_format((float) ($row->total_amount ?? 0), 2);
            })
            ->make(true);
    }
}
