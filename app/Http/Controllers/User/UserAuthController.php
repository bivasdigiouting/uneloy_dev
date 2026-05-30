<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BloodDonateOtherPoint;
use App\Models\ECardSevaEmergencyOtherPoint;
use App\Models\EmergencyContactDetail;
use App\Models\EmergencyFamilyContact;
use App\Models\ProductCategory;
use App\Models\Registration;
use App\Models\State;
use App\Models\UserLoginHistory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserAuthController extends Controller
{
    /**
     * Show the user login form
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $login = $request->input('login');
        $password = $request->input('password');

        // Try to find user by email or user_id
        $user = Registration::where('email_id', $login)
            ->orWhere('user_id', $login)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            // Store user session
            Session::put('user_auth', [
                'id' => $user->id,
                'user_id' => $user->user_id,
                'email' => $user->email_id,
                'full_name' => $user->full_name,
                'department_level' => $user->department_level,
                'business_category' => $user->business_category,
                'business_name' => $user->business_name,
            ]);

            // Record login history (web portal) for customer
            try {
                if ($user->department_level === 'customer') {
                    UserLoginHistory::create([
                        'user_id' => null,
                        'registration_id' => $user->id,
                        'ip_address' => $request->ip(),
                        'platform' => 'web',
                        'user_agent' => $request->header('User-Agent'),
                        'logged_in_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                // Do not block login on history errors
            }

            return redirect()->route('user.dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    /**
     * Show user dashboard
     */
    public function dashboard()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id'] ?? null);
        if (!$user) {
            Session::forget('user_auth');
            return redirect()->route('user.login');
        }

        $walletBalance = (float) ($user->wallet_balance ?? 0);

        // Calculate dynamic reward points based on order rewards
        $rewards = $this->buildUserRewards($user);
        $points = (int) ($rewards->sum('amount') * 10);
        if ($points <= 0) {
            $points = 200; // Fallback to 200 if no rewards exist yet
        }

        return view('user.dashboard', compact('user', 'walletBalance', 'points'));
    }

    /**
     * Handle user logout
     */
    public function logout()
    {
        try {
            if (\Illuminate\Support\Facades\Session::has('user_auth')) {
                $user = \Illuminate\Support\Facades\Session::get('user_auth');
                $history = \App\Models\UserLoginHistory::where('registration_id', $user['id'] ?? null)
                    ->where('platform', 'web')
                    ->orderByDesc('logged_in_at')
                    ->first();
                if ($history && empty($history->logged_out_at)) {
                    $history->logged_out_at = now();
                    $history->save();
                }
            }
        } catch (\Throwable $e) {
            // Ignore failures when updating history
        }
        Session::forget('user_auth');

        return redirect()->route('user.login')->with('success', 'Logged out successfully!');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);
        if (!$user) {
            Session::forget('user_auth');
            return redirect()->route('user.login');
        }

        // Calculate dynamic reward points based on order rewards
        $rewards = $this->buildUserRewards($user);
        $points = (int) ($rewards->sum('amount') * 10);
        if ($points <= 0) {
            $points = 200; // Fallback to 200 if no rewards exist yet
        }

        return view('user.profile', compact('user', 'points'));
    }

    /**
     * Show My QR page
     */
    public function showMyQr()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        if (! $user) {
            return redirect()->route('user.login')->with('error', 'User not found.');
        }

        $qrCodeUrl = null;

        // Only customers generate QR
        // You can adjust this condition if other roles also need QR
        if (strtolower($user->department_level) === 'customer') {
            $content = (string) ($user->user_id ?? $user->id);
            // Use SVG format as it doesn't require Imagick extension
            $relativePath = 'qr-codes/user-'.$content.'.svg';

            // Check if exists, if not generate
            if (! Storage::disk('public')->exists($relativePath)) {
                // Ensure directory exists
                if (! Storage::disk('public')->exists('qr-codes')) {
                    Storage::disk('public')->makeDirectory('qr-codes');
                }

                try {
                    // Generate SVG instead of PNG
                    $svg = QrCode::format('svg')->size(400)->margin(1)->errorCorrection('H')->generate($content);
                    Storage::disk('public')->put($relativePath, $svg);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('QR Generation Failed: '.$e->getMessage());
                }
            }

            if (Storage::disk('public')->exists($relativePath)) {
                $qrCodeUrl = Storage::url($relativePath);
            }
        }

        return view('user.my-qr', compact('user', 'qrCodeUrl'));
    }

    /**
     * Update user profile
     */
    public function editProfile()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login')->with('error', 'Please login first.');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        if (! $user) {
            return redirect()->route('user.login')->with('error', 'User not found.');
        }

        $states = State::active()->ordered()->get();

        return view('user.edit-profile', compact('user', 'states'));
    }

    public function updateProfile(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $validator = Validator::make($request->all(), [
            // 'full_name' => 'required|string|max:255', // Removed as it's now handled via components
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',

            'email_id' => [
                'nullable', // changed to nullable as modal might not send it
                'email',
                Rule::unique('registrations', 'email_id')->ignore($user->id),
            ],
            'mobile_no' => 'nullable|string|max:20', // nullable as modal might not send it? No, modal has it.
            'business_whatsapp' => 'nullable|string|max:20',
            'gmail_id' => 'nullable|email|max:255',

            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'state' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:20',

            'last_qualification' => 'nullable|string|max:255',
            'work_type' => 'nullable|string|max:255',
            'work_experience' => 'nullable|string|max:255',

            'aadhaar_no' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'aadhaar_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'aadhaar_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [];

        // Main Form Fields
        if ($request->has('email_id')) {
            $updateData['email_id'] = $request->email_id;
        }
        if ($request->has('mobile_no')) {
            $updateData['mobile_no'] = $request->mobile_no;
        }
        if ($request->has('aadhaar_no')) {
            $updateData['aadhaar_no'] = $request->aadhaar_no;
        }

        // Detailed Profile Fields
        if ($request->has('first_name')) {
            $updateData['first_name'] = $request->first_name;
        }
        if ($request->has('middle_name')) {
            $updateData['middle_name'] = $request->middle_name;
        }
        if ($request->has('last_name')) {
            $updateData['last_name'] = $request->last_name;
        }
        if ($request->has('father_name')) {
            $updateData['father_name'] = $request->father_name;
        }
        if ($request->has('mother_name')) {
            $updateData['mother_name'] = $request->mother_name;
        }
        if ($request->has('blood_group')) {
            $updateData['blood_group'] = $request->blood_group;
        }
        if ($request->has('date_of_birth')) {
            $updateData['date_of_birth'] = $request->date_of_birth;
        }
        if ($request->has('gender')) {
            $updateData['gender'] = $request->gender;
        }
        if ($request->has('marital_status')) {
            $updateData['marital_status'] = $request->marital_status;
        }

        if ($request->has('business_whatsapp')) {
            $updateData['business_whatsapp'] = $request->business_whatsapp;
        }
        if ($request->has('gmail_id')) {
            $updateData['gmail_id'] = $request->gmail_id;
        }

        if ($request->has('current_address')) {
            $updateData['current_address'] = $request->current_address;
        }
        if ($request->has('permanent_address')) {
            $updateData['permanent_address'] = $request->permanent_address;
        }
        if ($request->has('state')) {
            $updateData['state'] = $request->state;
        }
        if ($request->has('district')) {
            $updateData['district'] = $request->district;
        }
        if ($request->has('city')) {
            $updateData['city'] = $request->city;
        }
        if ($request->has('pin_code')) {
            $updateData['pin_code'] = $request->pin_code;
        }

        if ($request->has('last_qualification')) {
            $updateData['last_qualification'] = $request->last_qualification;
        }
        if ($request->has('work_type')) {
            $updateData['work_type'] = $request->work_type;
        }
        if ($request->has('work_experience')) {
            $updateData['work_experience'] = $request->work_experience;
        }

        // Handle Profile Image Upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile_images'), $imageName);
            $updateData['profile_image'] = 'uploads/profile_images/'.$imageName;
        }

        // Handle Aadhaar Front Image Upload
        if ($request->hasFile('aadhaar_front_image')) {
            $image = $request->file('aadhaar_front_image');
            $imageName = 'front_'.time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/aadhaar_images'), $imageName);
            $updateData['aadhaar_front_image'] = 'uploads/aadhaar_images/'.$imageName;
        }

        // Handle Aadhaar Back Image Upload
        if ($request->hasFile('aadhaar_back_image')) {
            $image = $request->file('aadhaar_back_image');
            $imageName = 'back_'.time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/aadhaar_images'), $imageName);
            $updateData['aadhaar_back_image'] = 'uploads/aadhaar_images/'.$imageName;
        }

        // Handle Share Profile to E-Card Seva
        $updateData['share_profile_to_ecard_seva'] = $request->has('share_profile_to_ecard_seva') ? 1 : 0;

        // Only update business_name if provided
        if ($request->has('business_name')) {
            $updateData['business_name'] = $request->business_name;
        }

        // If full_name is provided but first_name is NOT (e.g. legacy form submission), split it
        if ($request->has('full_name') && ! $request->has('first_name')) {
            $nameParts = explode(' ', $request->full_name);
            $updateData['first_name'] = $nameParts[0] ?? '';
            $updateData['middle_name'] = $nameParts[1] ?? '';
            $updateData['last_name'] = isset($nameParts[2]) ? implode(' ', array_slice($nameParts, 2)) : '';
        }

        $user->update($updateData);

        // Update session data
        if ($request->has('full_name')) {
            Session::put('user_auth.full_name', $request->full_name);
        } elseif (isset($updateData['first_name'])) {
            $fullName = trim(($updateData['first_name'] ?? '').' '.($updateData['middle_name'] ?? '').' '.($updateData['last_name'] ?? ''));
            Session::put('user_auth.full_name', $fullName);
        }

        if ($request->has('email_id')) {
            Session::put('user_auth.email', $request->email_id);
        }

        if ($request->has('business_name')) {
            Session::put('user_auth.business_name', $request->business_name);
        }
        if (isset($updateData['profile_image'])) {
            Session::put('user_auth.profile_image', $updateData['profile_image']);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show manage profile page
     */
    public function manageProfile()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        return view('user.manage-profile', compact('user'));
    }

    /**
     * Show change password page
     */
    public function showChangePassword()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        return view('user.change-password');
    }

    public function showSecuritySettings()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        return view('user.security-settings');
    }

    /**
     * Show Language Settings
     */
    public function showLanguageSettings()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $languages = [
            'en' => 'English',
            'hi' => 'Hindi (हिन्दी)',
            'bn' => 'Bengali (বাংলা)',
            'te' => 'Telugu (తెలుగు)',
            'mr' => 'Marathi (मराठी)',
            'ta' => 'Tamil (தமிழ்)',
            'ur' => 'Urdu (اردو)',
            'gu' => 'Gujarati (ગુજરાતી)',
            'kn' => 'Kannada (ಕನ್ನಡ)',
            'ml' => 'Malayalam (മലയാളം)',
            'or' => 'Odia (ଓଡ଼ିଆ)',
            'pa' => 'Punjabi (ਪੰਜਾਬੀ)',
            'as' => 'Assamese (অসমীয়া)',
            'mai' => 'Maithili (मैथिली)',
            'sat' => 'Santali (संताली)',
            'ks' => 'Kashmiri (कश्मीरी)',
            'ne' => 'Nepali (नेपाली)',
            'kok' => 'Konkani (कोंकणी)',
            'sd' => 'Sindhi (सिंधी)',
            'doi' => 'Dogri (डोगरी)',
            'mni' => 'Manipuri (মণিপুরী)',
            'brx' => 'Bodo (बड़ो)',
            'sa' => 'Sanskrit (संस्कृत)',
        ];

        return view('user.language', compact('languages'));
    }

    /**
     * Update Language
     */
    public function updateLanguage(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $request->validate([
            'locale' => 'required|string',
        ]);

        Session::put('locale', $request->locale);
        App::setLocale($request->locale);

        return back()->with('success', 'Language updated successfully.');
    }

    /**
     * Show login history for the authenticated user
     */
    public function loginHistory(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $registrationId = $userSession['id'] ?? null;

        $query = UserLoginHistory::query()
            ->where('registration_id', $registrationId)
            ->where('platform', 'web')
            ->orderByDesc('logged_in_at');

        if ($request->filled('from_date')) {
            $query->whereDate('logged_in_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('logged_in_at', '<=', $request->input('to_date'));
        }

        $loginHistories = $query->paginate(10)->withQueryString();

        return view('user.login-history', compact('loginHistories'));
    }

    /**
     * Show change PIN password page
     */
    public function showChangePinPassword()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        return view('user.change-pin-password');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Change user PIN password
     */
    public function changePinPassword(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'new_pin' => 'required|string|size:4|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Session::get('user_auth');
            // Re-fetch user to ensure we have the latest model
            $userModel = \App\Models\User::find($user->id);

            if (! $userModel) {
                return back()->with('error', 'User not found.');
            }

            $userModel->ecard_security_pin = $request->new_pin;
            $userModel->save();

            // Update session
            Session::put('user_auth', $userModel);

            return back()->with('success', 'PIN updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update PIN. Please try again.');
        }
    }

    /**
     * Show Upgrade ID page
     */
    public function showUpgradeId()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');

        return view('user.upgrade-id', compact('user'));
    }

    /**
     * Handle Upgrade ID submission (stub)
     */
    public function submitUpgradeId(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'tier' => 'required|in:basic,pro,elite',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement upgrade persistence/business rules
        return back()->with('success', 'Upgrade request submitted successfully.');
    }

    /**
     * Show Upload KYC page
     */
    public function showUploadKyc()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');

        return view('user.upload-kyc', compact('user'));
    }

    /**
     * Handle KYC document upload (stub)
     */
    public function uploadKyc(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:aadhaar,passport,driving_license',
            'document_number' => 'required|string|max:50',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Persist file and KYC metadata to storage/DB (e.g., user KYC table)
        // For now, just flash success.
        return back()->with('success', 'KYC document uploaded successfully.');
    }

    /**
     * Show E-Card page
     */
    public function showECard()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        return view('user.ecard', compact('user'));
    }

    /**
     * Show E-Card Details page
     */
    public function showECardDetails()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        return view('user.ecard-details', compact('user'));
    }

    /**
     * Send OTP for E-Card Update
     */
    public function sendEcardOtp(Request $request)
    {
        if (! Session::has('user_auth')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Generate OTP (Default 123456 as requested)
        $otp = '123456';

        // Store OTP in session
        Session::put('ecard_update_otp', $otp);
        Session::put('ecard_update_otp_expires', now()->addMinutes(10));
        Session::put('ecard_update_verified', false);

        // Simulate sending OTP to mobile and email
        // In production: SMS::send($user->mobile_no, $otp) and Mail::to($user->email_id)->send(...)

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to your registered mobile number and email.',
            'mobile' => $user->mobile_no, // Optional: return masked mobile for UI
        ]);
    }

    /**
     * Verify OTP for E-Card Update
     */
    public function verifyEcardOtp(Request $request)
    {
        if (! Session::has('user_auth')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $sessionOtp = Session::get('ecard_update_otp');
        $expiresAt = Session::get('ecard_update_otp_expires');

        if (! $sessionOtp || now()->greaterThan($expiresAt)) {
            return response()->json(['success' => false, 'message' => 'OTP expired or invalid. Please resend.'], 400);
        }

        if ($request->otp == $sessionOtp) {
            Session::put('ecard_update_verified', true);

            return response()->json(['success' => true, 'message' => 'OTP verified successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP.'], 400);
    }

    /**
     * Update E-Card Details
     */
    public function updateEcardDetails(Request $request)
    {
        if (! Session::has('user_auth')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        if (! Session::get('ecard_update_verified')) {
            return response()->json(['success' => false, 'message' => 'OTP verification required.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'ecard_cvv' => 'required|numeric|digits:3',
            'ecard_security_pin' => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $user->ecard_cvv = $request->ecard_cvv;
        $user->ecard_security_pin = $request->ecard_security_pin;
        $user->save();

        // Clear verification session
        Session::forget('ecard_update_verified');
        Session::forget('ecard_update_otp');
        Session::forget('ecard_update_otp_expires');

        return response()->json(['success' => true, 'message' => 'E-Card details updated successfully.']);
    }

    /**
     * Wallet: Show wallet request page
     */
    public function showWalletRequest()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');

        return view('user.wallet-request', compact('user'));
    }

    /**
     * Wallet: Submit wallet request (stub)
     */
    public function submitWalletRequest(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        // Minimal validation placeholder
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement wallet request persistence/business rules
        return back()->with('success', 'Wallet request submitted successfully.');
    }

    /**
     * Wallet: Show wallet transactions page
     */
    public function showWalletTransactions()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');
        // Provide transactions data to avoid undefined variable in view
        $transactions = [];

        return view('user.wallet-transactions', compact('user', 'transactions'));
    }

    /**
     * Wallet: Show bank settlement request page
     */
    public function showBankSettlementRequest()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');

        return view('user.bank-settlement', compact('user'));
    }

    /**
     * Wallet: Submit bank settlement request (stub)
     */
    public function submitBankSettlementRequest(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'account_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement bank settlement persistence
        return back()->with('success', 'Bank settlement request submitted successfully.');
    }

    /**
     * Wallet: Show QR-to-QR transfer page
     */
    public function showQrToQrTransfer()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');

        return view('user.transfer-qr', compact('user'));
    }

    /**
     * Wallet: Submit QR-to-QR transfer (stub)
     */
    public function submitQrToQrTransfer(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'to_qr' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement QR transfer logic
        return back()->with('success', 'QR-to-QR transfer submitted successfully.');
    }

    /**
     * Wallet: Show user-to-user transfer page
     */
    public function showUserToUserTransfer()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');

        return view('user.transfer-user', compact('user'));
    }

    /**
     * Wallet: Submit user-to-user transfer (stub)
     */
    public function submitUserToUserTransfer(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'to_user_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement user-to-user transfer logic
        return back()->with('success', 'User-to-user transfer submitted successfully.');
    }

    /**
     * Advertisement: Show advertisement management page
     */
    public function showAdvertisement()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');
        // Provide stub ads data to avoid undefined variable in view
        $ads = [
            ['title' => 'Diwali Offer', 'status' => 'Active', 'budget' => 5000],
            ['title' => 'Winter Sale', 'status' => 'Paused', 'budget' => 3000],
        ];

        return view('user.advertisement', compact('user', 'ads'));
    }

    /**
     * Advertisement: Show advertisement performance report page
     */
    public function showAdvertisementReport()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');
        // Provide stub report data to avoid undefined variable in view
        $report = [
            'impressions' => 12000,
            'clicks' => 950,
            'ctr' => 7.92,
            'spend' => 4200,
        ];

        return view('user.advertisement-report', compact('user', 'report'));
    }

    /**
     * Benefit: Show Book Camp page
     */
    public function showBookCamp()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');
        // Stub upcoming camps data to avoid undefined variable in view
        $camps = [
            ['name' => 'Health Awareness Camp', 'date' => now()->addDays(10)->format('Y-m-d'), 'location' => 'City Hall'],
            ['name' => 'Blood Donation Drive', 'date' => now()->addDays(20)->format('Y-m-d'), 'location' => 'Community Center'],
        ];

        return view('user.benefit-book-camp', compact('user', 'camps'));
    }

    /**
     * Benefit: Handle Book Camp submission
     */
    public function submitBookCamp(\Illuminate\Http\Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        // Basic validation for submission fields
        $validated = $request->validate([
            'camp_name' => 'required|string|max:255',
            'camp_date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        // TODO: persist booking request (e.g., save to DB)
        // For now, just flash success and redirect back
        return redirect()->route('user.benefit.bookcamp.show')
            ->with('success', 'Camp booking request submitted successfully.');
    }

    /**
     * Benefit: Show Book Camp report page
     */
    public function showBookCampReport()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = Session::get('user_auth');
        // Stub report data to avoid undefined variable in view
        $report = [
            ['name' => 'Health Awareness Camp', 'date' => now()->subDays(5)->format('Y-m-d'), 'status' => 'Approved'],
            ['name' => 'Blood Donation Drive', 'date' => now()->subDays(12)->format('Y-m-d'), 'status' => 'Pending'],
        ];

        return view('user.benefit-book-camp-report', compact('user', 'report'));
    }

    /**
     * Benefit: Show Eligible Report page
     */
    public function showEligibleReport()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        // Stub eligibility data to avoid undefined variable in view
        $eligibility = [
            ['scheme' => 'Health Insurance', 'eligible' => true],
            ['scheme' => 'Education Assistance', 'eligible' => false],
            ['scheme' => 'Senior Citizen Benefits', 'eligible' => true],
        ];

        return view('user.benefit-eligible-report', compact('user', 'eligibility'));
    }

    public function showBenefitCard(string $benefit)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        $benefits = [
            'sfd-e-card' => [
                'title' => 'SFD-E-CARD',
                'metric_label' => 'Years',
                'amount' => 20.00,
                'top_benefits' => 'SFD FUND A unique fund model ensuring sustained benefits over two decades. Special discounts during festivals, national days, and occasions. Continuous rewards ensure financial ease for families over 20 years.',
                'active' => 'Y',
                'deleted' => 'N',
            ],
            'esewa-e-card' => [
                'title' => 'Esewa E-CARD',
                'metric_label' => 'Years',
                'amount' => 1.00,
                'top_benefits' => 'Unlimited access to discounts and offers. Health, education, and emergency support benefits. Free participation in E-Card Seva campaigns & events.',
                'active' => 'Y',
                'deleted' => 'N',
            ],
            'epf-e-card' => [
                'title' => 'EPF-E-CARD',
                'metric_label' => 'Years',
                'amount' => 5.00,
                'top_benefits' => 'Franchise & Vendor Benefits – Users shopping through E-Card partner outlets receive double rewards. Festival & Seasonal Offers – Extra savings during Diwali, Holi, New Year, Independence Day, and more.',
                'active' => 'Y',
                'deleted' => 'N',
            ],
            'benefits-eps' => [
                'title' => 'BENEFITS E.P.S',
                'metric_label' => 'Purchase',
                'amount' => 10000000.00,
                'top_benefits' => 'With the E-Card Seva, your shopping and spending become more rewarding than ever. When you spend 1 cr Taka using your E-Card, you unlock exclusive extra benefits designed for long-term value.',
                'active' => 'Y',
                'deleted' => 'N',
            ],
            'benefits-02' => [
                'title' => 'BENEFITS 02',
                'metric_label' => 'Purchase',
                'amount' => 1000000.00,
                'top_benefits' => 'When you spend 10 lakh using your E-Card, you unlock exclusive extra benefits designed for long-term value.',
                'active' => 'Y',
                'deleted' => 'N',
            ],
            'benefits-01' => [
                'title' => 'BENEFITS 01',
                'metric_label' => 'Purchase',
                'amount' => 100000.00,
                'top_benefits' => 'When you spend 1 lakh using your E-Card, you unlock exclusive extra benefits designed for Ivalue.',
                'active' => 'Y',
                'deleted' => 'N',
            ],
        ];

        if (! array_key_exists($benefit, $benefits)) {
            abort(404);
        }

        $benefitData = $benefits[$benefit];

        return view('user.benefit-card', compact('user', 'benefitData'));
    }

    /**
     * Benefit: Show Scheme Fund Report page
     */
    public function showSchemeFundReport()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        // Stub scheme fund data to avoid undefined variable in view
        $funds = [
            ['scheme' => 'Health Insurance', 'amount' => 2500, 'date' => now()->subDays(10)->format('Y-m-d')],
            ['scheme' => 'Education Assistance', 'amount' => 1200, 'date' => now()->subDays(25)->format('Y-m-d')],
        ];

        return view('user.benefit-scheme-fund-report', compact('user', 'funds'));
    }

    /**
     * Benefit: Show E-Card Seva Request page
     */
    public function showEcardSevaRequest()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        return view('user.benefit-ecard-seva-request', compact('user'));
    }

    /**
     * Benefit: Submit E-Card Seva Request (stub)
     */
    public function submitEcardSevaRequest(\Illuminate\Http\Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'beneficiary_name' => 'required|string|max:255',
            'beneficiary_mobile' => 'required|string|max:20',
        ]);

        // TODO: Persist E-Card Seva request
        return back()->with('success', 'E-Card Seva request submitted successfully.');
    }

    /**
     * Benefit: Show E-Card Seva Self Report page
     */
    public function showEcardSevaSelfReport()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        $requests = [
            ['service' => 'Address Update', 'date' => now()->subDays(3)->format('Y-m-d'), 'status' => 'Completed'],
            ['service' => 'New Card Issue', 'date' => now()->subDays(12)->format('Y-m-d'), 'status' => 'Pending'],
        ];

        return view('user.benefit-ecard-seva-self-report', compact('user', 'requests'));
    }

    /**
     * Benefit: Show E-Card Seva Other Request Details page
     */
    public function showEcardSevaOtherDetails()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        $others = [
            ['name' => 'John Doe', 'service' => 'Name Correction', 'date' => now()->subDays(5)->format('Y-m-d'), 'status' => 'In Progress'],
            ['name' => 'Jane Smith', 'service' => 'Lost Card', 'date' => now()->subDays(20)->format('Y-m-d'), 'status' => 'Pending'],
        ];

        return view('user.benefit-ecard-seva-other-details', compact('user', 'others'));
    }

    /**
     * Benefit: Show Emergency E-Card Seva Request page
     */
    public function showEmergencyEcardSevaRequest()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        return view('user.benefit-emergency-ecard-seva-request', compact('user'));
    }

    public function showEmergencySevaDashboard()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $supportCount = 0;
        $acceptedCount = 0;
        $points = 0;

        if ($user) {
            if ($user->mobile_no) {
                $supportCount = ECardSevaEmergencyOtherPoint::query()
                    ->where('mobile_no', $user->mobile_no)
                    ->count();
            }

            if ($user->user_id) {
                $acceptedCount = ECardSevaEmergencyOtherPoint::query()
                    ->where('approved_id_no', $user->user_id)
                    ->count();

                $points = (int) ECardSevaEmergencyOtherPoint::query()
                    ->where('approved_id_no', $user->user_id)
                    ->sum('points');
            }
        }

        $stats = [
            'support' => $supportCount,
            'accepted' => $acceptedCount,
        ];

        return view('user.benefit-emergency-seva', compact('user', 'stats', 'points'));
    }

    /**
     * Benefit: Submit Emergency E-Card Seva Request
     */
    public function submitEmergencyEcardSevaRequest(\Illuminate\Http\Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'emergency_type' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => ['required', 'string', Rule::in(['Male', 'Female', 'Other'])],
            'live_location' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('emergency-seva', 'public');
        }

        $payload = [
            'name' => $user?->full_name ?: $validated['name'],
            'mobile_no' => $user?->mobile_no ?: $validated['mobile_no'],
            'emergency_type' => $validated['emergency_type'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'live_location' => $validated['live_location'],
            'description' => $validated['description'],
            'request_date' => now(),
            'image' => $imagePath,
            'status' => 'Pending',
            'points' => 0,
        ];

        ECardSevaEmergencyOtherPoint::create($payload);

        return redirect()->route('user.benefit.emergency.my.requests')->with('success', 'Emergency request submitted successfully.');
    }

    public function showEmergencyMyRequests()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $requests = collect();
        if ($user && $user->mobile_no) {
            $requests = ECardSevaEmergencyOtherPoint::query()
                ->where('mobile_no', $user->mobile_no)
                ->orderByDesc('id')
                ->get();
        }

        return view('user.benefit-emergency-my-requests', compact('user', 'requests'));
    }

    public function deleteEmergencyMyRequest(int $id)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        if (! $user) {
            return redirect()->route('user.login');
        }

        $row = ECardSevaEmergencyOtherPoint::find($id);
        if (! $row || $row->mobile_no !== $user->mobile_no) {
            return back()->with('error', 'Request not found.');
        }

        $row->delete();

        return back()->with('success', 'Request deleted successfully.');
    }

    /**
     * Benefit: Show Emergency E-Card Self Request Report page
     */
    public function showEmergencyEcardSelfReport()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        $emergencyRequests = [
            ['issue' => 'Card blocked', 'date' => now()->subDays(1)->format('Y-m-d'), 'status' => 'Resolved'],
            ['issue' => 'Pin reset required', 'date' => now()->subDays(7)->format('Y-m-d'), 'status' => 'Pending'],
        ];

        return view('user.benefit-emergency-ecard-self-report', compact('user', 'emergencyRequests'));
    }

    /**
     * Benefit: Show Emergency E-Card Other Request Details page
     */
    public function showEmergencyEcardOtherDetails()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $detail = null;
        if ($user) {
            $detail = EmergencyContactDetail::query()
                ->where('registration_id', $user->id)
                ->first();
        }

        return view('user.benefit-emergency-ecard-other-details', compact('user', 'detail'));
    }

    public function submitEmergencyContactDetails(\Illuminate\Http\Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validated = $request->validate([
            'self_name' => 'required|string|max:255',
            'self_mobile_no' => 'required|string|max:20',
            'blood_group' => 'required|string|max:10',
            'family_contact_1' => 'nullable|string|max:20',
            'family_contact_2' => 'nullable|string|max:20',
            'family_contact_3' => 'nullable|string|max:20',
            'best_friend_contact_1' => 'nullable|string|max:20',
            'best_friend_contact_2' => 'nullable|string|max:20',
            'best_friend_contact_3' => 'nullable|string|max:20',
        ]);

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);
        if (! $user) {
            return redirect()->route('user.login')->with('error', 'User not found.');
        }

        EmergencyContactDetail::updateOrCreate(
            ['registration_id' => $user->id],
            [
                'self_name' => $validated['self_name'],
                'self_mobile_no' => $validated['self_mobile_no'],
                'blood_group' => $validated['blood_group'],
                'family_contact_1' => $validated['family_contact_1'] ?? null,
                'family_contact_2' => $validated['family_contact_2'] ?? null,
                'family_contact_3' => $validated['family_contact_3'] ?? null,
                'best_friend_contact_1' => $validated['best_friend_contact_1'] ?? null,
                'best_friend_contact_2' => $validated['best_friend_contact_2'] ?? null,
                'best_friend_contact_3' => $validated['best_friend_contact_3'] ?? null,
            ]
        );

        return redirect()->route('user.benefit.emergency.ecard.other.details')->with('success', 'Emergency contact saved successfully.');
    }

    /**
     * Benefit: Show Emergency Family Contacts page
     */
    public function showEmergencyFamilyContacts()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $contacts = collect();
        if ($user) {
            $contacts = EmergencyFamilyContact::query()
                ->where('registration_id', $user->id)
                ->orderByDesc('id')
                ->get();
        }

        return view('user.benefit-emergency-family-contacts', compact('user', 'contacts'));
    }

    public function submitEmergencyFamilyContact(\Illuminate\Http\Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'relation' => 'nullable|string|max:80',
            'age' => 'required|integer|min:1|max:120',
            'gender' => ['required', 'string', Rule::in(['Male', 'Female', 'Other'])],
            'live_location' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);
        if (! $user) {
            return redirect()->route('user.login')->with('error', 'User not found.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('emergency-family-contacts', 'public');
        }

        EmergencyFamilyContact::create([
            'registration_id' => $user->id,
            'name' => $validated['name'],
            'mobile_no' => $validated['mobile_no'],
            'relation' => $validated['relation'] ?? null,
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'live_location' => $validated['live_location'],
            'description' => $validated['description'],
            'image' => $imagePath,
        ]);

        return redirect()->route('user.benefit.emergency.family.contacts')->with('success', 'Emergency family contact saved successfully.');
    }

    /**
     * Service: View Orders page
     */
    public function serviceOrdersView()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $orders = collect();
        $totalPurchaseAmount = 0.0;

        $table = null;
        if (Schema::hasTable('orders')) {
            $table = 'orders';
        } elseif (Schema::hasTable('user_orders')) {
            $table = 'user_orders';
        }

        if ($table) {
            $dateColumn = Schema::hasColumn($table, 'order_date') ? 'order_date' : (Schema::hasColumn($table, 'created_at') ? 'created_at' : 'id');
            $orderNoColumn = Schema::hasColumn($table, 'order_no') ? 'order_no' : (Schema::hasColumn($table, 'order_number') ? 'order_number' : null);
            $itemCountColumn = Schema::hasColumn($table, 'item_count') ? 'item_count' : (Schema::hasColumn($table, 'no_of_items') ? 'no_of_items' : null);
            $totalColumn = Schema::hasColumn($table, 'billing_amount') ? 'billing_amount' : (Schema::hasColumn($table, 'total_amount') ? 'total_amount' : (Schema::hasColumn($table, 'amount') ? 'amount' : null));
            $discountColumn = Schema::hasColumn($table, 'discount_amount') ? 'discount_amount' : (Schema::hasColumn($table, 'discount_amt') ? 'discount_amt' : null);
            $couponColumn = Schema::hasColumn($table, 'apply_coupon_amount') ? 'apply_coupon_amount' : (Schema::hasColumn($table, 'apply_coupon_amt') ? 'apply_coupon_amt' : null);
            $statusColumn = Schema::hasColumn($table, 'status') ? 'status' : (Schema::hasColumn($table, 'order_status') ? 'order_status' : null);
            $userNameColumn = Schema::hasColumn($table, 'user_name') ? 'user_name' : null;

            $qb = DB::table($table);

            if ($orderNoColumn) {
                $qb->addSelect("$table.$orderNoColumn as order_no");
            } else {
                $qb->addSelect("$table.id as order_no");
            }
            $qb->addSelect("$table.$dateColumn as order_date");

            if ($itemCountColumn) {
                $qb->addSelect("$table.$itemCountColumn as item_count");
            } else {
                $qb->addSelect(DB::raw('NULL as item_count'));
            }

            if ($totalColumn) {
                $qb->addSelect("$table.$totalColumn as total_amount");
            } else {
                $qb->addSelect(DB::raw('0 as total_amount'));
            }

            if ($discountColumn) {
                $qb->addSelect("$table.$discountColumn as discount_amount");
            } else {
                $qb->addSelect(DB::raw('0 as discount_amount'));
            }

            if ($couponColumn) {
                $qb->addSelect("$table.$couponColumn as coupon_amount");
            } else {
                $qb->addSelect(DB::raw('0 as coupon_amount'));
            }

            if ($statusColumn) {
                $qb->addSelect("$table.$statusColumn as status");
            } else {
                $qb->addSelect(DB::raw("'Confirmed' as status"));
            }

            if ($userNameColumn) {
                $qb->addSelect("$table.$userNameColumn as user_name");
            } else {
                $qb->addSelect(DB::raw('NULL as user_name'));
            }

            $userId = (string) ($user?->user_id ?? '');
            $mobile = (string) ($user?->mobile_no ?? '');

            if ($userId !== '' && Schema::hasColumn($table, 'user_id')) {
                $qb->where("$table.user_id", $userId);
            } elseif ($mobile !== '') {
                $matched = false;
                foreach (['user_mobile', 'mobile', 'mobile_no', 'buyer_mobile', 'customer_mobile'] as $col) {
                    if (Schema::hasColumn($table, $col)) {
                        $qb->where("$table.$col", $mobile);
                        $matched = true;
                        break;
                    }
                }

                if (! $matched && Schema::hasColumn($table, 'purchase_id') && is_numeric($user?->id)) {
                    $qb->where("$table.purchase_id", (int) $user->id);
                }
            }

            $rows = $qb->orderByDesc("$table.$dateColumn")->limit(50)->get();

            $orders = $rows->map(function ($row) use ($user) {
                $total = (float) ($row->total_amount ?? 0);
                $discount = (float) ($row->discount_amount ?? 0);
                $coupon = (float) ($row->coupon_amount ?? 0);
                $net = $total - $discount - $coupon;
                if ($net < 0) {
                    $net = 0;
                }

                $orderNo = trim((string) ($row->order_no ?? ''));
                $name = trim((string) ($row->user_name ?? ''));
                if ($name === '') {
                    $name = (string) ($user?->full_name ?? '');
                }

                $date = $row->order_date ?? null;
                $dateText = '';
                if ($date) {
                    try {
                        $dateText = date('Y-m-d', strtotime((string) $date));
                    } catch (\Exception $e) {
                        $dateText = (string) $date;
                    }
                }

                return [
                    'order_no' => $orderNo !== '' ? $orderNo : '-',
                    'user_name' => $name,
                    'date' => $dateText !== '' ? $dateText : '-',
                    'items' => (int) ($row->item_count ?? 0),
                    'total' => $total,
                    'discount' => $discount,
                    'coupon' => $coupon,
                    'net' => $net,
                    'status' => (string) ($row->status ?? 'Confirmed'),
                ];
            });
        }

        if ($orders->isEmpty()) {
            $orders = collect([
                [
                    'order_no' => '82744590',
                    'user_name' => (string) ($user?->full_name ?? 'User'),
                    'date' => now()->format('Y-m-d'),
                    'items' => 1,
                    'total' => 100.0,
                    'discount' => 0.0,
                    'coupon' => 0.0,
                    'net' => 100.0,
                    'status' => 'Confirmed',
                ],
            ]);
        }

        $totalPurchaseAmount = (float) $orders->sum('net');

        return view('user.service-orders-view', compact('user', 'orders', 'totalPurchaseAmount'));
    }

    public function estoreCategories()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $categories = ProductCategory::query()
            ->active()
            ->orderBy('sequence', 'asc')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'icon', 'sequence']);

        return view('user.estore-categories', compact('user', 'categories'));
    }

    public function estoreCategoryVendors(Request $request, $category)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $categoryModel = ProductCategory::query()->active()->findOrFail($category);

        $search = trim((string) $request->query('q', ''));

        $vendorsQuery = Vendor::query()
            ->active()
            ->whereJsonContains('product_categories', $categoryModel->name)
            ->orderBy('business_name', 'asc');

        if ($search !== '') {
            $vendorsQuery->where(function ($q) use ($search) {
                $q->where('business_name', 'like', '%'.$search.'%')
                    ->orWhere('contact_person', 'like', '%'.$search.'%')
                    ->orWhere('mobile_no', 'like', '%'.$search.'%')
                    ->orWhere('gmail_id', 'like', '%'.$search.'%')
                    ->orWhere('business_full_address', 'like', '%'.$search.'%');
            });
        }

        $vendors = $vendorsQuery->paginate(10)->withQueryString();

        return view('user.estore-category-vendors', [
            'user' => $user,
            'category' => $categoryModel,
            'vendors' => $vendors,
            'search' => $search,
        ]);
    }

    public function showBloodSevaDashboard()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        $stats = [
            'donations' => 5,
            'accepted' => 3,
        ];
        $points = 1000;

        return view('user.benefit-blood-seva', compact('user', 'stats', 'points'));
    }

    public function showBloodMyRequests()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $requests = collect();
        if ($user && $user->mobile_no) {
            $requests = BloodDonateOtherPoint::query()
                ->where('mobile_no', $user->mobile_no)
                ->orderByDesc('id')
                ->get();
        }

        return view('user.benefit-blood-my-requests', compact('user', 'requests'));
    }

    public function deleteBloodMyRequest(int $id)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        if (! $user) {
            return redirect()->route('user.login');
        }

        $requestRow = BloodDonateOtherPoint::find($id);

        if (! $requestRow || $requestRow->mobile_no !== $user->mobile_no) {
            return back()->with('error', 'Request not found.');
        }

        $requestRow->delete();

        return back()->with('success', 'Request deleted successfully.');
    }

    public function showBloodOtherRequests(\Illuminate\Http\Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $query = BloodDonateOtherPoint::query()->orderByDesc('id');

        if ($user && $user->mobile_no) {
            $query->where('mobile_no', '!=', $user->mobile_no);
        }

        $status = trim((string) $request->query('status', ''));
        if ($status !== '' && strtoupper($status) !== 'ALL') {
            $query->where('status', $status);
        }

        $requests = $query->get();

        $profilesByMobile = collect();
        $mobiles = $requests->pluck('mobile_no')->filter()->unique()->values();
        if ($mobiles->isNotEmpty()) {
            $profilesByMobile = Registration::query()
                ->whereIn('mobile_no', $mobiles)
                ->get()
                ->keyBy('mobile_no');
        }

        return view('user.benefit-blood-other-requests', compact('user', 'requests', 'profilesByMobile', 'status'));
    }

    public function acceptBloodOtherRequest(int $id)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        if (! $user) {
            return redirect()->route('user.login');
        }

        $requestRow = BloodDonateOtherPoint::find($id);
        if (! $requestRow) {
            return back()->with('error', 'Request not found.');
        }

        if ($user->mobile_no && $requestRow->mobile_no === $user->mobile_no) {
            return back()->with('error', 'Invalid request.');
        }

        if (($requestRow->status ?? '') !== 'Pending') {
            return back();
        }

        $requestRow->status = 'Approved';
        $requestRow->approved_id_no = $user->user_id ?? null;
        $requestRow->approved_name = $user->full_name ?? null;
        $requestRow->approved_date = now();
        $requestRow->save();

        return back()->with('success', 'Request accepted successfully.');
    }

    public function showBloodMyDonateDetails()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $donations = collect();
        if ($user && $user->user_id) {
            $donations = BloodDonateOtherPoint::query()
                ->where('approved_id_no', $user->user_id)
                ->orderByDesc('approved_date')
                ->orderByDesc('id')
                ->get();
        }

        return view('user.benefit-blood-my-details', compact('user', 'donations'));
    }

    /**
     * Benefit: Show Blood Donate Request page
     */
    public function showBloodDonateRequest()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        return view('user.benefit-blood-donate-request', compact('user'));
    }

    /**
     * Benefit: Submit Blood Donate Request (stub)
     */
    public function submitBloodDonateRequest(\Illuminate\Http\Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'age' => 'required|integer|min:1|max:120',
            'gender' => ['required', 'string', Rule::in(['Male', 'Female', 'Other'])],
            'blood_group' => ['required', 'string', 'max:5', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'hospital_name' => 'required|string|max:255',
            'hospital_address' => 'required|string|max:2000',
        ]);

        $payload = [
            'name' => $user?->full_name ?: $validated['name'],
            'mobile_no' => $user?->mobile_no ?: $validated['mobile_no'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'],
            'hospital_name' => $validated['hospital_name'],
            'hospital_address' => $validated['hospital_address'],
            'request_date' => now(),
            'status' => 'Pending',
        ];

        BloodDonateOtherPoint::create($payload);

        return redirect()->route('user.benefit.blood.my.requests')->with('success', 'Blood donate request submitted successfully.');
    }

    /**
     * Benefit: Show Blood Donate Self Report
     */
    public function showBloodDonateSelfReport()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        $donations = [
            ['date' => now()->subDays(7)->format('Y-m-d'), 'blood_group' => 'O+', 'status' => 'Completed'],
            ['date' => now()->subDays(30)->format('Y-m-d'), 'blood_group' => 'A-', 'status' => 'Completed'],
        ];

        return view('user.benefit-blood-donate-self-report', compact('user', 'donations'));
    }

    /**
     * Benefit: Show Blood Donate Other Request Details
     */
    public function showBloodDonateOtherDetails()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $query = BloodDonateOtherPoint::query()->orderByDesc('id');
        if ($user && $user->mobile_no) {
            $query->where('mobile_no', '!=', $user->mobile_no);
        }

        $otherDonations = $query
            ->whereIn('status', ['Approved', 'Send Point'])
            ->get();

        return view('user.benefit-blood-donate-other-details', compact('user', 'otherDonations'));
    }

    /**
     * Service: Report - Admin by Points
     */
    public function serviceReportAdminPoints()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        // Stub rows data for the view
        $rows = [
            ['admin' => 'Admin Alpha', 'points' => 1200],
            ['admin' => 'Admin Beta', 'points' => 875],
            ['admin' => 'Admin Gamma', 'points' => 420],
        ];

        return view('user.service-report-admin-points', compact('user', 'rows'));
    }

    /**
     * Service: Report - Vendor by Points
     */
    public function serviceReportVendorPoints()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        // Stub rows data for the view
        $rows = [
            ['vendor' => 'Vendor Alpha', 'points' => 980],
            ['vendor' => 'Vendor Beta', 'points' => 765],
            ['vendor' => 'Vendor Gamma', 'points' => 310],
        ];

        return view('user.service-report-vendor-points', compact('user', 'rows'));
    }

    /**
     * Service: Report - Coupon Summary Detail
     */
    public function serviceReportCouponSummary()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        // Stub coupons data for the view
        $coupons = [
            ['code' => 'SAVE10', 'discount' => '10%', 'redeemed' => 120],
            ['code' => 'WELCOME15', 'discount' => '15%', 'redeemed' => 75],
            ['code' => 'FEST50', 'discount' => '₹50', 'redeemed' => 42],
        ];

        return view('user.service-report-coupon-summary', compact('user', 'coupons'));
    }

    /**
     * Service: Report - Voucher Detail
     */
    public function serviceReportVoucherDetail()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        // Stub vouchers data to avoid undefined variable in view
        $vouchers = [
            ['voucher' => 'VCH-2025-001', 'issued' => now()->subDays(3)->format('Y-m-d'), 'value' => 100.00],
            ['voucher' => 'VCH-2025-002', 'issued' => now()->subDays(7)->format('Y-m-d'), 'value' => 250.50],
            ['voucher' => 'VCH-2025-003', 'issued' => now()->subDays(15)->format('Y-m-d'), 'value' => 75.25],
        ];

        return view('user.service-report-voucher-detail', compact('user', 'vouchers'));
    }

    /**
     * Service: Report - Global Disbursement Fund
     */
    public function serviceReportGlobalDisbursementFund()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        // Stub funds data for the view
        $funds = [
            ['region' => 'Global', 'date' => now()->subDays(2)->format('Y-m-d'), 'amount' => 50000.00],
            ['region' => 'APAC', 'date' => now()->subDays(10)->format('Y-m-d'), 'amount' => 18500.75],
            ['region' => 'EMEA', 'date' => now()->subDays(20)->format('Y-m-d'), 'amount' => 22340.50],
        ];

        return view('user.service-report-global-disbursement-fund', compact('user', 'funds'));
    }

    /**
     * Service: Report - Physically Challenged Fund
     */
    public function serviceReportPhysicallyChallengedFund()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        // Stub funds data expected by the view (benefit, date, amount)
        $funds = [
            ['benefit' => 'Wheelchair Assistance', 'date' => now()->subDays(3)->format('Y-m-d'), 'amount' => 2500.00],
            ['benefit' => 'Hearing Aid Support', 'date' => now()->subDays(7)->format('Y-m-d'), 'amount' => 5400.50],
            ['benefit' => 'Visual Aid Subsidy', 'date' => now()->subDays(14)->format('Y-m-d'), 'amount' => 3200.00],
        ];

        return view('user.service-report-physically-challenged-fund', compact('user', 'funds'));
    }

    /**
     * Service: Report - Month Wise User Redeem
     */
    public function serviceReportMonthWiseUserRedeem()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        // Stub redeems data expected by the view (month, count)
        $redeems = [
            ['month' => 'Jan', 'count' => 12],
            ['month' => 'Feb', 'count' => 9],
            ['month' => 'Mar', 'count' => 15],
            ['month' => 'Apr', 'count' => 7],
        ];

        return view('user.service-report-month-wise-user-redeem', compact('user', 'redeems'));
    }

    /**
     * Service: Report - Reward
     */
    public function serviceReportReward(Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = \Illuminate\Support\Facades\Session::get('user_auth');
        $user = Registration::find($userSession['id'] ?? null);

        $rewards = $this->buildUserRewards($user);

        $summary = [
            'total_earned' => (float) $rewards->sum('amount'),
            'available_count' => (int) $rewards->where('status', 'available')->count(),
            'redeemed_count' => (int) $rewards->where('status', 'redeemed')->count(),
            'expired_count' => (int) $rewards->where('status', 'expired')->count(),
            'available_amount' => (float) $rewards->where('status', 'available')->sum('amount'),
            'redeemed_amount' => (float) $rewards->where('status', 'redeemed')->sum('amount'),
        ];

        return view('user.service-report-reward', compact('user', 'rewards', 'summary'));
    }

    public function serviceReportRewardShow(Request $request, $reward)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id'] ?? null);

        $rewardItem = $this->findUserRewardById($user, (string) $reward);
        if (! $rewardItem) {
            abort(404);
        }

        return view('user.service-report-reward-show', [
            'user' => $user,
            'reward' => $rewardItem,
        ]);
    }

    public function serviceReportRewardRedeem(Request $request, $reward)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id'] ?? null);

        $rewardItem = $this->findUserRewardById($user, (string) $reward);
        if (! $rewardItem) {
            abort(404);
        }

        if (($rewardItem['status'] ?? null) !== 'available') {
            return redirect()
                ->route('user.service.report.reward.show', ['reward' => (string) $reward])
                ->with('error', 'This reward is not available for redemption.');
        }

        $redeemedIds = Session::get('user_reward_redeemed_ids', []);
        $rewardId = (string) ($rewardItem['id'] ?? $reward);
        if (! in_array($rewardId, $redeemedIds, true)) {
            $redeemedIds[] = $rewardId;
            Session::put('user_reward_redeemed_ids', $redeemedIds);
        }

        return redirect()
            ->route('user.service.report.reward.show', ['reward' => $rewardId])
            ->with('success', 'Reward redeemed successfully.');
    }

    private function detectUserOrdersTable(): ?string
    {
        if (Schema::hasTable('orders')) {
            return 'orders';
        }
        if (Schema::hasTable('user_orders')) {
            return 'user_orders';
        }

        return null;
    }

    private function resolveExistingColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }

        return null;
    }

    private function buildCoalesceExpr(string $table, array $candidates): string
    {
        $existing = array_values(array_filter($candidates, fn ($c) => Schema::hasColumn($table, $c)));
        if (empty($existing)) {
            return '0';
        }

        $expr = '0';
        foreach (array_reverse($existing) as $col) {
            $expr = "COALESCE($table.$col, $expr)";
        }

        return $expr;
    }

    private function buildUserRewards(?Registration $user): \Illuminate\Support\Collection
    {
        $redeemedIds = Session::get('user_reward_redeemed_ids', []);

        $demoRewards = collect([
            [
                'id' => 'demo-1',
                'title' => 'First Purchase Cashback',
                'subtitle' => 'Scratch to unlock your cashback',
                'amount' => 50.0,
                'earned_at' => now()->subDays(2),
                'expires_at' => now()->addDays(28),
                'source' => 'welcome',
            ],
            [
                'id' => 'demo-2',
                'title' => 'Festival Bonus',
                'subtitle' => 'Limited time reward',
                'amount' => 25.0,
                'earned_at' => now()->subDays(6),
                'expires_at' => now()->addDays(24),
                'source' => 'offer',
            ],
            [
                'id' => 'demo-3',
                'title' => 'Shopping Cashback',
                'subtitle' => 'Earned from recent order',
                'amount' => 15.0,
                'earned_at' => now()->subDays(12),
                'expires_at' => now()->addDays(18),
                'source' => 'order',
            ],
            [
                'id' => 'demo-4',
                'title' => 'Referral Reward',
                'subtitle' => 'Thanks for inviting a friend',
                'amount' => 75.0,
                'earned_at' => now()->subDays(20),
                'expires_at' => now()->addDays(10),
                'source' => 'referral',
            ],
        ])->map(function ($r) use ($redeemedIds) {
            $id = (string) ($r['id'] ?? '');
            $earnedAt = $r['earned_at'] instanceof \Carbon\Carbon ? $r['earned_at'] : now();
            $expiresAt = $r['expires_at'] instanceof \Carbon\Carbon ? $r['expires_at'] : $earnedAt->copy()->addDays(30);
            $isRedeemed = in_array($id, $redeemedIds, true);
            $status = $isRedeemed ? 'redeemed' : (now()->greaterThan($expiresAt) ? 'expired' : 'available');

            return [
                'id' => $id,
                'title' => (string) ($r['title'] ?? 'Reward'),
                'subtitle' => (string) ($r['subtitle'] ?? ''),
                'amount' => (float) ($r['amount'] ?? 0),
                'earned_at' => $earnedAt->toDateString(),
                'expires_at' => $expiresAt->toDateString(),
                'status' => $status,
                'source' => (string) ($r['source'] ?? 'reward'),
            ];
        });

        $table = $this->detectUserOrdersTable();
        if (! $table || ! $user) {
            return $demoRewards;
        }

        $rewardCandidates = [
            'reward_amount', 'reward', 'reward_points', 'points', 'points_earned', 'earned_reward', 'user_reward', 'user_reward_amount', 'reward_amt', 'reward_cash',
        ];
        $rewardExpr = $this->buildCoalesceExpr($table, $rewardCandidates);

        $dateColumn = $this->resolveExistingColumn($table, ['order_date', 'created_at', 'transaction_date', 'date']) ?? 'created_at';
        $orderNoColumn = $this->resolveExistingColumn($table, ['order_no', 'order_number', 'orderid', 'order_id']);

        $qb = DB::table($table)
            ->select("$table.id as id")
            ->selectRaw(($orderNoColumn ? "$table.$orderNoColumn" : "$table.id").' as order_no')
            ->selectRaw("$table.$dateColumn as earned_at")
            ->selectRaw("($rewardExpr) as amount")
            ->whereRaw("($rewardExpr) > 0");

        $userId = trim((string) ($user->user_id ?? ''));
        $mobile = trim((string) ($user->mobile_no ?? ''));

        if ($userId !== '' && Schema::hasColumn($table, 'user_id')) {
            $qb->where("$table.user_id", $userId);
        } elseif ($mobile !== '') {
            $matched = false;
            foreach (['user_mobile', 'mobile', 'mobile_no', 'buyer_mobile', 'customer_mobile'] as $col) {
                if (Schema::hasColumn($table, $col)) {
                    $qb->where("$table.$col", $mobile);
                    $matched = true;
                    break;
                }
            }

            if (! $matched && Schema::hasColumn($table, 'purchase_id') && is_numeric($user->id)) {
                $qb->where("$table.purchase_id", (int) $user->id);
            }
        } elseif (Schema::hasColumn($table, 'purchase_id') && is_numeric($user->id)) {
            $qb->where("$table.purchase_id", (int) $user->id);
        }

        $rows = $qb->orderByDesc("$table.$dateColumn")->limit(30)->get();

        $dbRewards = collect($rows)->map(function ($row) use ($redeemedIds) {
            $id = (string) ($row->id ?? '');
            $amount = (float) ($row->amount ?? 0);
            $orderNo = trim((string) ($row->order_no ?? ''));

            $earnedAt = null;
            try {
                $earnedAt = \Carbon\Carbon::parse((string) ($row->earned_at ?? ''));
            } catch (\Throwable $e) {
                $earnedAt = now();
            }
            $expiresAt = $earnedAt->copy()->addDays(30);

            $isRedeemed = in_array($id, $redeemedIds, true);
            $status = $isRedeemed ? 'redeemed' : (now()->greaterThan($expiresAt) ? 'expired' : 'available');

            return [
                'id' => $id,
                'title' => 'Cashback Reward',
                'subtitle' => $orderNo !== '' ? 'Order #'.$orderNo : 'Earned from your activity',
                'amount' => $amount,
                'earned_at' => $earnedAt->toDateString(),
                'expires_at' => $expiresAt->toDateString(),
                'status' => $status,
                'source' => 'order',
                'order_no' => $orderNo,
            ];
        })->values();

        return $dbRewards->isNotEmpty() ? $dbRewards : $demoRewards;
    }

    private function findUserRewardById(?Registration $user, string $rewardId): ?array
    {
        $rewards = $this->buildUserRewards($user);
        $found = $rewards->firstWhere('id', $rewardId);

        return $found ? (array) $found : null;
    }

    /**
     * Service: Recharge - Report
     */
    public function serviceRechargeReport()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        // Stub recharge data expected by the view (service, number, date, amount)
        $recharges = [
            ['service' => 'Mobile', 'number' => '9876543210', 'date' => now()->subDays(2)->format('Y-m-d'), 'amount' => 199.00],
            ['service' => 'DTH', 'number' => 'DTH1234567', 'date' => now()->subDays(7)->format('Y-m-d'), 'amount' => 350.50],
            ['service' => 'Mobile', 'number' => '9123456780', 'date' => now()->subDays(10)->format('Y-m-d'), 'amount' => 249.00],
        ];

        return view('user.service-recharge-report', compact('user', 'recharges'));
    }

    /**
     * Service: Recharge - Utility Link
     */
    public function serviceRechargeUtilityLink()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        $utilities = [
            ['name' => 'Mobile', 'icon' => 'fas fa-mobile-alt', 'color' => '#4e54c8', 'url' => route('user.service.recharge.mobile')],
            ['name' => 'DTH', 'icon' => 'fas fa-satellite-dish', 'color' => '#8f94fb', 'url' => route('user.service.recharge.dth')],
            ['name' => 'FASTag', 'icon' => 'fas fa-car', 'color' => '#11998e', 'url' => route('user.service.recharge.fastag')],
            ['name' => 'Electricity', 'icon' => 'fas fa-lightbulb', 'color' => '#f2994a', 'url' => route('user.service.recharge.bbps', ['category' => 'electricity'])],
            ['name' => 'Water', 'icon' => 'fas fa-tint', 'color' => '#2193b0', 'url' => route('user.service.recharge.bbps', ['category' => 'water'])],
            ['name' => 'Gas', 'icon' => 'fas fa-burn', 'color' => '#e100ff', 'url' => route('user.service.recharge.bbps', ['category' => 'gas'])],
            ['name' => 'Broadband', 'icon' => 'fas fa-wifi', 'color' => '#00b09b', 'url' => route('user.service.recharge.bbps', ['category' => 'broadband'])],
            ['name' => 'Landline', 'icon' => 'fas fa-phone-alt', 'color' => '#fc4a1a', 'url' => route('user.service.recharge.bbps', ['category' => 'landline'])],
            ['name' => 'Insurance', 'icon' => 'fas fa-shield-alt', 'color' => '#4ecdc4', 'url' => route('user.service.recharge.bbps', ['category' => 'insurance'])],
        ];

        return view('user.service-recharge-utility-link', compact('user', 'utilities'));
    }

    public function serviceRechargeBbps(Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');
        $category = $request->query('category', 'electricity');

        // Map category to potential DB search terms or service codes
        // For now, we'll fetch ALL BBPS operators or filter if possible
        // Ideally, we'd have 'Electricity' service in DB.

        // Try to find specific service first
        $service = \App\Models\RechargeService::where('service_name', 'LIKE', "%{$category}%")
            ->orWhere('service_code', 'LIKE', "%{$category}%")
            ->first();

        // If not found, fallback to generic BBPS service
        if (! $service) {
            $service = \App\Models\RechargeService::where('service_code', 'BBPS')->first();
        }

        $operators = [];
        if ($service) {
            $operators = \App\Models\RechargeOperator::where('recharge_service_id', $service->id)
                ->where('is_active', true)
                ->get();
        }

        // If still no operators (e.g. fresh DB), mock some for UI demonstration if needed,
        // or let the view handle empty state.
        // We'll inject common ones if empty, similar to FASTag.
        if ($operators->isEmpty()) {
            $operators = $this->getMockOperatorsForCategory($category);
        }

        return view('user.service-recharge-bbps', compact('user', 'category', 'operators'));
    }

    private function getMockOperatorsForCategory($category)
    {
        $list = [];
        switch (strtolower($category)) {
            case 'electricity':
                $list = ['Adani Electricity', 'Tata Power', 'MSEDCL', 'Bescom', 'TNEB', 'WBSEDCL'];
                break;
            case 'water':
                $list = ['Delhi Jal Board', 'Bangalore Water Supply', 'Mumbai Water', 'Chennai Metro Water'];
                break;
            case 'gas':
                $list = ['Adani Gas', 'Indraprastha Gas', 'Mahanagar Gas', 'Gujarat Gas'];
                break;
            case 'broadband':
                $list = ['Airtel Broadband', 'Jio Fiber', 'ACT Fibernet', 'Hathway', 'Tata Play Fiber'];
                break;
            case 'landline':
                $list = ['Airtel Landline', 'BSNL Landline', 'MTNL Delhi', 'MTNL Mumbai'];
                break;
            case 'insurance':
                $list = ['LIC', 'HDFC Life', 'SBI Life', 'ICICI Prudential', 'Max Life'];
                break;
        }

        $ops = collect();
        foreach ($list as $name) {
            $ops->push((object) [
                'operator_name' => $name,
                'operator_code' => strtoupper(str_replace(' ', '', $name)),
                'operator_logo' => null,
            ]);
        }

        return $ops;
    }

    /**
     * Service: Recharge - Mobile
     */
    public function serviceRechargeMobile()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        return view('user.service-recharge-mobile', compact('user'));
    }

    public function rechargeConfirm(\Illuminate\Http\Request $request)
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        // Get Cashfree Mode
        $gateway = \App\Models\PaymentGateway::where('slug', 'cashfree')->first();
        $cashfreeMode = ($gateway && $gateway->active_mode === 'live') ? 'production' : 'sandbox';

        return view('user.service-recharge-confirm', compact('cashfreeMode'));
    }

    public function fetchMobileOperator(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:10',
        ]);

        $mobile = $request->mobile;
        // Generate random 20 digit order id starting with OD
        $random = '';
        for ($i = 0; $i < 20; $i++) {
            $random .= mt_rand(0, 9);
        }
        $orderid = 'OD'.$random;

        $username = '9564853492';
        $token = '6d8b045b5c92ff9916806d5bd652fe6a';

        $url = 'https://connect.ekychub.in/v3/verification/operator_fetch';

        try {
            $response = \Illuminate\Support\Facades\Http::get($url, [
                'username' => $username,
                'token' => $token,
                'mobile' => $mobile,
                'orderid' => $orderid,
            ]);

            $data = $response->json();

            // Try to find matching operator in DB to get the logo
            $operatorName = $data['company'] ?? $data['operator'] ?? $data['operator_name'] ?? null;

            if ($operatorName) {
                // We use LIKE to match somewhat loosely
                $localOperator = \App\Models\RechargeOperator::where('operator_name', 'LIKE', "%{$operatorName}%")
                    ->orWhere('operator_code', $operatorName)
                    ->first();

                if ($localOperator && $localOperator->operator_logo) {
                    $data['operator_logo'] = asset('storage/'.$localOperator->operator_logo);
                }
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch operator'], 500);
        }
    }

    /**
     * Service: Recharge - DTH
     */
    public function serviceRechargeDth()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        // Fetch DTH Service
        $dthService = \App\Models\RechargeService::where('service_name', 'LIKE', '%DTH%')
            ->orWhere('service_code', 'LIKE', '%dth%')
            ->first();

        $operators = [];
        if ($dthService) {
            $operators = \App\Models\RechargeOperator::where('recharge_service_id', $dthService->id)
                ->where('is_active', true)
                ->get();
        }

        return view('user.service-recharge-dth', compact('user', 'operators'));
    }

    /**
     * Service: Recharge - FASTag
     */
    public function serviceRechargeFastag()
    {
        if (! \Illuminate\Support\Facades\Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $user = \Illuminate\Support\Facades\Session::get('user_auth');

        // Fetch FASTag Service
        $fastagService = \App\Models\RechargeService::where('service_name', 'LIKE', '%FASTag%')
            ->orWhere('service_code', 'LIKE', '%FASTAG%')
            ->first();

        $operators = [];
        if ($fastagService) {
            $operators = \App\Models\RechargeOperator::where('recharge_service_id', $fastagService->id)
                ->where('is_active', true)
                ->get();
        }

        // Fallback: If no operators in DB, use a comprehensive list of Indian FASTag banks
        if (count($operators) == 0) {
            $bankList = [
                'Airtel Payments Bank', 'Allahabad Bank', 'AU Small Finance Bank', 'Axis Bank',
                'Bank of Baroda', 'Bank of Maharashtra', 'Canara Bank', 'Central Bank of India',
                'City Union Bank', 'Cosmos Bank', 'DBS Bank', 'Equitas Small Finance Bank',
                'Federal Bank', 'Fino Payments Bank', 'HDFC Bank', 'ICICI Bank', 'IDBI Bank',
                'IDFC First Bank', 'Indian Bank', 'Indian Overseas Bank', 'IndusInd Bank',
                'Jammu and Kashmir Bank', 'Karnataka Bank', 'Karur Vysya Bank', 'Kotak Mahindra Bank',
                'Nagpur Nagarik Sahakari Bank', 'Paytm Payments Bank', 'Punjab National Bank',
                'Punjab & Maharashtra Co-op Bank', 'Saraswat Bank', 'South Indian Bank',
                'State Bank of India', 'Syndicate Bank', 'UCO Bank', 'Union Bank of India',
                'United Bank of India', 'Yes Bank',
            ];

            $operators = collect();
            foreach ($bankList as $bank) {
                $operators->push((object) [
                    'operator_name' => $bank,
                    'operator_code' => $bank, // Using name as code for now
                    'operator_logo' => null,
                ]);
            }
        }

        return view('user.service-recharge-fastag', compact('user', 'operators'));
    }

    /**
     * Show My Wallet page
     */
    public function showMyWallet(\Illuminate\Http\Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        $fromDate = $request->filled('from_date') ? (string) $request->input('from_date') : null;
        $toDate = $request->filled('to_date') ? (string) $request->input('to_date') : null;

        $transactionsQuery = \App\Models\WalletTransaction::query()
            ->where('registration_id', $user->id)
            ->orderByDesc('created_at');

        if ($fromDate) {
            $transactionsQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $transactionsQuery->whereDate('created_at', '<=', $toDate);
        }

        $isFirstWalletTopup = ! \App\Models\WalletTransaction::query()
            ->where('registration_id', $user->id)
            ->where('transaction_type', 'add')
            ->where(function ($q) {
                $q->where('narration', 'like', 'Wallet Topup%')
                    ->orWhere('narration', 'like', 'Wallet top-up%');
            })
            ->exists();

        if ($request->filled('export') && $request->input('export') === 'excel') {
            $rows = $transactionsQuery->get();

            $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
            {
                public function __construct(private \Illuminate\Support\Collection $rows) {}

                public function collection()
                {
                    return $this->rows;
                }

                public function headings(): array
                {
                    return [
                        'Date',
                        'Type',
                        'Description',
                        'Amount',
                        'Previous Balance',
                        'New Balance',
                    ];
                }

                public function map($row): array
                {
                    $type = (string) ($row->transaction_type ?? '');

                    return [
                        optional($row->created_at)->format('Y-m-d H:i'),
                        $type === 'add' ? 'Credit' : 'Debit',
                        (string) ($row->narration ?? ''),
                        (float) ($row->amount ?? 0),
                        (float) ($row->previous_balance ?? 0),
                        (float) ($row->new_balance ?? 0),
                    ];
                }
            };

            $fileName = 'wallet-transactions-'.now()->format('Y-m-d').'.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download($export, $fileName);
        }

        $transactions = $transactionsQuery->limit(200)->get();

        return view('user.my-wallet', compact('user', 'transactions', 'isFirstWalletTopup'));
    }

    /**
     * Show Refer & Earn page
     */
    public function referAndEarn()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        // Use user ID as base for referral code if not available
        // Format: ECARD + padded ID (e.g., ECARD00123)
        $referralCode = 'ECARD'.str_pad($user->id, 5, '0', STR_PAD_LEFT);

        return view('user.refer-earn', compact('user', 'referralCode'));
    }

    /**
     * Show Manage Payments page
     */
    public function managePayments()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $user = Registration::find($userSession['id']);

        return view('user.manage-payments', compact('user'));
    }

    public function fetchPlans(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:10',
            'opcode' => 'required',
            'circle' => 'required',
        ]);

        $mobile = $request->mobile;
        $opcode = $request->opcode;
        $circle = $request->circle;

        // Generate random 15 digit number for orderid
        $random = '';
        for ($i = 0; $i < 15; $i++) {
            $random .= mt_rand(0, 9);
        }
        $orderid = 'OD'.$random;

        $username = '9564853492';
        $token = '6d8b045b5c92ff9916806d5bd652fe6a';

        $url = 'https://connect.ekychub.in/v3/verification/operator_plan_fetch';

        try {
            $response = \Illuminate\Support\Facades\Http::get($url, [
                'username' => $username,
                'token' => $token,
                'mobile' => $mobile,
                'opcode' => $opcode,
                'circle' => $circle,
                'orderid' => $orderid,
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch plans'], 500);
        }
    }

    public function fetchDthPlans(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'opcode' => 'required',
            'subscriber_id' => 'required',
        ]);

        $opcode = $request->opcode;
        $dthNumber = $request->subscriber_id;

        // Generate random 15 digit number for orderid
        $random = '';
        for ($i = 0; $i < 15; $i++) {
            $random .= mt_rand(0, 9);
        }
        $orderid = $random; // User asked for 15 digit random unique order id, without 'OD' prefix mention, but standard is usually numeric or alphanumeric.
        // User prompt: "orderid=<15 digit random unique order id>"

        $username = '9564853492';
        $token = '6d8b045b5c92ff9916806d5bd652fe6a';

        $url = 'https://connect.ekychub.in/v3/verification/dth_plan_fetch';

        try {
            $response = \Illuminate\Support\Facades\Http::get($url, [
                'username' => $username,
                'token' => $token,
                'dth_number' => $dthNumber,
                'opcode' => $opcode,
                'orderid' => $orderid,
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch plans'], 500);
        }
    }
}
