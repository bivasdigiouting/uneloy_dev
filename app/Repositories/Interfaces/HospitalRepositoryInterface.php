<?php

namespace App\Repositories\Interfaces;

use App\Models\Hospital;

interface HospitalRepositoryInterface
{
    public function getHospital(): ?Hospital;

    public function update(array $data): Hospital;

    public function createIfNotExists(): Hospital;
}
