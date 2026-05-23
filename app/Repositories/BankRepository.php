<?php

namespace App\Repositories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BankRepository implements BankRepositoryInterface
{
    protected Bank $model;

    public function __construct(Bank $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated banks
     */
    public function getPaginatedBanks(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('bank_name')->paginate($perPage);
    }

    /**
     * Get active banks
     */
    public function getActiveBanks(): Collection
    {
        return $this->model->active()->orderBy('bank_name')->get();
    }

    /**
     * Find bank by ID
     */
    public function findBank(int $id): ?Bank
    {
        return $this->model->find($id);
    }

    /**
     * Create new bank
     */
    public function createBank(array $data): Bank
    {
        return $this->model->create($data);
    }

    /**
     * Update bank
     */
    public function updateBank(int $id, array $data): bool
    {
        $bank = $this->findBank($id);
        if (! $bank) {
            return false;
        }

        return $bank->update($data);
    }

    /**
     * Delete bank
     */
    public function deleteBank(int $id): bool
    {
        $bank = $this->findBank($id);
        if (! $bank) {
            return false;
        }

        return $bank->delete();
    }

    /**
     * Get banks for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select([
            'id',
            'bank_name',
            'status',
            'created_at',
        ])->orderBy('bank_name');
    }

    /**
     * Toggle bank status
     */
    public function toggleStatus(int $id): bool
    {
        $bank = $this->findBank($id);
        if (! $bank) {
            return false;
        }

        $newStatus = $bank->status === 'active' ? 'inactive' : 'active';

        return $bank->update(['status' => $newStatus]);
    }

    /**
     * Get bank count
     */
    public function getBankCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get active bank count
     */
    public function getActiveBankCount(): int
    {
        return $this->model->active()->count();
    }
}
