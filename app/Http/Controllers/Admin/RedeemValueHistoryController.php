<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedeemValueHistory;
use Illuminate\Http\Request;

class RedeemValueHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = RedeemValueHistory::query()
            ->with(['user' => function ($q) {
                $q->select('id', 'name');
            }])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.redeem_values.history', [
            'histories' => $histories,
        ]);
    }
}
