<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('admin')->user();

            // Check if user has Super Admin role
            if (! $user->hasRole('Super Admin')) {
                Auth::guard('admin')->logout();

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied. Super Admin role required.',
                        'errors' => [
                            'email' => ['Access denied. Super Admin role required.'],
                        ],
                    ], 403);
                }

                throw ValidationException::withMessages([
                    'email' => ['Access denied. Super Admin role required.'],
                ]);
            }

            $request->session()->regenerate();

            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful! Redirecting to dashboard...',
                    'redirect_url' => route('admin.dashboard'),
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        // Handle failed login
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
                'errors' => [
                    'email' => ['The provided credentials do not match our records.'],
                ],
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
