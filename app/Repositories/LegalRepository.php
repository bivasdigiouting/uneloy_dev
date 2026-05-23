<?php

namespace App\Repositories;

use App\Models\Legal;
use App\Repositories\Interfaces\LegalRepositoryInterface;

class LegalRepository implements LegalRepositoryInterface
{
    public function updateLegal(array $data)
    {
        $legal = Legal::first();

        if (!$legal) {
            $legal = new Legal();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($legal->image && file_exists(public_path($legal->image))) {
                unlink(public_path($legal->image));
            }

            $image = $data['image'];
            $imageName = 'legals_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-us'), $imageName);
            $data['image'] = 'uploads/about-us/' . $imageName;
        }

        $legal->fill($data);
        $legal->save();

        return $legal;
    }

    public function getLegal()
    {
        return Legal::first();
    }
}
