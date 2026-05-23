<?php

namespace App\Repositories;

use App\Models\ExpenseBill;
use App\Repositories\Interfaces\ExpenseBillRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseBillRepository implements ExpenseBillRepositoryInterface
{
    protected ExpenseBill $model;

    public function __construct(ExpenseBill $model)
    {
        $this->model = $model;
    }

    /**
     * Get all expense bills
     */
    public function all(): Collection
    {
        return $this->model->with('expense')->orderBy('date', 'desc')->get();
    }

    /**
     * Get paginated expense bills
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('expense')
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find expense bill by ID
     */
    public function find(int $id): ?ExpenseBill
    {
        return $this->model->with('expense')->find($id);
    }

    /**
     * Create new expense bill
     */
    public function create(array $data): ExpenseBill
    {
        return $this->model->create($data);
    }

    /**
     * Update expense bill
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete expense bill
     */
    public function delete(int $id): bool
    {
        $expenseBill = $this->find($id);
        if ($expenseBill) {
            return $expenseBill->delete();
        }

        return false;
    }

    /**
     * Get active expense bills
     */
    public function getActive(): Collection
    {
        return $this->model->with('expense')
            ->active()
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Toggle expense bill status
     */
    public function toggleStatus(int $id): bool
    {
        $expenseBill = $this->find($id);
        if ($expenseBill) {
            return $expenseBill->update(['status' => ! $expenseBill->status]);
        }

        return false;
    }

    /**
     * Get expense bills by payment mode
     */
    public function getByPaymentMode(string $paymentMode): Collection
    {
        return $this->model->with('expense')
            ->byPaymentMode($paymentMode)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get expense bills by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->with('expense')
            ->dateRange($startDate, $endDate)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get expense bills by expense ID
     */
    public function getByExpenseId(int $expenseId): Collection
    {
        return $this->model->with('expense')
            ->where('expense_id', $expenseId)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get total amount by payment mode
     */
    public function getTotalAmountByPaymentMode(string $paymentMode): float
    {
        return $this->model->where('payment_mode', $paymentMode)
            ->where('status', true)
            ->sum('amount');
    }

    /**
     * Search expense bills
     */
    public function search(string $query): Collection
    {
        return $this->model->with('expense')
            ->where(function ($q) use ($query) {
                $q->where('bill_no', 'LIKE', "%{$query}%")
                    ->orWhere('supplier', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhereHas('expense', function ($expenseQuery) use ($query) {
                        $expenseQuery->where('expense_name', 'LIKE', "%{$query}%");
                    });
            })
            ->orderBy('date', 'desc')
            ->get();
    }
}
