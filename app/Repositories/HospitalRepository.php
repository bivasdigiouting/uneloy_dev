<?php

namespace App\Repositories;

use App\Models\Hospital;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class HospitalRepository implements HospitalRepositoryInterface
{
    public function getHospital(): ?Hospital
    {
        return Hospital::first();
    }

    public function update(array $data): Hospital
    {
        $hospital = $this->getHospital();

        if (! $hospital) {
            $hospital = $this->createIfNotExists();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($hospital->image && Storage::disk('public')->exists($hospital->image)) {
                Storage::disk('public')->delete($hospital->image);
            }

            $data['image'] = $data['image']->store('hospitals', 'public');
        }

        $hospital->update($data);

        return $hospital->fresh();
    }

    public function createIfNotExists(): Hospital
    {
        return Hospital::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
