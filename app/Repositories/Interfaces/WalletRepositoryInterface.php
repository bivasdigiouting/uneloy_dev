<?php

namespace App\Repositories\Interfaces;

use App\Models\Registration;
use App\Models\WalletTransaction;

interface WalletRepositoryInterface
{
    /**
     * Find registration by ID/Aadhaar/Mobile identifier
     */
    public function findByIdentifier(string $identifier): ?Registration;

    /**
     * Get current wallet balance for a registration
     */
    public function getBalance(int $registrationId): float;

    /**
     * Process a wallet transaction and persist audit trail
     */
    public function processTransaction(
        int $registrationId,
        string $transactionType,
        float $amount,
        ?string $narration,
        ?int $performedByUserId = null
    ): WalletTransaction;
}
