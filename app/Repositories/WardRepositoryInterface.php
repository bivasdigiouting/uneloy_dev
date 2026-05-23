<?php

namespace App\Repositories;

use App\Models\Ward;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface WardRepositoryInterface
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    public function getActive(): Collection;

    public function find(int $id): ?Ward;

    public function create(array $data): Ward;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getForDataTables();

    public function toggleStatus(int $id): bool;

    public function getByMunicipality(int $municipalityId): Collection;
}
