<?php

namespace App\Repositories\Interfaces;

use App\Models\RechargeOperator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RechargeOperatorRepositoryInterface
{
    /** Get all operators */
    public function all(): Collection;

    /** Paginate operators (with related service) */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /** Find by ID */
    public function find(int $id): ?RechargeOperator;

    /** Create */
    public function create(array $data): RechargeOperator;

    /** Update */
    public function update(int $id, array $data): bool;

    /** Delete */
    public function delete(int $id): bool;

    /** Find by operator code */
    public function findByCode(string $code): ?RechargeOperator;

    /** Get operators by service */
    public function getByServiceId(int $serviceId): Collection;

    /** Toggle status */
    public function toggleStatus(int $id): bool;
}
