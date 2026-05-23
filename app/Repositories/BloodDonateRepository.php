<?php

namespace App\Repositories;

use App\Models\BloodDonate;
use App\Repositories\Interfaces\BloodDonateRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BloodDonateRepository implements BloodDonateRepositoryInterface
{
    /**
     * Get the Blood Donate data.
     *
     * @return \App\Models\BloodDonate|null
     */
    public function getBloodDonate()
    {
        return BloodDonate::first();
    }

    /**
     * Update or create the Blood Donate data.
     *
     * @param array $data
     * @return \App\Models\BloodDonate
     */
    public function update(array $data)
    {
        $bloodDonate = $this->getBloodDonate();

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($bloodDonate && $bloodDonate->image) {
                Storage::disk('public')->delete($bloodDonate->image);
            }
            $data['image'] = $data['image']->store('blood_donate', 'public');
        }

        if ($bloodDonate) {
            $bloodDonate->update($data);
            return $bloodDonate;
        }

        return BloodDonate::create($data);
    }
}
