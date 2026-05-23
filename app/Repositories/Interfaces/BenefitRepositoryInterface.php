<?php

namespace App\Repositories\Interfaces;

use App\Models\Benefit;

interface BenefitRepositoryInterface
{
    public function getForDataTable();

    public function findById(int $id): ?Benefit;

    public function create(array $data): Benefit;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
