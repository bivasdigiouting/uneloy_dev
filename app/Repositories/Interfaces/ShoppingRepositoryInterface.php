<?php

namespace App\Repositories\Interfaces;

use App\Models\Shopping;

interface ShoppingRepositoryInterface
{
    public function getShopping(): ?Shopping;

    public function update(array $data): Shopping;

    public function createIfNotExists(): Shopping;
}
