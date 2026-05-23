<?php

namespace App\Repositories;

use App\Models\UonlyByAppsUMart;
use App\Repositories\Interfaces\UonlyByAppsUMartRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class UonlyByAppsUMartRepository implements UonlyByAppsUMartRepositoryInterface
{
    public function getUMart(): ?UonlyByAppsUMart
    {
        return UonlyByAppsUMart::first();
    }

    public function update(array $data): UonlyByAppsUMart
    {
        $uMart = $this->getUMart();

        if (! $uMart) {
            $uMart = $this->createIfNotExists();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($uMart->image && Storage::disk('public')->exists($uMart->image)) {
                Storage::disk('public')->delete($uMart->image);
            }

            $data['image'] = $data['image']->store('uonly-by-apps/u-mart', 'public');
        }

        $uMart->update($data);

        return $uMart->fresh();
    }

    public function createIfNotExists(): UonlyByAppsUMart
    {
        return UonlyByAppsUMart::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
