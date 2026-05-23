<?php

namespace App\Repositories;

use App\Models\RealEstateBusiness;
use App\Repositories\Interfaces\RealEstateBusinessRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class RealEstateBusinessRepository implements RealEstateBusinessRepositoryInterface
{
    /**
     * Get the real estate business data.
     */
    public function getRealEstateBusiness(): ?RealEstateBusiness
    {
        return RealEstateBusiness::first();
    }

    /**
     * Update real estate business information.
     */
    public function update(array $data): RealEstateBusiness
    {
        $realEstateBusiness = $this->getRealEstateBusiness();

        if (! $realEstateBusiness) {
            $realEstateBusiness = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($realEstateBusiness->image && Storage::disk('public')->exists($realEstateBusiness->image)) {
                Storage::disk('public')->delete($realEstateBusiness->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('real_estate_business', 'public');
        }

        $realEstateBusiness->update($data);

        return $realEstateBusiness->fresh();
    }

    /**
     * Create real estate business record if it doesn't exist.
     */
    public function createIfNotExists(): RealEstateBusiness
    {
        return RealEstateBusiness::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
