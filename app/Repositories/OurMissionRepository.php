<?php

namespace App\Repositories;

use App\Models\OurMission;
use App\Repositories\Interfaces\OurMissionRepositoryInterface;

class OurMissionRepository implements OurMissionRepositoryInterface
{
    public function updateOurMission(array $data)
    {
        $ourMission = OurMission::first();

        if (!$ourMission) {
            $ourMission = new OurMission();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($ourMission->image && file_exists(public_path($ourMission->image))) {
                unlink(public_path($ourMission->image));
            }

            $image = $data['image'];
            $imageName = 'our_mission_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-us'), $imageName);
            $data['image'] = 'uploads/about-us/' . $imageName;
        }

        $ourMission->fill($data);
        $ourMission->save();

        return $ourMission;
    }

    public function getOurMission()
    {
        return OurMission::first();
    }
}
