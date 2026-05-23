<?php

namespace App\Repositories;

use App\Models\OnDemandService;
use App\Repositories\Interfaces\OnDemandServiceRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class OnDemandServiceRepository implements OnDemandServiceRepositoryInterface
{
    /**
     * Get the on demand service data.
     */
    public function getOnDemandService(): ?OnDemandService
    {
        return OnDemandService::first();
    }

    /**
     * Update on demand service information.
     */
    public function update(array $data): OnDemandService
    {
        $onDemandService = $this->getOnDemandService();

        if (! $onDemandService) {
            $onDemandService = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($onDemandService->image && Storage::disk('public')->exists($onDemandService->image)) {
                Storage::disk('public')->delete($onDemandService->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('on_demand_service', 'public');
        }

        $onDemandService->update($data);

        return $onDemandService->fresh();
    }

    /**
     * Create on demand service record if it doesn't exist.
     */
    public function createIfNotExists(): OnDemandService
    {
        return OnDemandService::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
