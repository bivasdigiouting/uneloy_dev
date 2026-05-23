<?php

namespace App\Repositories\Interfaces;

use App\Models\UonlyByAppsUAdmission;

interface UonlyByAppsUAdmissionRepositoryInterface
{
    public function getUAdmission(): ?UonlyByAppsUAdmission;

    public function update(array $data): UonlyByAppsUAdmission;

    public function createIfNotExists(): UonlyByAppsUAdmission;
}
