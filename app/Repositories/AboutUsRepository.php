<?php

namespace App\Repositories;

use App\Models\AboutUs;
use App\Repositories\Interfaces\AboutUsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class AboutUsRepository implements AboutUsRepositoryInterface
{
    protected AboutUs $model;

    public function __construct(AboutUs $model)
    {
        $this->model = $model;
    }

    /**
     * Get the about us record (should be only one)
     */
    public function getAboutUs(): ?AboutUs
    {
        return $this->model->first();
    }

    /**
     * Update about us information
     */
    public function update(array $data): AboutUs
    {
        $aboutUs = $this->getAboutUs();

        if (! $aboutUs) {
            $aboutUs = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            // Delete old image if exists
            if ($aboutUs->image && Storage::disk('public')->exists($aboutUs->image)) {
                Storage::disk('public')->delete($aboutUs->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('about-us-images', 'public');
        }

        $aboutUs->update($data);

        return $aboutUs->fresh();
    }

    /**
     * Create about us record if it doesn't exist
     */
    public function createIfNotExists(): AboutUs
    {
        return $this->model->firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
