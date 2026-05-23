<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\RechargeTransaction;
use App\Models\WebsiteSettings;
use App\Repositories\Interfaces\RechargeOperatorRepositoryInterface;
use App\Repositories\Interfaces\RechargeServiceRepositoryInterface;
use Illuminate\Http\Request;

class FastagRechargeController extends Controller
{
    protected RechargeServiceRepositoryInterface $services;

    protected RechargeOperatorRepositoryInterface $operators;

    public function __construct(
        RechargeServiceRepositoryInterface $services,
        RechargeOperatorRepositoryInterface $operators
    ) {
        $this->services = $services;
        $this->operators = $operators;
    }

    /**
     * List FASTag Operators
     *
     * @group FASTag Recharge
     *
     * @response 200 {"data": [{"id":1,"operator_name":"Example FASTag","operator_code":"FSTG","is_active":true}]}
     */
    public function operators()
    {
        $service = $this->services->findByCode('FASTAG');
        if (! $service) {
            return response()->json(['message' => 'FASTag service not configured'], 404);
        }
        $ops = $this->operators->getByServiceId($service->id)->map(function ($op) {
            return [
                'id' => $op->id,
                'operator_name' => $op->operator_name,
                'operator_code' => $op->operator_code,
                'is_active' => (bool) $op->is_active,
            ];
        });

        return response()->json(['data' => $ops]);
    }

    /**
     * Initiate FASTag Recharge
     *
     * @group FASTag Recharge
     *
     * @authenticated
     *
     * @bodyParam account_no string required The FASTag account/tag id. Example: TAG123456
     * @bodyParam operator_code string required The operator code. Example: FSTG
     * @bodyParam amount number required The recharge amount. Example: 500
     * @bodyParam payment_method string The payment method: ecard, ewallet, or eqr. Example: ecard
     *
     * @response 201 {"message":"Recharge initiated","transaction":{"transaction_id":"RCH202501011234560003","status":"pending","amount":500,"operator_code":"FSTG","account_no":"TAG123456"}}
     */
    public function initiate(Request $request)
    {
        $data = $request->validate([
            'account_no' => ['required', 'string', 'min:6'],
            'operator_code' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['nullable', 'in:ecard,ewallet,eqr'],
        ]);

        $service = $this->services->findByCode('FASTAG');
        if (! $service) {
            return response()->json(['message' => 'FASTag service not configured'], 404);
        }

        $operator = $this->operators->findByCode($data['operator_code']);
        if (! $operator || (int) $operator->recharge_service_id !== (int) $service->id || ! $operator->is_active) {
            return response()->json(['message' => 'Invalid or inactive operator for FASTag'], 422);
        }

        if (! empty($data['payment_method'])) {
            $settings = WebsiteSettings::first();
            if ($data['payment_method'] === 'ecard' && (! $settings || ! $settings->ecard_payment_enabled)) {
                return response()->json(['message' => 'E-Card payments are disabled'], 422);
            }
            if ($data['payment_method'] === 'ewallet' && (! $settings || ! $settings->ewallet_payment_enabled)) {
                return response()->json(['message' => 'E-Wallet payments are disabled'], 422);
            }
            if ($data['payment_method'] === 'eqr' && (! $settings || ! $settings->eqr_payment_enabled)) {
                return response()->json(['message' => 'E-QR payments are disabled'], 422);
            }
        }

        $txnId = 'RCH'.now()->format('YmdHis').sprintf('%04d', random_int(0, 9999));

        $txn = RechargeTransaction::create([
            'user_id' => optional($request->user())->id,
            'service_code' => 'FASTAG',
            'operator_id' => $operator->id,
            'recharge_no' => $data['account_no'],
            'amount' => (float) $data['amount'],
            'payment_method' => $data['payment_method'] ?? null,
            'status' => 'pending',
            'transaction_id' => $txnId,
            'response' => null,
        ]);

        return response()->json([
            'message' => 'Recharge initiated',
            'transaction' => [
                'transaction_id' => $txn->transaction_id,
                'status' => $txn->status,
                'amount' => $txn->amount,
                'operator_code' => $operator->operator_code,
                'account_no' => $data['account_no'],
            ],
        ], 201);
    }

    /**
     * Check FASTag Recharge Status
     *
     * @group FASTag Recharge
     *
     * @authenticated
     *
     * @urlParam transaction_id string required The transaction id to check. Example: RCH202501011234560003
     *
     * @response 200 {"transaction":{"transaction_id":"RCH202501011234560003","status":"pending","amount":500,"recharge_no":"TAG123456"}}
     */
    public function status(string $transaction_id)
    {
        $txn = RechargeTransaction::where('transaction_id', $transaction_id)->first();
        if (! $txn || $txn->service_code !== 'FASTAG') {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json([
            'transaction' => [
                'transaction_id' => $txn->transaction_id,
                'status' => $txn->status,
                'amount' => $txn->amount,
                'recharge_no' => $txn->recharge_no,
                'response' => $txn->response,
            ],
        ]);
    }
}
