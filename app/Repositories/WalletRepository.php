<?php

namespace App\Repositories;

use App\Models\Registration;
use App\Models\WalletTransaction;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletRepository implements WalletRepositoryInterface
{
    /**
     * Find registration by ID/Aadhaar/Mobile identifier
     */
    public function findByIdentifier(string $identifier): ?Registration
    {
        $identifier = trim($identifier);

        if ($identifier === '') {
            return null;
        }

        if (ctype_digit($identifier)) {
            $byId = Registration::find((int) $identifier);
            if ($byId) {
                return $byId;
            }

            return Registration::where('user_id', (int) $identifier)->first();
        }

        $registration = Registration::query()
            ->where('user_id', $identifier)
            ->orWhere('aadhaar_no', $identifier)
            ->orWhere('mobile_no', $identifier)
            ->orWhere('email_id', $identifier)
            ->orWhere('gmail_id', $identifier)
            ->orWhere(function ($q) use ($identifier) {
                $q->where('first_name', 'like', '%'.$identifier.'%')
                    ->orWhere('middle_name', 'like', '%'.$identifier.'%')
                    ->orWhere('last_name', 'like', '%'.$identifier.'%');
            })
            ->orderByDesc('id')
            ->first();

        return $registration ?: null;
    }

    /**
     * Get current wallet balance for a registration
     */
    public function getBalance(int $registrationId): float
    {
        return DB::transaction(function () use ($registrationId) {
            $registration = Registration::lockForUpdate()->find($registrationId);
            if (! $registration) {
                return 0.0;
            }

            $cr = (float) WalletTransaction::query()
                ->where('registration_id', $registrationId)
                ->where('transaction_type', 'add')
                ->sum('amount');

            $dr = (float) WalletTransaction::query()
                ->where('registration_id', $registrationId)
                ->where('transaction_type', 'remove')
                ->sum('amount');

            $ledgerBalance = $cr - $dr;

            $current = (float) ($registration->wallet_balance ?? 0);
            if (abs($ledgerBalance - $current) >= 0.005) {
                $registration->update(['wallet_balance' => $ledgerBalance]);
            }

            return $ledgerBalance;
        });
    }

    /**
     * Process a wallet transaction and persist audit trail
     */
    public function processTransaction(
        int $registrationId,
        string $transactionType,
        float $amount,
        ?string $narration,
        ?int $performedByUserId = null
    ): WalletTransaction {
        if (! in_array($transactionType, ['add', 'remove'])) {
            throw new InvalidArgumentException('Invalid transaction type.');
        }
        if ($amount <= 0) {
            throw new InvalidArgumentException('Transaction amount must be greater than zero.');
        }

        return DB::transaction(function () use ($registrationId, $transactionType, $amount, $narration, $performedByUserId) {
            $registration = Registration::lockForUpdate()->findOrFail($registrationId);
            $currentBalance = (float) ($registration->wallet_balance ?? 0);

            // Calculate new balance
            if ($transactionType === 'add') {
                $newBalance = $currentBalance + $amount;
            } else {
                if ($currentBalance < $amount) {
                    throw new InvalidArgumentException('Insufficient wallet balance.');
                }
                $newBalance = $currentBalance - $amount;
            }

            // Persist transaction
            $transaction = new WalletTransaction([
                'registration_id' => $registration->id,
                'transaction_type' => $transactionType,
                'amount' => $amount,
                'previous_balance' => $currentBalance,
                'new_balance' => $newBalance,
                'narration' => $narration,
                'credit_note' => $transactionType === 'add' ? ($narration ?: 'Wallet credit') : null,
                'debit_note' => $transactionType === 'remove' ? ($narration ?: 'Wallet debit') : null,
                'performed_by_user_id' => $performedByUserId,
            ]);
            $transaction->save();

            // Update wallet balance on registration
            $registration->update(['wallet_balance' => $newBalance]);

            return $transaction;
        });
    }
}
