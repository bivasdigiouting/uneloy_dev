<?php

namespace App\Repositories;

use App\Models\Excellence;
use App\Repositories\Interfaces\ExcellenceRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ExcellenceRepository implements ExcellenceRepositoryInterface
{
    public function getExcellence()
    {
        return Excellence::first();
    }

    public function updateExcellence(array $data)
    {
        $excellence = Excellence::first() ?? new Excellence();

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($excellence->image && Storage::disk('public')->exists($excellence->image)) {
                Storage::disk('public')->delete($excellence->image);
            }
            
            $path = $data['image']->store('excellence', 'public');
            $excellence->image = $path;
        }

        // Update other fields
        $excellence->text_header = $data['text_header'] ?? $excellence->text_header;
        $excellence->text_description = $data['text_description'] ?? $excellence->text_description;
        $excellence->footer_short_description = $data['footer_short_description'] ?? $excellence->footer_short_description;

        $excellence->save();

        return $excellence;
    }
}
