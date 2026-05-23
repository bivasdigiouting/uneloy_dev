<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ECardCredentialsMail;
use App\Models\ECardRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ECardRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = (int) request()->query('per_page', 25);
        $perPage = max(10, min(100, $perPage));

        $registrations = ECardRegistration::latest()->paginate($perPage)->withQueryString();


        return view('admin.ecard-registrations.index', compact('registrations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ecard-registrations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Business Details
            'business_name' => 'required|string|max:255',
            'business_mobile' => 'required|string|max:15',
            'business_whatsapp' => 'nullable|string|max:15',
            'business_gmail' => 'required|email|max:255',
            'business_address' => 'required|string',
            'business_gst' => 'nullable|string|max:15',
            'business_upi' => 'nullable|string|max:255',
            'business_location_map' => 'nullable|string',
            'department_level' => 'nullable|in:state_level,district_level,block_level,panchayat_level,village_level,customer',

            // Personal Details
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date',
            // 'gender' => 'required|in:male,female,other',
            // 'marital_status' => 'required|in:single,married,divorced,widowed',

            // Contact Details
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'nationality' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pin_code' => 'required|string|max:10',
            'mobile_no' => 'required|string|max:15|unique:ecard_registrations',
            'phone_no' => 'nullable|string|max:15',
            'email_id' => 'required|email|max:255|unique:ecard_registrations',
            'gmail_id' => 'nullable|email|max:255',
            'live_location_map' => 'nullable|string',

            // Bank Details
            'ifsc_code' => 'required|string|max:11',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:20',
            'pan_no' => 'required|string|max:10|unique:ecard_registrations',
            'aadhaar_no' => 'required|string|max:12|unique:ecard_registrations',

            // Qualification & Experience Details
            'last_qualification' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'work_experience' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['status'] = 'active';
            $data['wallet_balance'] = 0.00;

            $registration = ECardRegistration::create($data);

            // Auto-generate credentials and email
            $this->generateCredentialsAndEmail($registration);

            return redirect()->route('admin.ecard-registrations.index')
                ->with('success', 'E-Card registration created and credentials emailed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating E-Card registration: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ecardRegistration = ECardRegistration::findOrFail($id);

        return view('admin.ecard-registrations.show', compact('ecardRegistration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ecardRegistration = ECardRegistration::findOrFail($id);

        return view('admin.ecard-registrations.edit', compact('ecardRegistration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $registration = ECardRegistration::findOrFail($id);

        $input = $request->all();
        $input['department_level'] = $this->normalizeDepartmentLevel($input['department_level'] ?? null);
        $input['gender'] = $this->normalizeGender($input['gender'] ?? null);
        $input['marital_status'] = $this->normalizeMaritalStatus($input['marital_status'] ?? null);

        $validator = Validator::make($input, [
            // Business Details
            'business_name' => 'required|string|max:255',
            'business_mobile' => 'required|string|max:15',
            'business_whatsapp' => 'nullable|string|max:15',
            'business_gmail' => 'required|email|max:255',
            'business_address' => 'required|string',
            'business_gst' => 'nullable|string|max:15',
            'business_upi' => 'nullable|string|max:255',
            'business_location_map' => 'nullable|string',
            'department_level' => 'nullable|in:state_level,district_level,block_level,panchayat_level,village_level,customer',

            // Personal Details
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed,Other',

            // Contact Details
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'nationality' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pin_code' => 'required|string|max:10',
            'mobile_no' => 'required|string|max:15|unique:ecard_registrations,mobile_no,'.$id,
            'phone_no' => 'nullable|string|max:15',
            'email_id' => 'required|email|max:255|unique:ecard_registrations,email_id,'.$id,
            'gmail_id' => 'nullable|email|max:255',
            'live_location_map' => 'nullable|string',

            // Bank Details
            'ifsc_code' => 'required|string|max:11',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:20',
            'pan_no' => 'required|string|max:10|unique:ecard_registrations,pan_no,'.$id,
            'aadhaar_no' => 'required|string|max:12|unique:ecard_registrations,aadhaar_no,'.$id,

            // Qualification & Experience Details
            'last_qualification' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'work_experience' => 'required|string|max:255',

            'status' => 'nullable|in:active,inactive,pending,rejected',
            'wallet_balance' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $registration->update($validator->validated());

            return redirect()->route('admin.ecard-registrations.index')
                ->with('success', 'E-Card registration updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating E-Card registration: '.$e->getMessage())
                ->withInput();
        }
    }

    private function normalizeDepartmentLevel(mixed $level): ?string
    {
        if (! is_string($level)) {
            return null;
        }

        $v = trim($level);
        if ($v === '') {
            return null;
        }

        if (str_contains($v, '_')) {
            return strtolower($v);
        }

        $slug = strtolower(str_replace([' ', '-', 'member'], '', $v));

        return match ($slug) {
            'statelevel' => 'state_level',
            'districtlevel' => 'district_level',
            'blocklevel' => 'block_level',
            'panchayatlevel' => 'panchayat_level',
            'villagelevel' => 'village_level',
            'customer' => 'customer',
            default => strtolower($v),
        };
    }

    private function normalizeGender(mixed $gender): ?string
    {
        if (! is_string($gender)) {
            return null;
        }

        $v = trim($gender);
        if ($v === '') {
            return null;
        }

        $g = strtolower($v);

        return match ($g) {
            'male' => 'Male',
            'female', 'fe-male' => 'Female',
            default => 'Other',
        };
    }

    private function normalizeMaritalStatus(mixed $status): ?string
    {
        if (! is_string($status)) {
            return null;
        }

        $v = trim($status);
        if ($v === '') {
            return null;
        }

        $s = strtolower($v);

        return match ($s) {
            'single' => 'Single',
            'married' => 'Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            default => 'Other',
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $registration = ECardRegistration::findOrFail($id);
            $registration->delete();

            return redirect()->route('admin.ecard-registrations.index')
                ->with('success', 'E-Card registration deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting E-Card registration: '.$e->getMessage());
        }
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,pending,rejected',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $registration = ECardRegistration::findOrFail($id);
            $registration->update(['status' => $request->status]);

            return redirect()->back()
                ->with('success', 'E-Card registration status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating status: '.$e->getMessage());
        }
    }

    /**
     * Search user by ID for profile update
     */
    public function searchUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $registration = ECardRegistration::where('id', $request->search_id)
            ->orWhere('aadhaar_no', $request->search_id)
            ->orWhere('mobile_no', $request->search_id)
            ->first();

        if (! $registration) {
            return redirect()->back()
                ->with('error', 'No E-Card registration found with the provided ID/Aadhaar/Mobile number.')
                ->withInput();
        }

        return view('admin.ecard-registrations.profile-update', compact('registration'));
    }

    /**
     * Generate user credentials for an existing E-Card registration and send email.
     */
    public function generateCredentials(string $id)
    {
        $registration = ECardRegistration::findOrFail($id);
        try {
            $this->generateCredentialsAndEmail($registration);

            return redirect()->back()->with('success', 'Credentials generated and email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate credentials: '.$e->getMessage());
        }
    }

    /**
     * Helper to generate User ID, set default password, and send email.
     */
    protected function generateCredentialsAndEmail(ECardRegistration $registration): void
    {
        // Build User ID: E + first two words of state (no spaces) + 8 random digits
        $state = trim((string) ($registration->state ?? 'NA'));
        $words = preg_split('/\s+/', $state);
        $firstTwo = array_slice($words, 0, 2);
        $stateKey = strtoupper(preg_replace('/[^A-Za-z]/', '', implode('', $firstTwo)));
        if ($stateKey === '') {
            $stateKey = 'NA';
        }

        // Ensure uniqueness
        $userId = null;
        for ($i = 0; $i < 5; $i++) {
            $digits = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $candidate = 'E'.$stateKey.$digits;
            if (! ECardRegistration::where('user_id', $candidate)->exists()) {
                $userId = $candidate;
                break;
            }
        }
        if (! $userId) {
            // Fallback with timestamp suffix
            $userId = 'E'.$stateKey.substr((string) time(), -8);
        }

        $registration->user_id = $userId;
        $plainPassword = '12345678';
        $registration->password = Hash::make($plainPassword);
        $registration->save();

        // Send credentials email
        $loginUrl = url('/ecard/login');
        if ($registration->email_id) {
            Mail::to($registration->email_id)->send(new ECardCredentialsMail($registration, $userId, $plainPassword, $loginUrl));
        }
    }
    /**
     * Update the KYC status of the specified resource.
     */
    public function updateKycStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $registration = ECardRegistration::findOrFail($id);

        $registration->kyc_status = $request->input('status');
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'KYC status updated successfully to ' . ucfirst($request->input('status')) . '.',
        ]);
    }
}
