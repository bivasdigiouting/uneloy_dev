<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BillingPaymentController extends Controller
{
    private function getAuthenticatedVendor()
    {
        $vendorId = session('vendor_id');
        if (! $vendorId) {
            return null;
        }

        return \App\Models\Vendor::where('id', $vendorId)->where('status', 'active')->first();
    }

    public function pay(Request $request)
    {
        $vendor = $this->getAuthenticatedVendor();
        if (! $vendor) {
            return response()->json(['message' => 'Vendor not authenticated'], 401);
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

        // If any item id/product_id is missing (frontend bug), redirect back to billing page
        // so user can choose payment option and rebuild cart.
        foreach ($payload['items'] as $i) {
            if (empty($i['product_id'])) {
                return response()->json([
                    'redirect_url' => route('vendor.billing')
                ], 422);
            }
        }


        // Map UI method => gateway slug
        // Need both Cashfree and PhonePe: (Mall Card => Cashfree, Mobile/Govt ID => PhonePe)
        $gatewaySlug = match ($payload['payment_method']) {
            'mall_card' => 'cashfree',
            'mobile', 'govt_id' => 'phonepe',
            default => 'cashfree',
        };

        $gateway = PaymentGateway::where('slug', $gatewaySlug)->where('is_enabled', true)->first();
        if (! $gateway) {
            return response()->json(['message' => 'Selected payment gateway is not enabled'], 400);
        }

        $total = (float) $payload['total'];
        $orderId = 'VEND_BILL_' . Str::random(10) . '_' . time();

        if ($gateway->slug === 'cashfree') {
            return $this->initiateCashfree($gateway, $total, $orderId, $payload, $vendor);
        }

        if ($gateway->slug === 'phonepe') {
            return $this->initiatePhonePe($gateway, $total, $orderId, $payload, $vendor);
        }

        return response()->json(['message' => 'Unsupported gateway'], 400);
    }

    private function initiateCashfree($gateway, float $amount, string $orderId, array $payload, $vendor)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (! $appId || ! $secretKey) {
            return response()->json(['message' => 'Cashfree configuration missing'], 500);
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        // DEBUG: ensure we return detailed error if Cashfree call fails


        $customerPhone = '9999999999';
        $customerEmail = 'customer@example.com';

        $callbackUrl = url('/'); // fallback
        $returnUrl = route('vendor.billing');

        // Note: Cashfree "orders" endpoint returns payment_session_id. Client-side checkout requires session_id.
        // Here we redirect to a generic checkout blade if you already have one; otherwise user may handle manually.
        // For now we use payment link API if available; otherwise we return session_id-based checkout view.

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
                    'message' => 'Cashfree order creation failed',
                    'status' => $response->status(),
                    'provider_body' => $response->body(),
                ], 500);
            }

            $data = $response->json();

            $paymentSessionId = $data['payment_session_id'] ?? null;

            if (! $paymentSessionId) {
                return response()->json(['message' => 'Cashfree did not return payment_session_id'], 500);
            }

            // Use existing Cashfree hosted checkout view pattern if present.
            // We'll redirect to an internal blade that triggers Cashfree checkout by payment_session_id.
            return response()->json([
                'redirect_url' => route('vendor.billing.cashfree.checkout', [
                    'payment_session_id' => $paymentSessionId,
                ]),
            ]);
        } catch (\Throwable $e) {
            Log::error('Cashfree exception', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Cashfree gateway error'], 500);
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
            return response()->json(['message' => 'PhonePe configuration missing'], 500);
        }

        // Minimal PhonePe integration: we generate redirect URL response by calling PhonePe "initiate".
        // Many setups require checksum/signing. If your PhonePe flow already exists elsewhere, we can reuse it.
        // For now, we reuse the same api approach used in your project controllers if available.

        try {
            // In this repo, PhonePe initiation is complex; if you already have a working endpoint/view,
            // better to call it. We'll redirect to a dedicated blade that can create/check checksum.
            // For that, we just pass required fields.

            $redirectUrl = route('vendor.billing.phonepe.checkout', [
                'merchant_id' => $merchantId,
                'salt_index' => $saltIndex,
                'amount' => $amount,
                'transaction_id' => $orderId,
                'environment' => $env,
                // NOTE: In production you should not expose saltKey to browser.
                // Here we keep only what the server needs; salts should be handled server-side in that view/controller.
            ]);

            return response()->json(['redirect_url' => $redirectUrl]);
        } catch (\Throwable $e) {
            Log::error('PhonePe initiation failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'PhonePe gateway error'], 500);
        }
    }
}

