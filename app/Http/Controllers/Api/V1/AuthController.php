<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use App\Models\UserLoginHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(property="phone", type="string", example="+1234567890")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="token", type="string", example="1|abc123...")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customerRole = \App\Models\Role::where('name', 'customer')->first();
        if (! $customerRole) {
            $customerRole = \App\Models\Role::firstOrCreate(
                ['name' => 'customer', 'guard_name' => 'web'],
                ['display_name' => 'Member', 'description' => 'Member level user', 'is_active' => true]
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => $customerRole->id,
        ]);

        try {
            $user->assignRole($customerRole->name);
        } catch (\Throwable $e) {
            // ignore role assignment errors
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Login customer",
     *     tags={"Authentication"},
     *     description="Authenticate a customer using Registration credentials. Accepts either user_id or email, with department_level enforced as 'customer'.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"password"},
     *
     *             @OA\Property(property="user_id", type="string", example="UP12345678", description="Required if email is not provided"),
     *             @OA\Property(property="email", type="string", format="email", example="customer@example.com", description="Required if user_id is not provided"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="token", type="string", example="1|abc123...")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object", example={"user_id":{"The user id or email is required."}})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     *
     * @group Authentication
     *
     * @title Login (Member)
     * Authenticate customer via registration credentials (user_id or email). Only registrations with department_level='customer' are allowed.
     *
     * @unauthenticated
     *
     * @bodyParam user_id string required_without:email The registration user ID. Example: UP12345678.
     * @bodyParam email string required_without:user_id The registration email. Example: customer@example.com.
     * @bodyParam password string required The account password.
     *
     * @response 200 {"success": true, "message": "Login successful", "data": {"user": {"id": 12, "name": "John Member", "email": "customer@example.com", "user_id": "UP12345678", "role_id": 3}, "token": "1|abc123..."}}
     * @response 422 {"success": false, "message": "Validation failed", "errors": {"user_id": ["The user id or email is required."]}}
     * @response 401 {"success": false, "message": "Invalid credentials"}
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|string|required_without:email',
            'email' => 'nullable|string|email|required_without:user_id',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Find registration by user_id or email and ensure department_level is customer
        $registrationQuery = \App\Models\Registration::query()->where('department_level', 'customer');
        $registration = $request->filled('user_id')
            ? $registrationQuery->where('user_id', $request->user_id)->first()
            : $registrationQuery->where('email_id', $request->email)->first();

        // Check credentials
        if (! $registration || ! Hash::check($request->password, $registration->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Ensure corresponding User exists to issue Sanctum token
        $user = User::where('user_id', $registration->user_id)
            ->orWhere('email', $registration->email_id)
            ->first();

        if (! $user) {
            $customerRole = \App\Models\Role::where('name', 'customer')->first();
            if (! $customerRole) {
                $customerRole = \App\Models\Role::firstOrCreate(
                    ['name' => 'customer', 'guard_name' => 'web'],
                    ['display_name' => 'Member', 'description' => 'Member level user', 'is_active' => true]
                );
            }

            $name = trim(implode(' ', array_filter([
                $registration->first_name,
                $registration->middle_name,
                $registration->last_name,
            ]))) ?: ($registration->business_name ?: 'Customer');

            $email = $registration->email_id ?: ('customer+'.$registration->user_id.'@uonly.local');

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $registration->password, // already hashed
                'phone' => $registration->mobile_no,
                'role_id' => $customerRole->id,
                'user_id' => $registration->user_id,
            ]);

            try {
                $user->assignRole($customerRole->name);
            } catch (\Throwable $e) {
            }
        }

        // Log the user in and issue token
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        // Record login history (API/mobile)
        try {
            UserLoginHistory::create([
                'user_id' => $user->id ?? null,
                'registration_id' => $registration->id ?? null,
                'ip_address' => $request->ip(),
                'platform' => $request->header('X-Client-Platform', 'mobile'),
                'user_agent' => $request->header('User-Agent'),
                'logged_in_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Swallow errors to not block login
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Logout user",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logout successful")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $authUser = $request->user();
        // Mark latest login history as logged out for this user/registration/platform
        try {
            $registration = null;
            if ($authUser) {
                // Try mapping by user_id on registration first
                if (! empty($authUser->user_id)) {
                    $registration = \App\Models\Registration::where('user_id', $authUser->user_id)->first();
                }
                if (! $registration && ! empty($authUser->email)) {
                    $registration = \App\Models\Registration::where('email_id', $authUser->email)->first();
                }
                if (! $registration) {
                    $registration = \App\Models\Registration::where('user_id', $authUser->id)->first();
                }

                $platform = strtolower($request->header('X-Client-Platform', 'mobile'));
                $history = \App\Models\UserLoginHistory::where('registration_id', optional($registration)->id)
                    ->where('platform', $platform)
                    ->orderByDesc('logged_in_at')
                    ->first();

                if ($history && empty($history->logged_out_at)) {
                    $history->logged_out_at = now();
                    // Preserve original IP; if needed, update to latest seen IP (optional)
                    $history->save();
                }
            }
        } catch (\Throwable $e) {
            // Do not block logout on history errors
        }

        if ($authUser && $authUser->currentAccessToken()) {
            $authUser->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/profile",
     *     summary="Get user profile",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profile retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => $request->user(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/registration-profile",
     *     summary="Get registration profile (customer)",
     *     tags={"Registration"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Registration profile retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Registration profile retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user_id", type="string", example="UP12345678"),
     *                 @OA\Property(property="department_level", type="string", example="customer"),
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Member"),
     *                 @OA\Property(property="email_id", type="string", example="customer@example.com"),
     *                 @OA\Property(property="mobile_no", type="string", example="+1234567890"),
     *                 @OA\Property(property="profile_image_url", type="string", nullable=true, example="/storage/profile_images/abc123.jpg")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Only customers can access this endpoint",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Only customers can access this endpoint")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Registration record not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Registration record not found")
     *         )
     *     )
     * )
     *
     * @group Registration
     *
     * @authenticated
     *
     * @response 200 {"success":true,"message":"Registration profile retrieved successfully","data":{"user_id":"UP12345678","department_level":"customer","first_name":"John","last_name":"Member","email_id":"customer@example.com","mobile_no":"+1234567890","profile_image_url":"/storage/profile_images/abc123.jpg"}}
     * @response 403 {"success":false,"message":"Only customers can access this endpoint"}
     * @response 404 {"success":false,"message":"Registration record not found"}
     */
    public function registrationProfile(Request $request)
    {
        $user = $request->user();

        $registration = null;
        if (! empty($user->user_id)) {
            $registration = Registration::where('user_id', $user->user_id)->first();
        }
        if (! $registration && ! empty($user->email)) {
            $registration = Registration::where('email_id', $user->email)->first();
        }

        if (! $registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration record not found',
            ], Response::HTTP_NOT_FOUND);
        }

        if (strtolower((string) $registration->department_level) !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can access this endpoint',
            ], Response::HTTP_FORBIDDEN);
        }

        // Resolve profile image URL from authenticated user if available
        $profileImageUrl = null;
        try {
            if (! empty($user->image) && Storage::disk('public')->exists($user->image)) {
                $profileImageUrl = Storage::url($user->image);
            }
        } catch (\Throwable $e) {
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration profile retrieved successfully',
            'data' => array_merge($registration->toArray(), [
                'profile_image_url' => $profileImageUrl,
            ]),
        ]);
    }

    /**
     * @OA@Post(
     *     path="/api/v1/auth/registration-profile/image",
     *     summary="Update registration profile image",
     *     tags={"Registration"},
     *     security={{"sanctum":{}}},
     *
     *     @OA=RequestBody(
     *         required=true,
     *         content={
     *             "multipart/form-data": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "image": {
     *                             "type": "string",
     *                             "format": "binary",
     *                             "description": "Profile image file"
     *                         }
     *                     },
     *                     "required": {"image"}
     *                 }
     *             }
     *         }
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile image updated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profile image updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="profile_image_url", type="string", example="/storage/profile_images/abc123.jpg"),
     *                 @OA\Property(property="uploaded", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     *
     * @group Registration
     *
     * @authenticated
     *
     * @title Update Registration Profile Image
     * Upload and set the authenticated user's profile image.
     *
     * @bodyParam image file required The profile image file (JPEG/PNG, max 2MB).
     *
     * @response 200 {"success":true,"message":"Profile image updated successfully","data":{"profile_image_url":"/storage/profile_images/abc123.jpg","uploaded":true}}
     * @response 422 {"success":false,"message":"Validation failed","errors":{"image":["The image field is required."]}}
     */
    public function updateRegistrationProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $request->user();

        $path = $request->file('image')->store('profile_images', 'public');

        // Optionally delete old image if exists
        try {
            if (! empty($user->image) && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
        } catch (\Throwable $e) {
        }

        $user->image = $path;
        $user->save();

        $uploaded = Storage::disk('public')->exists($user->image);
        $url = $uploaded ? Storage::url($user->image) : null;

        return response()->json([
            'success' => true,
            'message' => 'Profile image updated successfully',
            'data' => [
                'profile_image_url' => $url,
                'uploaded' => (bool) $uploaded,
            ],
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/auth/registration-profile",
     *     summary="Update registration profile (customer)",
     *     tags={"Registration"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"department_level","first_name","date_of_birth","current_address","permanent_address","nationality","state","district","city","pin_code","mobile_no","live_location_map","aadhaar_no","otp_required"},
     *
     *             @OA\Property(property="department_level", type="string", example="customer"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="middle_name", type="string", example="M"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="father_name", type="string", example="Richard Doe"),
     *             @OA\Property(property="mother_name", type="string", example="Jane Doe"),
     *             @OA\Property(property="blood_group", type="string", example="O+"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1995-05-15"),
     *             @OA\Property(property="gender", type="string", example="Male"),
     *             @OA\Property(property="marital_status", type="string", example="Single"),
     *             @OA\Property(property="current_address", type="string", example="123 Street, Area"),
     *             @OA\Property(property="permanent_address", type="string", example="456 Avenue, City"),
     *             @OA\Property(property="nationality", type="string", example="India"),
     *             @OA\Property(property="state", type="string", example="Bihar"),
     *             @OA\Property(property="district", type="string", example="Patna"),
     *             @OA\Property(property="city", type="string", example="Patna"),
     *             @OA\Property(property="pin_code", type="string", example="800001"),
     *             @OA\Property(property="mobile_no", type="string", example="9876543210"),
     *             @OA\Property(property="phone_no", type="string", example="0612-123456"),
     *             @OA\Property(property="email_id", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="gmail_id", type="string", format="email", example="user@gmail.com"),
     *             @OA\Property(property="live_location_map", type="string", example="25.612,85.135"),
     *             @OA\Property(property="aadhaar_no", type="string", example="123412341234"),
     *             @OA\Property(property="otp_required", type="boolean", example=true),
     *             @OA\Property(property="otp_verified", type="boolean", example=true),
     *             @OA\Property(property="last_qualification", type="string", example="B.Sc"),
     *             @OA\Property(property="work_type", type="string", example="Field"),
     *             @OA\Property(property="work_experience", type="string", example="3 years")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Registration profile updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Registration profile updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Only customers can access this endpoint",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Only customers can access this endpoint")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Registration record not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Registration record not found")
     *         )
     *     )
     * )
     *
     * @group Registration
     *
     * @authenticated
     *
     * @bodyParam department_level string required One of: state_level, district_level, block_level, panchayat_level, village_level, customer.
     * @bodyParam first_name string required
     * @bodyParam middle_name string
     * @bodyParam last_name string
     * @bodyParam father_name string
     * @bodyParam mother_name string
     * @bodyParam blood_group string One of: A+, A-, B+, B-, AB+, AB-, O+, O-
     * @bodyParam date_of_birth date required Between 5 and 60 years ago
     * @bodyParam gender string One of: Male, Female
     * @bodyParam marital_status string One of: Single, Married, Divorced, Widowed
     * @bodyParam current_address string required
     * @bodyParam permanent_address string required
     * @bodyParam nationality string required
     * @bodyParam state string required
     * @bodyParam district string required
     * @bodyParam city string required
     * @bodyParam pin_code string required
     * @bodyParam mobile_no string required
     * @bodyParam phone_no string
     * @bodyParam email_id email
     * @bodyParam gmail_id email
     * @bodyParam live_location_map string required
     * @bodyParam aadhaar_no string required
     * @bodyParam otp_required boolean required
     * @bodyParam otp_verified boolean
     * @bodyParam last_qualification string
     * @bodyParam work_type string
     * @bodyParam work_experience string
     *
     * @response 200 {"success":true,"message":"Registration profile updated successfully","data":{}}
     */
    public function updateRegistrationProfile(Request $request)
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
            'pin_code' => 'required|string|max:10',
            'mobile_no' => 'required|string|max:15',
            'email_id' => 'nullable|email|max:255',
            'gmail_id' => 'nullable|email|max:255',
            'live_location_map' => 'required|string',
            'aadhaar_no' => 'required|string|max:12',
            'otp_required' => 'required|boolean',
            'otp_verified' => 'nullable|boolean',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender' => 'nullable|string|in:Male,Female',
            'marital_status' => 'nullable|string|in:Single,Married,Divorced,Widowed',
            'phone_no' => 'nullable|string|max:20',
            'last_qualification' => 'nullable|string|max:255',
            'work_type' => 'nullable|string|max:255',
            'work_experience' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $request->user();

        $registration = null;
        if (! empty($user->user_id)) {
            $registration = Registration::where('user_id', $user->user_id)->first();
        }
        if (! $registration && ! empty($user->email)) {
            $registration = Registration::where('email_id', $user->email)->first();
        }

        if (! $registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration record not found',
            ], Response::HTTP_NOT_FOUND);
        }

        if (strtolower((string) $registration->department_level) !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can access this endpoint',
            ], Response::HTTP_FORBIDDEN);
        }

        $data = $validator->validated();
        $registration->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Registration profile updated successfully',
            'data' => $registration->fresh(),
        ]);
    }

    /**
     * Generate My QR code (customer only)
     *
     * @group Registration
     *
     * @authenticated
     *
     * Generate a dynamic QR code using the logged-in user's `user_id` from the registrations table.
     * Only available when the associated registration has `department_level = customer`.
     *
     * @OA\Post(
     *     path="/api/v1/auth/my-qr/generate",
     *     tags={"Registration"},
     *     security={{"sanctum":{}}},
     *     summary="Generate my QR (customer)",
     *     description="Creates a PNG QR image based on the registration's user_id and stores it on the public disk.",
     *
     *     @OA\Response(
     *         response=201,
     *         description="QR code generated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="QR code generated"),
     *             @OA\Property(property="file", type="string", example="qr-codes/user-123.png"),
     *             @OA\Property(property="url", type="string", example="http://127.0.0.1:8000/api/v1/auth/my-qr")
     *         )
     *     ),
     *
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level"),
     *     @OA\Response(response=404, description="Registration not found for user")
     * )
     */
    /**
     * Fetch My QR code (customer only)
     *
     * @group Registration
     *
     * @authenticated
     *
     * Returns the PNG QR image generated for the logged-in user's registration.
     * Requires the registration to have `department_level = customer` and the QR to be generated first.
     *
     * @OA\Get(
     *     path="/api/v1/auth/my-qr",
     *     tags={"Registration"},
     *     security={{"sanctum":{}}},
     *     summary="Fetch my QR image (customer)",
     *
     *     @OA\Response(response=200, description="PNG image returned"),
     *     @OA\Response(response=404, description="QR not generated or registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    /**
     * @OA\Post(
     *     path="/api/v1/auth/change-password",
     *     summary="Change password",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"current_password","new_password","new_password_confirmation"},
     *
     *             @OA\Property(property="current_password", type="string", format="password", example="oldPass123"),
     *             @OA\Property(property="new_password", type="string", format="password", example="NewPass123!"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="password", example="NewPass123!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password changed successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     *
     * Change the authenticated user's password. If a linked registration exists,
     * its password is updated to keep credentials in sync.
     *
     * @group Authentication
     *
     * @authenticated
     *
     * @bodyParam current_password string required The current password.
     * @bodyParam new_password string required The new password (min 8). Must be confirmed.
     * @bodyParam new_password_confirmation string required Must match new_password.
     *
     * @response 200 {"success":true,"message":"Password changed successfully"}
     * @response 422 {"success":false,"message":"Validation failed","errors":{"current_password":["Current password is incorrect"]}}
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect'],
            ]);
        }

        $newHashed = Hash::make($request->new_password);

        // Update User password
        $user->update(['password' => $newHashed]);

        // Try to sync Registration password for customer-level accounts
        try {
            $registration = null;
            if (! empty($user->user_id)) {
                $registration = Registration::where('user_id', $user->user_id)->first();
            }
            if (! $registration && ! empty($user->email)) {
                $registration = Registration::where('email_id', $user->email)->first();
            }

            if ($registration) {
                $registration->update(['password' => $newHashed]);
            }
        } catch (\Throwable $e) {
            // Do not block password change if registration sync fails
        }

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }
}
