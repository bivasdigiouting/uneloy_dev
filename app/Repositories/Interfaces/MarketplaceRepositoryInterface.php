<?php

namespace App\Repositories\Interfaces;

use App\Models\Marketplace;

interface MarketplaceRepositoryInterface
{
    /**
     * Get the marketplace data.
     */
    public function getMarketplace(): ?Marketplace;

    /**
     * Update marketplace information.
     */
    public function update(array $data): Marketplace;

    /**
     * Create marketplace record if it doesn't exist.
     */
    public function createIfNotExists(): Marketplace;
}
