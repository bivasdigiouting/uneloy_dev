<?php

namespace App\Repositories;

use App\Models\Education;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class EducationRepository implements EducationRepositoryInterface
{
    /**
     * Get the education data.
     */
    public function getEducation(): ?Education
    {
        return Education::first();
    }

    /**
     * Update education information.
     */
    public function update(array $data): Education
    {
        $education = $this->getEducation();

        if (! $education) {
            $education = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($education->image && Storage::disk('public')->exists($education->image)) {
                Storage::disk('public')->delete($education->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('education', 'public');
        }

        $education->update($data);

        return $education->fresh();
    }

    /**
     * Create education record if it doesn't exist.
     */
    public function createIfNotExists(): Education
    {
        return Education::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
