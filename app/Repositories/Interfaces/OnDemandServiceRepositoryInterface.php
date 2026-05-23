<?php

namespace App\Repositories\Interfaces;

use App\Models\OnDemandService;

interface OnDemandServiceRepositoryInterface
{
    /**
     * Get the on demand service data.
     */
    public function getOnDemandService(): ?OnDemandService;

    /**
     * Update on demand service information.
     */
    public function update(array $data): OnDemandService;

    /**
     * Create on demand service record if it doesn't exist.
     */
    public function createIfNotExists(): OnDemandService;
}
