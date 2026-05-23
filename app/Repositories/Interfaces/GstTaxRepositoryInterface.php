<?php

namespace App\Repositories\Interfaces;

use App\Models\GstTax;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface GstTaxRepositoryInterface
{
    /** Base query for DataTables */
    public function getForDataTable(): Builder;

    /** Get active GST taxes */
    public function getActive(): Collection;

    /** Find by ID */
    public function findById(int $id): ?GstTax;

    /** Create */
    public function create(array $data): GstTax;

    /** Update */
    public function update(int $id, array $data): bool;

    /** Delete */
    public function delete(int $id): bool;

    /** Toggle active/inactive */
    public function toggleStatus(int $id): bool;
}
