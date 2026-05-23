<?php

namespace App\Http\Controllers;

use App\Models\ECardLoginHistory;
use App\Models\ECardRegistration;
use App\Models\TwoFactorOtp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ECardAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('ecard')->check()) {
            return redirect()->route('ecard.dashboard');
        }
        return view('ecard.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        $ecard = ECardRegistration::where('user_id', $login)
            ->orWhere('email_id', $login)
            ->orWhere('mobile_no', $login)
            ->first();

        if (! $ecard || ! $ecard->password || ! Hash::check($password, $ecard->password)) {
            return back()->withErrors(['login' => 'Invalid credentials or password not set'])->withInput();
        }

        $email = trim((string) ($ecard->email_id ?? ''));
        if ($email === '') {
            return back()->withErrors(['login' => 'Email is not registered for this account. Please contact admin.'])->withInput();
        }

        [$otp, $plainOtp] = TwoFactorOtp::issue('ecard', (int) $ecard->id, $email, 10);
        try {
            Mail::to($email)->send(new OtpMail($plainOtp));
        } catch (\Throwable $e) {
            Log::error('E-Card OTP mail send failed', [
                'ecard_id' => (int) $ecard->id,
                'message' => $e->getMessage(),
            ]);

            if (! app()->environment('local')) {
                return back()->withErrors(['login' => 'Unable to send OTP email right now. Please try again later.'])->withInput();
            }

            $request->session()->put('twofa.ecard.dev_otp', $plainOtp);
        }

        $request->session()->put('twofa.ecard', [
            'otp_id' => (int) $otp->id,
            'ecard_id' => (int) $ecard->id,
            'email' => $email,
        ]);

        return redirect()->route('ecard.login.otp')->with('success', 'OTP sent to your registered email.');
    }

    public function showOtpForm(Request $request)
    {
        if (Auth::guard('ecard')->check()) {
            return redirect()->route('ecard.dashboard');
        }

        $pending = (array) $request->session()->get('twofa.ecard', []);
        $email = (string) ($pending['email'] ?? '');
        if ($email === '' || empty($pending['otp_id']) || empty($pending['ecard_id'])) {
            return redirect()->route('ecard.login')->withErrors(['login' => 'Please login again.']);
        }

        $maskedEmail = preg_replace('/(^.).*(@.*$)/', '$1***$2', $email) ?: $email;
        $devOtp = $request->session()->get('twofa.ecard.dev_otp');

        return view('ecard.auth.otp', [
            'maskedEmail' => $maskedEmail,
            'devOtp' => $devOtp,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        if (Auth::guard('ecard')->check()) {
            return redirect()->route('ecard.dashboard');
        }

        $pending = (array) $request->session()->get('twofa.ecard', []);
        $otpId = (int) ($pending['otp_id'] ?? 0);
        $ecardId = (int) ($pending['ecard_id'] ?? 0);
        if ($otpId <= 0 || $ecardId <= 0) {
            return redirect()->route('ecard.login')->withErrors(['login' => 'Please login again.']);
        }

        $result = TwoFactorOtp::verifyAndConsume($otpId, 'ecard', (string) $request->otp);
        if (! ($result['success'] ?? false)) {
            return back()->withErrors(['otp' => $result['message'] ?? 'OTP verification failed'])->withInput();
        }

        $ecard = ECardRegistration::find($ecardId);
        if (! $ecard) {
            $request->session()->forget('twofa.ecard');
            return redirect()->route('ecard.login')->withErrors(['login' => 'Account not found. Please login again.']);
        }

        Auth::guard('ecard')->login($ecard);
        $request->session()->forget('twofa.ecard');

        try {
            ECardLoginHistory::create([
                'ecard_registration_id' => $ecard->id,
                'ip_address' => $request->ip(),
                'platform' => 'web',
                'user_agent' => $request->userAgent(),
                'logged_in_at' => now(),
            ]);
        } catch (\Throwable $e) {
        }

        return redirect()->route('ecard.dashboard')->with('success', 'Logged in successfully');
    }

    public function resendOtp(Request $request)
    {
        if (Auth::guard('ecard')->check()) {
            return redirect()->route('ecard.dashboard');
        }

        $pending = (array) $request->session()->get('twofa.ecard', []);
        $ecardId = (int) ($pending['ecard_id'] ?? 0);
        $email = (string) ($pending['email'] ?? '');
        if ($ecardId <= 0 || $email === '') {
            return redirect()->route('ecard.login')->withErrors(['login' => 'Please login again.']);
        }

        $existingOtpId = (int) ($pending['otp_id'] ?? 0);
        if ($existingOtpId > 0) {
            $existing = TwoFactorOtp::query()->find($existingOtpId);
            if ($existing && ! $existing->canResend(60)) {
                return back()->withErrors(['otp' => 'Please wait before requesting a new OTP.']);
            }
        }

        [$otp, $plainOtp] = TwoFactorOtp::issue('ecard', $ecardId, $email, 10);
        try {
            Mail::to($email)->send(new OtpMail($plainOtp));
            $request->session()->forget('twofa.ecard.dev_otp');
        } catch (\Throwable $e) {
            Log::error('E-Card OTP resend mail send failed', [
                'ecard_id' => (int) $ecardId,
                'message' => $e->getMessage(),
            ]);

            if (! app()->environment('local')) {
                return back()->withErrors(['otp' => 'Unable to resend OTP email right now. Please try again later.']);
            }

            $request->session()->put('twofa.ecard.dev_otp', $plainOtp);
        }

        $request->session()->put('twofa.ecard.otp_id', (int) $otp->id);

        return back()->with('success', 'OTP resent to your email.');
    }

    public function logout(Request $request)
    {
        // Update last login history logout time for this user
        try {
            $user = Auth::guard('ecard')->user();
            if ($user) {
                $history = ECardLoginHistory::where('ecard_registration_id', $user->id)
                    ->orderByDesc('logged_in_at')
                    ->first();
                if ($history && empty($history->logged_out_at)) {
                    $history->logged_out_at = now();
                    $history->save();
                }
            }
        } catch (\Throwable $e) {
            // Ignore history update failures
        }

        Auth::guard('ecard')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ecard.login')->with('success', 'Logged out successfully');
    }
}
