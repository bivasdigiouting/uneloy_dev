<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class UserECardReportController extends Controller
{
    public function index(Request $request)
    {
        $states = DB::table('states')->select('id', 'state_name')->orderBy('state_name')->get();

        return view('admin.user-ecard-report.index', compact('states'));
    }

    public function districts(Request $request)
    {
        $stateId = (int) $request->query('state_id');
        if (! $stateId) {
            return response()->json([]);
        }
        $districts = DB::table('districts')
            ->select('id', 'district_name')
            ->where('state_id', $stateId)
            ->orderBy('district_name')
            ->get();

        return response()->json($districts);
    }

    public function cities(Request $request)
    {
        $districtId = (int) $request->query('district_id');
        if (! $districtId) {
            return response()->json([]);
        }
        $cities = DB::table('cities')
            ->select('id', 'city_name')
            ->where('district_id', $districtId)
            ->orderBy('city_name')
            ->get();

        return response()->json($cities);
    }

    public function data(Request $request)
    {
        // Removed Doctrine DBAL schema manager call to avoid requiring doctrine/dbal
        $filters = [
            'from_date' => $request->query('from_date'),
            'to_date' => $request->query('to_date'),
            'status' => $request->query('status'), // All, Active, De-Active
            'state_id' => $request->query('state_id'),
            'district_id' => $request->query('district_id'),
            'city_id' => $request->query('city_id'),
            'search_text' => $request->query('q'),
        ];

        $query = DB::table('ecard_registrations as er');

        // Date range filter
        if (Schema::hasColumn('ecard_registrations', 'created_at')) {
            if (! empty($filters['from_date']) && ! empty($filters['to_date'])) {
                try {
                    $from = Carbon::parse($filters['from_date'])->startOfDay();
                    $to = Carbon::parse($filters['to_date'])->endOfDay();
                    $query->whereBetween('er.created_at', [$from, $to]);
                } catch (\Exception $e) {
                    // ignore invalid date
                }
            } elseif (! empty($filters['from_date'])) {
                try {
                    $from = Carbon::parse($filters['from_date'])->startOfDay();
                    $query->where('er.created_at', '>=', $from);
                } catch (\Exception $e) {
                }
            } elseif (! empty($filters['to_date'])) {
                try {
                    $to = Carbon::parse($filters['to_date'])->endOfDay();
                    $query->where('er.created_at', '<=', $to);
                } catch (\Exception $e) {
                }
            }
        }

        // Status filter
        if (! empty($filters['status']) && $filters['status'] !== 'All') {
            $strings = $filters['status'] === 'Active'
                ? ['active', 'Active', 'ACTIVE', 'yes', 'true']
                : ['inactive', 'Inactive', 'INACTIVE', 'no', 'false'];
            $numeric = $filters['status'] === 'Active' ? [1, '1'] : [0, '0'];
            $query->where(function ($q) use ($strings, $numeric) {
                if (Schema::hasColumn('ecard_registrations', 'status')) {
                    $q->orWhereIn(DB::raw('LOWER(er.status)'), array_map(fn ($s) => strtolower($s), $strings))
                        ->orWhereIn('er.status', $numeric);
                }
            });
        }

        // Resolve state/district/city names from master tables and filter against string columns in registrations
        $stateName = null;
        if (! empty($filters['state_id'])) {
            $stateName = DB::table('states')->where('id', $filters['state_id'])->value('state_name');
            if ($stateName && Schema::hasColumn('ecard_registrations', 'state')) {
                $query->whereRaw('LOWER(er.state) = ?', [strtolower($stateName)]);
            }
        }
        $districtName = null;
        if (! empty($filters['district_id'])) {
            $districtName = DB::table('districts')->where('id', $filters['district_id'])->value('district_name');
            if ($districtName && Schema::hasColumn('ecard_registrations', 'district')) {
                $query->whereRaw('LOWER(er.district) = ?', [strtolower($districtName)]);
            }
        }
        $cityName = null;
        if (! empty($filters['city_id'])) {
            $cityName = DB::table('cities')->where('id', $filters['city_id'])->value('city_name');
            if ($cityName && Schema::hasColumn('ecard_registrations', 'city')) {
                $query->whereRaw('LOWER(er.city) = ?', [strtolower($cityName)]);
            }
        }

        // Search filter: Id/Name/Email
        if (! empty($filters['search_text'])) {
            $st = trim($filters['search_text']);
            $query->where(function ($q) use ($st) {
                $q->where('er.id', 'like', "%$st%")
                    ->orWhere('er.first_name', 'like', "%$st%")
                    ->orWhere('er.middle_name', 'like', "%$st%")
                    ->orWhere('er.last_name', 'like', "%$st%")
                    ->orWhere('er.email_id', 'like', "%$st%")
                    ->orWhere('er.gmail_id', 'like', "%$st%")
                    ->orWhere('er.mobile_no', 'like', "%$st%")
                    ->orWhere('er.aadhaar_no', 'like', "%$st%");
            });
        }

        // Select columns with best-effort optional fields
        $selects = [
            'er.id',
            'er.first_name', 'er.middle_name', 'er.last_name',
            'er.state', 'er.district', 'er.city',
            'er.status', 'er.created_at',
        ];

        // Safe optional fields for ID and contact
        foreach (['aadhaar_no', 'mobile_no', 'gmail_id', 'email_id'] as $optional) {
            if (Schema::hasColumn('ecard_registrations', $optional)) {
                $selects[] = "er.$optional";
            }
        }

        // Optional e-card specific fields
        foreach (['ecard_number', 'ecard_no', 'eev_no', 'security_number', 'expiry_date', 'print_date'] as $optional) {
            if (Schema::hasColumn('ecard_registrations', $optional)) {
                $selects[] = "er.$optional";
            }
        }

        $query->select($selects);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('select', function ($row) {
                return '<input type="checkbox" class="row-select" value="'.e((string) ($row->id ?? '')).'">';
            })
            ->addColumn('level_id_no', function ($row) {
                // Using Aadhaar or ID as proxy for Level ID No.
                return (string) ($row->aadhaar_no ?? $row->id ?? '');
            })
            ->addColumn('id_no', function ($row) {
                // Using Aadhaar or Mobile as ID No.
                return (string) ($row->aadhaar_no ?? $row->mobile_no ?? $row->id ?? '');
            })
            ->addColumn('state_name', fn ($row) => (string) ($row->state ?? ''))
            ->addColumn('district_name', fn ($row) => (string) ($row->district ?? ''))
            ->addColumn('city_name', fn ($row) => (string) ($row->city ?? ''))
            ->addColumn('ecard_number', function ($row) {
                return (string) ($row->ecard_number ?? $row->ecard_no ?? '');
            })
            ->addColumn('ecard_no', function ($row) {
                return (string) ($row->ecard_no ?? $row->ecard_number ?? '');
            })
            ->addColumn('status_label', function ($row) {
                $raw = $row->status ?? null;
                $isActive = null;
                if (is_numeric($raw)) {
                    $isActive = ((int) $raw === 1);
                } else {
                    $s = strtolower((string) $raw);
                    if (in_array($s, ['active', 'yes', 'true', '1'])) {
                        $isActive = true;
                    } elseif (in_array($s, ['inactive', 'no', 'false', '0'])) {
                        $isActive = false;
                    }
                }
                $class = $isActive === true ? 'badge bg-success' : ($isActive === false ? 'badge bg-danger' : 'badge bg-secondary');
                $text = $isActive === true ? 'Active' : ($isActive === false ? 'De-Active' : 'N/A');

                return '<span class="'.$class.'">'.$text.'</span>';
            })
            ->addColumn('eev_no', fn ($row) => (string) ($row->eev_no ?? ''))
            ->addColumn('security_number', fn ($row) => (string) ($row->security_number ?? ''))
            ->addColumn('create_date', fn ($row) => (string) ($row->created_at ?? ''))
            ->addColumn('expiry_date', fn ($row) => (string) ($row->expiry_date ?? ''))
            ->addColumn('status2', function ($row) {
                // Placeholder second status column if present
                $status = property_exists($row, 'card_status') ? (string) $row->card_status : '';

                return $status;
            })
            ->addColumn('print_date', fn ($row) => (string) ($row->print_date ?? ''))
            ->addColumn('action', function ($row) {
                $id = (string) ($row->id ?? '');
                $url = route('admin.user-ecard-report.print', ['id' => $id]);

                return '<a href="'.$url.'" class="btn btn-sm btn-primary" target="_blank">Click to print</a>';
            })
            ->rawColumns(['select', 'status_label', 'action'])
            ->make(true);
    }

    public function print(string $id)
    {
        $row = DB::table('ecard_registrations')->where('id', $id)->first();
        if (! $row) {
            abort(404);
        }

        return view('admin.user-ecard-report.print', ['record' => $row]);
    }
}
