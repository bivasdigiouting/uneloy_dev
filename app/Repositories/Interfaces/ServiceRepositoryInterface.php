<?php

namespace App\Repositories\Interfaces;

use App\Models\Service;

interface ServiceRepositoryInterface
{
    public function getForDataTable();

    public function findById(int $id): ?Service;

    public function create(array $data): Service;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
