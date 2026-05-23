<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\UploadedFile;

interface CampDetailRepositoryInterface
{
    public function getForDataTable();

    public function findById(int $id);

    public function create(array $data, ?UploadedFile $banner = null);

    public function update(int $id, array $data, ?UploadedFile $banner = null);

    public function delete(int $id): bool;
}
