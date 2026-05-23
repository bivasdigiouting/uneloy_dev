<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HelpSupportReportController extends Controller
{
    public function index()
    {
        // Provide states for filter dropdowns
        $states = DB::table('states')
            ->select('id', 'state_name')
            ->orderBy('state_name')
            ->get();

        return view('admin.reports.help-support.index', compact('states'));
    }

    public function data(Request $request)
    {
        // Base query: helplines with location names
        $query = DB::table('helplines as h')
            ->leftJoin('states as s', 's.id', '=', 'h.state_id')
            ->leftJoin('districts as d', 'd.id', '=', 'h.district_id')
            ->leftJoin('cities as c', 'c.id', '=', 'h.city_id')
            ->select([
                'h.id',
                'h.helpline_name',
                'h.helpline_number',
                DB::raw('COALESCE(s.state_name, "-") as state_name'),
                DB::raw('COALESCE(d.district_name, "-") as district_name'),
                DB::raw('COALESCE(c.city_name, "-") as city_name'),
                'h.created_at',
            ]);

        // Filters
        if ($stateId = $request->get('state_id')) {
            $query->where('h.state_id', $stateId);
        }
        if ($districtId = $request->get('district_id')) {
            $query->where('h.district_id', $districtId);
        }
        if ($cityId = $request->get('city_id')) {
            $query->where('h.city_id', $cityId);
        }
        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('h.helpline_name', 'like', "%$search%")
                    ->orWhere('h.helpline_number', 'like', "%$search%")
                    ->orWhere('s.state_name', 'like', "%$search%")
                    ->orWhere('d.district_name', 'like', "%$search%")
                    ->orWhere('c.city_name', 'like', "%$search%");
            });
        }

        // Date range filter (optional)
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        if ($startDate) {
            $query->whereDate('h.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('h.created_at', '<=', $endDate);
        }

        // DataTables paging parameters
        $length = (int) ($request->get('length') ?? 10);
        $start = (int) ($request->get('start') ?? 0);

        // Ordering
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = [
            'created_at',
            'helpline_name',
            'helpline_number',
            'state_name',
            'district_name',
            'city_name',
        ];
        if (isset($columns[$orderColumnIndex])) {
            $query->orderBy($columns[$orderColumnIndex], $orderDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('h.created_at', 'desc');
        }

        // Summary count before paging
        $summaryCount = (clone $query)->count();

        // Paging
        $dataRows = $query->skip($start)->take($length)->get();

        // Map data for DataTables
        $data = $dataRows->map(function ($row) {
            return [
                'created_at' => (string) $row->created_at,
                'helpline_name' => $row->helpline_name,
                'helpline_number' => $row->helpline_number,
                'state_name' => $row->state_name,
                'district_name' => $row->district_name,
                'city_name' => $row->city_name,
            ];
        });

        return response()->json([
            'draw' => (int) ($request->get('draw') ?? 1),
            'recordsTotal' => $summaryCount,
            'recordsFiltered' => $summaryCount,
            'summary' => [
                'count' => $summaryCount,
            ],
            'data' => $data,
        ]);
    }
}
