<?php

namespace App\Repositories\Interfaces;

use App\Models\Vendor;
use App\Models\VendorWalletTransaction;

interface VendorWalletRepositoryInterface
{
    /**
     * Find vendor by ID/Vendor Number/Mobile/Email/Name identifier
     */
    public function findByIdentifier(string $identifier): ?Vendor;

    /**
     * Get current wallet balance for a vendor
     */
    public function getBalance(int $vendorId): float;

    /**
     * Process a wallet transaction for vendor
     */
    public function processTransaction(
        int $vendorId,
        string $transactionType,
        float $amount,
        ?string $narration,
        ?int $performedByUserId = null
    ): VendorWalletTransaction;
}
