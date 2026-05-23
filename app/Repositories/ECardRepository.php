<?php

namespace App\Repositories;

use App\Models\ECardService;
use App\Repositories\Interfaces\ECardRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ECardRepository implements ECardRepositoryInterface
{
    /**
     * Get the E-Card service data.
     *
     * @return \App\Models\ECardService|null
     */
    public function getECardService()
    {
        return ECardService::first();
    }

    /**
     * Update or create the E-Card service data.
     *
     * @param array $data
     * @return \App\Models\ECardService
     */
    public function update(array $data)
    {
        $eCard = $this->getECardService();

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($eCard && $eCard->image) {
                Storage::disk('public')->delete($eCard->image);
            }
            $data['image'] = $data['image']->store('e_card_service', 'public');
        }

        if ($eCard) {
            $eCard->update($data);
            return $eCard;
        }

        return ECardService::create($data);
    }
}
