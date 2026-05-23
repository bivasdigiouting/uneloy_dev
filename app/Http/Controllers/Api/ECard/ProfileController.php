<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\ECardRegistration;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * @group User Profile
 *
 * APIs for managing ECard user profile and fetching location data.
 */
class ProfileController extends Controller
{
    /**
     * Get Profile
     *
     * Fetch the authenticated user's profile details.
     *
     * @group User Profile
     * @authenticated
     *
     * @response 200 {
     *  "message": "Profile fetched successfully",
     *  "user": {
     *    "id": 1,
     *    "first_name": "John",
     *    "last_name": "Doe",
     *    "mobile_no": "9876543210",
     *    "email_id": "john@example.com",
     *    "profile_image_url": "http://localhost/storage/profile_images/abc.jpg",
     *    "state": "1",
     *    "district": "2",
     *    "city": "3",
     *    "whatsapp_no": "9876543210",
     *    "user_level": "state_level"
     *  }
     * }
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();
        
        // Append full image URL
        $user->profile_image_url = $user->profile_image 
            ? asset('storage/' . $user->profile_image) 
            : null;

        // Ensure wallet balance is a float
        $user->wallet_balance = (float) ($user->wallet_balance ?? 0);

        // Add user_level (same as department_level)
        $user->user_level = $user->department_level;

        return response()->json([
            'message' => 'Profile fetched successfully',
            'user' => $user
        ]);
    }

    /**
     * Update Profile
     *
     * Update the user's profile information including profile image.
     *
     * @group User Profile
     * @authenticated
     *
     * @bodyParam profile_image file The profile picture (jpeg, png, jpg, gif, max 2MB).
     * @bodyParam first_name string required First Name.
     * @bodyParam middle_name string Middle Name.
     * @bodyParam last_name string Last Name.
     * @bodyParam father_name string Father's Name.
     * @bodyParam mother_name string Mother's Name.
     * @bodyParam blood_group string Blood Group (e.g., A+, B-).
     * @bodyParam date_of_birth date Date of Birth (YYYY-MM-DD).
     * @bodyParam gender string Gender (Male, Female, Other).
     * @bodyParam marital_status string Marital Status (Single, Married, Divorced, Widowed).
     * @bodyParam mobile_no string required Mobile Number.
     * @bodyParam whatsapp_no string WhatsApp Number.
     * @bodyParam email_id string Email Address.
     * @bodyParam current_address string Current Address.
     * @bodyParam permanent_address string Permanent Address.
     * @bodyParam state string State ID or Name.
     * @bodyParam district string District ID or Name.
     * @bodyParam city string City ID or Name.
     * @bodyParam pin_code string Pin Code.
     * @bodyParam last_qualification string Last Qualification.
     * @bodyParam work_type string Work Type.
     * @bodyParam work_experience string Work Experience.
     *
     * @response 200 {
     *  "message": "Profile updated successfully",
     *  "user": {
     *    "first_name": "John",
     *    "last_name": "Doe",
     *    "profile_image": "profile_images/xyz.jpg"
     *  }
     * }
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            
            // Contact
            'mobile_no' => ['required', 'string', 'max:15', Rule::unique('ecard_registrations')->ignore($user->id)],
            'whatsapp_no' => 'nullable|string|max:15',
            'email_id' => ['nullable', 'email', 'max:255', Rule::unique('ecard_registrations')->ignore($user->id)],
            'gmail_id' => 'nullable|email|max:255', // If used separately
            
            // Address
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'state' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'pin_code' => 'nullable|string|max:10',
            
            // Qualification
            'last_qualification' => 'nullable|string|max:255',
            'work_type' => 'nullable|string|max:255',
            'work_experience' => 'nullable|string|max:255',

            // Image
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Handle File Upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        // Update User
        $user->update($data);

        // Refresh user to get updated data
        $user->refresh();
        
        $user->profile_image_url = $user->profile_image 
            ? asset('storage/' . $user->profile_image) 
            : null;

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Get States
     *
     * Fetch list of all active states.
     *
     * @group Master Data
     *
     * @response 200 {
     *  "states": [
     *    {"id": 1, "state_name": "Maharashtra", "status": "active"},
     *    {"id": 2, "state_name": "Karnataka", "status": "active"}
     *  ]
     * }
     */
    public function getStates()
    {
        $states = State::where('status', 'active')->get();
        return response()->json(['states' => $states]);
    }

    /**
     * Get Districts
     *
     * Fetch list of districts for a given state.
     *
     * @group Master Data
     *
     * @urlParam state_id int required The ID of the state.
     *
     * @response 200 {
     *  "districts": [
     *    {"id": 1, "district_name": "Pune", "state_id": 1, "status": "active"}
     *  ]
     * }
     */
    public function getDistricts($state_id)
    {
        $districts = District::where('state_id', $state_id)
                             ->where('status', 'active')
                             ->get();
        return response()->json(['districts' => $districts]);
    }

    /**
     * Get Cities
     *
     * Fetch list of cities for a given district.
     *
     * @group Master Data
     *
     * @urlParam district_id int required The ID of the district.
     *
     * @response 200 {
     *  "cities": [
     *    {"id": 1, "city_name": "Pune City", "district_id": 1, "status": "active"}
     *  ]
     * }
     */
    public function getCities($district_id)
    {
        $cities = City::where('district_id', $district_id)
                      ->where('status', 'active')
                      ->get();
        return response()->json(['cities' => $cities]);
    }
}
