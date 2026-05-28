<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\TwoFactorOtp;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Mail\OtpMail;

class VendorAuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $vendor = Vendor::where('gmail_id', $request->email)
            ->where('status', 'active')
            ->first();

        if (! $vendor || ! Hash::check($request->password, $vendor->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $email = trim((string) ($vendor->gmail_id ?? ''));
        if ($email === '') {
            throw ValidationException::withMessages([
                'email' => ['Email is not registered for this vendor. Please contact admin.'],
            ]);
        }

        [$otp, $plainOtp] = TwoFactorOtp::issue('vendor', (int) $vendor->id, $email, 10);

        try {
            Mail::to($email)->send(new OtpMail($plainOtp));
        } catch (\Throwable $e) {
            Log::error('Vendor OTP mail send failed', [
                'vendor_id' => (int) $vendor->id,
                'message' => $e->getMessage(),
            ]);

            if (! app()->environment('local')) {
                throw ValidationException::withMessages([
                    'email' => ['Unable to send OTP email right now. Please try again later.'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                // For verification we only need vendor_id + otp_id (otp_id exists server-side in TwoFactorOtp)
                'vendor_id' => (int) $vendor->id,
                'otp_id' => (int) $otp->id,
                'otp_expires_in_seconds' => 600,
            ],
            'message' => 'OTP sent to vendor registered email.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'vendor_id' => 'required|integer|min:1',
            'otp_id' => 'required|integer|min:1',
        ]);

        $pendingVendor = Vendor::where('id', (int) $request->input('vendor_id'))
            ->where('status', 'active')
            ->first();

        if (! $pendingVendor) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found or inactive.',
            ], 401);
        }

        $email = trim((string) ($pendingVendor->gmail_id ?? ''));
        if ($email === '') {
            return response()->json([
                'success' => false,
                'message' => 'Vendor email not configured.',
            ], 422);
        }

        $result = TwoFactorOtp::verifyAndConsume(
            (int) $request->input('otp_id'),
            'vendor',
            (string) $request->input('otp')
        );

        if (! ($result['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'OTP verification failed',
            ], 422);
        }

        // Create Sanctum token for vendor API.
        // Requires Vendor model to use HasApiTokens.
        // If Vendor model doesn't have Sanctum trait yet, we still return an explicit error.
        if (! method_exists($pendingVendor, 'createToken')) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor Sanctum token not configured on Vendor model. Add Laravel\Sanctum\HasApiTokens to Vendor model.',
            ], 500);
        }

        $tokenName = 'vendor-api-' . Str::random(6);
        $token = $pendingVendor->createToken($tokenName)->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'vendor_id' => (int) $pendingVendor->id,
            ],
            'message' => 'Vendor authenticated successfully.',
        ]);
    }

    public function logout(Request $request)
    {
        // Sanctum: revoke current access token
        $user = $request->user();
        if ($user && method_exists($user, 'currentAccessToken')) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out',
        ]);
    }
}

