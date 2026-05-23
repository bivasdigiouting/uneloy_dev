<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /**
     * Send Mobile OTP
     * 
     * Send an OTP to the user's registered mobile number for verification.
     * 
     * @group Verification
     * @authenticated
     * 
     * @response 200 {
     *  "message": "OTP sent successfully to registered mobile number.",
     *  "dev_otp": 123456
     * }
     */
    public function sendMobileOtp(Request $request)
    {
        $user = $request->user();

        // 1. Generate OTP
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        // 2. Store OTP
        VerificationCode::create([
            'ecard_registration_id' => $user->id,
            'type' => 'mobile',
            'contact' => $user->mobile_no,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        // 3. Send SMS (Mock implementation for now, or replace with actual gateway)
        $this->sendSms($user->mobile_no, "Your OTP is: $otp");

        // Return response (In production, DO NOT return OTP in response)
        // For development convenience, we can log it or include it if debug mode is on.
        // Here we will just say sent.
        
        $response = [
            'message' => 'OTP sent successfully to registered mobile number.',
        ];

        // FOR DEVELOPMENT ONLY: Return OTP in response to allow testing without SMS gateway
        if (config('app.debug')) {
            $response['dev_otp'] = $otp;
        }

        return response()->json($response);
    }

    /**
     * Verify Mobile OTP
     * 
     * Verify the OTP sent to the mobile number.
     * 
     * @group Verification
     * @authenticated
     * 
     * @bodyParam otp string required The 6-digit OTP. Example: 123456
     * 
     * @response 200 {
     *  "message": "Mobile number verified successfully."
     * }
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *    "otp": ["Invalid or expired OTP."]
     *  }
     * }
     */
    public function verifyMobileOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = $request->user();

        $record = VerificationCode::where('ecard_registration_id', $user->id)
            ->where('type', 'mobile')
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$record) {
             throw ValidationException::withMessages([
                'otp' => ['Invalid or expired OTP.'],
            ]);
        }

        // Mark as verified
        $user->mobile_verified_at = Carbon::now();
        $user->save();

        // Optional: Delete used OTP
        $record->delete();

        return response()->json([
            'message' => 'Mobile number verified successfully.',
        ]);
    }

    /**
     * Send Email OTP
     * 
     * Send an OTP to the user's registered email address for verification.
     * 
     * @group Verification
     * @authenticated
     * 
     * @response 200 {
     *  "message": "OTP sent successfully to registered email address."
     * }
     * @response 400 {
     *  "message": "No email address found for this user."
     * }
     */
    public function sendEmailOtp(Request $request)
    {
        $user = $request->user();
        
        if (empty($user->email_id)) {
            return response()->json(['message' => 'No email address found for this user.'], 400);
        }

        // 1. Generate OTP
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        // 2. Store OTP
        VerificationCode::create([
            'ecard_registration_id' => $user->id,
            'type' => 'email',
            'contact' => $user->email_id,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        // 3. Send Email
        try {
            Mail::to($user->email_id)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email: " . $e->getMessage());
            return response()->json(['message' => 'Failed to send email. Please try again later.'], 500);
        }

        return response()->json([
            'message' => 'OTP sent successfully to registered email address.',
        ]);
    }

    /**
     * Verify Email OTP
     * 
     * Verify the OTP sent to the email address.
     * 
     * @group Verification
     * @authenticated
     * 
     * @bodyParam otp string required The 6-digit OTP. Example: 123456
     * 
     * @response 200 {
     *  "message": "Email address verified successfully."
     * }
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *    "otp": ["Invalid or expired OTP."]
     *  }
     * }
     */
    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = $request->user();

        $record = VerificationCode::where('ecard_registration_id', $user->id)
            ->where('type', 'email')
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$record) {
             throw ValidationException::withMessages([
                'otp' => ['Invalid or expired OTP.'],
            ]);
        }

        // Mark as verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Optional: Delete used OTP
        $record->delete();

        return response()->json([
            'message' => 'Email address verified successfully.',
        ]);
    }

    /**
     * Private helper to send SMS
     */
    private function sendSms($mobile, $message)
    {
        // Integration with Free SMS API or Firebase would go here.
        // Example with a generic GET request (uncomment and configure to use):
        
        /*
        $apiKey = config('services.sms.key');
        $url = "https://www.fast2sms.com/dev/bulkV2?authorization=$apiKey&message=".urlencode($message)."&language=english&route=q&numbers=".urlencode($mobile);
        
        try {
            \Illuminate\Support\Facades\Http::get($url);
        } catch (\Exception $e) {
            Log::error("SMS Send Error: " . $e->getMessage());
        }
        */

        Log::info("SMS Mock: To $mobile - Message: $message");
    }
}
