<?php

namespace App\Repositories\Interfaces;

use App\Models\Camp;

interface CampRepositoryInterface
{
    public function getForDataTable();

    public function findById(int $id): ?Camp;

    public function create(array $data): Camp;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function toggleStatus(int $id): bool;
}
