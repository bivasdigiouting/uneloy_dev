<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PhonePeController extends Controller
{
    private function getGatewayConfig(PaymentGateway $gateway): array
    {
        $config = $gateway->active_mode === 'live' ? ($gateway->live_config ?? []) : ($gateway->test_config ?? []);
        return is_array($config) ? $config : [];
    }

    private function buildChecksum(string $stringToHash, string $saltKey): string
    {
        // PhonePe checksum is SHA256(stringToHash + '/' + saltKey)
        return hash('sha256', $stringToHash . $saltKey);
    }

    /**
     * Render a page that immediately redirects to PhonePe using a server-generated initiate request.
     * This keeps the initiate + redirect server-side (no saltKey exposure to browser).
     */
    public function checkout(Request $request)
    {
        $gateway = PaymentGateway::where('slug', 'phonepe')->where('is_enabled', true)->first();
        abort_if(! $gateway, 404);

        $config = $this->getGatewayConfig($gateway);

        $merchantId = $config['merchant_id'] ?? null;
        $saltKey = $config['salt_key'] ?? null;
        $saltIndex = $config['salt_index'] ?? null;
        $env = strtoupper((string) ($config['environment'] ?? 'TEST'));

        $amount = (float) $request->query('amount');
        $transactionId = (string) $request->query('transaction_id');
        $currency = 'INR';

        abort_if(! $merchantId || ! $saltKey || ! $saltIndex, 500, 'PhonePe configuration missing');
        abort_if(! $amount || $amount <= 0, 422, 'Invalid amount');
        abort_if(! $transactionId, 422, 'Missing transaction id');

        $callbackUrl = url('/'); // webhook/notify URL ideally should be dedicated route
        $redirectUrl = url('/'); // return to checkout confirmation

        $apiHost = $env === 'LIVE' ? 'api.phonepe.com' : 'api-preprod.phonepe.com';
        $basePath = '/pg/v1/pay';
        $url = 'https://' . $apiHost . $basePath;

        $orderId = $transactionId;

        $payload = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $orderId,
            'amount' => (string) intval(round($amount * 100)) / 100 * 100, // keep as string int-ish
            'redirectUrl' => $redirectUrl,
            'redirectMode' => 'POST',
            'callbackUrl' => $callbackUrl,
            'mobileNumber' => '9999999999',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE',
            ],
        ];

        // PhonePe expects amount in paise as string integer for many integrations.
        // If your config/UI uses rupees, you may need to pass paise.
        // We'll calculate paise from amount.
        $amountPaise = (int) round($amount * 100);
        $payload['amount'] = (string) $amountPaise;

        $jsonString = json_encode($payload, JSON_UNESCAPED_SLASHES);

        $checksumStr = $basePath . $jsonString;
        $checksum = $this->buildChecksum($checksumStr, $saltKey);

        $headers = [
            'Content-Type' => 'application/json',
            'X-VERIFY' => $saltIndex . ':' . $checksum,
            // Some PhonePe accounts require additional headers like X-Merchant-Id; keeping minimal.
        ];

        try {
            $response = Http::withHeaders($headers)->post($url, $payload);
            if (! $response->successful()) {
                Log::error('PhonePe initiate failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return abort(500, 'PhonePe initiate failed');
            }

            $data = $response->json();
            $redirect = $data['data']['redirectUrl'] ?? $data['redirectUrl'] ?? null;

            if (! $redirect) {
                Log::error('PhonePe initiate missing redirectUrl', ['body' => $response->body()]);
                return abort(500, 'PhonePe initiate missing redirectUrl');
            }

            return view('vendor.billing.phonepe-checkout', [
                'redirect_url' => $redirect,
                'amount' => $amount,
                'transaction_id' => $transactionId,
            ]);
        } catch (\Throwable $e) {
            Log::error('PhonePe checkout exception', ['error' => $e->getMessage()]);
            return abort(500, 'PhonePe gateway error');
        }
    }
}

