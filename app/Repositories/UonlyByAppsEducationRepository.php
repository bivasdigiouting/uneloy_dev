<?php

namespace App\Repositories;

use App\Models\UonlyByAppsEducation;
use App\Repositories\Interfaces\UonlyByAppsEducationRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class UonlyByAppsEducationRepository implements UonlyByAppsEducationRepositoryInterface
{
    public function getEducation(): ?UonlyByAppsEducation
    {
        return UonlyByAppsEducation::first();
    }

    public function update(array $data): UonlyByAppsEducation
    {
        $education = $this->getEducation();

        if (! $education) {
            $education = $this->createIfNotExists();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($education->image && Storage::disk('public')->exists($education->image)) {
                Storage::disk('public')->delete($education->image);
            }

            $data['image'] = $data['image']->store('uonly-by-apps/education', 'public');
        }

        $education->update($data);

        return $education->fresh();
    }

    public function createIfNotExists(): UonlyByAppsEducation
    {
        return UonlyByAppsEducation::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
