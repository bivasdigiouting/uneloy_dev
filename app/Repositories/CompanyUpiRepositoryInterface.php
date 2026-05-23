<?php

namespace App\Repositories;

use App\Models\CompanyUpi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyUpiRepositoryInterface
{
    /**
     * Get paginated company UPIs
     */
    public function getPaginatedCompanyUpis(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active company UPIs
     */
    public function getActiveCompanyUpis(): Collection;

    /**
     * Find company UPI by ID
     */
    public function findCompanyUpi(int $id): ?CompanyUpi;

    /**
     * Create new company UPI
     */
    public function createCompanyUpi(array $data): CompanyUpi;

    /**
     * Update company UPI
     */
    public function updateCompanyUpi(int $id, array $data): bool;

    /**
     * Delete company UPI
     */
    public function deleteCompanyUpi(int $id): bool;

    /**
     * Get company UPIs for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle company UPI status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get company UPI count
     */
    public function getCompanyUpiCount(): int;

    /**
     * Get active company UPI count
     */
    public function getActiveCompanyUpiCount(): int;
}
