<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected Expense $model;

    public function __construct(Expense $model)
    {
        $this->model = $model;
    }

    /**
     * Get all expenses
     */
    public function all(): Collection
    {
        return $this->model->orderBy('expense_name')->get();
    }

    /**
     * Get paginated expenses
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('expense_name')
            ->paginate($perPage);
    }

    /**
     * Find expense by ID
     */
    public function find(int $id): ?Expense
    {
        return $this->model->find($id);
    }

    /**
     * Create new expense
     */
    public function create(array $data): Expense
    {
        return $this->model->create($data);
    }

    /**
     * Update expense
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete expense
     */
    public function delete(int $id): bool
    {
        $expense = $this->find($id);
        if ($expense && $expense->canBeDeleted()) {
            return $expense->delete();
        }

        return false;
    }

    /**
     * Find expense by name
     */
    public function findByName(string $name): ?Expense
    {
        return $this->model->where('expense_name', $name)->first();
    }

    /**
     * Get active expenses
     */
    public function getActive(): Collection
    {
        return $this->model->active()->orderBy('expense_name')->get();
    }

    /**
     * Toggle expense status
     */
    public function toggleStatus(int $id): bool
    {
        $expense = $this->find($id);
        if ($expense) {
            return $expense->update(['is_active' => ! $expense->is_active]);
        }

        return false;
    }

    /**
     * Get expenses by amount range
     */
    public function getByAmountRange(float $minAmount, float $maxAmount): Collection
    {
        return $this->model->whereBetween('amount', [$minAmount, $maxAmount])
            ->orderBy('expense_name')
            ->get();
    }

    /**
     * Get total expense amount
     */
    public function getTotalAmount(): float
    {
        return $this->model->where('is_active', true)->sum('amount');
    }

    /**
     * Search expenses by name or description
     */
    public function search(string $query): Collection
    {
        return $this->model->where('expense_name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('expense_name')
            ->get();
    }
}
