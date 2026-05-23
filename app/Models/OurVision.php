<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurVision extends Model
{
    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return null;
    }
}
