<?php

namespace App\Repositories\Interfaces;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Builder;

interface AdvertisementRepositoryInterface
{
    /**
     * Base query for DataTables
     */
    public function getForDataTable(): Builder;

    /** Find advertisement by ID */
    public function findById(int $id): ?Advertisement;

    /** Create new advertisement */
    public function create(array $data): Advertisement;

    /** Update advertisement */
    public function update(int $id, array $data): bool;

    /** Delete advertisement */
    public function delete(int $id): bool;

    /** Toggle active/inactive */
    public function toggleStatus(int $id): bool;
}
