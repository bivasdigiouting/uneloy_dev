<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\RechargeCommission;
use App\Models\RechargeCommissionRule;
use App\Models\RechargeOperator;
use App\Models\RechargeService;
use App\Models\RechargeTransaction;
use App\Models\Registration;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserRechargeController extends Controller
{
    public function __construct(
        private WalletRepositoryInterface $walletRepository
    ) {}

    /**
     * Create Cashfree Order for Recharge
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'mobile' => 'required|string',
            'operator' => 'required|string',
            'circle' => 'nullable|string',
            'plan_desc' => 'nullable|string',
            'service' => 'nullable|string|in:mobile,dth,fastag,bbps',
            'category' => 'nullable|string',
        ]);

        $userSession = Session::get('user_auth');
        if (! $userSession) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Fetch User for correct mobile number
        $user = Registration::find($userSession['id']);
        $userMobile = $user ? $user->mobile : '9999999999';

        // Fetch Cashfree Config
        $gateway = PaymentGateway::where('slug', 'cashfree')->first();
        if (! $gateway || ! $gateway->is_enabled) {
            return response()->json(['status' => 'error', 'message' => 'Payment gateway not available'], 400);
        }

        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';

        if (! $appId || ! $secretKey) {
            return response()->json(['status' => 'error', 'message' => 'Payment gateway configuration missing'], 500);
        }

        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        $orderId = 'RCH_'.Str::random(10).'_'.time();
        $returnUrl = route('user.service.recharge.confirm')."?order_id={$orderId}&service={$request->service}&category={$request->category}"; // We handle callback on page or separate

        // Store recharge details in Cache for 30 mins
        $rechargeData = $request->only(['amount', 'mobile', 'operator', 'circle', 'plan_desc', 'service', 'category']);
        $rechargeData['user_id'] = $userSession['id'];
        Cache::put("recharge_order_{$orderId}", $rechargeData, 60 * 30);

        $requestData = [
            'order_id' => $orderId,
            'order_amount' => $request->amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => (string) $userSession['id'],
                'customer_email' => $userSession['email_id'] ?? 'customer@example.com',
                'customer_phone' => (string) $userMobile,
                'customer_name' => $userSession['full_name'] ?? 'User',
            ],
            'order_meta' => [
                'return_url' => $returnUrl,
            ],
            'order_note' => "Recharge ({$request->service}): {$request->mobile}",
        ];

        try {
            $response = Http::withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2022-09-01',
                'Content-Type' => 'application/json',
            ])->post("$baseUrl/orders", $requestData);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'status' => 'success',
                    'payment_session_id' => $data['payment_session_id'],
                    'order_id' => $orderId,
                ]);
            } else {
                Log::error('Cashfree Order Error: '.$response->body());

                return response()->json(['status' => 'error', 'message' => 'Payment initiation failed'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Cashfree Exception: '.$e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Payment initiation exception'], 500);
        }
    }

    /**
     * Process Recharge after Payment
     */
    public function processRecharge(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $orderId = $request->order_id;
        $rechargeData = Cache::get("recharge_order_{$orderId}");

        if (! $rechargeData) {
            return response()->json(['status' => 'error', 'message' => 'Order session expired or not found'], 404);
        }

        // Verify Payment Status (Optional but recommended)
        // For now, we trust the frontend triggers this ONLY after success,
        // OR we can verify with Cashfree API here.
        // Let's verify for security.

        $gateway = PaymentGateway::where('slug', 'cashfree')->first();
        $config = $gateway->active_mode === 'live' ? $gateway->live_config : $gateway->test_config;
        $appId = $config['app_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;
        $env = $config['environment'] ?? 'TEST';
        $baseUrl = ($env === 'LIVE' || $gateway->active_mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        try {
            $verifyResponse = Http::withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2022-09-01',
            ])->get("$baseUrl/orders/$orderId");

            if (! $verifyResponse->successful() || $verifyResponse->json()['order_status'] !== 'PAID') {
                // Check if it's already processed or just not paid
                return response()->json(['status' => 'error', 'message' => 'Payment not verified'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Cashfree Verify Exception: '.$e->getMessage());

            // Fallback: Proceed if we trust frontend? No, security risk.
            return response()->json(['status' => 'error', 'message' => 'Payment verification failed'], 500);
        }

        // Payment Verified. Proceed to Recharge.

        // 1. Get Opcode
        $operatorName = $rechargeData['operator'];
        $opcode = $this->getOpcode($operatorName);

        if (! $opcode) {
            // Try to use what was passed in request if available (fallback)
            $opcode = $request->input('opcode');
            if (! $opcode) {
                return response()->json(['status' => 'error', 'message' => 'Operator code not found'], 400);
            }
        }

        // 2. Call Inspay API
        $inspayUrl = 'http://www.connect.inspay.in/v3/recharge/api';
        $inspayParams = [
            'username' => 'IP9564853492',
            'token' => 'fa80d1258738ee725d2f98ef8bd57cce',
            'opcode' => $opcode,
            'number' => $rechargeData['mobile'],
            'amount' => $rechargeData['amount'],
            'orderid' => $orderId, // Use our order ID
            'format' => 'json',
        ];

        try {
            // Note: External API URL has repeated path in user prompt "/v3/recharge/api//v3/recharge/api", assuming standard path.
            // User provided: "http://www.connect.inspay.in/v3/recharge/api//v3/recharge/api" - likely a typo in prompt.
            // Using standard: "http://www.connect.inspay.in/v3/recharge/api"

            $apiResponse = Http::get('http://www.connect.inspay.in/v3/recharge/api', $inspayParams);
            $apiData = $apiResponse->json(); // If json format

            // 3. Save History
            $status = 'pending'; // Default
            if (isset($apiData['status'])) {
                $status = strtolower($apiData['status']) == 'success' ? 'success' : 'failed';
            }

            $serviceCode = isset($rechargeData['service']) ? strtoupper($rechargeData['service']) : 'MOBILE';

            $txn = RechargeTransaction::create([
                'user_id' => $rechargeData['user_id'],
                'service_code' => $serviceCode,
                'operator_id' => $this->getOperatorId($operatorName),
                'recharge_no' => $rechargeData['mobile'],
                'amount' => $rechargeData['amount'],
                'payment_method' => 'cashfree',
                'status' => $status,
                'transaction_id' => $orderId,
                'response' => $apiData,
            ]);

            if ($status === 'success') {
                try {
                    $this->applyRechargeCommission($txn);
                } catch (\Throwable $e) {
                    Log::error('Recharge commission error: '.$e->getMessage());
                }
                Cache::forget("recharge_order_{$orderId}"); // Clear cache

                return response()->json(['status' => 'success', 'message' => 'Recharge successful', 'data' => $apiData, 'mobile' => $rechargeData['mobile']]);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Recharge failed at provider', 'data' => $apiData, 'mobile' => $rechargeData['mobile']]);
            }

        } catch (\Exception $e) {
            Log::error('Recharge API Exception: '.$e->getMessage());

            // Attempt to return mobile if available in catch block, though rechargeData might be out of scope if error happened earlier.
            // But here it is in scope.
            return response()->json(['status' => 'error', 'message' => 'Recharge API failed', 'mobile' => $rechargeData['mobile'] ?? null], 500);
        }
    }

    private function getOpcode($name)
    {
        $op = RechargeOperator::where('operator_name', 'LIKE', "%$name%")
            ->orWhere('operator_code', $name)
            ->first();

        if ($op) {
            return $op->operator_code;
        }

        // Manual Mapping for common banks
        $map = [
            'State Bank of India' => 'SBI',
            'Punjab National Bank' => 'PNB',
            'Paytm Payments Bank' => 'PAYTM',
            'Airtel Payments Bank' => 'AIRTEL',
            'Bank of Baroda' => 'BOB',
            'Union Bank of India' => 'UBI',
            'Canara Bank' => 'CANARA',
            'Indian Bank' => 'INDIAN',
            'Indian Overseas Bank' => 'IOB',
            'South Indian Bank' => 'SIB',
            'Central Bank of India' => 'CBI',
            'IndusInd Bank' => 'INDUSIND',
            'IDFC First Bank' => 'IDFC',
            'Kotak Mahindra Bank' => 'KOTAK',
        ];

        foreach ($map as $key => $val) {
            if (stripos($name, $key) !== false) {
                return $val;
            }
        }

        // Fallback for FASTag banks not in DB
        // Try to guess code: "HDFC Bank" -> "HDFC"
        $simpleName = str_ireplace([' Bank', ' Payments', ' Small Finance', ' Co-op'], '', $name);

        return trim($simpleName);
    }

    private function getOperatorId($name)
    {
        $op = RechargeOperator::where('operator_name', 'LIKE', "%$name%")
            ->orWhere('operator_code', $name)
            ->first();

        return $op ? $op->id : null;
    }

    private function applyRechargeCommission(RechargeTransaction $txn): void
    {
        if ($txn->status !== 'success') {
            return;
        }

        $registrationId = (int) ($txn->user_id ?? 0);
        if ($registrationId <= 0) {
            return;
        }

        $registration = Registration::find($registrationId);
        if (! $registration) {
            return;
        }

        $serviceCode = strtoupper((string) $txn->service_code);
        $service = RechargeService::query()
            ->whereRaw('UPPER(service_code) = ?', [$serviceCode])
            ->first();
        if (! $service) {
            return;
        }

        $amount = (float) ($txn->amount ?? 0);
        if ($amount <= 0) {
            return;
        }

        $operatorId = $txn->operator_id ? (int) $txn->operator_id : null;
        $departmentLevel = $registration->department_level ?: null;

        $rule = RechargeCommissionRule::query()
            ->where('is_active', true)
            ->where('recharge_service_id', $service->id)
            ->where(function ($q) use ($operatorId) {
                $q->whereNull('recharge_operator_id');
                if ($operatorId) {
                    $q->orWhere('recharge_operator_id', $operatorId);
                }
            })
            ->where(function ($q) use ($departmentLevel) {
                $q->whereNull('department_level');
                if ($departmentLevel) {
                    $q->orWhere('department_level', $departmentLevel);
                }
            })
            ->where(function ($q) use ($amount) {
                $q->whereNull('min_amount')->orWhere('min_amount', '<=', $amount);
            })
            ->where(function ($q) use ($amount) {
                $q->whereNull('max_amount')->orWhere('max_amount', '>=', $amount);
            })
            ->orderByRaw('recharge_operator_id is null asc')
            ->orderByRaw('department_level is null asc')
            ->orderByDesc('id')
            ->first();

        if (! $rule) {
            return;
        }

        $commissionAmount = 0.0;
        $commissionValue = (float) ($rule->commission_value ?? 0);
        if ($rule->commission_type === 'percentage') {
            $commissionAmount = round(($amount * $commissionValue) / 100, 2);
        } else {
            $commissionAmount = round($commissionValue, 2);
        }

        if ($commissionAmount <= 0) {
            return;
        }

        DB::transaction(function () use ($txn, $registration, $service, $operatorId, $departmentLevel, $rule, $amount, $commissionAmount) {
            $commission = null;
            try {
                $commission = RechargeCommission::create([
                    'recharge_transaction_id' => $txn->id,
                    'registration_id' => $registration->id,
                    'recharge_commission_rule_id' => $rule->id,
                    'recharge_service_id' => $service->id,
                    'recharge_operator_id' => $operatorId,
                    'department_level' => $departmentLevel,
                    'commission_type' => $rule->commission_type,
                    'commission_value' => $rule->commission_value,
                    'recharge_amount' => $amount,
                    'commission_amount' => $commissionAmount,
                    'status' => 'pending',
                    'wallet_transaction_id' => null,
                    'meta' => [
                        'transaction_id' => $txn->transaction_id,
                    ],
                ]);
            } catch (QueryException $e) {
                if ((string) $e->getCode() === '23000') {
                    return;
                }
                throw $e;
            }

            $walletTxn = $this->walletRepository->processTransaction(
                (int) $registration->id,
                'add',
                (float) $commissionAmount,
                'Recharge commission for '.$txn->transaction_id,
                null
            );

            $commission->status = 'credited';
            $commission->wallet_transaction_id = $walletTxn->id;
            $commission->save();
        });
    }
}
