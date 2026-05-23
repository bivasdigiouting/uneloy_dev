<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\CampDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BookCampReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CampDetail::query()
                ->with(['camp'])
                ->select('camp_details.*');

            // Filters
            if ($request->filled('camp_id')) {
                $query->where('camp_id', (int) $request->camp_id);
            }
            if ($request->filled('from_date')) {
                $query->whereDate('from_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('to_date', '<=', $request->to_date);
            }

            $selectedUserType = $request->get('user_type');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('camp_name', function ($row) {
                    return $row->camp->camp_name ?? '-';
                })
                ->editColumn('city_name', function ($row) {
                    return optional($row->city)->city_name ?? '-';
                })
                ->editColumn('title', function ($row) {
                    return $row->title ?? '-';
                })
                ->editColumn('capacity', function ($row) {
                    return $row->capacity ?? 0;
                })
                ->addColumn('total_participants', function ($row) use ($selectedUserType, $request) {
                    return $this->getParticipantsCountByUserType($row, $selectedUserType, $request);
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
                ->addColumn('user_type', function ($row) use ($selectedUserType) {
                    return $this->normalizeUserTypeLabel($selectedUserType) ?? 'All';
                })
                ->addColumn('actions', function ($row) {
                    $viewUrl = route('admin.camp-details.edit', $row->id);

                    return '<a href="'.e($viewUrl).'" class="btn btn-sm btn-primary"><i class="ti ti-eye"></i></a>';
                })
                ->rawColumns(['banner', 'actions'])
                ->make(true);
        }

        $camps = Camp::orderBy('camp_name')->get(['id', 'camp_name']);

        return view('admin.reports.book-camp.index', compact('camps'));
    }

    private function normalizeUserTypeLabel(?string $type): ?string
    {
        if (! $type || strtolower($type) === 'all') {
            return 'All';
        }
        $map = [
            'membership' => 'Membership Member',
            'membership_member' => 'Membership Member',
            'e-store' => 'E-Store Member',
            'estore' => 'E-Store Member',
            'e-store_member' => 'E-Store Member',
            'e-card' => 'E-card Member',
            'ecard' => 'E-card Member',
            'e-card_member' => 'E-card Member',
        ];
        $key = strtolower(str_replace(' ', '_', $type));

        return $map[$key] ?? $type;
    }

    private function getParticipantsCountByUserType(CampDetail $campDetail, ?string $userType, Request $request): int
    {
        try {
            $schema = DB::getSchemaBuilder();
            $from = $request->get('from_date');
            $to = $request->get('to_date');

            $normalized = strtolower($userType ?? 'all');

            if ($normalized === 'membership' || $normalized === 'membership_member') {
                if ($schema->hasTable('registrations')) {
                    $query = DB::table('registrations');
                    // Try to filter by city and/or camp if present
                    if ($schema->hasColumn('registrations', 'city_id')) {
                        $query->where('city_id', $campDetail->city_id);
                    }
                    if ($schema->hasColumn('registrations', 'camp_id')) {
                        $query->where('camp_id', $campDetail->camp_id);
                    }
                    if ($schema->hasColumn('registrations', 'registration_date')) {
                        if ($from) {
                            $query->whereDate('registration_date', '>=', $from);
                        }
                        if ($to) {
                            $query->whereDate('registration_date', '<=', $to);
                        }
                    }

                    return (int) $query->count();
                }
            } elseif ($normalized === 'e-card' || $normalized === 'ecard' || $normalized === 'e-card_member') {
                if ($schema->hasTable('ecard_registrations')) {
                    $query = DB::table('ecard_registrations');
                    // ecard has city (string)
                    if ($schema->hasColumn('ecard_registrations', 'city') && $campDetail->city) {
                        $query->where('city', $campDetail->city->city_name);
                    }
                    if ($schema->hasColumn('ecard_registrations', 'created_at')) {
                        if ($from) {
                            $query->whereDate('created_at', '>=', $from);
                        }
                        if ($to) {
                            $query->whereDate('created_at', '<=', $to);
                        }
                    }

                    return (int) $query->count();
                }
            } elseif ($normalized === 'e-store' || $normalized === 'estore' || $normalized === 'e-store_member') {
                // Fallback: count users possibly linked to e-store activity (no clear table provided)
                if ($schema->hasTable('users')) {
                    $query = DB::table('users');
                    if ($schema->hasColumn('users', 'city')) {
                        $query->where('city', optional($campDetail->city)->city_name);
                    }
                    if ($schema->hasColumn('users', 'created_at')) {
                        if ($from) {
                            $query->whereDate('created_at', '>=', $from);
                        }
                        if ($to) {
                            $query->whereDate('created_at', '<=', $to);
                        }
                    }

                    return (int) $query->count();
                }
            }

            // Default: use generic participants count similar to summary
            if ($schema->hasTable('camp_participants')) {
                if ($schema->hasColumn('camp_participants', 'camp_detail_id')) {
                    return (int) DB::table('camp_participants')->where('camp_detail_id', $campDetail->id)->count();
                }
                if ($schema->hasColumn('camp_participants', 'camp_id')) {
                    return (int) DB::table('camp_participants')->where('camp_id', $campDetail->camp_id)->count();
                }
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
