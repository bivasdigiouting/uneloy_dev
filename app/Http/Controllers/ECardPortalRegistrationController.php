<?php

namespace App\Http\Controllers;

use App\Mail\ECardRegistrationCredentials;
use App\Mail\RegistrationCredentials;
use App\Models\Bank;
use App\Models\City;
use App\Models\District;
use App\Models\ECardRegistration;
use App\Models\ECardWalletTransaction;
use App\Models\FirstRechargePlan;
use App\Models\Municipality;
use App\Models\Panchayat;
use App\Models\PaymentGateway;
use App\Models\Registration;
use App\Models\State;
use App\Models\Village;
use App\Models\WalletTransaction;
use App\Models\Ward;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class ECardPortalRegistrationController extends Controller
{
    private const OTHER_OPTION_VALUE = '__other__';

    public function create()
    {
        $states = State::active()->ordered()->get(['id', 'state_name']);
        $user = Auth::guard('ecard')->user();
        $businessCategories = [
            'Private limited', 'Proprietorship', 'Partnership', 'Limited', 'NGO',
        ];
        $departments = [
            'State e-Card Seva', 'District e-Card Seva', 'Block - e-Card Seva', 'G P M e-Card Seva', 'e-Card Seva', 'Member', 'Employee',
        ];
        $bloodGroups = ['A-', 'A+', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $banks = Bank::active()->orderBy('bank_name')->get(['id', 'bank_name']);

        $currentSlug = $this->normalizeDepartmentSlug($user->department_level ?? null) ?? 'state_level';
        $nextSlug = $this->nextDepartmentSlug($currentSlug);
        $allowedDepartmentLabel = $this->labelForDepartmentSlug($nextSlug);
        $allowedDepartmentSlug = $nextSlug;

        $firstRechargePlans = collect();
        if ($nextSlug === 'customer') {
            $firstRechargePlans = FirstRechargePlan::query()
                ->where('is_active', true)
                ->orderBy('plan_value')
                ->get();
        }

        $aadhaarPrefill = old('aadhaar_no');
        $verificationIdFromQuery = request()->query('verification_id');
        if ($verificationIdFromQuery) {
            $temp = DB::table('adhar_temp_data')->where('verification_id', $verificationIdFromQuery)->first();
            if ($temp && $temp->adhar_no) {
                $aadhaarPrefill = (string) $temp->adhar_no;
            }
        }

        return view('ecard.registration.create', compact(
            'states',
            'businessCategories',
            'departments',
            'bloodGroups',
            'banks',
            'user',
            'allowedDepartmentSlug',
            'allowedDepartmentLabel',
            'firstRechargePlans',
            'aadhaarPrefill'
        ));
    }

    public function store(Request $request)
    {
        $currentSlug = $this->normalizeDepartmentSlug(Auth::guard('ecard')->user()->department_level ?? null) ?? 'state_level';
        $targetSlug = $this->nextDepartmentSlug($currentSlug);
        $targetLabel = $this->labelForDepartmentSlug($targetSlug);
        $isCustomer = ($targetSlug === 'customer');

        $rules = [
            // Official
            'department_level' => 'required|string',
            'business_category' => 'nullable|string',

            // Personal
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:150',
            'mother_name' => 'nullable|string|max:150',
            'blood_group' => 'required|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Fe-Male,Others,Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Other,Divorced,Widowed',

            // Contact
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'nationality' => 'nullable|string',
            'state_id' => 'required|integer|exists:states,id',
            'district_id' => 'required|integer|exists:districts,id',
            'city_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === self::OTHER_OPTION_VALUE) {
                        return;
                    }

                    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                        $fail('The selected city is invalid.');

                        return;
                    }

                    $exists = City::query()
                        ->whereKey((int) $value)
                        ->where('state_id', (int) $request->input('state_id'))
                        ->where('district_id', (int) $request->input('district_id'))
                        ->exists();

                    if (! $exists) {
                        $fail('The selected city is invalid.');
                    }
                },
            ],
            'city_other' => 'nullable|required_if:city_id,'.self::OTHER_OPTION_VALUE.'|string|max:255',
            'pin_code' => 'required|string|max:10',
            'mobile_no' => 'required|string|min:10|max:20',
            'phone_no' => 'nullable|string|max:20',
            'email_id' => 'required|email',
            'gmail_id' => 'nullable|email',
            'live_location_map' => 'required|string',

            // Bank
            'ifsc_code' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string',
            'branch_name' => 'nullable|string',
            'account_no' => 'nullable|string',
            'pan_no' => 'nullable|string|max:20',
            'aadhaar_no' => 'nullable|string|max:20',

            // Qualification
            'last_qualification' => 'nullable|string',
            'work_type' => 'nullable|string',
            'work_experience' => 'nullable|string',

            // Agreements
            'agree_terms' => 'accepted',

            // Optional image
            'profile_image' => 'nullable|image|max:2048',
        ];

        if ($isCustomer) {
            $rules['customer_user_type'] = 'required|in:free,paid';
            $rules['first_recharge_plan_id'] = [
                'nullable',
                'required_if:customer_user_type,paid',
                Rule::exists('first_recharge_plans', 'id')->where(fn ($q) => $q->where('is_active', true)),
            ];
            $rules['business_name'] = 'nullable|string|max:255';
            $rules['business_mobile'] = 'nullable|string|min:10|max:20';
            $rules['business_whatsapp'] = 'nullable|string|min:10|max:20';
            $rules['business_gmail'] = 'nullable|email';
            $rules['business_address'] = 'nullable|string';
            $rules['business_gst'] = 'nullable|string|max:20';
            $rules['business_upi'] = 'nullable|string|max:100';
            $rules['business_location_map'] = 'nullable|string';
            $rules['aadhaar_no'] = 'required|string|max:20';
            $rules['otp_required'] = 'nullable|boolean';
            $rules['otp_code'] = ['nullable', 'digits:6'];
            $rules['area'] = 'required|in:Village_area,Municipality_area';
            $rules['panchayat'] = 'required_if:area,Village_area';
            $rules['village_name'] = 'required_if:area,Village_area';
            $rules['panchayat_other'] = 'nullable|required_if:panchayat,'.self::OTHER_OPTION_VALUE.'|string|max:255';
            $rules['village_other'] = 'nullable|required_if:village_name,'.self::OTHER_OPTION_VALUE.'|string|max:255';
            $rules['municipality'] = 'required_if:area,Municipality_area';
            $rules['municipality_id'] = 'nullable|integer';
            $rules['municipality_other'] = 'nullable|required_if:municipality,'.self::OTHER_OPTION_VALUE.'|string|max:255';
            $rules['ward_no'] = 'required_if:area,Municipality_area';
            $rules['ward_other'] = 'nullable|required_if:ward_no,'.self::OTHER_OPTION_VALUE.'|string|max:255';
        } else {
            $rules['business_name'] = 'required|string|max:255';
            $rules['business_mobile'] = 'required|string|min:10|max:20';
            $rules['business_whatsapp'] = 'nullable|string|min:10|max:20';
            $rules['business_gmail'] = 'nullable|email';
            $rules['business_address'] = 'required|string';
            $rules['business_gst'] = 'nullable|string|max:20';
            $rules['business_upi'] = 'nullable|string|max:100';
            $rules['business_location_map'] = 'required|string';
            $rules['otp_required'] = 'nullable';
            $rules['otp_code'] = 'nullable';
            $rules['area'] = 'nullable|string';
            $rules['panchayat'] = 'nullable|string';
            $rules['village_name'] = 'nullable|string';
            $rules['municipality'] = 'nullable|string';
            $rules['ward_no'] = 'nullable|string';

            // KYC Documents Validation
            $kycValidation = 'file|mimes:jpeg,png,jpg,pdf|max:2048';
            $rules['aadhaar_front'] = 'required|' . $kycValidation;
            $rules['aadhaar_back'] = 'required|' . $kycValidation;
            $rules['pan_card'] = 'required|' . $kycValidation;
            $rules['cheque_book'] = 'required|' . $kycValidation;
            $rules['business_document'] = 'required|' . $kycValidation;
            $rules['gst_document'] = 'nullable|' . $kycValidation;
            $rules['business_photo'] = 'required|' . $kycValidation;
            $rules['signature'] = 'required|' . $kycValidation;
            $rules['user_photo'] = 'required|' . $kycValidation;
        }

        $request->validate($rules);

        $state = State::find($request->state_id);
        $district = District::find($request->district_id);
        $cityIdInput = (string) $request->input('city_id');
        if ($cityIdInput === self::OTHER_OPTION_VALUE) {
            $cityId = $this->upsertCity(trim((string) $request->input('city_other')), (int) $request->state_id, (int) $request->district_id);
        } else {
            $cityId = (int) $cityIdInput;
        }
        $city = City::find($cityId);
        if (! $city) {
            return back()->withInput()->withErrors(['city_id' => 'The selected city is invalid.']);
        }

        // Generate user ID with state prefix
        $prefix = 'E'.Str::upper(Str::substr($state->state_name, 0, 2));
        $userId = null;
        do {
            $random = (string) random_int(10000000, 99999999);
            $candidate = $prefix.$random;
            $exists = Registration::where('user_id', $candidate)->exists() || ECardRegistration::where('user_id', $candidate)->exists();
            if (! $exists) {
                $userId = $candidate;
            }
        } while (! $userId);

        $passwordPlain = '12345678';
        $passwordHash = Hash::make($passwordPlain);

        // Handle optional image upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('uploads/ecard_registrations', 'public');
        }

        $common = [
            // Business
            'business_name' => $request->business_name,
            'business_mobile' => $request->business_mobile,
            'business_whatsapp' => $request->business_whatsapp,
            'business_gmail' => $request->business_gmail,
            'business_address' => $request->business_address,
            'business_gst' => $request->business_gst,
            'business_upi' => $request->business_upi,
            'business_location_map' => $request->business_location_map,

            // Personal
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'blood_group' => $request->blood_group,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $this->normalizeGender($request->gender),
            'marital_status' => $this->normalizeMaritalStatus($request->marital_status),

            // Contact
            'current_address' => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'nationality' => $request->nationality ?? 'INDIA',
            'state' => $state->state_name,
            'district' => $district->district_name,
            'city' => $city->city_name,
            'pin_code' => $request->pin_code,
            'mobile_no' => $request->mobile_no,
            'phone_no' => $request->phone_no,
            'email_id' => $request->email_id,
            'gmail_id' => $request->gmail_id,
            'live_location_map' => $request->live_location_map,

            // Bank
            'ifsc_code' => $request->ifsc_code,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'account_no' => $request->account_no,
            'pan_no' => $request->pan_no,
            'aadhaar_no' => $request->aadhaar_no,

            // Qualification
            'last_qualification' => $request->last_qualification,
            'work_type' => $request->work_type,
            'work_experience' => $request->work_experience,
        ];
        $parentId = Auth::guard('ecard')->id();

        if ($isCustomer) {
            $customerUserType = (string) $request->input('customer_user_type');
            $selectedPlanId = $request->input('first_recharge_plan_id');
            $successMessage = 'Member registration submitted successfully and credentials emailed.';

            $area = (string) $request->input('area');
            $panchayatName = null;
            $villageName = null;
            $municipalityName = null;
            $wardNo = null;

            if ($area === 'Village_area') {
                $panchayatName = (string) $request->input('panchayat');
                if ($panchayatName === self::OTHER_OPTION_VALUE) {
                    $panchayatName = trim((string) $request->input('panchayat_other'));
                }
                $villageName = (string) $request->input('village_name');
                if ($villageName === self::OTHER_OPTION_VALUE) {
                    $villageName = trim((string) $request->input('village_other'));
                }
            }

            if ($area === 'Municipality_area') {
                $municipalityName = (string) $request->input('municipality');
                if ($municipalityName === self::OTHER_OPTION_VALUE) {
                    $municipalityName = trim((string) $request->input('municipality_other'));
                }
                $wardNo = (string) $request->input('ward_no');
                if ($wardNo === self::OTHER_OPTION_VALUE) {
                    $wardNo = trim((string) $request->input('ward_other'));
                }
            }

            try {
                $registration = DB::transaction(function () use (
                    $common,
                    $userId,
                    $passwordPlain,
                    $passwordHash,
                    $parentId,
                    $request,
                    $profileImagePath,
                    $customerUserType,
                    $selectedPlanId,
                    $area,
                    $panchayatName,
                    $villageName,
                    $municipalityName,
                    $wardNo,
                    $cityId,
                    &$successMessage
                ) {
                    if ($area === 'Village_area') {
                        if ($request->input('panchayat') === self::OTHER_OPTION_VALUE) {
                            $this->upsertPanchayat(trim((string) $panchayatName), (int) $request->state_id, (int) $request->district_id, $cityId);
                        }
                        if ($request->input('village_name') === self::OTHER_OPTION_VALUE) {
                            $this->upsertVillage(trim((string) $villageName), (int) $request->state_id, (int) $request->district_id, $cityId);
                        }
                    }

                    if ($area === 'Municipality_area') {
                        $municipalityId = null;

                        if ($request->input('municipality') === self::OTHER_OPTION_VALUE) {
                            $municipalityId = $this->upsertMunicipality(trim((string) $municipalityName), (int) $request->state_id, (int) $request->district_id, $cityId);
                        } else {
                            $municipalityId = $this->resolveMunicipalityId((int) $request->input('municipality_id'), trim((string) $municipalityName), $cityId);
                        }

                        if ($request->input('ward_no') === self::OTHER_OPTION_VALUE && $municipalityId) {
                            $this->upsertWard(trim((string) $wardNo), (int) $request->state_id, (int) $request->district_id, $cityId, $municipalityId);
                        }
                    }

                    $payload = array_merge($common, [
                        'user_id' => $userId,
                        'password' => $passwordHash,
                        'department_level' => 'customer',
                        'business_category' => null,
                        'parent_id' => $parentId,
                        'area' => $area,
                        'panchayat' => $panchayatName,
                        'municipality' => $municipalityName,
                        'village_name' => $villageName,
                        'ward_no' => $wardNo,
                        'otp_required' => $request->boolean('otp_required'),
                        'otp_code' => $request->boolean('otp_required') ? $request->input('otp_code') : null,
                        'otp_verified' => $request->boolean('otp_required'),
                        'wallet_balance' => 0,
                        'status' => 'pending',
                    ]);

                    if ($profileImagePath) {
                        $payload['profile_image'] = $profileImagePath;
                    }

                    $registration = Registration::create($payload);

                    if ($customerUserType !== 'paid') {
                        Mail::to($registration->email_id)->send(new RegistrationCredentials($registration, $passwordPlain));

                        return redirect()->route('ecard.users.my')->with('success', 'Registration successful! Credentials sent to email.');
                    }

                    // For paid users, redirect to payment selection page
                    session()->put('registration_plan_' . $registration->id, $selectedPlanId);
                    session()->put('registration_password_' . $registration->id, $passwordPlain);
                    
                    return redirect()->route('ecard.registration.payment', ['id' => $registration->id]);
                });

                return $registration;

            } catch (\Exception $e) {
                // If transaction fails or any error occurs
                return back()->withInput()->withErrors(['error' => $e->getMessage()]);
            }
        } else {
            $payload = array_merge($common, [
                'user_id' => $userId,
                'password' => $passwordHash,
                'department_level' => $targetSlug,
                'business_category' => $request->business_category,
                'parent_id' => $parentId,
            ]);

            if ($profileImagePath) {
                $payload['profile_image'] = $profileImagePath;
            }

            // Handle multiple KYC documents
            $kycFields = [
                'aadhaar_front', 'aadhaar_back', 'pan_card', 'cheque_book', 
                'business_document', 'gst_document', 'business_photo', 
                'signature', 'user_photo'
            ];
            
            $kycDocumentsUploaded = false;
            foreach ($kycFields as $field) {
                if ($request->hasFile($field)) {
                    $payload[$field] = $request->file($field)->store('uploads/kyc_documents', 'public');
                    $kycDocumentsUploaded = true;
                }
            }
            
            if ($kycDocumentsUploaded) {
                $payload['kyc_status'] = 'pending';
            }

            try {
                $ecard = DB::transaction(function () use ($payload, $passwordPlain) {
                    $ecard = ECardRegistration::create($payload);
                    Mail::to($ecard->email_id)->send(new ECardRegistrationCredentials($ecard, $passwordPlain));

                    return $ecard;
                });
            } catch (\Throwable $e) {
                Log::error('Failed to create e-card registration or send credentials email', [
                    'message' => $e->getMessage(),
                    'email_id' => (string) $request->input('email_id'),
                ]);

                return back()->withInput()->with('error', 'Registration could not be completed because the credentials email failed to send. Please try again.');
            }
        }

        return redirect()->route('ecard.dashboard')->with('success', 'Registration submitted successfully and credentials emailed.');
    }

    public function verifyAadhaar(Request $request)
    {
        $request->validate([
            'aadhaar_no' => ['required', 'digits:12'],
        ]);

        $aadhaarNo = $request->input('aadhaar_no');

        $random = '';
        for ($i = 0; $i < 15; $i++) {
            $random .= mt_rand(0, 9);
        }
        $orderId = 'AD'.$random;

        $username = '9564853492';
        $token = '6d8b045b5c92ff9916806d5bd652fe6a';

        $baseRedirectUrl = route('ecard.registration.create');

        try {
            $createResponse = Http::get('https://connect.ekychub.in/v3/digilocker/create_url_aadhaar', [
                'username' => $username,
                'token' => $token,
                'redirect_url' => $baseRedirectUrl,
                'orderid' => $orderId,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'Failure',
                'message' => 'Unable to connect to Aadhaar verification service.',
            ], 502);
        }

        $createJson = $createResponse->json();
        $status = $createJson['status'] ?? null;

        if ($status !== 'Success') {
            return response()->json([
                'status' => $status ?: 'Failure',
                'message' => $createJson['message'] ?? 'Aadhaar verification failed.',
            ], 422);
        }

        $verificationId = $createJson['verification_id'] ?? null;
        $referenceId = $createJson['reference_id'] ?? null;
        $url = $createJson['url'] ?? null;

        if (! $verificationId || ! $referenceId) {
            return response()->json([
                'status' => 'Failure',
                'message' => 'Aadhaar verification response missing required identifiers.',
            ], 422);
        }

        DB::table('adhar_temp_data')->updateOrInsert(
            ['adhar_no' => $aadhaarNo],
            [
                'verification_id' => (string) $verificationId,
                'reference_id' => (string) $referenceId,
                'url' => $url,
                'orderid' => $orderId,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'Success',
            'message' => 'Digilocker URL created successfully.',
            'verification_id' => $verificationId,
            'reference_id' => $referenceId,
            'orderid' => $orderId,
            'url' => $url,
        ]);
    }

    public function verifyAadhaarDocument(Request $request)
    {
        $request->validate([
            'verification_id' => ['required'],
        ]);

        $verificationId = (string) $request->input('verification_id');
        $temp = DB::table('adhar_temp_data')->where('verification_id', $verificationId)->first();

        if (! $temp || ! $temp->adhar_no || ! $temp->reference_id || ! $temp->orderid) {
            return response()->json([
                'status' => 'Failure',
                'message' => 'Temporary Aadhaar verification data not found. Please verify Aadhaar again.',
            ], 422);
        }

        $aadhaarNo = (string) $temp->adhar_no;
        $referenceId = (string) $temp->reference_id;
        $orderId = (string) $temp->orderid;

        $username = '9564853492';
        $token = '6d8b045b5c92ff9916806d5bd652fe6a';

        try {
            $documentResponse = Http::get('https://connect.ekychub.in/v3/digilocker/get_document', [
                'username' => $username,
                'token' => $token,
                'verification_id' => $verificationId,
                'reference_id' => $referenceId,
                'orderid' => $orderId,
                'document_type' => 'AADHAAR',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'Failure',
                'message' => 'Unable to fetch Aadhaar document from verification service.',
            ], 502);
        }

        $documentJson = $documentResponse->json();
        $documentStatus = $documentJson['status'] ?? null;

        if ($documentStatus !== 'Success') {
            return response()->json([
                'status' => $documentStatus ?: 'Failure',
                'message' => $documentJson['message'] ?? 'Aadhaar document fetch failed.',
            ], 422);
        }

        DB::table('adhar_verification_details')->updateOrInsert(
            ['adhar_no' => $aadhaarNo],
            [
                'adhar_status' => $documentStatus,
                'verification_id' => $verificationId,
                'adhar_response' => json_encode($documentJson),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'Success',
            'message' => 'Aadhaar verified successfully.',
            'aadhaar_no' => $aadhaarNo,
        ]);
    }

    private function normalizeGender(?string $gender): ?string
    {
        if (! $gender) {
            return null;
        }
        $g = Str::lower($gender);

        return match ($g) {
            'male' => 'Male',
            'female', 'fe-male' => 'Female',
            default => 'Other',
        };
    }

    private function normalizeMaritalStatus(?string $status): ?string
    {
        if (! $status) {
            return null;
        }
        $s = Str::lower($status);

        return match ($s) {
            'single' => 'Single',
            'married' => 'Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            default => 'Other',
        };
    }

    private function normalizeDepartmentSlug(?string $level): ?string
    {
        if (! $level) {
            return null;
        }
        $raw = Str::lower(trim($level));
        $v = str_replace([' ', '-'], ['', '_'], $raw);

        return match (true) {
            str_contains($v, 'state') => 'state_level',
            str_contains($v, 'district') => 'district_level',
            str_contains($v, 'block') => 'block_level',
            str_contains($v, 'panchayat') => 'panchayat_level',
            str_contains($v, 'village') => 'village_level',
            str_contains($v, 'customer') => 'customer',
            $raw === 'member' => 'customer',
            default => null,
        };
    }

    private function nextDepartmentSlug(string $slug): string
    {
        return match ($slug) {
            'state_level' => 'district_level',
            'district_level' => 'block_level',
            'block_level' => 'panchayat_level',
            'panchayat_level' => 'village_level',
            'village_level' => 'customer',
            default => 'customer',
        };
    }

    private function labelForDepartmentSlug(string $slug): string
    {
        return match ($slug) {
            'state_level' => 'State e-Card Seva',
            'district_level' => 'District e-Card Seva',
            'block_level' => 'Block - e-Card Seva',
            'panchayat_level' => 'G P M e-Card Seva',
            'village_level' => 'e-Card Seva',
            'customer' => 'Member',
            default => 'Member',
        };
    }

    private function upsertCity(string $name, int $stateId, int $districtId): ?int
    {
        if ($name === '') {
            return null;
        }

        $existing = City::query()
            ->where('state_id', $stateId)
            ->where('district_id', $districtId)
            ->whereRaw('LOWER(city_name) = ?', [mb_strtolower($name)])
            ->first();

        if ($existing) {
            if (($existing->status ?? null) !== 'active') {
                $existing->update(['status' => 'active']);
            }

            return (int) $existing->id;
        }

        try {
            $created = City::query()->create([
                'city_name' => $name,
                'state_id' => $stateId,
                'district_id' => $districtId,
                'status' => 'active',
            ]);

            return (int) $created->id;
        } catch (QueryException $e) {
            $row = City::query()
                ->where('state_id', $stateId)
                ->where('district_id', $districtId)
                ->whereRaw('LOWER(city_name) = ?', [mb_strtolower($name)])
                ->first();

            if ($row && ($row->status ?? null) !== 'active') {
                $row->update(['status' => 'active']);
            }

            return $row ? (int) $row->id : null;
        }
    }

    private function upsertPanchayat(string $name, int $stateId, int $districtId, int $cityId): void
    {
        if ($name === '') {
            return;
        }

        $existing = Panchayat::query()
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(panchayat_name) = ?', [mb_strtolower($name)])
            ->first();

        if ($existing) {
            if (($existing->status ?? null) !== 'active') {
                $existing->update(['status' => 'active']);
            }

            return;
        }

        try {
            Panchayat::query()->create([
                'panchayat_name' => $name,
                'state_id' => $stateId,
                'district_id' => $districtId,
                'city_id' => $cityId,
                'status' => 'active',
            ]);
        } catch (QueryException $e) {
            $row = Panchayat::query()
                ->where('city_id', $cityId)
                ->whereRaw('LOWER(panchayat_name) = ?', [mb_strtolower($name)])
                ->first();
            if ($row && ($row->status ?? null) !== 'active') {
                $row->update(['status' => 'active']);
            }
        }
    }

    private function upsertVillage(string $name, int $stateId, int $districtId, int $cityId): void
    {
        if ($name === '') {
            return;
        }

        $existing = Village::query()
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(village_name) = ?', [mb_strtolower($name)])
            ->first();

        if ($existing) {
            if (($existing->status ?? null) !== 'active') {
                $existing->update(['status' => 'active']);
            }

            return;
        }

        try {
            Village::query()->create([
                'village_name' => $name,
                'state_id' => $stateId,
                'district_id' => $districtId,
                'city_id' => $cityId,
                'status' => 'active',
            ]);
        } catch (QueryException $e) {
            $row = Village::query()
                ->where('city_id', $cityId)
                ->whereRaw('LOWER(village_name) = ?', [mb_strtolower($name)])
                ->first();
            if ($row && ($row->status ?? null) !== 'active') {
                $row->update(['status' => 'active']);
            }
        }
    }

    private function upsertMunicipality(string $name, int $stateId, int $districtId, int $cityId): ?int
    {
        if ($name === '') {
            return null;
        }

        $existing = Municipality::query()
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(municipality_name) = ?', [mb_strtolower($name)])
            ->first();

        if ($existing) {
            if (($existing->status ?? null) !== 'active') {
                $existing->update(['status' => 'active']);
            }

            return (int) $existing->id;
        }

        try {
            $created = Municipality::query()->create([
                'municipality_name' => $name,
                'state_id' => $stateId,
                'district_id' => $districtId,
                'city_id' => $cityId,
                'status' => 'active',
            ]);

            return (int) $created->id;
        } catch (QueryException $e) {
            $row = Municipality::query()
                ->where('city_id', $cityId)
                ->whereRaw('LOWER(municipality_name) = ?', [mb_strtolower($name)])
                ->first();

            if ($row && ($row->status ?? null) !== 'active') {
                $row->update(['status' => 'active']);
            }

            return $row ? (int) $row->id : null;
        }
    }

    private function resolveMunicipalityId(int $municipalityId, string $municipalityName, int $cityId): ?int
    {
        if ($municipalityId > 0) {
            $valid = Municipality::query()
                ->where('id', $municipalityId)
                ->where('city_id', $cityId)
                ->exists();
            if ($valid) {
                return $municipalityId;
            }
        }

        if ($municipalityName === '') {
            return null;
        }

        $row = Municipality::query()
            ->where('city_id', $cityId)
            ->whereRaw('LOWER(municipality_name) = ?', [mb_strtolower($municipalityName)])
            ->first();

        return $row ? (int) $row->id : null;
    }

    private function upsertWard(string $wardNo, int $stateId, int $districtId, int $cityId, int $municipalityId): void
    {
        if ($wardNo === '') {
            return;
        }

        $existing = Ward::query()
            ->where('municipality_id', $municipalityId)
            ->whereRaw('LOWER(ward_no) = ?', [mb_strtolower($wardNo)])
            ->first();

        if ($existing) {
            if (($existing->status ?? null) !== 'active') {
                $existing->update(['status' => 'active']);
            }

            return;
        }

        Ward::query()->create([
            'ward_no' => $wardNo,
            'state_id' => $stateId,
            'district_id' => $districtId,
            'city_id' => $cityId,
            'municipality_id' => $municipalityId,
            'status' => 'active',
        ]);
    }

    public function paymentSelection($id)
    {
        $registration = Registration::findOrFail($id);
        $planId = session('registration_plan_' . $id);
        
        if (!$planId) {
            return redirect()->route('ecard.users.my')->with('error', 'No plan selected or session expired.');
        }
        
        $plan = FirstRechargePlan::findOrFail($planId);
        $user = Auth::guard('ecard')->user();
        
        // Fetch active payment gateways
        $gateways = PaymentGateway::where('is_enabled', true)->get();
        
        return view('ecard.registration.payment_selection', compact('registration', 'plan', 'user', 'gateways'));
    }

    public function processWalletPayment($id)
    {
        $registration = Registration::findOrFail($id);
        $planId = session('registration_plan_' . $id);
        $passwordPlain = session('registration_password_' . $id);
        
        if (!$planId) {
            return redirect()->route('ecard.users.my')->with('error', 'Session expired. Please try again.');
        }
        
        $plan = FirstRechargePlan::findOrFail($planId);
        $planValue = (float) $plan->plan_value;
        $bonusValue = (float) $plan->bonus_value;
        $totalCredit = $planValue + $bonusValue;
        
        $registeringUser = ECardRegistration::findOrFail(Auth::guard('ecard')->id());
        
        if ($registeringUser->wallet_balance < $planValue) {
            return back()->with('error', 'Insufficient wallet balance.');
        }
        
        try {
            DB::transaction(function () use ($registeringUser, $registration, $plan, $planValue, $bonusValue, $totalCredit, $passwordPlain, $id) {
                // Deduct from parent
                $registeringUser->decrement('wallet_balance', $planValue);
                $registeringUser->refresh(); // Refresh to get updated balance
                
                ECardWalletTransaction::create([
                    'ecard_registration_id' => $registeringUser->id,
                    'transaction_type' => 'remove',
                    'amount' => $planValue,
                    'previous_balance' => $registeringUser->wallet_balance + $planValue,
                    'new_balance' => $registeringUser->wallet_balance,
                    'narration' => "First recharge plan purchase for customer {$registration->user_id} ({$plan->plan_name})",
                    'performed_by_id' => $registeringUser->id,
                    'reference_type' => 'first_recharge_plan',
                    'reference_id' => $plan->id,
                ]);
                
                $this->completeRegistrationSuccess($registration, $plan, $planValue, $bonusValue, $totalCredit, $registeringUser->id, $passwordPlain);
            });
            
            session()->forget('registration_password_' . $id);
            session()->forget('registration_plan_' . $id);
            
            return redirect()->route('ecard.users.my')->with('success', 'Payment successful! Registration completed.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function processGatewayPayment(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        $planId = session('registration_plan_' . $id);
        
        if (!$planId) {
             return redirect()->route('ecard.users.my')->with('error', 'Session expired.');
        }

        $plan = FirstRechargePlan::findOrFail($planId);
        
        $gatewayId = $request->input('gateway_id');
        if (!$gatewayId) {
            return back()->with('error', 'Please select a payment gateway.');
        }

        $gateway = PaymentGateway::findOrFail($gatewayId);

        if ($gateway->slug === 'phonepe') {
            return $this->initiatePhonePePayment($gateway, $registration, $plan);
        } elseif ($gateway->slug === 'cashfree') {
            return $this->initiateCashfreePayment($gateway, $registration, $plan);
        }
        
        return back()->with('error', 'Selected gateway is not supported yet.');
    }

    private function initiateCashfreePayment($gateway, $registration, $plan)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (!$appId || !$secretKey) {
            return back()->with('error', 'Cashfree configuration is missing.');
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        $orderId = 'REG_' . $registration->id . '_' . $plan->id . '_' . time();
        $returnUrl = route('ecard.registration.payment.callback') . '?order_id={order_id}';
        
        $payload = [
            'order_id' => $orderId,
            'order_amount' => (float) $plan->plan_value,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'CUST_' . $registration->user_id,
                'customer_email' => $registration->email ?? 'customer@example.com',
                'customer_phone' => (string) $registration->mobile_no,
            ],
            'order_meta' => [
                'return_url' => $returnUrl,
            ],
            'order_note' => 'Plan Purchase: ' . $plan->name
        ];

        try {
            $response = Http::withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2023-08-01',
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/orders', $payload);

            $resData = $response->json();

            if ($response->successful() && isset($resData['payment_session_id'])) {
                 // For standard checkout, redirect to payment link if available, or use session_id with JS.
                 // The 'payment_link' field is sometimes present or we might need to construct it.
                 // However, Cashfree recommends using their SDK. 
                 // But for backend-only redirect, let's see if 'payment_link' is in the response.
                 // If not, we might need to render a view that auto-submits or uses JS.
                 // Let's check for 'payment_link' first.
                 
                 // Note: If 'payment_link' is not available, we can fallback to a view that uses the session_id.
                 // But for simplicity, let's assume we can get a link or handle it.
                 // Actually, Cashfree's newer API might require a separate call for link or return it.
                 // Let's try to get payment_link if possible.
                 
                 if (isset($resData['payment_link'])) {
                     return redirect()->away($resData['payment_link']);
                 }
                 
                 // If no link, render a view to handle payment session
                 // But we don't have a view for that yet.
                 // Let's try to use the 'payment_session_id' to construct a payment link or redirect?
                 // No, you can't construct it manually.
                 // Let's try to use the 'payment_link' feature. It usually needs to be enabled in dashboard.
                 // Or we can use the 'sessions' endpoint.
                 
                 // Alternative: Create a simple view 'ecard.registration.cashfree_checkout' that takes session_id and redirects using JS.
                 return view('ecard.registration.cashfree_checkout', [
                     'payment_session_id' => $resData['payment_session_id'],
                     'environment' => $env === 'LIVE' || $gateway->active_mode === 'live' ? 'production' : 'sandbox'
                 ]);
            }
            
            Log::error('Cashfree Order Creation Failed', ['response' => $resData]);
            return back()->with('error', 'Failed to initiate payment: ' . ($resData['message'] ?? 'Unknown error'));

        } catch (\Exception $e) {
            Log::error('Cashfree Exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    private function initiatePhonePePayment($gateway, $registration, $plan)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $merchantId = $config['client_id'] ?? null; // Mapping client_id to merchantId as per user instruction
        $saltKey = $config['client_secret'] ?? null; // Mapping client_secret to saltKey
        $env = $config['environment'] ?? 'TEST';
        $saltIndex = (int) ($config['salt_index'] ?? 1);
        if ($saltIndex <= 0) {
            $saltIndex = 1;
        }

        if (!$merchantId || !$saltKey) {
            return back()->with('error', 'PhonePe configuration is missing.');
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.phonepe.com/apis/hermes'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox';

        // Store plan ID in registration temporarily or rely on callback metadata
        // Since we can't easily pass custom metadata that persists in callback URL query params for all gateways,
        // we will encode it in the transaction ID.
        $transactionId = 'REG_' . $registration->id . '_' . $plan->id . '_' . time();

        $callbackUrl = route('ecard.registration.payment.callback');
        // PhonePe expects the user to be redirected to the redirectUrl provided in the response.
        // And it will post to the redirectUrl when the user completes payment.
        
        $payload = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID' . $registration->user_id,
            'amount' => (int)($plan->plan_value * 100), // Amount in paise
            'redirectUrl' => $callbackUrl,
            'redirectMode' => 'POST',
            'callbackUrl' => $callbackUrl, // Server to server callback
            'mobileNumber' => $registration->mobile_no,
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $base64Payload = base64_encode(json_encode($payload));
        $checksum = hash('sha256', $base64Payload . '/pg/v1/pay' . $saltKey) . '###' . $saltIndex;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-VERIFY' => $checksum
            ])->post($baseUrl . '/pg/v1/pay', [
                'request' => $base64Payload
            ]);

            $resData = $response->json();

            if ($response->successful() && isset($resData['success']) && $resData['success']) {
                $redirectUrl = $resData['data']['instrumentResponse']['redirectInfo']['url'] ?? null;
                if ($redirectUrl) {
                    return redirect()->away($redirectUrl);
                }
            }
            
            Log::error('PhonePe Payment Initiation Failed', ['response' => $resData]);
            return back()->with('error', 'Failed to initiate payment: ' . ($resData['message'] ?? 'Unknown error'));

        } catch (\Exception $e) {
            Log::error('PhonePe Exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        // Handle Payment Callbacks (PhonePe & Cashfree)
        $input = $request->all();
        $paymentSuccess = false;
        $transactionId = null;
        $errorMsg = 'Payment failed or cancelled.';
        $isS2S = false;

        // --- 0. PhonePe S2S Callback (POST with response & X-VERIFY) ---
        if ($request->has('response') && $request->header('X-VERIFY')) {
            $isS2S = true;
            $encodedResponse = $request->input('response');
            $responseJson = base64_decode($encodedResponse);
            $responseData = json_decode($responseJson, true);
            
            if (isset($responseData['code']) && $responseData['code'] === 'PAYMENT_SUCCESS') {
                // Verify Checksum
                $gateway = PaymentGateway::where('slug', 'phonepe')->first();
                if ($gateway) {
                    $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
                    $saltKey = $config['client_secret'] ?? null;
                    $saltIndex = (int) ($config['salt_index'] ?? 1);
                    if ($saltIndex <= 0) {
                        $saltIndex = 1;
                    }
                    
                    if ($saltKey) {
                        $calculatedChecksum = hash('sha256', $encodedResponse . $saltKey) . '###' . $saltIndex;
                        if ($calculatedChecksum === $request->header('X-VERIFY')) {
                            $paymentSuccess = true;
                            $transactionId = $responseData['data']['merchantTransactionId'] ?? null;
                        } else {
                            Log::error('PhonePe S2S Checksum Mismatch', ['calculated' => $calculatedChecksum, 'received' => $request->header('X-VERIFY')]);
                        }
                    }
                }
            } else {
                $errorMsg = 'PhonePe S2S: ' . ($responseData['message'] ?? 'Payment Failed');
            }
        }
        // --- 1. PhonePe User Redirection (POST) ---
        elseif (isset($input['code'])) {
            if ($input['code'] === 'PAYMENT_SUCCESS') {
                $paymentSuccess = true;
                $transactionId = $input['transactionId'] ?? $input['merchantTransactionId'] ?? null;
            } else {
                $errorMsg = 'PhonePe: ' . ($input['message'] ?? 'Payment Failed');
            }
        }
        // --- 2. Cashfree Callback (GET with order_id) ---
        elseif ($request->has('order_id')) {
            $orderId = $request->input('order_id');
            
            // Verify with Cashfree API
            $gateway = PaymentGateway::where('slug', 'cashfree')->first();
            if ($gateway) {
                $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
                $appId = $config['app_id'] ?? null;
                $secretKey = $config['secret_key'] ?? null;
                $env = $config['environment'] ?? 'TEST';
                
                $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
                    ? 'https://api.cashfree.com/pg'
                    : 'https://sandbox.cashfree.com/pg';
                    
                try {
                    $response = Http::withHeaders([
                        'x-client-id' => $appId,
                        'x-client-secret' => $secretKey,
                        'x-api-version' => '2023-08-01'
                    ])->get($baseUrl . '/orders/' . $orderId);
                    
                    $resData = $response->json();
                    
                    if ($response->successful() && isset($resData['order_status']) && $resData['order_status'] === 'PAID') {
                        $paymentSuccess = true;
                        $transactionId = $orderId;
                    } else {
                        $errorMsg = 'Cashfree Payment Status: ' . ($resData['order_status'] ?? 'Unknown');
                    }
                } catch (\Exception $e) {
                    Log::error('Cashfree Verification Error', ['error' => $e->getMessage()]);
                    $errorMsg = 'Cashfree Verification Error';
                }
            } else {
                $errorMsg = 'Cashfree Configuration Not Found';
            }
        }

        if (!$paymentSuccess || !$transactionId) {
             return redirect()->route('ecard.users.my')->with('error', $errorMsg);
        }

        // --- Common Success Logic ---
        // Parse ID: REG_{reg_id}_{plan_id}_{timestamp}
        $parts = explode('_', $transactionId);
        if (count($parts) < 4 || $parts[0] !== 'REG') {
             return redirect()->route('ecard.users.my')->with('error', 'Invalid transaction format.');
        }

        $regId = $parts[1];
        $planId = $parts[2];
        
        $registration = Registration::find($regId);
        if (!$registration) {
             return redirect()->route('ecard.users.my')->with('error', 'Registration not found.');
        }
        
        if ($registration->status === 'approved') {
             return redirect()->route('ecard.users.my')->with('success', 'Registration already completed.');
        }

        $plan = FirstRechargePlan::find($planId);
        if (!$plan) {
             return redirect()->route('ecard.users.my')->with('error', 'Plan not found.');
        }

        $planValue = (float) $plan->plan_value;
        $bonusValue = (float) $plan->bonus_value;
        $totalCredit = $planValue + $bonusValue;
        
        $performerId = $registration->parent_id;

        // Use transaction to ensure idempotency if multiple callbacks come
        try {
            DB::transaction(function () use ($registration, $plan, $planValue, $bonusValue, $totalCredit, $performerId, $transactionId, $regId) {
                // Check again inside lock
                $regLocked = Registration::lockForUpdate()->find($registration->id);
                if ($regLocked->status === 'approved') return;

                $passwordPlain = session('registration_password_' . $regId);
                $this->completeRegistrationSuccess($regLocked, $plan, $planValue, $bonusValue, $totalCredit, $performerId, $passwordPlain);
            });
            
            // Clear session if exists
            session()->forget('registration_plan_' . $regId);
            session()->forget('registration_password_' . $regId);

            if ($isS2S) {
                return response()->json(['status' => 'success', 'message' => 'Payment processed successfully']);
            }

            return redirect()->route('ecard.users.my')->with('success', 'Payment successful! Registration completed.');

        } catch (\Exception $e) {
            Log::error('Payment Callback Error', ['error' => $e->getMessage()]);
            
            if ($isS2S) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            
            return redirect()->route('ecard.users.my')->with('error', 'Error completing registration: ' . $e->getMessage());
        }
    }

    private function completeRegistrationSuccess($registration, $plan, $planValue, $bonusValue, $totalCredit, $performerId, $passwordPlain = null)
    {
        // 1. Create ECardRegistration (Active User)
        // Filter fields that exist in ECardRegistration
        $ecardData = $registration->only([
            'parent_id', 'department_level', 'business_category',
            'business_name', 'business_mobile', 'business_whatsapp', 'business_gmail', 'business_address', 'business_gst', 'business_upi', 'business_location_map',
            'first_name', 'middle_name', 'last_name', 'father_name', 'mother_name',
            'blood_group', 'date_of_birth', 'gender', 'marital_status',
            'current_address', 'permanent_address', 'nationality', 'state', 'district', 'city', 'pin_code',
            'mobile_no', 'phone_no', 'email_id', 'gmail_id', 'live_location_map',
            'ifsc_code', 'bank_name', 'branch_name', 'account_no', 'pan_no', 'aadhaar_no',
            'last_qualification', 'work_type', 'work_experience',
            'user_id', 'password', 'profile_image', 'aadhaar_front_image', 'aadhaar_back_image'
        ]);

        // Add defaults and wallet balance
        $ecardData['wallet_balance'] = $totalCredit;
        $ecardData['status'] = 'active'; // Activate user
        
        // Create ECardRegistration
        $ecardUser = ECardRegistration::create($ecardData);

        // 2. Update Registration (Application Record)
        $registration->update([
            'wallet_balance' => $totalCredit,
            'status' => 'approved'
        ]);
        
        // 3. Log Wallet Transactions
        
        // Log for Registration (Application) History
        WalletTransaction::create([
            'registration_id' => $registration->id,
            'transaction_type' => 'add',
            'amount' => $planValue,
            'previous_balance' => 0,
            'new_balance' => $planValue,
            'narration' => "First recharge credit ({$plan->plan_name})",
            'performed_by_user_id' => null, // Set null to avoid FK error if performer is not Admin User
        ]);
        
        if ($bonusValue > 0) {
            WalletTransaction::create([
                'registration_id' => $registration->id,
                'transaction_type' => 'add',
                'amount' => $bonusValue,
                'previous_balance' => $planValue,
                'new_balance' => $totalCredit,
                'narration' => "First recharge bonus ({$plan->plan_name})",
                'performed_by_user_id' => null,
            ]);
        }

        // Log for ECardRegistration (Active User) History
        ECardWalletTransaction::create([
            'ecard_registration_id' => $ecardUser->id,
            'transaction_type' => 'add',
            'amount' => $planValue,
            'previous_balance' => 0,
            'new_balance' => $planValue,
            'narration' => "First recharge credit ({$plan->plan_name})",
            'performed_by_id' => $performerId, // The agent/parent
            'reference_type' => 'first_recharge_plan',
            'reference_id' => $plan->id,
        ]);

        if ($bonusValue > 0) {
            ECardWalletTransaction::create([
                'ecard_registration_id' => $ecardUser->id,
                'transaction_type' => 'add',
                'amount' => $bonusValue,
                'previous_balance' => $planValue,
                'new_balance' => $totalCredit,
                'narration' => "First recharge bonus ({$plan->plan_name})",
                'performed_by_id' => $performerId,
                'reference_type' => 'first_recharge_plan',
                'reference_id' => $plan->id,
            ]);
        }
        
        // Send Email
        // If password is not provided (Gateway flow), we try to get it from session if available, 
        // or we just send the email without password if logic supports it.
        // We send email to the NEW ECardRegistration user (though email is same)
        if ($passwordPlain) {
             try {
                 Mail::to($ecardUser->email_id)->send(new RegistrationCredentials($registration, $passwordPlain));
                 // Also try ECardRegistrationCredentials if exists, but RegistrationCredentials seems standard here
             } catch (\Exception $e) {
                 Log::error('Failed to send registration email: ' . $e->getMessage());
             }
        } else {
             try {
                 Mail::to($ecardUser->email_id)->send(new RegistrationCredentials($registration, "******** (As set during registration)"));
             } catch (\Exception $e) {
                Log::error('Failed to send registration email: ' . $e->getMessage());
            }
        }
    }
}
