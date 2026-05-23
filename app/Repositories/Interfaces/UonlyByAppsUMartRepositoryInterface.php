<?php

namespace App\Repositories\Interfaces;

use App\Models\UonlyByAppsUMart;

interface UonlyByAppsUMartRepositoryInterface
{
    public function getUMart(): ?UonlyByAppsUMart;

    public function update(array $data): UonlyByAppsUMart;

    public function createIfNotExists(): UonlyByAppsUMart;
}
