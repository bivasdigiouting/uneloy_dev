<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserDeviceController extends Controller
{
    public function index()
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $registration = Registration::find($userSession['id']);

        return view('user.device-permission', compact('registration'));
    }

    public function update(Request $request)
    {
        if (! Session::has('user_auth')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $userSession = Session::get('user_auth');
        $registration = Registration::find($userSession['id']);

        if (! $registration) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $registration->device_sharing_enabled = $request->boolean('enabled');
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Device sharing '.($registration->device_sharing_enabled ? 'enabled' : 'disabled').' successfully.',
        ]);
    }
}
