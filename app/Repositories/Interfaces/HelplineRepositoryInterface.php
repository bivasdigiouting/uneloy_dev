<?php

namespace App\Repositories\Interfaces;

use App\Models\Helpline;

interface HelplineRepositoryInterface
{
    public function getForDataTable();

    public function findById(int $id): ?Helpline;

    public function create(array $data): Helpline;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
