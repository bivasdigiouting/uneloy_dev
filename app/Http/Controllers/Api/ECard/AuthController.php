<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * ECard Login
     * 
     * Authenticate an ECard user using User ID, Email, or Mobile Number.
     * 
     * @group Authentication
     * @unauthenticated
     * 
     * @bodyParam login string required The User ID, Email ID, or Mobile Number. Example: USER123
     * @bodyParam password string required The password. Example: password123
     * 
     * @response 200 {
     *  "message": "Login successful",
     *  "token": "1|laravel_sanctum_token...",
     *  "user": {
     *      "id": 1,
     *      "user_level": "state_level",
     *      "department_level": "state_level"
     *  }
     * }
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *    "login": ["The provided credentials are incorrect."]
     *  }
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');

        // Check for User ID, Email ID, or Mobile Number
        $user = ECardRegistration::where('user_id', $login)
            ->orWhere('email_id', $login)
            ->orWhere('mobile_no', $login)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('ecard-app-token')->plainTextToken;

        $user->user_level = $user->department_level;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * ECard Logout
     * 
     * Revoke the current user's access token.
     * 
     * @group Authentication
     * @authenticated
     * 
     * @response 200 {
     *  "message": "Logged out successfully"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
