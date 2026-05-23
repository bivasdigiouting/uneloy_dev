<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessFocus extends Model
{
    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
