<?php

namespace App\Repositories\Interfaces;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ExpenseRepositoryInterface
{
    /**
     * Get all expenses
     */
    public function all(): Collection;

    /**
     * Get paginated expenses
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find expense by ID
     */
    public function find(int $id): ?Expense;

    /**
     * Create new expense
     */
    public function create(array $data): Expense;

    /**
     * Update expense
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete expense
     */
    public function delete(int $id): bool;

    /**
     * Find expense by name
     */
    public function findByName(string $name): ?Expense;

    /**
     * Get active expenses
     */
    public function getActive(): Collection;

    /**
     * Toggle expense status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get expenses by amount range
     */
    public function getByAmountRange(float $minAmount, float $maxAmount): Collection;

    /**
     * Get total expense amount
     */
    public function getTotalAmount(): float;
}
