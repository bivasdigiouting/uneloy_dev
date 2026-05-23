<?php

namespace App\Repositories;

use App\Models\Municipality;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface MunicipalityRepositoryInterface
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    public function getActive(): Collection;

    public function find(int $id): ?Municipality;

    public function create(array $data): Municipality;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getForDataTables();

    public function toggleStatus(int $id): bool;

    public function getByCity(int $cityId): Collection;
}
