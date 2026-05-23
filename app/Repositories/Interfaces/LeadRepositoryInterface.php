<?php

namespace App\Repositories\Interfaces;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;

interface LeadRepositoryInterface
{
    /** Base query for DataTables */
    public function getForDataTable(): Builder;

    /** Find lead by ID */
    public function findById(int $id): ?Lead;

    /** Create new lead */
    public function create(array $data): Lead;

    /** Update lead */
    public function update(int $id, array $data): bool;

    /** Delete lead */
    public function delete(int $id): bool;

    /** Toggle active/inactive */
    public function toggleStatus(int $id): bool;
}
