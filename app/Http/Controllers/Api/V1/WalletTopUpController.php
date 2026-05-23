<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Registration;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletTopUpController extends Controller
{
    /**
     * @group Wallet
     *
     * Add funds to user wallet via active payment gateway
     *
     * This endpoint credits a user's wallet by the specified amount, attributing
     * the transaction to the currently active payment gateway (eg. PhonePe or Cashfree).
     * It records a credit note on the wallet transaction for audit purposes.
     *
     * @authenticated
     *
     * @bodyParam registration_id int required The target registration ID. Example: 12
     * @bodyParam amount number required The amount to add. Must be > 0. Example: 250.50
     * @bodyParam note string Optional additional note to append to the credit note. Example: "Manual top-up"
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Wallet topped up successfully",
     *   "data": {
     *     "transaction": {
     *       "id": 101,
     *       "registration_id": 12,
     *       "transaction_type": "add",
     *       "amount": "250.50",
     *       "previous_balance": "1000.00",
     *       "new_balance": "1250.50",
     *       "narration": "Wallet top-up",
     *       "credit_note": "Top-up via PhonePe (test)",
     *       "debit_note": null
     *     }
     *   }
     * }
     * @response 422 {"success": false, "message": "No active payment gateway configured"}
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'registration_id' => ['required', 'integer', 'exists:registrations,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $registrationId = (int) $data['registration_id'];
        $amount = (float) $data['amount'];

        // Resolve active payment gateway
        $gateway = PaymentGateway::query()
            ->where('is_enabled', true)
            ->orderByDesc('id')
            ->first();

        if (! $gateway) {
            return response()->json([
                'success' => false,
                'message' => 'No active payment gateway configured',
            ], 422);
        }

        $isFirstTopUp = ! WalletTransaction::query()
            ->where('registration_id', $registrationId)
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
                return response()->json([
                    'success' => false,
                    'message' => 'First wallet recharge allows only 150 or 500',
                ], 422);
            }
        }

        // Perform atomic wallet credit
        $result = DB::transaction(function () use ($registrationId, $amount, $data, $gateway) {
            $registration = Registration::lockForUpdate()->findOrFail($registrationId);
            $isFirstTopUp = ! WalletTransaction::query()
                ->where('registration_id', $registration->id)
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

            $previous = (float) ($registration->wallet_balance ?? 0);
            $new = $previous + $amount;

            // Update wallet balance
            $registration->wallet_balance = $new;
            $registration->save();

            // Build credit note (include gateway and mode)
            $notePieces = [
                sprintf('Top-up via %s (%s)', $gateway->name, $gateway->active_mode),
            ];
            if (! empty($data['note'])) {
                $notePieces[] = $data['note'];
            }
            $creditNote = implode(' - ', $notePieces);

            // Record transaction
            $tx = WalletTransaction::create([
                'registration_id' => $registration->id,
                'transaction_type' => 'add',
                'amount' => $amount,
                'previous_balance' => $previous,
                'new_balance' => $new,
                'narration' => 'Wallet top-up',
                'credit_note' => $creditNote,
                'debit_note' => null,
                'performed_by_user_id' => Auth::id(),
            ]);

            $bonusTx = null;
            if ($bonus > 0) {
                $bonusNew = $new + $bonus;
                $bonusTx = WalletTransaction::create([
                    'registration_id' => $registration->id,
                    'transaction_type' => 'add',
                    'amount' => $bonus,
                    'previous_balance' => $new,
                    'new_balance' => $bonusNew,
                    'narration' => 'Welcome Bonus (First Wallet Recharge)',
                    'credit_note' => "Bonus for first recharge - {$creditNote}",
                    'debit_note' => null,
                    'performed_by_user_id' => Auth::id(),
                ]);

                $registration->wallet_balance = $bonusNew;
                $registration->save();
            }

            return ['status' => 'ok', 'transaction' => $tx, 'bonus' => $bonus, 'bonus_transaction' => $bonusTx];
        });

        if (($result['status'] ?? null) === 'invalid_amount') {
            return response()->json([
                'success' => false,
                'message' => 'First wallet recharge allows only 150 or 500',
            ], 422);
        }

        $transaction = $result['transaction'];

        return response()->json([
            'success' => true,
            'message' => 'Wallet topped up successfully',
            'data' => [
                'bonus_amount' => (string) ((float) ($result['bonus'] ?? 0)),
                'transaction' => [
                    'id' => $transaction->id,
                    'registration_id' => $transaction->registration_id,
                    'transaction_type' => $transaction->transaction_type,
                    'amount' => (string) $transaction->amount,
                    'previous_balance' => (string) $transaction->previous_balance,
                    'new_balance' => (string) $transaction->new_balance,
                    'narration' => $transaction->narration,
                    'credit_note' => $transaction->credit_note,
                    'debit_note' => $transaction->debit_note,
                ],
            ],
        ]);
    }
}
