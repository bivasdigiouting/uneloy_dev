<?php

namespace App\Repositories;

use App\Models\OurVision;
use App\Repositories\Interfaces\OurVisionRepositoryInterface;

class OurVisionRepository implements OurVisionRepositoryInterface
{
    public function updateOurVision(array $data)
    {
        $ourVision = OurVision::first();

        if (!$ourVision) {
            $ourVision = new OurVision();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($ourVision->image && file_exists(public_path($ourVision->image))) {
                unlink(public_path($ourVision->image));
            }

            $image = $data['image'];
            $imageName = 'our_vision_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-us'), $imageName);
            $data['image'] = 'uploads/about-us/' . $imageName;
        }

        $ourVision->fill($data);
        $ourVision->save();

        return $ourVision;
    }

    public function getOurVision()
    {
        return OurVision::first();
    }
}
