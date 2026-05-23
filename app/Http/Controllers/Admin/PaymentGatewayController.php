<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        // Ensure gateways exist
        $phonepe = PaymentGateway::firstOrCreate(
            ['slug' => 'phonepe'],
            [
                'name' => 'PhonePe',
                'is_enabled' => true,
                'active_mode' => 'test',
                'test_config' => [
                    'client_id' => '',
                    'client_secret' => '',
                    'environment' => 'TEST',
                ],
                'live_config' => [
                    'client_id' => '',
                    'client_secret' => '',
                    'environment' => 'LIVE',
                ],
            ]
        );

        $cashfree = PaymentGateway::firstOrCreate(
            ['slug' => 'cashfree'],
            [
                'name' => 'Cashfree',
                'is_enabled' => true,
                'active_mode' => 'test',
                'test_config' => [
                    'app_id' => '',
                    'secret_key' => '',
                    'environment' => 'TEST',
                ],
                'live_config' => [
                    'app_id' => '',
                    'secret_key' => '',
                    'environment' => 'LIVE',
                ],
            ]
        );

        $gateways = PaymentGateway::whereIn('slug', ['phonepe', 'cashfree'])->get()->keyBy(function ($item) {
            return strtolower($item->slug);
        });

        return view('admin.settings.payment_gateways', [
            'gateways' => $gateways,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'phonepe_is_enabled' => 'nullable|boolean',
            'phonepe_active_mode' => 'nullable|in:test,live',
            'phonepe_test_client_id' => 'nullable|string|max:255',
            'phonepe_test_client_secret' => 'nullable|string|max:255',
            'phonepe_test_environment' => 'nullable|string|max:20',
            'phonepe_live_client_id' => 'nullable|string|max:255',
            'phonepe_live_client_secret' => 'nullable|string|max:255',
            'phonepe_live_environment' => 'nullable|string|max:20',
            'phonepe_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',

            'cashfree_is_enabled' => 'nullable|boolean',
            'cashfree_active_mode' => 'nullable|in:test,live',
            'cashfree_test_app_id' => 'nullable|string',
            'cashfree_test_secret_key' => 'nullable|string',
            'cashfree_test_environment' => 'nullable|string',
            'cashfree_live_app_id' => 'nullable|string',
            'cashfree_live_secret_key' => 'nullable|string',
            'cashfree_live_environment' => 'nullable|string',
            'cashfree_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        // Update PhonePe
        $phonepe = PaymentGateway::firstOrCreate(['slug' => 'phonepe'], ['name' => 'PhonePe']);
        $phonepe->is_enabled = (bool) $request->boolean('phonepe_is_enabled');
        $phonepe->active_mode = $request->get('phonepe_active_mode', $phonepe->active_mode);
        $existingTestSaltIndex = (int) data_get($phonepe->test_config, 'salt_index', 1);
        $existingLiveSaltIndex = (int) data_get($phonepe->live_config, 'salt_index', 1);
        $phonepe->test_config = [
            'client_id' => $request->get('phonepe_test_client_id'),
            'client_secret' => $request->get('phonepe_test_client_secret'),
            'salt_index' => $existingTestSaltIndex > 0 ? $existingTestSaltIndex : 1,
            'environment' => $request->get('phonepe_test_environment', 'TEST'),
        ];
        $phonepe->live_config = [
            'client_id' => $request->get('phonepe_live_client_id'),
            'client_secret' => $request->get('phonepe_live_client_secret'),
            'salt_index' => $existingLiveSaltIndex > 0 ? $existingLiveSaltIndex : 1,
            'environment' => $request->get('phonepe_live_environment', 'LIVE'),
        ];
        if ($request->hasFile('phonepe_logo')) {
            $path = $request->file('phonepe_logo')->store('payment-gateways/logos', 'public');
            $phonepe->logo = $path;
        }
        $phonepe->save();

        // Update Cashfree
        $cashfree = PaymentGateway::firstOrCreate(['slug' => 'cashfree'], ['name' => 'Cashfree']);
        $cashfree->is_enabled = (bool) $request->boolean('cashfree_is_enabled');
        $cashfree->active_mode = $request->get('cashfree_active_mode', $cashfree->active_mode);
        $cashfree->test_config = [
            'app_id' => $request->get('cashfree_test_app_id'),
            'secret_key' => $request->get('cashfree_test_secret_key'),
            'environment' => $request->get('cashfree_test_environment', 'TEST'),
        ];
        $cashfree->live_config = [
            'app_id' => $request->get('cashfree_live_app_id'),
            'secret_key' => $request->get('cashfree_live_secret_key'),
            'environment' => $request->get('cashfree_live_environment', 'LIVE'),
        ];
        if ($request->hasFile('cashfree_logo')) {
            $path = $request->file('cashfree_logo')->store('payment-gateways/logos', 'public');
            $cashfree->logo = $path;
        }
        $cashfree->save();

        return redirect()->route('admin.payment-gateways.index')->with('success', 'Payment gateway settings updated successfully.');
    }
}
