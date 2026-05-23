<?php

namespace App\Repositories;

use App\Models\BusinessFocus;
use App\Repositories\Interfaces\BusinessFocusRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BusinessFocusRepository implements BusinessFocusRepositoryInterface
{
    protected BusinessFocus $model;

    public function __construct(BusinessFocus $model)
    {
        $this->model = $model;
    }

    /**
     * Get the business focus record (should be only one)
     */
    public function getBusinessFocus(): ?BusinessFocus
    {
        return $this->model->first();
    }

    /**
     * Update business focus information
     */
    public function update(array $data): BusinessFocus
    {
        $businessFocus = $this->getBusinessFocus();

        if (! $businessFocus) {
            $businessFocus = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            // Delete old image if exists
            if ($businessFocus->image && Storage::disk('public')->exists($businessFocus->image)) {
                Storage::disk('public')->delete($businessFocus->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('business-focus-images', 'public');
        }

        $businessFocus->update($data);

        return $businessFocus->fresh();
    }

    /**
     * Create business focus record if it doesn't exist
     */
    public function createIfNotExists(): BusinessFocus
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
