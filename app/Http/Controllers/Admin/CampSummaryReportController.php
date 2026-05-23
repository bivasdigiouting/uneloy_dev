<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\CampDetail;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CampSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CampDetail::query()
                ->with(['camp', 'city'])
                ->select('camp_details.*');

            // Filters
            if ($request->filled('camp_id')) {
                $query->where('camp_id', (int) $request->camp_id);
            }
            if ($request->filled('city_id')) {
                $query->where('city_id', (int) $request->city_id);
            }
            if ($request->filled('from_date')) {
                $query->whereDate('from_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('to_date', '<=', $request->to_date);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('camp_name', function ($row) {
                    return $row->camp->camp_name ?? '-';
                })
                ->editColumn('city_name', function ($row) {
                    return $row->city->city_name ?? '-';
                })
                ->editColumn('title', function ($row) {
                    return $row->title ?? '-';
                })
                ->editColumn('capacity', function ($row) {
                    return $row->capacity ?? 0;
                })
                ->addColumn('total_participants', function ($row) {
                    return $this->getParticipantsCount($row);
                })
                ->editColumn('from_date', function ($row) {
                    return $row->from_date ? $row->from_date->format('Y-m-d') : '-';
                })
                ->editColumn('to_date', function ($row) {
                    return $row->to_date ? $row->to_date->format('Y-m-d') : '-';
                })
                ->editColumn('banner', function ($row) {
                    $url = $row->banner_url ?? null;

                    return $url ? '<img src="'.e($url).'" alt="Banner" style="height:40px;width:60px;object-fit:cover;" />' : '-';
                })
                ->addColumn('actions', function ($row) {
                    $viewUrl = route('admin.camp-details.edit', $row->id);

                    return '<a href="'.e($viewUrl).'" class="btn btn-sm btn-primary"><i class="ti ti-eye"></i></a>';
                })
                ->rawColumns(['banner', 'actions'])
                ->make(true);
        }

        $camps = Camp::orderBy('camp_name')->get(['id', 'camp_name']);

        return view('admin.reports.camp-summary.index', compact('camps'));
    }

    public function getCitiesByCamp(Request $request)
    {
        $campId = (int) $request->get('camp_id');
        if (! $campId) {
            return response()->json(['data' => []]);
        }
        $cityIds = CampDetail::where('camp_id', $campId)->pluck('city_id')->unique()->filter();
        $cities = City::whereIn('id', $cityIds)->orderBy('city_name')->get(['id', 'city_name']);

        return response()->json(['data' => $cities]);
    }

    private function getParticipantsCount(CampDetail $campDetail): int
    {
        try {
            $schema = DB::getSchemaBuilder();

            // Common possibilities for participant storage
            if ($schema->hasTable('camp_participants')) {
                if ($schema->hasColumn('camp_participants', 'camp_detail_id')) {
                    return (int) DB::table('camp_participants')->where('camp_detail_id', $campDetail->id)->count();
                }
                if ($schema->hasColumn('camp_participants', 'camp_id')) {
                    return (int) DB::table('camp_participants')->where('camp_id', $campDetail->camp_id)->count();
                }
            }

            if ($schema->hasTable('registrations')) {
                // Try linking via city and date range if columns exist
                $query = DB::table('registrations');
                if ($schema->hasColumn('registrations', 'camp_id')) {
                    $query->where('camp_id', $campDetail->camp_id);
                }
                if ($schema->hasColumn('registrations', 'city_id')) {
                    $query->where('city_id', $campDetail->city_id);
                }
                if ($schema->hasColumn('registrations', 'registration_date')) {
                    $query->whereDate('registration_date', '>=', optional($campDetail->from_date)->format('Y-m-d'))
                        ->whereDate('registration_date', '<=', optional($campDetail->to_date)->format('Y-m-d'));
                }

                return (int) $query->count();
            }
        } catch (\Exception $e) {
            // Fallback to 0 on any error
        }

        return 0;
    }
}
