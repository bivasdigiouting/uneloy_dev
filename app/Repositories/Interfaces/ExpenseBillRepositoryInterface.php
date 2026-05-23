<?php

namespace App\Repositories\Interfaces;

use App\Models\ExpenseBill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ExpenseBillRepositoryInterface
{
    /**
     * Get all expense bills
     */
    public function all(): Collection;

    /**
     * Get paginated expense bills
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find expense bill by ID
     */
    public function find(int $id): ?ExpenseBill;

    /**
     * Create new expense bill
     */
    public function create(array $data): ExpenseBill;

    /**
     * Update expense bill
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete expense bill
     */
    public function delete(int $id): bool;

    /**
     * Get active expense bills
     */
    public function getActive(): Collection;

    /**
     * Toggle expense bill status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get expense bills by payment mode
     */
    public function getByPaymentMode(string $paymentMode): Collection;

    /**
     * Get expense bills by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get expense bills by expense ID
     */
    public function getByExpenseId(int $expenseId): Collection;

    /**
     * Get total amount by payment mode
     */
    public function getTotalAmountByPaymentMode(string $paymentMode): float;

    /**
     * Search expense bills
     */
    public function search(string $query): Collection;
}
