<?php

namespace App\Repositories;

use App\Models\Village;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface VillageRepositoryInterface
{
    public function getPaginatedVillages(int $perPage = 15): LengthAwarePaginator;

    public function getActiveVillages(): Collection;

    public function findVillage(int $id): ?Village;

    public function createVillage(array $data): Village;

    public function updateVillage(int $id, array $data): bool;

    public function deleteVillage(int $id): bool;

    public function getForDataTables();

    public function toggleStatus(int $id): bool;

    public function getVillagesByCity(int $cityId): Collection;

    public function findByNameAndCity(string $name, int $cityId): ?Village;
}
