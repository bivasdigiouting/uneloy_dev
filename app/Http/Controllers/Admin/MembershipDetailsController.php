<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\SecurityAmountMaster;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MembershipDetailsController extends Controller
{
    /**
     * Show the My Membership Details page
     */
    public function index(Request $request)
    {
        return view('admin.membership.details');
    }

    /**
     * DataTables endpoint: Membership details list with filters and export
     */
    public function data(Request $request)
    {
        $query = Registration::query()
            ->select([
                'registrations.id',
                'registrations.user_id',
                'registrations.first_name',
                'registrations.last_name',
                'registrations.mobile_no',
                'registrations.phone_no',
                'registrations.email_id',
                'registrations.gmail_id',
                'registrations.created_at',
                'registrations.date_of_birth',
                'registrations.gender',
                'registrations.current_address',
                'registrations.permanent_address',
                'registrations.state',
                'registrations.district',
                'registrations.city',
                'registrations.pin_code',
                'registrations.bank_name',
                'registrations.account_no',
                'registrations.ifsc_code',
                'registrations.branch_name',
                'registrations.pan_no',
                'registrations.aadhaar_no',
                'registrations.last_qualification',
                'registrations.work_type',
                'registrations.work_experience',
                'registrations.department_level',
                'registrations.business_gst',
                'registrations.business_upi',
                'registrations.status',
            ]);

        // Date range filter (created_at)
        if ($request->filled('from_date')) {
            $query->whereDate('registrations.created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('registrations.created_at', '<=', $request->input('to_date'));
        }

        // Plan type filter (placeholder - no schema available yet)
        // Values: all|recharge1|recharge2 (currently no effect without schema)
        // if ($request->filled('plan_type') && in_array($request->input('plan_type'), ['recharge1','recharge2'])) {
        //     // TODO: Implement real plan type filter once schema is available
        // }

        // Combined Search: by Id / Email / Mobile / User ID
        if ($request->filled('search_id')) {
            $identifier = trim($request->input('search_id'));
            $query->where(function ($q) use ($identifier) {
                $q->where('registrations.id', $identifier)
                    ->orWhere('registrations.user_id', $identifier)
                    ->orWhere('registrations.email_id', 'like', '%'.$identifier.'%')
                    ->orWhere('registrations.mobile_no', 'like', '%'.$identifier.'%');
            });
        }

        // Default department-wise ordering
        $query->orderBy('registrations.department_level');

        // Preload active security amounts (single record)
        $securityAmounts = SecurityAmountMaster::where('is_active', true)
            ->orderByDesc('id')
            ->first();

        $deptMap = [
            'state_level' => 'State e-Card Seva',
            'district_level' => 'District e-Card Seva',
            'block_level' => 'Block - e-Card Seva',
            'panchayat_level' => 'G P M e-Card Seva',
            'village_level' => 'e-Card Seva',
        ];

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('login', function ($row) {
                // Using user_id as login reference (no username field present)
                return $row->user_id ?: 'N/A';
            })
            ->addColumn('profile_photo', function ($row) {
                // No profile image field on registrations; show placeholder
                $img = asset('backend-assets/img/profiles/avatar-02.jpg');

                return '<img src="'.e($img).'" alt="Profile" class="img-thumbnail" style="width:40px;height:40px;object-fit:cover;">';
            })
            ->addColumn('department', function ($row) use ($deptMap) {
                return $deptMap[$row->department_level] ?? ($row->department_level ?: 'N/A');
            })
            ->addColumn('plan_type', function ($row) {
                // Placeholder until plan type schema exists
                return 'N/A';
            })
            ->addColumn('login_id', function ($row) {
                return $row->user_id ?: 'N/A';
            })
            ->addColumn('password', function ($row) {
                // Password is stored hashed; do not expose
                return '********';
            })
            ->addColumn('mpin', function ($row) {
                // No MPin field found in schema
                return 'N/A';
            })
            ->editColumn('first_name', function ($row) {
                return $row->first_name ?: 'N/A';
            })
            ->editColumn('last_name', function ($row) {
                return $row->last_name ?: 'N/A';
            })
            ->editColumn('mobile_no', function ($row) {
                return $row->mobile_no ?: 'N/A';
            })
            ->editColumn('phone_no', function ($row) {
                return $row->phone_no ?: 'N/A';
            })
            ->editColumn('email_id', function ($row) {
                return $row->email_id ?: 'N/A';
            })
            ->editColumn('gmail_id', function ($row) {
                return $row->gmail_id ?: 'N/A';
            })
            ->addColumn('registration_date', function ($row) {
                return optional($row->created_at)->format('d M Y, h:i A') ?: 'N/A';
            })
            ->editColumn('date_of_birth', function ($row) {
                if (! $row->date_of_birth) {
                    return 'N/A';
                }
                try {
                    return \Illuminate\Support\Carbon::parse($row->date_of_birth)->format('d M Y');
                } catch (\Exception $e) {
                    return $row->date_of_birth;
                }
            })
            ->editColumn('gender', function ($row) {
                return $row->gender ?: 'N/A';
            })
            ->editColumn('current_address', function ($row) {
                return $row->current_address ?: 'N/A';
            })
            ->editColumn('permanent_address', function ($row) {
                return $row->permanent_address ?: 'N/A';
            })
            ->editColumn('state', function ($row) {
                return $row->state ?: 'N/A';
            })
            ->editColumn('district', function ($row) {
                return $row->district ?: 'N/A';
            })
            ->editColumn('city', function ($row) {
                return $row->city ?: 'N/A';
            })
            ->editColumn('pin_code', function ($row) {
                return $row->pin_code ?: 'N/A';
            })
            ->editColumn('bank_name', function ($row) {
                return $row->bank_name ?: 'N/A';
            })
            ->editColumn('account_no', function ($row) {
                return $row->account_no ?: 'N/A';
            })
            ->editColumn('ifsc_code', function ($row) {
                return $row->ifsc_code ?: 'N/A';
            })
            ->editColumn('branch_name', function ($row) {
                return $row->branch_name ?: 'N/A';
            })
            ->editColumn('pan_no', function ($row) {
                return $row->pan_no ?: 'N/A';
            })
            ->editColumn('aadhaar_no', function ($row) {
                return $row->aadhaar_no ?: 'N/A';
            })
            ->addColumn('security_amount', function ($row) use ($securityAmounts) {
                if (! $securityAmounts) {
                    return '₹0.00';
                }
                $map = [
                    'state_level' => $securityAmounts->state_level_amount,
                    'district_level' => $securityAmounts->district_level_amount,
                    'block_level' => $securityAmounts->block_level_amount,
                    'panchayat_level' => $securityAmounts->panchayat_level_amount,
                    'village_level' => $securityAmounts->village_level_amount,
                ];
                $amount = $map[$row->department_level] ?? 0;

                return '₹'.number_format((float) $amount, 2);
            })
            ->addColumn('gst_no', function ($row) {
                return $row->business_gst ?: 'N/A';
            })
            ->addColumn('upi_address', function ($row) {
                return $row->business_upi ?: 'N/A';
            })
            ->editColumn('last_qualification', function ($row) {
                return $row->last_qualification ?: 'N/A';
            })
            ->editColumn('work_type', function ($row) {
                return $row->work_type ?: 'N/A';
            })
            ->editColumn('work_experience', function ($row) {
                return $row->work_experience ?: 'N/A';
            })
            ->rawColumns(['profile_photo'])
            ->make(true);
    }
}
