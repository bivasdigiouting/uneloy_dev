<?php

namespace App\Repositories;

use App\Models\LeadershipWithTrust;
use App\Repositories\Interfaces\LeadershipWithTrustRepositoryInterface;

class LeadershipWithTrustRepository implements LeadershipWithTrustRepositoryInterface
{
    public function updateLeadershipWithTrust(array $data)
    {
        $leadershipWithTrust = LeadershipWithTrust::first();

        if (!$leadershipWithTrust) {
            $leadershipWithTrust = new LeadershipWithTrust();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($leadershipWithTrust->image && file_exists(public_path($leadershipWithTrust->image))) {
                unlink(public_path($leadershipWithTrust->image));
            }

            $image = $data['image'];
            $imageName = 'leadership_with_trust_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-us'), $imageName);
            $data['image'] = 'uploads/about-us/' . $imageName;
        }

        $leadershipWithTrust->fill($data);
        $leadershipWithTrust->save();

        return $leadershipWithTrust;
    }

    public function getLeadershipWithTrust()
    {
        return LeadershipWithTrust::first();
    }
}
