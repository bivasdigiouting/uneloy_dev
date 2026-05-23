<?php

namespace App\Repositories;

use App\Models\Vendor;
use App\Models\VendorWalletTransaction;
use App\Repositories\Interfaces\VendorWalletRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class VendorWalletRepository implements VendorWalletRepositoryInterface
{
    /**
     * Find vendor by ID/Vendor Number/Mobile/Email/Name identifier
     */
    public function findByIdentifier(string $identifier): ?Vendor
    {
        $identifier = trim($identifier);

        $query = Vendor::query();

        // Exact identifier matches
        $query->where('id', $identifier)
            ->orWhere('vendor_number', $identifier)
            ->orWhere('mobile_no', $identifier)
            ->orWhere('contact_mobile_no', $identifier)
            ->orWhere('gmail_id', $identifier)
            ->orWhere('contact_gmail_id', $identifier);

        // Partial matches for name/business fields
        $query->orWhere('business_name', 'like', '%'.$identifier.'%');
        if (Schema::hasColumn('vendors', 'contact_person')) {
            $query->orWhere('contact_person', 'like', '%'.$identifier.'%');
        }
        if (Schema::hasColumn('vendors', 'vendor_name')) {
            $query->orWhere('vendor_name', 'like', '%'.$identifier.'%');
        }
        if (Schema::hasColumn('vendors', 'first_name')) {
            $query->orWhere(function ($q) use ($identifier) {
                $q->where('first_name', 'like', '%'.$identifier.'%');
                if (Schema::hasColumn('vendors', 'middle_name')) {
                    $q->orWhere('middle_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'last_name')) {
                    $q->orWhere('last_name', 'like', '%'.$identifier.'%');
                }
            });
        }

        return $query->first();
    }

    /**
     * Get current wallet balance for a vendor
     */
    public function getBalance(int $vendorId): float
    {
        $vendor = Vendor::find($vendorId);

        return (float) ($vendor->wallet_balance ?? 0);
    }

    /**
     * Process a wallet transaction and persist audit trail
     */
    public function processTransaction(
        int $vendorId,
        string $transactionType,
        float $amount,
        ?string $narration,
        ?int $performedByUserId = null
    ): VendorWalletTransaction {
        if (! in_array($transactionType, ['add', 'remove'])) {
            throw new InvalidArgumentException('Invalid transaction type.');
        }
        if ($amount <= 0) {
            throw new InvalidArgumentException('Transaction amount must be greater than zero.');
        }

        return DB::transaction(function () use ($vendorId, $transactionType, $amount, $narration, $performedByUserId) {
            $vendor = Vendor::lockForUpdate()->findOrFail($vendorId);
            $currentBalance = (float) ($vendor->wallet_balance ?? 0);

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
            $transaction = new VendorWalletTransaction([
                'vendor_id' => $vendor->id,
                'transaction_type' => $transactionType,
                'amount' => $amount,
                'previous_balance' => $currentBalance,
                'new_balance' => $newBalance,
                'narration' => $narration,
                'performed_by_user_id' => $performedByUserId,
            ]);
            $transaction->save();

            // Update wallet balance on vendor
            $vendor->update(['wallet_balance' => $newBalance]);

            return $transaction;
        });
    }
}
