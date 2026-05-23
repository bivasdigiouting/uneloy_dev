<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class ECardSevaSummaryController extends Controller
{
    /**
     * Show the E-Card Seva Summary page
     */
    public function index(Request $request)
    {
        $states = DB::table('states')->select('id', 'state_name')->orderBy('state_name')->get();
        $statuses = ['All', 'Active', 'Inactive'];

        return view('admin.ecard-seva-summary.index', compact('states', 'statuses'));
    }

    /**
     * DataTables endpoint: aggregated E-Card registration summary
     */
    public function data(Request $request)
    {
        $filters = [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'status' => $request->input('status'),
            'state_id' => $request->input('state_id'),
            'district_id' => $request->input('district_id'),
            'city_id' => $request->input('city_id'),
            'q' => $request->input('q'),
        ];

        // Base registration query with filters applied
        $base = DB::table('ecard_registrations as er');

        // Date range filter guarded by column existence
        if (Schema::hasColumn('ecard_registrations', 'created_at')) {
            $from = trim((string) ($filters['from_date'] ?? ''));
            $to = trim((string) ($filters['to_date'] ?? ''));
            if ($from !== '' && $to !== '') {
                try {
                    $start = Carbon::parse($from)->startOfDay();
                    $end = Carbon::parse($to)->endOfDay();
                    $base->whereBetween('er.created_at', [$start, $end]);
                } catch (\Throwable $e) {
                }
            } elseif ($from !== '') {
                try {
                    $start = Carbon::parse($from)->startOfDay();
                    $base->where('er.created_at', '>=', $start);
                } catch (\Throwable $e) {
                }
            } elseif ($to !== '') {
                try {
                    $end = Carbon::parse($to)->endOfDay();
                    $base->where('er.created_at', '<=', $end);
                } catch (\Throwable $e) {
                }
            }
        }

        // Status filter (Active/Inactive) robust handling
        if (! empty($filters['status']) && $filters['status'] !== 'All') {
            $isActive = $filters['status'] === 'Active';
            $strings = $isActive ? ['active', 'yes', 'true'] : ['inactive', 'no', 'false'];
            $numeric = $isActive ? [1, '1'] : [0, '0'];
            $base->where(function ($q) use ($strings, $numeric) {
                if (Schema::hasColumn('ecard_registrations', 'status')) {
                    $q->orWhereIn(DB::raw('LOWER(er.status)'), array_map(fn ($s) => strtolower($s), $strings))
                        ->orWhereIn('er.status', $numeric);
                }
            });
        }

        // Resolve and apply state/district/city filters if present
        if (! empty($filters['state_id'])) {
            $stateName = DB::table('states')->where('id', $filters['state_id'])->value('state_name');
            if ($stateName && Schema::hasColumn('ecard_registrations', 'state')) {
                $base->whereRaw('LOWER(er.state) = ?', [strtolower($stateName)]);
            }
        }
        if (! empty($filters['district_id'])) {
            $districtName = DB::table('districts')->where('id', $filters['district_id'])->value('district_name');
            if ($districtName && Schema::hasColumn('ecard_registrations', 'district')) {
                $base->whereRaw('LOWER(er.district) = ?', [strtolower($districtName)]);
            }
        }
        if (! empty($filters['city_id'])) {
            $cityName = DB::table('cities')->where('id', $filters['city_id'])->value('city_name');
            if ($cityName && Schema::hasColumn('ecard_registrations', 'city')) {
                $base->whereRaw('LOWER(er.city) = ?', [strtolower($cityName)]);
            }
        }

        // Optional search across grouping fields
        if (! empty($filters['q'])) {
            $st = trim($filters['q']);
            $base->where(function ($q) use ($st) {
                if (Schema::hasColumn('ecard_registrations', 'state')) {
                    $q->orWhere('er.state', 'like', "%$st%");
                }
                if (Schema::hasColumn('ecard_registrations', 'district')) {
                    $q->orWhere('er.district', 'like', "%$st%");
                }
                if (Schema::hasColumn('ecard_registrations', 'city')) {
                    $q->orWhere('er.city', 'like', "%$st%");
                }
            });
        }

        // Build summary query: group by state/district/city
        $query = clone $base;

        // Dynamic select for group fields depending on column existence
        if (Schema::hasColumn('ecard_registrations', 'state')) {
            $query->addSelect('er.state as state');
        } else {
            $query->addSelect(DB::raw('NULL as state'));
        }
        if (Schema::hasColumn('ecard_registrations', 'district')) {
            $query->addSelect('er.district as district');
        } else {
            $query->addSelect(DB::raw('NULL as district'));
        }
        if (Schema::hasColumn('ecard_registrations', 'city')) {
            $query->addSelect('er.city as city');
        } else {
            $query->addSelect(DB::raw('NULL as city'));
        }

        $activeCondition = '( (LOWER(er.status) IN ("active","yes","true")) OR (er.status IN (1, "1")) )';
        $inactiveCondition = '( (LOWER(er.status) IN ("inactive","no","false")) OR (er.status IN (0, "0")) )';

        $query->selectRaw('COUNT(*) AS total_registrations');
        if (Schema::hasColumn('ecard_registrations', 'status')) {
            $query->selectRaw('SUM(CASE WHEN '.$activeCondition.' THEN 1 ELSE 0 END) AS active_count')
                ->selectRaw('SUM(CASE WHEN '.$inactiveCondition.' THEN 1 ELSE 0 END) AS inactive_count');
        } else {
            $query->selectRaw('0 AS active_count')
                ->selectRaw('0 AS inactive_count');
        }

        if (Schema::hasColumn('ecard_registrations', 'created_at')) {
            $query->selectRaw('MAX(er.created_at) AS latest_registration_date');
        } else {
            $query->selectRaw('NULL AS latest_registration_date');
        }

        $query->groupBy('state', 'district', 'city');

        // Prepare totals across all filtered registrations
        $totalsBase = clone $base;
        $totalsBase = $totalsBase
            ->selectRaw('COUNT(*) AS total');
        if (Schema::hasColumn('ecard_registrations', 'status')) {
            $totalsBase = $totalsBase
                ->selectRaw('SUM(CASE WHEN '.$activeCondition.' THEN 1 ELSE 0 END) AS active')
                ->selectRaw('SUM(CASE WHEN '.$inactiveCondition.' THEN 1 ELSE 0 END) AS inactive');
        } else {
            $totalsBase = $totalsBase
                ->selectRaw('0 AS active')
                ->selectRaw('0 AS inactive');
        }
        $totals = $totalsBase->first();
        $sumTotal = (int) ($totals->total ?? 0);
        $sumActive = (int) ($totals->active ?? 0);
        $sumInactive = (int) ($totals->inactive ?? 0);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('state', function ($row) {
                return $row->state ?: 'Unknown';
            })
            ->editColumn('district', function ($row) {
                return $row->district ?: 'Unknown';
            })
            ->editColumn('city', function ($row) {
                return $row->city ?: 'Unknown';
            })
            ->editColumn('latest_registration_date', function ($row) {
                if (! empty($row->latest_registration_date)) {
                    try {
                        return Carbon::parse($row->latest_registration_date)->format('d-M-Y');
                    } catch (\Throwable $e) {
                    }
                }

                return '';
            })
            ->with([
                'sum_total' => $sumTotal,
                'sum_active' => $sumActive,
                'sum_inactive' => $sumInactive,
            ])
            ->make(true);
    }
}
