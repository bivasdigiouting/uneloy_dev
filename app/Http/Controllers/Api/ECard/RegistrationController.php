<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Mail\ECardRegistrationCredentials;
use App\Mail\RegistrationCredentials;
use App\Models\City;
use App\Models\District;
use App\Models\ECardRegistration;
use App\Models\ECardWalletTransaction;
use App\Models\FirstRechargePlan;
use App\Models\Municipality;
use App\Models\Panchayat;
use App\Models\Registration;
use App\Models\State;
use App\Models\Village;
use App\Models\WalletTransaction;
use App\Models\Ward;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class RegistrationController extends Controller
{
    private const OTHER_OPTION_VALUE = '__other__';

    /**
     * Register New User
     *
     * Create a new ECard Registration or Member Registration.
     *
     * @group ECard Registration
     * @authenticated
     *
     * @bodyParam department_level string nullable The current department level. Example: state_level
     * @bodyParam business_category string nullable The business category. Example: Private limited
     * @bodyParam first_name string nullable First name. Example: John
     * @bodyParam last_name string nullable Last name. Example: Doe
     * @bodyParam state_id integer nullable State ID. Example: 1
     * @bodyParam district_id integer nullable District ID. Example: 1
     * @bodyParam city_id mixed nullable City ID or '__other__'. Example: 1
     * @bodyParam mobile_no string nullable Mobile number. Example: 9876543210
     * @bodyParam email_id string nullable Email address. Example: john@example.com
     * @bodyParam profile_image file nullable Profile image.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $customers = Registration::where('parent_id', $user->id)
            ->latest()
            ->get();

        $members = ECardRegistration::where('parent_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'customers' => $customers,
            'members' => $members,
        ]);
    }

    /**
     * Register New User
     *
     * Create a new ECard Registration or Member Registration.
     *
     * @group ECard Registration
     * @authenticated
     *
     * @bodyParam department_level string nullable The current department level. Example: state_level
     * @bodyParam business_category string nullable The business category. Example: Private limited
     * @bodyParam first_name string nullable First name. Example: John
     * @bodyParam middle_name string nullable Middle name. Example:
     * @bodyParam last_name string nullable Last name. Example: Doe
     * @bodyParam father_name string nullable Father's name.
     * @bodyParam mother_name string nullable Mother's name.
     * @bodyParam blood_group string nullable Blood group. Example: A+
     * @bodyParam date_of_birth date nullable Date of birth. Example: 1990-01-01
     * @bodyParam gender string nullable Gender. Example: Male
     * @bodyParam marital_status string nullable Marital status. Example: Single
     * @bodyParam current_address string nullable Current address.
     * @bodyParam permanent_address string nullable Permanent address.
     * @bodyParam nationality string nullable Nationality. Example: INDIA
     * @bodyParam state_id integer nullable State ID. Example: 1
     * @bodyParam district_id integer nullable District ID. Example: 1
     * @bodyParam city_id mixed nullable City ID or '__other__'. Example: 1
     * @bodyParam city_other string nullable Other city name if city_id is __other__.
     * @bodyParam pin_code string nullable Pin code. Example: 123456
     * @bodyParam mobile_no string nullable Mobile number. Example: 9876543210
     * @bodyParam phone_no string nullable Phone number.
     * @bodyParam email_id string nullable Email address. Example: john@example.com
     * @bodyParam gmail_id string nullable Gmail address.
     * @bodyParam live_location_map string nullable Live location map URL.
     * @bodyParam ifsc_code string nullable Bank IFSC code.
     * @bodyParam bank_name string nullable Bank name.
     * @bodyParam branch_name string nullable Branch name.
     * @bodyParam account_no string nullable Account number.
     * @bodyParam pan_no string nullable PAN number.
     * @bodyParam aadhaar_no string nullable Aadhaar number.
     * @bodyParam last_qualification string nullable Last qualification.
     * @bodyParam work_type string nullable Work type.
     * @bodyParam work_experience string nullable Work experience.
     * @bodyParam agree_terms boolean nullable Agree to terms.
     * @bodyParam profile_image file nullable Profile image.
     * @bodyParam customer_user_type string nullable Member user type (free/paid).
     * @bodyParam first_recharge_plan_id integer nullable First recharge plan ID.
     * @bodyParam business_name string nullable Business name.
     * @bodyParam business_mobile string nullable Business mobile.
     * @bodyParam business_whatsapp string nullable Business whatsapp.
     * @bodyParam business_gmail string nullable Business gmail.
     * @bodyParam business_address string nullable Business address.
     * @bodyParam business_gst string nullable Business GST.
     * @bodyParam business_upi string nullable Business UPI.
     * @bodyParam business_location_map string nullable Business location map.
     * @bodyParam otp_required boolean nullable OTP required.
     * @bodyParam otp_code string nullable OTP code.
     * @bodyParam area string nullable Area (Village_area/Municipality_area).
     * @bodyParam panchayat string nullable Panchayat name.
     * @bodyParam village_name string nullable Village name.
     * @bodyParam panchayat_other string nullable Other panchayat name.
     * @bodyParam village_other string nullable Other village name.
     * @bodyParam municipality string nullable Municipality name.
     * @bodyParam municipality_id integer nullable Municipality ID.
     * @bodyParam municipality_other string nullable Other municipality name.
     * @bodyParam ward_no string nullable Ward number.
     * @bodyParam ward_other string nullable Other ward number.
     */
    public function store(Request $request)
    {
        // Use Sanctum authenticated user
        $currentUser = $request->user();
        
        $currentSlug = $this->normalizeDepartmentSlug($currentUser->department_level ?? null) ?? 'state_level';
        $targetSlug = $this->nextDepartmentSlug($currentSlug);
        $targetLabel = $this->labelForDepartmentSlug($targetSlug);
        $isCustomer = ($targetSlug === 'customer');

        // All fields are made nullable/optional as requested
        $rules = [
            // Official
            'department_level' => 'nullable|string',
            'business_category' => 'nullable|string',

            // Personal
            'first_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'father_name' => 'nullable|string|max:150',
            'mother_name' => 'nullable|string|max:150',
            'blood_group' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Fe-Male,Others,Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Other,Divorced,Widowed',

            // Contact
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'nationality' => 'nullable|string',
            'state_id' => 'nullable|integer|exists:states,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'city_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (is_null($value)) return;
                    if ($value === self::OTHER_OPTION_VALUE) {
                        return;
                    }

                    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                        $fail('The selected city is invalid.');
                        return;
                    }

                    if ($request->input('state_id') && $request->input('district_id')) {
                        $exists = City::query()
                            ->whereKey((int) $value)
                            ->where('state_id', (int) $request->input('state_id'))
                            ->where('district_id', (int) $request->input('district_id'))
                            ->exists();

                        if (! $exists) {
                            $fail('The selected city is invalid.');
                        }
                    }
                },
            ],
            'city_other' => 'nullable|required_if:city_id,'.self::OTHER_OPTION_VALUE.'|string|max:255',
            'pin_code' => 'nullable|string|max:10',
            'mobile_no' => 'nullable|string|min:10|max:20',
            'phone_no' => 'nullable|string|max:20',
            'email_id' => 'nullable|email',
            'gmail_id' => 'nullable|email',
            'live_location_map' => 'nullable|string',

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
            'agree_terms' => 'nullable',

            // Optional image
            'profile_image' => 'nullable|image|max:2048',
        ];

        if ($isCustomer) {
            $rules['customer_user_type'] = 'nullable|in:free,paid';
            $rules['first_recharge_plan_id'] = [
                'nullable',
                // Removed required_if logic for flexibility
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
            $rules['aadhaar_no'] = 'nullable|string|max:20';
            $rules['otp_required'] = 'nullable|boolean';
            $rules['otp_code'] = ['nullable', 'digits:6', 'in:123456'];
            $rules['area'] = 'nullable|in:Village_area,Municipality_area';
            $rules['panchayat'] = 'nullable'; // Removed required_if
            $rules['village_name'] = 'nullable'; // Removed required_if
            $rules['panchayat_other'] = 'nullable|string|max:255';
            $rules['village_other'] = 'nullable|string|max:255';
            $rules['municipality'] = 'nullable'; // Removed required_if
            $rules['municipality_id'] = 'nullable|integer';
            $rules['municipality_other'] = 'nullable|string|max:255';
            $rules['ward_no'] = 'nullable'; // Removed required_if
            $rules['ward_other'] = 'nullable|string|max:255';
        } else {
            $rules['business_name'] = 'nullable|string|max:255';
            $rules['business_mobile'] = 'nullable|string|min:10|max:20';
            $rules['business_whatsapp'] = 'nullable|string|min:10|max:20';
            $rules['business_gmail'] = 'nullable|email';
            $rules['business_address'] = 'nullable|string';
            $rules['business_gst'] = 'nullable|string|max:20';
            $rules['business_upi'] = 'nullable|string|max:100';
            $rules['business_location_map'] = 'nullable|string';
            $rules['otp_required'] = 'nullable';
            $rules['otp_code'] = 'nullable';
            $rules['area'] = 'nullable|string';
            $rules['panchayat'] = 'nullable|string';
            $rules['village_name'] = 'nullable|string';
            $rules['municipality'] = 'nullable|string';
            $rules['ward_no'] = 'nullable|string';
        }

        $request->validate($rules);

        // Fallback or default values for critical fields if missing
        $stateId = $request->input('state_id') ?? State::first()->id ?? 1;
        $state = State::find($stateId);
        
        $districtId = $request->input('district_id') ?? District::where('state_id', $stateId)->first()->id ?? 1;
        $district = District::find($districtId);
        
        $cityId = 1; // Default
        if ($request->has('city_id')) {
            $cityIdInput = (string) $request->input('city_id');
            if ($cityIdInput === self::OTHER_OPTION_VALUE) {
                $cityId = $this->upsertCity(trim((string) $request->input('city_other')), (int) $stateId, (int) $districtId);
            } else {
                $cityId = (int) $cityIdInput;
            }
        }
        
        $city = City::find($cityId);
        // If city not found, just use first city of district or fallback
        if (! $city) {
            $city = City::where('district_id', $districtId)->first();
            $cityId = $city ? $city->id : 1;
        }

        // Generate user ID with state prefix
        $prefix = $state ? 'E'.Str::upper(Str::substr($state->state_name, 0, 2)) : 'XX';
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
            'state' => $state ? $state->state_name : '',
            'district' => $district ? $district->district_name : '',
            'city' => $city ? $city->city_name : '',
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
        $parentId = $currentUser->id;

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
                    $stateId,
                    $districtId,
                    &$successMessage,
                    $currentUser
                ) {
                    if ($area === 'Village_area') {
                        if ($request->input('panchayat') === self::OTHER_OPTION_VALUE) {
                            $this->upsertPanchayat(trim((string) $panchayatName), (int) $stateId, (int) $districtId, $cityId);
                        }
                        if ($request->input('village_name') === self::OTHER_OPTION_VALUE) {
                            $this->upsertVillage(trim((string) $villageName), (int) $stateId, (int) $districtId, $cityId);
                        }
                    }

                    if ($area === 'Municipality_area') {
                        $municipalityId = null;

                        if ($request->input('municipality') === self::OTHER_OPTION_VALUE) {
                            $municipalityId = $this->upsertMunicipality(trim((string) $municipalityName), (int) $stateId, (int) $districtId, $cityId);
                        } else {
                            $municipalityId = $this->resolveMunicipalityId((int) $request->input('municipality_id'), trim((string) $municipalityName), $cityId);
                        }

                        if ($request->input('ward_no') === self::OTHER_OPTION_VALUE && $municipalityId) {
                            $this->upsertWard(trim((string) $wardNo), (int) $stateId, (int) $districtId, $cityId, $municipalityId);
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
                        if ($registration->email_id) {
                            Mail::to($registration->email_id)->send(new RegistrationCredentials($registration, $passwordPlain));
                        }
                        return $registration;
                    }

                    $plan = FirstRechargePlan::query()
                        ->where('is_active', true)
                        ->find($selectedPlanId);

                    if ($plan) {
                        $planValue = (float) $plan->plan_value;
                        $bonusValue = (float) $plan->bonus_value;
                        $totalCredit = $planValue + $bonusValue;

                        // Lock user for update
                        $registeringUser = ECardRegistration::query()
                            ->lockForUpdate()
                            ->findOrFail($currentUser->id);

                        $registeringPrevious = (float) ($registeringUser->wallet_balance ?? 0);
                        if ($registeringPrevious < $planValue) {
                            throw new InvalidArgumentException('Insufficient wallet balance to purchase the selected plan.');
                        }

                        $registeringNew = $registeringPrevious - $planValue;

                        ECardWalletTransaction::create([
                            'ecard_registration_id' => $registeringUser->id,
                            'transaction_type' => 'remove',
                            'amount' => $planValue,
                            'previous_balance' => $registeringPrevious,
                            'new_balance' => $registeringNew,
                            'narration' => "First recharge plan purchase for customer {$registration->user_id} ({$plan->plan_name})",
                            'performed_by_id' => $registeringUser->id,
                            'reference_type' => 'first_recharge_plan',
                            'reference_id' => $plan->id,
                        ]);

                        $registeringUser->update(['wallet_balance' => $registeringNew]);

                        $customerPrevious = (float) ($registration->wallet_balance ?? 0);
                        $customerAfterPlan = $customerPrevious + $planValue;
                        WalletTransaction::create([
                            'registration_id' => $registration->id,
                            'transaction_type' => 'add',
                            'amount' => $planValue,
                            'previous_balance' => $customerPrevious,
                            'new_balance' => $customerAfterPlan,
                            'narration' => "First recharge credit ({$plan->plan_name})",
                            'performed_by_user_id' => $registeringUser->id,
                        ]);

                        $customerAfterBonus = $customerAfterPlan + $bonusValue;
                        if ($bonusValue > 0) {
                            WalletTransaction::create([
                                'registration_id' => $registration->id,
                                'transaction_type' => 'add',
                                'amount' => $bonusValue,
                                'previous_balance' => $customerAfterPlan,
                                'new_balance' => $customerAfterBonus,
                                'narration' => "First recharge bonus ({$plan->plan_name})",
                                'performed_by_user_id' => $registeringUser->id,
                            ]);
                        }

                        $registration->update(['wallet_balance' => $customerAfterBonus]);

                        $successMessage = "Member registration submitted. Plan '{$plan->plan_name}' purchased (₹".number_format($planValue, 2).') and credited ₹'.number_format($totalCredit, 2).' (including bonus).';
                    }

                    if ($registration->email_id) {
                        Mail::to($registration->email_id)->send(new RegistrationCredentials($registration, $passwordPlain));
                    }

                    return $registration;
                });
            } catch (InvalidArgumentException $e) {
                return response()->json(['message' => $e->getMessage(), 'errors' => ['first_recharge_plan_id' => [$e->getMessage()]]], 422);
            } catch (\Throwable $e) {
                Log::error('Failed to create customer registration or send credentials email', [
                    'message' => $e->getMessage(),
                    'email_id' => (string) $request->input('email_id'),
                ]);

                return response()->json(['message' => 'Registration could not be completed. ' . $e->getMessage()], 500);
            }

            return response()->json(['message' => $successMessage, 'data' => $registration], 201);
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

            try {
                $ecard = DB::transaction(function () use ($payload, $passwordPlain, $request) {
                    $ecard = ECardRegistration::create($payload);
                    if ($ecard->email_id) {
                        Mail::to($ecard->email_id)->send(new ECardRegistrationCredentials($ecard, $passwordPlain));
                    }

                    return $ecard;
                });
            } catch (\Throwable $e) {
                Log::error('Failed to create e-card registration or send credentials email', [
                    'message' => $e->getMessage(),
                    'email_id' => (string) $request->input('email_id'),
                ]);

                return response()->json(['message' => 'Registration could not be completed. ' . $e->getMessage()], 500);
            }
            
            return response()->json(['message' => 'Registration submitted successfully and credentials emailed.', 'data' => $ecard], 201);
        }
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
}
