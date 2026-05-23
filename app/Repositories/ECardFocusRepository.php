<?php

namespace App\Repositories;

use App\Models\ECardFocus;
use App\Repositories\Interfaces\ECardFocusRepositoryInterface;

class ECardFocusRepository implements ECardFocusRepositoryInterface
{
    public function updateECardFocus(array $data)
    {
        $eCardFocus = ECardFocus::first();

        if (!$eCardFocus) {
            $eCardFocus = new ECardFocus();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($eCardFocus->image && file_exists(public_path($eCardFocus->image))) {
                unlink(public_path($eCardFocus->image));
            }

            $image = $data['image'];
            $imageName = 'ecard_focus_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about-us'), $imageName);
            $data['image'] = 'uploads/about-us/' . $imageName;
        }

        $eCardFocus->fill($data);
        $eCardFocus->save();

        return $eCardFocus;
    }

    public function getECardFocus()
    {
        return ECardFocus::first();
    }
}
