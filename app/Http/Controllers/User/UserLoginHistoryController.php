<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserLoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserLoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $registrationId = $userSession['id'];

        $query = UserLoginHistory::where('registration_id', $registrationId)
            ->orderBy('logged_in_at', 'desc');

        // Date Filter
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('logged_in_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('logged_in_at', '<=', $request->to_date);
        }

        $loginHistories = $query->paginate(10);

        return view('user.login-history', compact('loginHistories'));
    }
}
