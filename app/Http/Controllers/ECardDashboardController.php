<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ECardDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('ecard')->user();

        return view('ecard.dashboard.index', compact('user'));
    }

    public function saveTheme(Request $request)
    {
        $request->validate([
            'theme_settings' => 'required|array',
        ]);

        $user = Auth::guard('ecard')->user();
        $user->theme_settings = $request->theme_settings;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Theme settings saved successfully.']);
    }
}
