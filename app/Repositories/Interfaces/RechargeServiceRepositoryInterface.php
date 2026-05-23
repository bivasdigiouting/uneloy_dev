<?php

namespace App\Repositories\Interfaces;

use App\Models\RechargeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RechargeServiceRepositoryInterface
{
    /** Get all services */
    public function all(): Collection;

    /** Paginate services */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /** Find by ID */
    public function find(int $id): ?RechargeService;

    /** Create */
    public function create(array $data): RechargeService;

    /** Update */
    public function update(int $id, array $data): bool;

    /** Delete */
    public function delete(int $id): bool;

    /** Find by service code */
    public function findByCode(string $code): ?RechargeService;

    /** Get active */
    public function getActive(): Collection;

    /** Toggle status */
    public function toggleStatus(int $id): bool;
}
