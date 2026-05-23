<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedeemSetting;
use App\Models\RedeemValueHistory;
use Illuminate\Http\Request;

class RedeemValueController extends Controller
{
    public function index()
    {
        $setting = RedeemSetting::query()->first();

        return view('admin.redeem_values.index', [
            'setting' => $setting,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'total_user_points' => ['required', 'numeric', 'min:0'],
            'redeem_amount' => ['required', 'numeric', 'min:0'],
            'redeem_value' => ['required', 'numeric', 'min:0'],
        ]);

        $setting = RedeemSetting::query()->first();
        if (! $setting) {
            $setting = new RedeemSetting;
        }
        $setting->fill($validated);
        $setting->save();

        RedeemValueHistory::create([
            'total_user_points' => $setting->total_user_points,
            'redeem_amount' => $setting->redeem_amount,
            'redeem_value' => $setting->redeem_value,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.redeem-values.index')
            ->with('success', 'Redeem values updated successfully.');
    }
}
