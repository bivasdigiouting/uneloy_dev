<?php

namespace App\Repositories;

use App\Models\Affiliate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AffiliateRepositoryInterface
{
    /**
     * Get paginated affiliates
     */
    public function getPaginatedAffiliates(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active affiliates
     */
    public function getActiveAffiliates(): Collection;

    /**
     * Find affiliate by ID
     */
    public function findAffiliate(int $id): ?Affiliate;

    /**
     * Create new affiliate
     */
    public function createAffiliate(array $data): Affiliate;

    /**
     * Update affiliate
     */
    public function updateAffiliate(int $id, array $data): bool;

    /**
     * Delete affiliate
     */
    public function deleteAffiliate(int $id): bool;

    /**
     * Get affiliates for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle affiliate status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get all affiliates ordered by service name
     */
    public function getAllOrdered(): Collection;
}
