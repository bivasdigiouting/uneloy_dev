<?php

namespace App\Repositories;

use App\Models\Marketplace;
use App\Repositories\Interfaces\MarketplaceRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class MarketplaceRepository implements MarketplaceRepositoryInterface
{
    /**
     * Get the marketplace data.
     */
    public function getMarketplace(): ?Marketplace
    {
        return Marketplace::first();
    }

    /**
     * Update marketplace information.
     */
    public function update(array $data): Marketplace
    {
        $marketplace = $this->getMarketplace();

        if (! $marketplace) {
            $marketplace = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($marketplace->image && Storage::disk('public')->exists($marketplace->image)) {
                Storage::disk('public')->delete($marketplace->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('marketplace', 'public');
        }

        $marketplace->update($data);

        return $marketplace->fresh();
    }

    /**
     * Create marketplace record if it doesn't exist.
     */
    public function createIfNotExists(): Marketplace
    {
        return Marketplace::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
