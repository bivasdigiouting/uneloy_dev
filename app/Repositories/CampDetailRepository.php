<?php

namespace App\Repositories;

use App\Models\CampDetail;
use App\Repositories\Interfaces\CampDetailRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CampDetailRepository implements CampDetailRepositoryInterface
{
    public function getForDataTable()
    {
        return CampDetail::with(['camp', 'state', 'district', 'city'])
            ->select('camp_details.*');
    }

    public function findById(int $id)
    {
        return CampDetail::with(['camp', 'state', 'district', 'city'])->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $banner = null)
    {
        if ($banner) {
            $data['banner'] = $this->uploadBanner($banner);
        }

        return CampDetail::create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $banner = null)
    {
        $campDetail = CampDetail::findOrFail($id);

        if ($banner) {
            if ($campDetail->banner && Storage::disk('public')->exists($campDetail->banner)) {
                Storage::disk('public')->delete($campDetail->banner);
            }
            $data['banner'] = $this->uploadBanner($banner);
        }

        $campDetail->update($data);

        return $campDetail->fresh(['camp', 'state', 'district', 'city']);
    }

    public function delete(int $id): bool
    {
        $campDetail = CampDetail::findOrFail($id);
        if ($campDetail->banner && Storage::disk('public')->exists($campDetail->banner)) {
            Storage::disk('public')->delete($campDetail->banner);
        }

        return (bool) $campDetail->delete();
    }

    protected function uploadBanner(UploadedFile $file): string
    {
        return $file->store('camp-details/banners', 'public');
    }
}
