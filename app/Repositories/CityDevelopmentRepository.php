<?php

namespace App\Repositories;

use App\Models\CityDevelopment;
use App\Repositories\Interfaces\CityDevelopmentRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CityDevelopmentRepository implements CityDevelopmentRepositoryInterface
{
    /**
     * Get the city development data.
     */
    public function getCityDevelopment(): ?CityDevelopment
    {
        return CityDevelopment::first();
    }

    /**
     * Update city development information.
     */
    public function update(array $data): CityDevelopment
    {
        $cityDevelopment = $this->getCityDevelopment();

        if (! $cityDevelopment) {
            $cityDevelopment = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($cityDevelopment->image && Storage::disk('public')->exists($cityDevelopment->image)) {
                Storage::disk('public')->delete($cityDevelopment->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('city_development', 'public');
        }

        $cityDevelopment->update($data);

        return $cityDevelopment->fresh();
    }

    /**
     * Create city development record if it doesn't exist.
     */
    public function createIfNotExists(): CityDevelopment
    {
        return CityDevelopment::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
