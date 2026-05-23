<?php

namespace App\Http\Controllers\ECard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ECardWalletTransaction;

class DigitalWalletController extends Controller
{
    public function index()
    {
        $user = Auth::guard('ecard')->user();
        
        $transactions = ECardWalletTransaction::where('ecard_registration_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('ecard.wallet.digital_index', compact('user', 'transactions'));
    }
}
