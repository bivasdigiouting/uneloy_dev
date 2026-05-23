<?php

namespace App\Repositories\Interfaces;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface VendorRepositoryInterface
{
    /**
     * Get paginated vendors
     */
    public function getPaginatedVendors(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active vendors
     */
    public function getActiveVendors(): Collection;

    /**
     * Find vendor by ID
     */
    public function findVendor(int $id): ?Vendor;

    /**
     * Create new vendor
     */
    public function createVendor(array $data): Vendor;

    /**
     * Update vendor
     */
    public function updateVendor(int $id, array $data): bool;

    /**
     * Delete vendor
     */
    public function deleteVendor(int $id): bool;

    /**
     * Get vendors for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle vendor status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get vendor count
     */
    public function getVendorCount(): int;

    /**
     * Get active vendor count
     */
    public function getActiveVendorCount(): int;

    /**
     * Get inactive vendor count
     */
    public function getInactiveVendorCount(): int;

    /**
     * Get vendors by status
     */
    public function getVendorsByStatus(string $status): Collection;
}
