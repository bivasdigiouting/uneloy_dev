<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardRegistration;
use App\Models\ECardWalletTransaction;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    /**
     * Get Wallet Balance
     *
     * Returns the current wallet balance of the authenticated user.
     *
     * @group Wallet
     * @authenticated
     */
    public function getBalance(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'wallet_balance' => (float) ($user->wallet_balance ?? 0),
            'currency' => 'INR'
        ]);
    }

    /**
     * Get Wallet Transactions
     *
     * Returns a paginated list of wallet transactions.
     *
     * @group Wallet
     * @authenticated
     */
    public function getTransactions(Request $request)
    {
        $transactions = ECardWalletTransaction::where('ecard_registration_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($transactions);
    }

    /**
     * Add Money
     *
     * Initiates a wallet top-up transaction using the active payment gateway.
     *
     * @group Wallet
     * @authenticated
     * @bodyParam amount number required The amount to add (e.g., 100.00). Example: 100
     */
    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = (float) $request->amount;
        $user = $request->user();

        // Get Active Gateway
        $gateway = PaymentGateway::where('is_enabled', true)->first();

        if (!$gateway) {
            return response()->json(['message' => 'No active payment gateway found.'], 400);
        }

        $orderId = 'ORD_' . Str::random(10) . '_' . time();

        // Create Pending Transaction
        $transaction = ECardWalletTransaction::create([
            'ecard_registration_id' => $user->id,
            'transaction_type' => 'add',
            'amount' => $amount,
            'previous_balance' => $user->wallet_balance ?? 0,
            'new_balance' => $user->wallet_balance ?? 0, // Will update on success
            'narration' => 'Wallet Topup via ' . $gateway->name,
            'gateway_transaction_id' => $orderId,
            'gateway_name' => $gateway->slug,
            'payment_status' => 'pending',
            'payment_meta' => null,
        ]);

        $paymentData = [];
        $message = 'Payment initiated';

        if ($gateway->slug === 'cashfree') {
            $paymentData = $this->initiateCashfree($gateway, $amount, $orderId, $user);
        } elseif ($gateway->slug === 'phonepe') {
            $paymentData = $this->initiatePhonePe($gateway, $amount, $orderId, $user);
        } else {
            return response()->json(['message' => 'Unsupported gateway type.'], 400);
        }

        if (isset($paymentData['error'])) {
             return response()->json(['message' => $paymentData['error']], 500);
        }

        return response()->json([
            'message' => $message,
            'order_id' => $orderId,
            'gateway' => $gateway->slug,
            'payment_data' => $paymentData
        ]);
    }

    private function initiateCashfree($gateway, $amount, $orderId, $user)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (!$appId || !$secretKey) {
            return ['error' => 'Gateway configuration missing'];
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        // Use Payment Links API to get a direct payment URL if possible
        // Or construct payload for Order API
        
        $payload = [
            'order_id' => $orderId,
            'order_amount' => $amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => (string)$user->id,
                'customer_email' => $user->email_id ?? 'customer@example.com',
                'customer_phone' => $user->mobile_no ?? '9999999999',
            ],
            'order_meta' => [
                'return_url' => route('api.ecard.wallet.verify') . '?order_id={order_id}',
            ]
        ];

        // Create Order at Cashfree
        try {
            $response = Http::withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2022-09-01',
            ])->post("$baseUrl/orders", $payload);

            if ($response->successful()) {
                $data = $response->json();
                // Attempt to return payment_link if available in order_meta or construct one
                // For Cashfree PG 2022-09-01, usually you use payment_session_id with SDK
                // But for "open the page", we can provide the checkout link if using hosted checkout
                
                // If the response doesn't have a direct link, we return the data.
                // However, we can try to generate a link using /links endpoint if desired.
                // But let's stick to standard order creation and return the session.
                // The client (Postman/App) can use the session_id or if we want a browser flow
                // we might need to use a different flow.
                
                // Note: Cashfree 'orders' endpoint returns 'payment_session_id'.
                // To pay in browser, one can use the checkout JS.
                // Since this is a REST API, returning the session_id is standard.
                // But to satisfy "it will open the payment gate way page", 
                // we can return the 'payment_link' if we use the Payment Links API.
                
                return $data; 
            } else {
                Log::error('Cashfree Order Creation Failed', ['response' => $response->body()]);
                return ['error' => 'Failed to create payment order: ' . ($response->json()['message'] ?? 'Unknown error')];
            }
        } catch (\Exception $e) {
            Log::error('Cashfree Exception', ['error' => $e->getMessage()]);
            return ['error' => 'Payment gateway error'];
        }
    }

    private function initiatePhonePe($gateway, $amount, $orderId, $user)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        
        // For PhonePe, usually we return parameters for the SDK or a payment link
        // Returning config for client-side SDK integration as a placeholder
        return [
            'merchant_id' => $config['merchant_id'] ?? '',
            'salt_key' => $config['salt_key'] ?? '',
            'salt_index' => $config['salt_index'] ?? '',
            'env' => $config['environment'] ?? 'TEST',
            'amount' => $amount * 100, // in paise
            'transaction_id' => $orderId,
            'user_id' => (string)$user->id,
            'callback_url' => route('api.ecard.wallet.verify'),
        ];
    }

    /**
     * Verify Payment
     *
     * Verifies the payment status with the gateway and updates the wallet balance.
     *
     * @group Wallet
     * @authenticated
     * @bodyParam order_id string required The transaction/order ID.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $orderId = $request->order_id;
        $transaction = ECardWalletTransaction::where('gateway_transaction_id', $orderId)
            ->where('ecard_registration_id', $request->user()->id)
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaction->payment_status === 'success') {
            return response()->json([
                'message' => 'Transaction already successful',
                'status' => 'success',
                'balance' => $transaction->new_balance
            ]);
        }

        // Fetch Gateway Details
        $gateway = PaymentGateway::where('slug', $transaction->gateway_name)->first();
        if (!$gateway) {
            return response()->json(['message' => 'Gateway configuration not found'], 500);
        }

        $status = 'pending';
        $amount = 0;

        if ($gateway->slug === 'cashfree') {
             // Verify Cashfree
             $status = $this->verifyCashfree($gateway, $orderId);
        } elseif ($gateway->slug === 'phonepe') {
             // Verify PhonePe
             $status = $this->verifyPhonePe($gateway, $orderId);
        }

        if ($status === 'paid' || $status === 'success') {
            // Update Transaction and Balance
            DB::transaction(function () use ($transaction, $request) {
                // Lock user
                $user = ECardRegistration::lockForUpdate()->find($request->user()->id);
                
                // Double check if already updated to prevent race conditions
                if ($transaction->payment_status !== 'success') {
                    $transaction->payment_status = 'success';
                    $transaction->previous_balance = $user->wallet_balance ?? 0;
                    $user->wallet_balance = ($user->wallet_balance ?? 0) + $transaction->amount;
                    $transaction->new_balance = $user->wallet_balance;
                    $transaction->save();
                    $user->save();
                }
            });
            
            // Reload transaction to get updated balance
            $transaction->refresh();

            return response()->json([
                'message' => 'Payment successful, wallet updated.',
                'status' => 'success',
                'balance' => $transaction->new_balance
            ]);
        } elseif ($status === 'failed') {
            $transaction->payment_status = 'failed';
            $transaction->save();
            return response()->json(['message' => 'Payment failed.', 'status' => 'failed'], 400);
        }

        return response()->json(['message' => 'Payment status is ' . $status, 'status' => $status]);
    }

    private function verifyCashfree($gateway, $orderId)
    {
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';
        
        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        try {
            $response = Http::withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2022-09-01',
            ])->get("$baseUrl/orders/$orderId");

            if ($response->successful()) {
                $data = $response->json();
                if (($data['order_status'] ?? '') === 'PAID') {
                    return 'success';
                }
                return strtolower($data['order_status'] ?? 'pending');
            }
        } catch (\Exception $e) {
            Log::error('Cashfree Verification Error', ['error' => $e->getMessage()]);
        }
        return 'pending';
    }

    private function verifyPhonePe($gateway, $orderId)
    {
        // Placeholder for PhonePe Status Check
        // Needs proper checksum generation and API call
        return 'pending';
    }
}
