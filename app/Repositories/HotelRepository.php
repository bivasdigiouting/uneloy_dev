<?php

namespace App\Repositories;

use App\Models\Hotel;
use App\Repositories\Interfaces\HotelRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class HotelRepository implements HotelRepositoryInterface
{
    /**
     * Get the hotel data.
     */
    public function getHotel(): ?Hotel
    {
        return Hotel::first();
    }

    /**
     * Update hotel information.
     */
    public function update(array $data): Hotel
    {
        $hotel = $this->getHotel();

        if (! $hotel) {
            $hotel = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($hotel->image && Storage::disk('public')->exists($hotel->image)) {
                Storage::disk('public')->delete($hotel->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('hotels', 'public');
        }

        $hotel->update($data);

        return $hotel->fresh();
    }

    /**
     * Create hotel record if it doesn't exist.
     */
    public function createIfNotExists(): Hotel
    {
        return Hotel::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
