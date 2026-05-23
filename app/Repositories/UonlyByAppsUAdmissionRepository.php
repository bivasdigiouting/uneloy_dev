<?php

namespace App\Repositories;

use App\Models\UonlyByAppsUAdmission;
use App\Repositories\Interfaces\UonlyByAppsUAdmissionRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class UonlyByAppsUAdmissionRepository implements UonlyByAppsUAdmissionRepositoryInterface
{
    public function getUAdmission(): ?UonlyByAppsUAdmission
    {
        return UonlyByAppsUAdmission::first();
    }

    public function update(array $data): UonlyByAppsUAdmission
    {
        $uAdmission = $this->getUAdmission();

        if (! $uAdmission) {
            $uAdmission = $this->createIfNotExists();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($uAdmission->image && Storage::disk('public')->exists($uAdmission->image)) {
                Storage::disk('public')->delete($uAdmission->image);
            }

            $data['image'] = $data['image']->store('uonly-by-apps/u-admission', 'public');
        }

        $uAdmission->update($data);

        return $uAdmission->fresh();
    }

    public function createIfNotExists(): UonlyByAppsUAdmission
    {
        return UonlyByAppsUAdmission::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
