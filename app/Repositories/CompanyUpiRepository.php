<?php

namespace App\Repositories;

use App\Models\CompanyUpi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyUpiRepository implements CompanyUpiRepositoryInterface
{
    protected CompanyUpi $model;

    public function __construct(CompanyUpi $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated company UPIs
     */
    public function getPaginatedCompanyUpis(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get active company UPIs
     */
    public function getActiveCompanyUpis(): Collection
    {
        return $this->model->active()->orderBy('upi_id')->get();
    }

    /**
     * Find company UPI by ID
     */
    public function findCompanyUpi(int $id): ?CompanyUpi
    {
        return $this->model->find($id);
    }

    /**
     * Create new company UPI
     */
    public function createCompanyUpi(array $data): CompanyUpi
    {
        return $this->model->create($data);
    }

    /**
     * Update company UPI
     */
    public function updateCompanyUpi(int $id, array $data): bool
    {
        $companyUpi = $this->findCompanyUpi($id);

        if (! $companyUpi) {
            return false;
        }

        return $companyUpi->update($data);
    }

    /**
     * Delete company UPI
     */
    public function deleteCompanyUpi(int $id): bool
    {
        $companyUpi = $this->findCompanyUpi($id);

        if (! $companyUpi) {
            return false;
        }

        return $companyUpi->delete();
    }

    /**
     * Get company UPIs for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Toggle company UPI status
     */
    public function toggleStatus(int $id): bool
    {
        $companyUpi = $this->findCompanyUpi($id);

        if (! $companyUpi) {
            return false;
        }

        $newStatus = $companyUpi->status === 'active' ? 'inactive' : 'active';

        return $companyUpi->update(['status' => $newStatus]);
    }

    /**
     * Get company UPI count
     */
    public function getCompanyUpiCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get active company UPI count
     */
    public function getActiveCompanyUpiCount(): int
    {
        return $this->model->active()->count();
    }
}
