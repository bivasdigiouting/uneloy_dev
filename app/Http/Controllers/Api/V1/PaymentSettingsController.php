<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSettings;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    /**
     * Payments
     * Retrieve current payment enablement flags.
     *
     * Returns the boolean status for e-card, e-wallet, and e-QR payments.
     *
     * @group Payments
     *
     * @unauthenticated
     *
     * @response 200 {"ecard_payment_enabled": true, "ewallet_payment_enabled": false, "eqr_payment_enabled": true}
     */
    public function index()
    {
        $settings = WebsiteSettings::getSettings();

        return response()->json([
            'ecard_payment_enabled' => (bool) ($settings->ecard_payment_enabled ?? false),
            'ewallet_payment_enabled' => (bool) ($settings->ewallet_payment_enabled ?? false),
            'eqr_payment_enabled' => (bool) ($settings->eqr_payment_enabled ?? false),
        ]);
    }

    /**
     * Update e-card payment status
     * Toggle e-card payments on or off.
     *
     * @group Payments
     *
     * @authenticated
     *
     * @bodyParam status boolean required Set to true to enable, false to disable. Example: true
     *
     * @response 200 {"ecard_payment_enabled": true}
     */
    public function updateEcard(Request $request)
    {
        $data = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $settings = WebsiteSettings::getSettings();
        $settings->ecard_payment_enabled = $data['status'];
        $settings->save();

        return response()->json([
            'ecard_payment_enabled' => (bool) $settings->ecard_payment_enabled,
        ]);
    }

    /**
     * Update e-wallet payment status
     * Toggle e-wallet payments on or off.
     *
     * @group Payments
     *
     * @authenticated
     *
     * @bodyParam status boolean required Set to true to enable, false to disable. Example: false
     *
     * @response 200 {"ewallet_payment_enabled": false}
     */
    public function updateEwallet(Request $request)
    {
        $data = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $settings = WebsiteSettings::getSettings();
        $settings->ewallet_payment_enabled = $data['status'];
        $settings->save();

        return response()->json([
            'ewallet_payment_enabled' => (bool) $settings->ewallet_payment_enabled,
        ]);
    }

    /**
     * Update e-QR payment status
     * Toggle e-QR payments on or off.
     *
     * @group Payments
     *
     * @authenticated
     *
     * @bodyParam status boolean required Set to true to enable, false to disable. Example: true
     *
     * @response 200 {"eqr_payment_enabled": true}
     */
    public function updateEqr(Request $request)
    {
        $data = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $settings = WebsiteSettings::getSettings();
        $settings->eqr_payment_enabled = $data['status'];
        $settings->save();

        return response()->json([
            'eqr_payment_enabled' => (bool) $settings->eqr_payment_enabled,
        ]);
    }

    /**
     * Payment sharing percentages
     * Get the current percentage split across e-card, e-wallet, and e-QR.
     *
     * @group Payments
     *
     * @unauthenticated
     *
     * @response 200 {"ecard_share_percent": 50.0, "ewallet_share_percent": 30.0, "eqr_share_percent": 20.0, "total": 100.0}
     */
    public function sharingIndex()
    {
        $s = WebsiteSettings::getSettings();
        $ecard = (float) ($s->ecard_share_percent ?? 0);
        $ewallet = (float) ($s->ewallet_share_percent ?? 0);
        $eqr = (float) ($s->eqr_share_percent ?? 0);

        return response()->json([
            'ecard_share_percent' => $ecard,
            'ewallet_share_percent' => $ewallet,
            'eqr_share_percent' => $eqr,
            'total' => round($ecard + $ewallet + $eqr, 2),
        ]);
    }

    /**
     * Update payment sharing percentages
     * Set percentages across e-card, e-wallet, and e-QR (must sum to 100).
     *
     * @group Payments
     *
     * @authenticated
     *
     * @bodyParam ecard_share_percent number required Example: 50.0
     * @bodyParam ewallet_share_percent number required Example: 30.0
     * @bodyParam eqr_share_percent number required Example: 20.0
     *
     * @response 200 {"ecard_share_percent": 50.0, "ewallet_share_percent": 30.0, "eqr_share_percent": 20.0, "total": 100.0}
     */
    public function sharingUpdate(Request $request)
    {
        $data = $request->validate([
            'ecard_share_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'ewallet_share_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'eqr_share_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $total = (float) ($data['ecard_share_percent'] + $data['ewallet_share_percent'] + $data['eqr_share_percent']);
        if (round($total, 2) !== 100.0) {
            return response()->json([
                'message' => 'Percentages must sum to 100',
                'total' => round($total, 2),
            ], 422);
        }

        $s = WebsiteSettings::getSettings();
        $s->ecard_share_percent = $data['ecard_share_percent'];
        $s->ewallet_share_percent = $data['ewallet_share_percent'];
        $s->eqr_share_percent = $data['eqr_share_percent'];
        $s->save();

        return response()->json([
            'ecard_share_percent' => (float) $s->ecard_share_percent,
            'ewallet_share_percent' => (float) $s->ewallet_share_percent,
            'eqr_share_percent' => (float) $s->eqr_share_percent,
            'total' => round((float) $s->ecard_share_percent + (float) $s->ewallet_share_percent + (float) $s->eqr_share_percent, 2),
        ]);
    }

    /**
     * Calculate payment sharing for an amount
     * Given an `amount`, returns how it splits across e-card, e-wallet, and e-QR based on current percentages.
     *
     * @group Payments
     *
     * @unauthenticated
     *
     * @bodyParam amount number required Example: 1000
     *
     * @response 200 {"amount":1000,"shares":{"ecard":500,"ewallet":300,"eqr":200},"percentages":{"ecard":50,"ewallet":30,"eqr":20},"total":1000}
     */
    public function sharingCalculate(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
        ]);
        $amount = (float) $data['amount'];

        $s = WebsiteSettings::getSettings();
        $ecardP = (float) ($s->ecard_share_percent ?? 0);
        $ewalletP = (float) ($s->ewallet_share_percent ?? 0);
        $eqrP = (float) ($s->eqr_share_percent ?? 0);

        $ecard = round($amount * $ecardP / 100, 2);
        $ewallet = round($amount * $ewalletP / 100, 2);
        $eqr = round($amount * $eqrP / 100, 2);

        return response()->json([
            'amount' => $amount,
            'shares' => [
                'ecard' => $ecard,
                'ewallet' => $ewallet,
                'eqr' => $eqr,
            ],
            'percentages' => [
                'ecard' => $ecardP,
                'ewallet' => $ewalletP,
                'eqr' => $eqrP,
            ],
            'total' => round($ecard + $ewallet + $eqr, 2),
        ]);
    }
}
