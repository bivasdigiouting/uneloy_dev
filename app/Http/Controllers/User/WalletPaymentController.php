<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Registration;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class WalletPaymentController extends Controller
{
    private function resolveGatewayForWallet(): ?PaymentGateway
    {
        $phonepe = PaymentGateway::query()->where('slug', 'phonepe')->where('is_enabled', true)->first();
        if ($phonepe) {
            return $phonepe;
        }

        return PaymentGateway::query()->where('slug', 'cashfree')->where('is_enabled', true)->first();
    }

    /**
     * Initiate Add Money to Wallet
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:100',
        ]);

        $amount = (float) $request->amount;
        $note = $request->input('note');
        $userSession = Session::get('user_auth');

        if (! $userSession) {
            return redirect()->route('user.login');
        }

        $user = Registration::find($userSession['id']);
        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        $isFirstTopUp = ! WalletTransaction::query()
            ->where('registration_id', $user->id)
            ->where('transaction_type', 'add')
            ->where(function ($q) {
                $q->where('narration', 'like', 'Wallet Topup%')
                    ->orWhere('narration', 'like', 'Wallet top-up%');
            })
            ->exists();

        if ($isFirstTopUp) {
            $allowed = [150.0, 500.0];
            $isAllowed = false;
            foreach ($allowed as $value) {
                if (abs($amount - $value) < 0.001) {
                    $isAllowed = true;
                    break;
                }
            }

            if (! $isAllowed) {
                return back()->with('error', 'First wallet recharge allows only ₹150 (get ₹50 bonus) or ₹500 (get ₹300 bonus).');
            }
        }

        $gateway = $this->resolveGatewayForWallet();
        if (! $gateway) {
            return back()->with('error', 'Payment gateway is not enabled.');
        }

        if ($gateway->slug === 'phonepe') {
            return $this->initiatePhonePeWalletTopup($gateway, $user, $amount, $note);
        }

        return $this->initiateCashfreeWalletTopup($gateway, $user, $amount, $note);
    }

    private function initiateCashfreeWalletTopup(PaymentGateway $gateway, Registration $user, float $amount, ?string $note)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;

        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (! $appId || ! $secretKey) {
            return back()->with('error', 'Payment gateway configuration is missing.');
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        $orderId = 'ORD_'.Str::random(10).'_'.time();
        $returnUrl = route('user.wallet.callback').'?order_id={order_id}';

        $orderNote = $note ? "Wallet Topup (Order: {$orderId}): {$note}" : "Wallet Topup (Order: {$orderId})";

        $requestData = [
            'order_id' => $orderId,
            'order_amount' => $amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => (string) $user->id,
                'customer_email' => $user->email_id ?? 'customer@example.com',
                'customer_phone' => $user->mobile_no ?? '9999999999',
                'customer_name' => $user->full_name ?? 'User',
            ],
            'order_meta' => [
                'return_url' => $returnUrl,
                'notify_url' => route('user.wallet.webhook'),
            ],
            'order_note' => $orderNote,
        ];

        $response = Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2022-09-01',
            'Content-Type' => 'application/json',
        ])->post("$baseUrl/orders", $requestData);

        if ($response->successful()) {
            $data = $response->json();
            $paymentSessionId = $data['payment_session_id'] ?? null;

            if ($paymentSessionId) {
                return view('user.payment.checkout', [
                    'payment_session_id' => $paymentSessionId,
                    'order_id' => $orderId,
                    'env' => $gateway->active_mode,
                ]);
            }
        }

        return back()->with('error', 'Failed to initiate payment. Please try again.');
    }

    private function initiatePhonePeWalletTopup(PaymentGateway $gateway, Registration $user, float $amount, ?string $note)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $merchantId = $config['client_id'] ?? null;
        $saltKey = $config['client_secret'] ?? null;
        $env = $config['environment'] ?? 'TEST';
        $saltIndex = (int) ($config['salt_index'] ?? 1);
        if ($saltIndex <= 0) {
            $saltIndex = 1;
        }

        if (! $merchantId || ! $saltKey) {
            return back()->with('error', 'PhonePe configuration is missing.');
        }

        // PhonePe initiate base URLs.
        // IMPORTANT: Keep Hermes/PG consistent with how your PhonePe account is configured.
        // In your logs, Hermes was mapped with 404 and PG returns: "Bad Request - Api Mapping Not Found".
        // That indicates the initiate endpoint+payload format is not matching your PhonePe integration.
        // We'll stop guessing by using ONLY PG initiate format (consistent with `apis/pg`).
        $pgBaseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.phonepe.com/apis/pg'
            : 'https://api-preprod.phonepe.com/apis/pg';


        $amountPaise = (int) round($amount * 100);
        $transactionId = 'UWLT_'.$user->id.'_'.$amountPaise.'_'.time();

        $redirectUrl = route('user.wallet.callback');
        $callbackUrl = route('user.wallet.webhook');

        $payload = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID'.$user->id,
            'amount' => $amountPaise,
            'redirectUrl' => $redirectUrl,
            'redirectMode' => 'POST',
            'callbackUrl' => $callbackUrl,
            'mobileNumber' => $user->mobile_no ?? null,
            'paymentInstrument' => [
                'type' => 'PAY_PAGE',
            ],
        ];

        $base64Payload = base64_encode(json_encode($payload));
        // Keep checksum exactly as expected by your existing PhonePe integration.
        // Current project uses PhonePe Hermes where checksum = sha256(base64Payload + '/pg/v1/pay' + saltKey) + '###' + saltIndex
        $checksum = hash('sha256', $base64Payload.'/pg/v1/pay'.$saltKey).'###'.$saltIndex;


        // NOTE: checksum format for PhonePe Hermes integration is:
        // X-VERIFY: <sha256(base64Payload + '/pg/v1/pay' + saltKey) + '###' + saltIndex>
        // If your account expects a different checksum format, initiation will fail.
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'X-VERIFY' => $checksum,
            ];

            $pgUrlCandidates = [
                $pgBaseUrl.'/pg/v1/pay',
                $pgBaseUrl.'/v1/pay',
            ];

            $response = null;
            $resData = null;
            $lastError = null;

            foreach ($pgUrlCandidates as $candidateUrl) {
                try {
                    Log::error('PhonePe wallet initiate attempt', [
                        'pg_url' => $candidateUrl,
                        'amountPaise' => $amountPaise,
                        'transactionId' => $transactionId,
                        'merchantId' => $merchantId,
                        'callbackUrl' => $callbackUrl,
                        'redirectUrl' => $redirectUrl,
                        'checksum_preview' => substr((string) $checksum, 0, 12),
                    ]);

                    $response = Http::withHeaders($headers)->post($candidateUrl, [
                        'request' => $base64Payload,
                    ]);

                    $resData = $response->json();

                    // PhonePe mapping errors typically include this message.
                    $message = $resData['message']
                        ?? ($resData['error']['message'] ?? null)
                        ?? '';

                    if (stripos((string) $message, 'Api Mapping Not Found') !== false) {
                        $lastError = $message;
                        continue;
                    }

                    // If we got here, we have something other than mapping-not-found.
                    $pgUrl = $candidateUrl;
                    break;
                } catch (\Throwable $e) {
                    $lastError = $e->getMessage();
                    continue;
                }
            }

            if (! $response) {
                Log::error('PhonePe wallet initiate failed: no response', [
                    'lastError' => $lastError,
                    'amountPaise' => $amountPaise,
                    'transactionId' => $transactionId,
                ]);
                return back()->with('error', 'Failed to initiate payment (PhonePe). Please try again.');
            }


            // Log raw response for debugging failed initiation
            Log::error('PhonePe wallet initiate response', [
                'http_status' => $response->status(),
                'response' => $resData,
            ]);

            // Debug to help identify the exact redirect/callback error during wallet topup.
            Log::error('PhonePe wallet initiate computed urls', [
                'redirectUrl' => $redirectUrl,
                'callbackUrl' => $callbackUrl,
                'transactionId' => $transactionId,
                'amountPaise' => $amountPaise,
            ]);

            // PhonePe response structure can vary.
            // Try common redirect locations before failing.
            $redirectUrl = data_get($resData, 'data.instrumentResponse.redirectInfo.url')
                ?? data_get($resData, 'data.instrumentResponse.redirectInfo');

            if (is_string($redirectUrl) && $redirectUrl !== '') {
                return redirect()->away($redirectUrl);
            }

            $altRedirect = data_get($resData, 'data.instrumentResponse.redirectInfo.url')
                ?? data_get($resData, 'data.redirectUrl')
                ?? data_get($resData, 'redirectUrl');

            if (is_string($altRedirect) && $altRedirect !== '') {
                return redirect()->away($altRedirect);
            }

            // If PhonePe didn't return redirectUrl, it means initiate failed.
            // Add strong debug to know what exact request we sent.
            Log::error('PhonePe wallet initiate no redirect url found', [
                'http_status' => $response->status(),
                'raw_response' => $resData,
                'pg_url' => $pgUrl ?? null,
                'amountPaise' => $amountPaise,
                'transactionId' => $transactionId,
                'merchantId' => $merchantId,
                'callbackUrl' => $callbackUrl,
                'redirectUrlIntended' => $redirectUrl,
            ]);

            $msg = $resData['message']
                ?? ($resData['error']['message'] ?? null)
                ?? ('Failed to initiate payment (code: '.(string) ($resData['code'] ?? 'n/a').')');

            return back()->with('error', (string) $msg);
        } catch (\Throwable $e) {
            Log::error('PhonePe wallet initiation error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Payment gateway error.');
        }

    }

    /**
     * Handle Payment Callback
     */
    public function handleCallback(Request $request)
    {
        Log::error('Wallet callback hit', [
            'full_url' => $request->fullUrl(),
            'method' => $request->method(),
            'has_order_id' => $request->has('order_id'),
            'has_response' => $request->has('response'),
            'x_verify_present' => (bool) $request->header('X-VERIFY'),
            'keys' => array_keys($request->all()),
        ]);

        if ($request->has('order_id')) {
            return $this->handleCashfreeCallback($request);
        }

        return $this->handlePhonePeCallback($request);
    }

    private function handleCashfreeCallback(Request $request)
    {
        $orderId = $request->query('order_id');
        if (! $orderId) {
            return redirect()->route('user.wallet.show')->with('error', 'Invalid payment response.');
        }

        $gateway = PaymentGateway::where('slug', 'cashfree')->first();
        if (! $gateway || ! $gateway->is_enabled) {
            return redirect()->route('user.wallet.show')->with('error', 'Payment gateway not available.');
        }
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (! $appId || ! $secretKey) {
            return redirect()->route('user.wallet.show')->with('error', 'Payment gateway configuration missing.');
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        $response = Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2022-09-01',
        ])->get("$baseUrl/orders/$orderId");

        if (! $response->successful()) {
            return redirect()->route('user.wallet.show')->with('error', 'Unable to verify payment status.');
        }

        $orderData = $response->json();
        if (($orderData['order_status'] ?? '') !== 'PAID') {
            return redirect()->route('user.wallet.show')->with('error', 'Payment failed or pending.');
        }

        $amount = (float) ($orderData['order_amount'] ?? 0);
        $customerId = (int) ($orderData['customer_details']['customer_id'] ?? 0);
        $orderNote = $orderData['order_note'] ?? null;

        if (! $customerId || $amount <= 0) {
            return redirect()->route('user.wallet.show')->with('error', 'Invalid payment response.');
        }

        return $this->creditWalletAfterGateway($customerId, $amount, 'cashfree', $orderId, (string) ($orderNote ?? ''));
    }

    private function handlePhonePeCallback(Request $request)
    {
        $paymentSuccess = false;
        $transactionId = null;
        $errorMsg = 'Payment failed or cancelled.';

        if ($request->has('response') && $request->header('X-VERIFY')) {
            $encodedResponse = (string) $request->input('response');
            $responseJson = base64_decode($encodedResponse);
            $responseData = json_decode($responseJson, true);

            if (($responseData['code'] ?? null) === 'PAYMENT_SUCCESS') {
                $gateway = PaymentGateway::where('slug', 'phonepe')->first();
                if ($gateway) {
                    $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
                    $saltKey = $config['client_secret'] ?? null;
                    $saltIndex = (int) ($config['salt_index'] ?? 1);
                    if ($saltIndex <= 0) {
                        $saltIndex = 1;
                    }

                    if ($saltKey) {
                        // Must match PhonePe Hermes initiate checksum format used in this project:
                        // X-VERIFY: sha256(base64Payload + '/pg/v1/pay' + saltKey) + '###' + saltIndex
                        // Here, base64Payload is the encodedResponse string we received.
                        $calculatedChecksum = hash('sha256', $encodedResponse . '/pg/v1/pay' . $saltKey) . '###' . $saltIndex;

                        Log::error('PhonePe callback checksum validation', [
                            'transaction_candidate' => data_get($responseData, 'data.merchantTransactionId'),
                            'encoded_response_present' => (is_string($encodedResponse) && $encodedResponse !== ''),
                            'salt_index' => $saltIndex,
                            'x_verify' => (string) $request->header('X-VERIFY'),
                            'calculated_x_verify' => $calculatedChecksum,
                        ]);

                        if (hash_equals((string) $request->header('X-VERIFY'), $calculatedChecksum)) {
                            $paymentSuccess = true;
                            $transactionId = data_get($responseData, 'data.merchantTransactionId')
                                ?? data_get($responseData, 'data.instrumentResponse.merchantTransactionId');
                        }
                    }
                }
            } else {
                $errorMsg = 'PhonePe: '.($responseData['message'] ?? 'Payment Failed');
            }
        } elseif ($request->has('code')) {
            $code = (string) $request->input('code');
            if ($code === 'PAYMENT_SUCCESS') {
                $paymentSuccess = true;
                $transactionId = $request->input('transactionId') ?? $request->input('merchantTransactionId');
            } else {
                $errorMsg = 'PhonePe: '.((string) $request->input('message', 'Payment Failed'));
            }
        }

        if (! $paymentSuccess || ! is_string($transactionId) || $transactionId === '') {
            return redirect()->route('user.wallet.show')->with('error', $errorMsg);
        }

        $parts = explode('_', $transactionId);
        if (count($parts) < 4 || $parts[0] !== 'UWLT') {
            return redirect()->route('user.wallet.show')->with('error', 'Invalid transaction format.');
        }

        $customerId = (int) $parts[1];
        $amountPaise = (int) $parts[2];
        $amount = $amountPaise / 100;
        if ($customerId <= 0 || $amount <= 0) {
            return redirect()->route('user.wallet.show')->with('error', 'Invalid transaction data.');
        }

        return $this->creditWalletAfterGateway($customerId, $amount, 'phonepe', $transactionId, '');
    }

    private function creditWalletAfterGateway(int $customerId, float $amount, string $gatewaySlug, string $referenceId, string $orderNote)
    {
        try {
            $result = DB::transaction(function () use ($customerId, $amount, $gatewaySlug, $referenceId, $orderNote) {
                $user = Registration::lockForUpdate()->find($customerId);
                if (! $user) {
                    return ['status' => 'error'];
                }

                $alreadyProcessed = WalletTransaction::query()
                    ->where('registration_id', $user->id)
                    ->where(function ($q) use ($referenceId) {
                        $q->where('narration', 'like', "%{$referenceId}%")
                            ->orWhere('credit_note', 'like', "%{$referenceId}%");
                    })
                    ->exists();

                if ($alreadyProcessed) {
                    return ['status' => 'already'];
                }

                $isFirstTopUp = ! WalletTransaction::query()
                    ->where('registration_id', $user->id)
                    ->where('transaction_type', 'add')
                    ->where(function ($q) {
                        $q->where('narration', 'like', 'Wallet Topup%')
                            ->orWhere('narration', 'like', 'Wallet top-up%');
                    })
                    ->exists();

                if ($isFirstTopUp) {
                    $allowed = [150.0, 500.0];
                    $isAllowed = false;
                    foreach ($allowed as $value) {
                        if (abs($amount - $value) < 0.001) {
                            $isAllowed = true;
                            break;
                        }
                    }

                    if (! $isAllowed) {
                        return ['status' => 'invalid_amount'];
                    }
                }

                $bonus = 0.0;
                if ($isFirstTopUp && abs($amount - 150.0) < 0.001) {
                    $bonus = 50.0;
                } elseif ($isFirstTopUp && abs($amount - 500.0) < 0.001) {
                    $bonus = 300.0;
                }

                $previousBalance = (float) ($user->wallet_balance ?? 0);
                $newBalance = $previousBalance + $amount;

                WalletTransaction::create([
                    'registration_id' => $user->id,
                    'transaction_type' => 'add',
                    'amount' => $amount,
                    'previous_balance' => $previousBalance,
                    'new_balance' => $newBalance,
                    'narration' => $orderNote !== '' ? $orderNote : "Wallet Topup ({$gatewaySlug}: {$referenceId})",
                    'credit_note' => strtoupper($gatewaySlug)." Ref: {$referenceId}",
                    'debit_note' => null,
                    'performed_by_user_id' => $user->id,
                ]);

                $user->wallet_balance = $newBalance;
                $user->save();

                if ($bonus > 0) {
                    $bonusNewBalance = $newBalance + $bonus;

                    WalletTransaction::create([
                        'registration_id' => $user->id,
                        'transaction_type' => 'add',
                        'amount' => $bonus,
                        'previous_balance' => $newBalance,
                        'new_balance' => $bonusNewBalance,
                        'narration' => 'Welcome Bonus (First Wallet Recharge)',
                        'credit_note' => "Bonus for first recharge ({$referenceId})",
                        'debit_note' => null,
                        'performed_by_user_id' => $user->id,
                    ]);

                    $user->wallet_balance = $bonusNewBalance;
                    $user->save();
                }

                return ['status' => 'ok', 'bonus' => $bonus];
            });
        } catch (\Throwable $e) {
            Log::error('Wallet callback processing failed', ['ref' => $referenceId, 'error' => $e->getMessage()]);
            return redirect()->route('user.wallet.show')->with('error', 'Unable to process wallet update.');
        }

        if (($result['status'] ?? null) === 'already') {
            return redirect()->route('user.wallet.show')->with('info', 'Transaction already processed.');
        }

        if (($result['status'] ?? null) === 'invalid_amount') {
            return redirect()->route('user.wallet.show')->with('error', 'First wallet recharge allows only ₹150 or ₹500.');
        }

        if (($result['status'] ?? null) !== 'ok') {
            return redirect()->route('user.wallet.show')->with('error', 'Unable to process wallet update.');
        }

        $bonus = (float) ($result['bonus'] ?? 0);
        $message = $bonus > 0
            ? "Payment successful! Wallet updated with ₹{$bonus} bonus."
            : 'Payment successful! Wallet updated.';

        return redirect()->route('user.wallet.show')->with('success', $message);
    }

    public function webhook(Request $request)
    {
        // PhonePe may confirm payment via webhook/callbackUrl.
        // To make wallet recharge reliable, process the callback here too.
        // This will credit wallet if PhonePe sends success payload.
        // NOTE: this endpoint is POST-only.
        // PhonePe usually sends webhook/callback here with POST method.
        // We must not break redirect GET flow.
        try {
            return $this->handlePhonePeCallback($request);
        } catch (\Throwable $e) {
            Log::error('PhonePe wallet webhook failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json(['status' => 'error', 'message' => 'webhook failed'], 500);
        }
    }
}
