<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Str;

class VendorBillingApiController extends Controller
{
    private function vendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

    public function pay(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $payload = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.name' => 'required',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.stock' => 'required|integer|min:0',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'gst' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        foreach ($payload['items'] as $i) {
            if (empty($i['product_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid item (product_id missing)',
                ], 422);
            }
        }

        $gatewaySlug = match ($payload['payment_method']) {
            'mall_card' => 'cashfree',
            'mobile', 'govt_id' => 'phonepe',
            default => 'cashfree',
        };

        $gateway = PaymentGateway::where('slug', $gatewaySlug)->where('is_enabled', true)->first();
        if (! $gateway) {
            return response()->json(['success' => false, 'message' => 'Selected payment gateway not enabled'], 400);
        }

        $total = (float) $payload['total'];
        $orderId = 'VEND_BILL_' . Str::random(10) . '_' . time();

        if ($gateway->slug === 'cashfree') {
            return $this->initiateCashfree($gateway, $total, $orderId, $payload, $vendor);
        }

        if ($gateway->slug === 'phonepe') {
            return $this->initiatePhonePe($gateway, $total, $orderId, $payload, $vendor);
        }

        return response()->json(['success' => false, 'message' => 'Unsupported gateway'], 400);
    }

    private function initiateCashfree($gateway, float $amount, string $orderId, array $payload, $vendor)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (! $appId || ! $secretKey) {
            return response()->json(['success' => false, 'message' => 'Cashfree configuration missing'], 500);
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        $customerPhone = '9999999999';
        $customerEmail = 'customer@example.com';

        $callbackUrl = url('/');
        $returnUrl = url('/');

        try {
            $payloadCF = [
                'order_id' => $orderId,
                'order_amount' => $amount,
                'order_currency' => 'INR',
                'customer_details' => [
                    'customer_id' => (string) $vendor->id,
                    'customer_email' => $customerEmail,
                    'customer_phone' => $customerPhone,
                ],
                'order_meta' => [
                    'return_url' => $returnUrl,
                    'notify_url' => $callbackUrl,
                    'payment_method' => 'link',
                ],
            ];

            $response = Http::withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2022-09-01',
            ])->post("$baseUrl/orders", $payloadCF);

            if (! $response->successful()) {
                Log::error('Cashfree order create failed', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cashfree order creation failed',
                    'status' => $response->status(),
                    'provider_body' => $response->body(),
                ], 500);
            }

            $data = $response->json();
            $paymentSessionId = $data['payment_session_id'] ?? null;

            if (! $paymentSessionId) {
                return response()->json(['success' => false, 'message' => 'Cashfree did not return payment_session_id'], 500);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $orderId,
                    'payment_session_id' => $paymentSessionId,
                    'redirect_url' => route('vendor.billing.cashfree.checkout', [
                        'payment_session_id' => $paymentSessionId,
                    ]),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Cashfree exception', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Cashfree gateway error'], 500);
        }
    }

    private function initiatePhonePe($gateway, float $amount, string $orderId, array $payload, $vendor)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $merchantId = $config['merchant_id'] ?? null;
        $saltKey = $config['salt_key'] ?? null;
        $saltIndex = $config['salt_index'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (! $merchantId || ! $saltKey || ! $saltIndex) {
            return response()->json(['success' => false, 'message' => 'PhonePe configuration missing'], 500);
        }

        try {
            $redirectUrl = route('vendor.billing.phonepe.checkout', [
                'merchant_id' => $merchantId,
                'salt_index' => $saltIndex,
                'amount' => $amount,
                'transaction_id' => $orderId,
                'environment' => $env,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $orderId,
                    'redirect_url' => $redirectUrl,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('PhonePe initiation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'PhonePe gateway error'], 500);
        }
    }
}

