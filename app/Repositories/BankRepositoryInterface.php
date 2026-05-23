<?php

namespace App\Repositories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BankRepositoryInterface
{
    /**
     * Get paginated banks
     */
    public function getPaginatedBanks(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active banks
     */
    public function getActiveBanks(): Collection;

    /**
     * Find bank by ID
     */
    public function findBank(int $id): ?Bank;

    /**
     * Create new bank
     */
    public function createBank(array $data): Bank;

    /**
     * Update bank
     */
    public function updateBank(int $id, array $data): bool;

    /**
     * Delete bank
     */
    public function deleteBank(int $id): bool;

    /**
     * Get banks for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle bank status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get bank count
     */
    public function getBankCount(): int;

    /**
     * Get active bank count
     */
    public function getActiveBankCount(): int;
}
