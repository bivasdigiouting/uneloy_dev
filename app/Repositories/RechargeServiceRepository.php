<?php

namespace App\Repositories;

use App\Models\RechargeService;
use App\Repositories\Interfaces\RechargeServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RechargeServiceRepository implements RechargeServiceRepositoryInterface
{
    protected RechargeService $model;

    public function __construct(RechargeService $model)
    {
        $this->model = $model;
    }

    /** Get all services */
    public function all(): Collection
    {
        return $this->model->orderBy('service_name')->get();
    }

    /** Paginate services */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /** Find by ID */
    public function find(int $id): ?RechargeService
    {
        return $this->model->find($id);
    }

    /** Create */
    public function create(array $data): RechargeService
    {
        return $this->model->create($data);
    }

    /** Update */
    public function update(int $id, array $data): bool
    {
        $service = $this->find($id);
        if (! $service) {
            return false;
        }

        return $service->update($data);
    }

    /** Delete */
    public function delete(int $id): bool
    {
        $service = $this->find($id);
        if (! $service) {
            return false;
        }

        return $service->delete();
    }

    /** Find by service code */
    public function findByCode(string $code): ?RechargeService
    {
        return $this->model->where('service_code', $code)->first();
    }

    /** Get active */
    public function getActive(): Collection
    {
        return $this->model->active()->orderBy('service_name')->get();
    }

    /** Toggle status */
    public function toggleStatus(int $id): bool
    {
        $service = $this->find($id);
        if (! $service) {
            return false;
        }

        return $service->update(['is_active' => ! $service->is_active]);
    }
}
