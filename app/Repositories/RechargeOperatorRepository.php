<?php

namespace App\Repositories;

use App\Models\RechargeOperator;
use App\Repositories\Interfaces\RechargeOperatorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RechargeOperatorRepository implements RechargeOperatorRepositoryInterface
{
    protected RechargeOperator $model;

    public function __construct(RechargeOperator $model)
    {
        $this->model = $model;
    }

    /** Get all operators */
    public function all(): Collection
    {
        return $this->model->with('service')->orderBy('operator_name')->get();
    }

    /** Paginate operators with service relation */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('service')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /** Find by ID */
    public function find(int $id): ?RechargeOperator
    {
        return $this->model->with('service')->find($id);
    }

    /** Create */
    public function create(array $data): RechargeOperator
    {
        return $this->model->create($data);
    }

    /** Update */
    public function update(int $id, array $data): bool
    {
        $operator = $this->model->find($id);
        if (! $operator) {
            return false;
        }

        return $operator->update($data);
    }

    /** Delete */
    public function delete(int $id): bool
    {
        $operator = $this->model->find($id);
        if (! $operator) {
            return false;
        }

        return $operator->delete();
    }

    /** Find by operator code */
    public function findByCode(string $code): ?RechargeOperator
    {
        return $this->model->where('operator_code', $code)->first();
    }

    /** Get operators by service */
    public function getByServiceId(int $serviceId): Collection
    {
        return $this->model->where('recharge_service_id', $serviceId)->orderBy('operator_name')->get();
    }

    /** Toggle status */
    public function toggleStatus(int $id): bool
    {
        $operator = $this->model->find($id);
        if (! $operator) {
            return false;
        }

        return $operator->update(['is_active' => ! $operator->is_active]);
    }
}
