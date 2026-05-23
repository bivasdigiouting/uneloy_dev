<?php

namespace App\Repositories\Interfaces;

use App\Models\SocialMedia;
use Illuminate\Database\Eloquent\Builder;

interface SocialMediaRepositoryInterface
{
    /** Base query for DataTables */
    public function getForDataTable(): Builder;

    /** Find by ID */
    public function findById(int $id): ?SocialMedia;

    /** Create */
    public function create(array $data): SocialMedia;

    /** Update */
    public function update(int $id, array $data): bool;

    /** Delete */
    public function delete(int $id): bool;

    /** Toggle active/inactive */
    public function toggleStatus(int $id): bool;
}
