<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use App\Models\User;
use App\Models\UserEmailVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends Controller
{
    /**
     * Email Verification
     *
     * Send a 6-digit verification code to the provided email.
     *
     * @group Auth
     *
     * @subgroup Email Verification
     *
     * @unauthenticated
     *
     * @bodyParam email string required The email address to verify. Example: johndoe@example.com
     *
     * @response 200 {"message":"Verification code sent"}
     * @response 404 {"message":"Email not found"}
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if (! $user) {
            return response()->json(['message' => 'Email not found'], Response::HTTP_NOT_FOUND);
        }

        $code = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        UserEmailVerification::create([
            'user_id' => $user->id,
            'email' => $email,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        Mail::to($email)->send(new EmailVerificationCodeMail($code, 10));

        return response()->json(['message' => 'Verification code sent'], Response::HTTP_OK);
    }

    /**
     * Verify Email Code
     *
     * Verify the 6-digit code sent to email and mark user verified.
     *
     * @group Auth
     *
     * @subgroup Email Verification
     *
     * @unauthenticated
     *
     * @bodyParam email string required The email address to verify. Example: johndoe@example.com
     * @bodyParam code string required The 6-digit verification code. Example: 123456
     *
     * @response 200 {"message":"Email verified","verified": true}
     * @response 422 {"message":"Invalid or expired code","verified": false}
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $request->input('email');
        $code = $request->input('code');

        $verification = UserEmailVerification::where('email', $email)
            ->where('code', $code)
            ->orderByDesc('id')
            ->first();

        if (! $verification || $verification->used_at || $verification->isExpired()) {
            return response()->json(['message' => 'Invalid or expired code', 'verified' => false], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            return response()->json(['message' => 'Email not found'], Response::HTTP_NOT_FOUND);
        }

        // Mark verification as used
        $verification->used_at = Carbon::now();
        $verification->save();

        // Mark user email as verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json(['message' => 'Email verified', 'verified' => true], Response::HTTP_OK);
    }

    /**
     * Email Verification Status
     *
     * Check whether a user's email is verified.
     *
     * @group Auth
     *
     * @subgroup Email Verification
     *
     * @unauthenticated
     *
     * @bodyParam email string required The email address to check. Example: johndoe@example.com
     *
     * @response 200 {"verified": true}
     * @response 200 {"verified": false}
     */
    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        $verified = $user ? ! empty($user->email_verified_at) : false;

        return response()->json(['verified' => $verified], Response::HTTP_OK);
    }
}
