<?php

namespace App\Repositories\Interfaces;

use App\Models\UonlyByAppsEducation;

interface UonlyByAppsEducationRepositoryInterface
{
    public function getEducation(): ?UonlyByAppsEducation;

    public function update(array $data): UonlyByAppsEducation;

    public function createIfNotExists(): UonlyByAppsEducation;
}
