<?php

namespace App\Repositories\Interfaces;

use App\Models\RealEstateBusiness;

interface RealEstateBusinessRepositoryInterface
{
    /**
     * Get the real estate business data.
     */
    public function getRealEstateBusiness(): ?RealEstateBusiness;

    /**
     * Update real estate business information.
     */
    public function update(array $data): RealEstateBusiness;

    /**
     * Create real estate business record if it doesn't exist.
     */
    public function createIfNotExists(): RealEstateBusiness;
}
