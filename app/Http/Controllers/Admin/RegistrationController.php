<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationCredentials;
use App\Models\City;
use App\Models\Department;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Panchayat;
use App\Models\Registration;
use App\Models\SecurityAmountMaster;
use App\Models\State;
use App\Models\Village;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Registration::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_details', function ($registration) {
                    $fullName = e($registration->full_name);
                    $email = e($registration->email_id ?? 'No email');
                    $showUrl = route('admin.registrations.show', $registration->id);
                    $avatar = asset('assets/img/profiles/avatar-default.jpg');

                    return '<div class="d-flex align-items-center">'
                        .'<a href="'.$showUrl.'" class="avatar avatar-md me-2">'
                        .'<img src="'.$avatar.'" class="img-fluid rounded-circle" alt="img">'
                        .'</a>'
                        .'<div>'
                        .'<h6 class="fw-medium"><a href="'.$showUrl.'">'.$fullName.'</a></h6>'
                        .'<span class="fs-12 text-muted">'.$email.'</span>'
                        .'</div>'
                        .'</div>';
                })
                ->addColumn('business_name', function ($registration) {
                    return e($registration->business_name ?? '-');
                })
                ->addColumn('contact_info', function ($registration) {
                    $mobile = e($registration->mobile_no ?? 'No mobile');
                    $email = e($registration->email_id ?? 'No email');

                    return '<div>'
                        .'<span class="fw-medium d-block">'.$mobile.'</span>'
                        .'<span class="fs-12 text-muted">'.$email.'</span>'
                        .'</div>';
                })
                ->addColumn('status', function ($registration) {
                    $status = $registration->status ?? 'pending';
                    if ($status === 'approved') {
                        return '<span class="badge bg-success-transparent"><i class="ti ti-check me-1"></i>Approved</span>';
                    } elseif ($status === 'rejected') {
                        return '<span class="badge bg-danger-transparent"><i class="ti ti-x me-1"></i>Rejected</span>';
                    }

                    return '<span class="badge bg-warning-transparent"><i class="ti ti-clock me-1"></i>Pending</span>';
                })
                ->addColumn('created_at', function ($registration) {
                    return $registration->created_at ? $registration->created_at->format('M d, Y') : '';
                })
                ->addColumn('action', function ($registration) {
                    $showUrl = route('admin.registrations.show', $registration->id);
                    $editUrl = route('admin.registrations.edit', $registration->id);
                    $actions = '<div class="dropdown" data-bs-display="static">'
                        .'<a href="javascript:void(0);" class="btn btn-white btn-icon btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">'
                        .'<i class="ti ti-dots-vertical"></i>'
                        .'</a>'
                        .'<ul class="dropdown-menu dropdown-menu-end p-3">'
                        .'<li><a class="dropdown-item rounded-1" href="'.$showUrl.'"><i class="ti ti-eye me-2"></i>View Details</a></li>'
                        .'<li><a class="dropdown-item rounded-1" href="'.$editUrl.'"><i class="ti ti-edit me-2"></i>Edit Registration</a></li>';

                    if (($registration->status ?? 'pending') === 'pending') {
                        $actions .= '<li><hr class="dropdown-divider"></li>'
                            .'<li><a class="dropdown-item rounded-1 text-success" href="javascript:void(0);" onclick="updateStatus('.$registration->id.', \"approved\")"><i class="ti ti-check me-2"></i>Approve</a></li>'
                            .'<li><a class="dropdown-item rounded-1 text-warning" href="javascript:void(0);" onclick="updateStatus('.$registration->id.', \"rejected\")"><i class="ti ti-x me-2"></i>Reject</a></li>';
                    }

                    $actions .= '<li><hr class="dropdown-divider"></li>'
                        .'<li><a class="dropdown-item rounded-1 text-danger" href="javascript:void(0);" onclick="deleteRegistration('.$registration->id.')"><i class="ti ti-trash me-2"></i>Delete Registration</a></li>'
                        .'</ul>'
                        .'</div>';

                    return $actions;
                })
                ->rawColumns(['user_details', 'contact_info', 'status', 'action'])
                ->make(true);
        }

        return view('admin.registrations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Define department levels with their display names
        $departmentLevels = [
            'state_level' => 'State e-Card Seva',
            'district_level' => 'District e-Card Seva',
            'block_level' => 'Block - e-Card Seva',
            'panchayat_level' => 'G P M e-Card Seva',
            'village_level' => 'e-Card Seva',
            'customer' => 'Member',
        ];

        // Get security amounts (get the first active record or create default values)
        $securityAmounts = SecurityAmountMaster::where('is_active', true)->first();
        if (! $securityAmounts) {
            $securityAmounts = (object) [
                'state_level_amount' => 0.00,
                'district_level_amount' => 0.00,
                'block_level_amount' => 0.00,
                'panchayat_level_amount' => 0.00,
                'village_level_amount' => 0.00,
            ];
        }

        // Define business categories
        $businessCategories = [
            'private_limited' => 'Private Limited',
            'proprietorship' => 'Proprietorship',
            'partnership' => 'Partnership',
            'limited' => 'Limited',
            'ngo' => 'NGO',
        ];

        // Departments list for Official Details (default Customer)
        $departments = \App\Models\Department::orderBy('department_name')->get();

        // States for dropdowns
        $states = \App\Models\State::active()->ordered()->get();

        return view('admin.registrations.create', compact('departmentLevels', 'securityAmounts', 'businessCategories', 'departments', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_level' => 'required|in:state_level,district_level,block_level,panchayat_level,village_level,customer',
            'first_name' => 'required|string|max:255',
            'date_of_birth' => ['required', 'date', 'before_or_equal:'.Carbon::now()->subYears(5)->toDateString(), 'after_or_equal:'.Carbon::now()->subYears(60)->toDateString()],
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'nationality' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'city_other' => 'nullable|string|max:255|required_if:city,__other__',
            'area' => 'required|in:Village_area,Municipality_area',
            'panchayat' => 'nullable|string|max:255|required_if:area,Village_area',
            'panchayat_other' => 'nullable|string|max:255|required_if:panchayat,__other__',
            'municipality' => 'nullable|string|max:255|required_if:area,Municipality_area',
            'municipality_other' => 'nullable|string|max:255|required_if:municipality,__other__',
            'village_name' => 'nullable|string|max:255|required_if:area,Village_area',
            'village_other' => 'nullable|string|max:255|required_if:village_name,__other__',
            'ward_no' => 'nullable|string|max:255|required_if:area,Municipality_area',
            'ward_other' => 'nullable|string|max:255|required_if:ward_no,__other__',
            'pin_code' => 'required|string|max:10',
            'mobile_no' => 'required|string|max:15',
            'email_id' => 'nullable|email|max:255',
            'gmail_id' => 'nullable|email|max:255',
            'live_location_map' => 'required|string',
            'aadhaar_no' => 'required|string|max:12',
            'otp_required' => 'required|boolean',
            'otp_verified' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate user ID: first two letters of state + 8-digit random number
        $stateName = strtoupper(substr($request->state, 0, 2));
        do {
            $randomNumber = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $userId = $stateName.$randomNumber;
        } while (Registration::where('user_id', $userId)->exists());

        // Default password
        $passwordPlain = '12345678';
        $passwordHashed = Hash::make($passwordPlain);

        // Prepare data with generated credentials
        $data = $request->all();
        $citySelected = (string) ($data['city'] ?? '');
        $cityName = $citySelected === '__other__'
            ? $this->normalizeName($data['city_other'] ?? null)
            : $this->normalizeName($citySelected);
        if ($cityName === '') {
            return redirect()->back()
                ->withErrors(['city_other' => 'City name is required.'])
                ->withInput();
        }

        [$stateId, $districtId, $cityId] = $this->resolveLocationIds(
            (string) ($data['state'] ?? ''),
            (string) ($data['district'] ?? ''),
            $cityName
        );
        if (! $stateId || ! $districtId) {
            return redirect()->back()
                ->withErrors(['district' => 'Invalid location selected.'])
                ->withInput();
        }
        if (! $cityId) {
            $city = $this->ensureCityExists($cityName, $stateId, $districtId);
            $cityId = (int) $city->id;
        }
        $data['city'] = $cityName;

        // Normalize area-dependent fields
        if (($data['area'] ?? null) === 'Village_area') {

            $panchayatSelected = (string) ($data['panchayat'] ?? '');
            $panchayatName = $panchayatSelected === '__other__'
                ? $this->normalizeName($data['panchayat_other'] ?? null)
                : $this->normalizeName($panchayatSelected);
            if ($panchayatName === '') {
                return redirect()->back()
                    ->withErrors(['panchayat_other' => 'Panchayat name is required.'])
                    ->withInput();
            }
            $this->ensurePanchayatExists($panchayatName, $stateId, $districtId, $cityId);
            $data['panchayat'] = $panchayatName;

            $villageSelected = (string) ($data['village_name'] ?? '');
            $villageName = $villageSelected === '__other__'
                ? $this->normalizeName($data['village_other'] ?? null)
                : $this->normalizeName($villageSelected);
            if ($villageName === '') {
                return redirect()->back()
                    ->withErrors(['village_other' => 'Village name is required.'])
                    ->withInput();
            }
            $this->ensureVillageExists($villageName, $stateId, $districtId, $cityId);
            $data['village_name'] = $villageName;

            $data['municipality'] = null;
            $data['ward_no'] = null;
        } elseif (($data['area'] ?? null) === 'Municipality_area') {
            [$stateId, $districtId, $cityId] = $this->resolveLocationIds(
                (string) ($data['state'] ?? ''),
                (string) ($data['district'] ?? ''),
                (string) ($data['city'] ?? '')
            );
            if (! $stateId || ! $districtId || ! $cityId) {
                return redirect()->back()
                    ->withErrors(['city' => 'Invalid location selected.'])
                    ->withInput();
            }

            $municipalitySelected = (string) ($data['municipality'] ?? '');
            $municipalityName = $municipalitySelected === '__other__'
                ? $this->normalizeName($data['municipality_other'] ?? null)
                : $this->normalizeName($municipalitySelected);
            if ($municipalityName === '') {
                return redirect()->back()
                    ->withErrors(['municipality_other' => 'Municipality name is required.'])
                    ->withInput();
            }
            $municipality = $this->ensureMunicipalityExists($municipalityName, $stateId, $districtId, $cityId);
            $data['municipality'] = $municipalityName;

            $wardSelected = (string) ($data['ward_no'] ?? '');
            $wardName = $wardSelected === '__other__'
                ? $this->normalizeName($data['ward_other'] ?? null)
                : $this->normalizeName($wardSelected);
            if ($wardName === '') {
                return redirect()->back()
                    ->withErrors(['ward_other' => 'Ward no & name is required.'])
                    ->withInput();
            }
            $this->ensureWardExists($wardName, $stateId, $districtId, $cityId, (int) $municipality->id);
            $data['ward_no'] = $wardName;

            $data['panchayat'] = null;
            $data['village_name'] = null;
        }
        $data['user_id'] = $userId;
        $data['password'] = $passwordHashed;

        // Persist
        $registration = Registration::create($data);

        // Send email with credentials
        try {
            Mail::to($registration->email_id)->send(new RegistrationCredentials($registration, $passwordPlain));
        } catch (\Exception $e) {
            \Log::error('Failed to send registration email: '.$e->getMessage());
        }

        return redirect()->route('admin.registrations.index')
            ->with('success', 'Registration created successfully.')
            ->with('user_credentials', [
                'user_id' => $userId,
                'password' => $passwordPlain,
                'email' => $registration->email_id,
                'department' => $registration->department_level,
                'full_name' => $registration->getFullNameAttribute(),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $registration = Registration::find($id);

        if (! $registration) {
            return redirect()->route('admin.registrations.index')
                ->with('error', 'Registration not found.');
        }

        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * Display the form for editing a specific resource.
     */
    public function edit(string $id)
    {
        $registration = Registration::find($id);

        if (! $registration) {
            return redirect()->route('admin.registrations.index')
                ->with('error', 'Registration not found.');
        }

        $departmentLevels = [
            'state_level' => 'State e-Card Seva',
            'district_level' => 'District e-Card Seva',
            'block_level' => 'Block - e-Card Seva',
            'panchayat_level' => 'G P M e-Card Seva',
            'village_level' => 'e-Card Seva',
            'customer' => 'Member',
        ];

        $securityAmounts = SecurityAmountMaster::where('is_active', true)->first();
        if (! $securityAmounts) {
            $securityAmounts = (object) [
                'state_level_amount' => 0.00,
                'district_level_amount' => 0.00,
                'block_level_amount' => 0.00,
                'panchayat_level_amount' => 0.00,
                'village_level_amount' => 0.00,
            ];
        }

        $departments = \App\Models\Department::orderBy('department_name')->get();
        $states = \App\Models\State::active()->ordered()->get();

        return view('admin.registrations.edit', compact('registration', 'departmentLevels', 'securityAmounts', 'departments', 'states'));
    }

    /**
     * Update the specific resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $registration = Registration::find($id);

        if (! $registration) {
            return redirect()->route('admin.registrations.index')
                ->with('error', 'Registration not found.');
        }

        $validator = Validator::make($request->all(), [
            'department_level' => 'required|in:state_level,district_level,block_level,panchayat_level,village_level,customer',
            'first_name' => 'required|string|max:255',
            'date_of_birth' => ['required', 'date', 'before_or_equal:'.Carbon::now()->subYears(5)->toDateString(), 'after_or_equal:'.Carbon::now()->subYears(60)->toDateString()],
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'nationality' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'city_other' => 'nullable|string|max:255|required_if:city,__other__',
            'area' => 'required|in:Village_area,Municipality_area',
            'panchayat' => 'nullable|string|max:255|required_if:area,Village_area',
            'panchayat_other' => 'nullable|string|max:255|required_if:panchayat,__other__',
            'municipality' => 'nullable|string|max:255|required_if:area,Municipality_area',
            'municipality_other' => 'nullable|string|max:255|required_if:municipality,__other__',
            'village_name' => 'nullable|string|max:255|required_if:area,Village_area',
            'village_other' => 'nullable|string|max:255|required_if:village_name,__other__',
            'ward_no' => 'nullable|string|max:255|required_if:area,Municipality_area',
            'ward_other' => 'nullable|string|max:255|required_if:ward_no,__other__',
            'pin_code' => 'required|string|max:10',
            'mobile_no' => 'required|string|max:15',
            'email_id' => 'nullable|email|max:255',
            'gmail_id' => 'nullable|email|max:255',
            'live_location_map' => 'required|string',
            'aadhaar_no' => 'required|string|max:12',
            'otp_required' => 'required|boolean',
            'otp_verified' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $citySelected = (string) ($data['city'] ?? '');
        $cityName = $citySelected === '__other__'
            ? $this->normalizeName($data['city_other'] ?? null)
            : $this->normalizeName($citySelected);
        if ($cityName === '') {
            return redirect()->back()
                ->withErrors(['city_other' => 'City name is required.'])
                ->withInput();
        }

        [$stateId, $districtId, $cityId] = $this->resolveLocationIds(
            (string) ($data['state'] ?? ''),
            (string) ($data['district'] ?? ''),
            $cityName
        );
        if (! $stateId || ! $districtId) {
            return redirect()->back()
                ->withErrors(['district' => 'Invalid location selected.'])
                ->withInput();
        }
        if (! $cityId) {
            $city = $this->ensureCityExists($cityName, $stateId, $districtId);
            $cityId = (int) $city->id;
        }
        $data['city'] = $cityName;

        if (($data['area'] ?? null) === 'Village_area') {

            $panchayatSelected = (string) ($data['panchayat'] ?? '');
            $panchayatName = $panchayatSelected === '__other__'
                ? $this->normalizeName($data['panchayat_other'] ?? null)
                : $this->normalizeName($panchayatSelected);
            if ($panchayatName === '') {
                return redirect()->back()
                    ->withErrors(['panchayat_other' => 'Panchayat name is required.'])
                    ->withInput();
            }
            $this->ensurePanchayatExists($panchayatName, $stateId, $districtId, $cityId);
            $data['panchayat'] = $panchayatName;

            $villageSelected = (string) ($data['village_name'] ?? '');
            $villageName = $villageSelected === '__other__'
                ? $this->normalizeName($data['village_other'] ?? null)
                : $this->normalizeName($villageSelected);
            if ($villageName === '') {
                return redirect()->back()
                    ->withErrors(['village_other' => 'Village name is required.'])
                    ->withInput();
            }
            $this->ensureVillageExists($villageName, $stateId, $districtId, $cityId);
            $data['village_name'] = $villageName;

            $data['municipality'] = null;
            $data['ward_no'] = null;
        } elseif (($data['area'] ?? null) === 'Municipality_area') {
            [$stateId, $districtId, $cityId] = $this->resolveLocationIds(
                (string) ($data['state'] ?? ''),
                (string) ($data['district'] ?? ''),
                (string) ($data['city'] ?? '')
            );
            if (! $stateId || ! $districtId || ! $cityId) {
                return redirect()->back()
                    ->withErrors(['city' => 'Invalid location selected.'])
                    ->withInput();
            }

            $municipalitySelected = (string) ($data['municipality'] ?? '');
            $municipalityName = $municipalitySelected === '__other__'
                ? $this->normalizeName($data['municipality_other'] ?? null)
                : $this->normalizeName($municipalitySelected);
            if ($municipalityName === '') {
                return redirect()->back()
                    ->withErrors(['municipality_other' => 'Municipality name is required.'])
                    ->withInput();
            }
            $municipality = $this->ensureMunicipalityExists($municipalityName, $stateId, $districtId, $cityId);
            $data['municipality'] = $municipalityName;

            $wardSelected = (string) ($data['ward_no'] ?? '');
            $wardName = $wardSelected === '__other__'
                ? $this->normalizeName($data['ward_other'] ?? null)
                : $this->normalizeName($wardSelected);
            if ($wardName === '') {
                return redirect()->back()
                    ->withErrors(['ward_other' => 'Ward no & name is required.'])
                    ->withInput();
            }
            $this->ensureWardExists($wardName, $stateId, $districtId, $cityId, (int) $municipality->id);
            $data['ward_no'] = $wardName;

            $data['panchayat'] = null;
            $data['village_name'] = null;
        }
        $registration->update($data);

        return redirect()->route('admin.registrations.show', $registration->id)
            ->with('success', 'Registration updated successfully.');
    }

    private function normalizeName(mixed $value): string
    {
        $v = trim((string) ($value ?? ''));
        $v = preg_replace('/\s+/', ' ', $v);

        return (string) ($v ?? '');
    }

    private function resolveLocationIds(string $stateName, string $districtName, string $cityName): array
    {
        $stateName = $this->normalizeName($stateName);
        $districtName = $this->normalizeName($districtName);
        $cityName = $this->normalizeName($cityName);

        $state = $stateName !== ''
            ? State::query()->whereRaw('LOWER(state_name) = ?', [mb_strtolower($stateName)])->first()
            : null;
        if (! $state) {
            return [0, 0, 0];
        }

        $district = $districtName !== ''
            ? District::query()
                ->where('state_id', $state->id)
                ->whereRaw('LOWER(district_name) = ?', [mb_strtolower($districtName)])
                ->first()
            : null;
        if (! $district) {
            return [$state->id, 0, 0];
        }

        $city = $cityName !== ''
            ? City::query()
                ->where('state_id', $state->id)
                ->where('district_id', $district->id)
                ->whereRaw('LOWER(city_name) = ?', [mb_strtolower($cityName)])
                ->first()
            : null;
        if (! $city) {
            return [$state->id, $district->id, 0];
        }

        return [$state->id, $district->id, $city->id];
    }

    private function ensureCityExists(string $cityName, int $stateId, int $districtId): City
    {
        $n = $this->normalizeName($cityName);
        $existing = City::query()
            ->where('state_id', $stateId)
            ->where('district_id', $districtId)
            ->whereRaw('LOWER(city_name) = ?', [mb_strtolower($n)])
            ->first();
        if ($existing) {
            return $existing;
        }

        return City::query()->create([
            'city_name' => $n,
            'state_id' => $stateId,
            'district_id' => $districtId,
            'status' => 'active',
        ]);
    }

    private function ensurePanchayatExists(string $panchayatName, int $stateId, int $districtId, int $cityId): void
    {
        $n = $this->normalizeName($panchayatName);
        if ($n === '') {
            return;
        }

        $byCity = Panchayat::query()
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(panchayat_name) = ?', [mb_strtolower($n)])
            ->first();
        if ($byCity) {
            return;
        }

        $global = Panchayat::query()
            ->whereRaw('LOWER(panchayat_name) = ?', [mb_strtolower($n)])
            ->first();
        if ($global) {
            return;
        }

        Panchayat::query()->create([
            'panchayat_name' => $n,
            'state_id' => $stateId,
            'district_id' => $districtId,
            'city_id' => $cityId,
            'status' => 'active',
        ]);
    }

    private function ensureVillageExists(string $villageName, int $stateId, int $districtId, int $cityId): void
    {
        $n = $this->normalizeName($villageName);
        if ($n === '') {
            return;
        }

        $byCity = Village::query()
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(village_name) = ?', [mb_strtolower($n)])
            ->first();
        if ($byCity) {
            return;
        }

        $global = Village::query()
            ->whereRaw('LOWER(village_name) = ?', [mb_strtolower($n)])
            ->first();
        if ($global) {
            return;
        }

        Village::query()->create([
            'village_name' => $n,
            'state_id' => $stateId,
            'district_id' => $districtId,
            'city_id' => $cityId,
            'status' => 'active',
        ]);
    }

    private function ensureMunicipalityExists(string $municipalityName, int $stateId, int $districtId, int $cityId): Municipality
    {
        $n = $this->normalizeName($municipalityName);
        $existing = Municipality::query()
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(municipality_name) = ?', [mb_strtolower($n)])
            ->first();
        if ($existing) {
            return $existing;
        }

        $global = Municipality::query()
            ->whereRaw('LOWER(municipality_name) = ?', [mb_strtolower($n)])
            ->first();
        if ($global) {
            return $global;
        }

        try {
            return Municipality::query()->create([
                'municipality_name' => $n,
                'state_id' => $stateId,
                'district_id' => $districtId,
                'city_id' => $cityId,
                'status' => 'active',
            ]);
        } catch (\Throwable $e) {
            $fallback = Municipality::query()
                ->whereRaw('LOWER(municipality_name) = ?', [mb_strtolower($n)])
                ->first();
            if ($fallback) {
                return $fallback;
            }

            throw $e;
        }
    }

    private function ensureWardExists(string $wardNo, int $stateId, int $districtId, int $cityId, int $municipalityId): Ward
    {
        $n = $this->normalizeName($wardNo);
        $existing = Ward::query()
            ->where('municipality_id', $municipalityId)
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(ward_no) = ?', [mb_strtolower($n)])
            ->first();
        if ($existing) {
            return $existing;
        }

        try {
            return Ward::query()->create([
                'ward_no' => $n,
                'state_id' => $stateId,
                'district_id' => $districtId,
                'city_id' => $cityId,
                'municipality_id' => $municipalityId,
                'status' => 'active',
            ]);
        } catch (\Throwable $e) {
            $fallback = Ward::query()
                ->where('municipality_id', $municipalityId)
                ->whereRaw('LOWER(ward_no) = ?', [mb_strtolower($n)])
                ->first();
            if ($fallback) {
                return $fallback;
            }

            throw $e;
        }
    }
}
