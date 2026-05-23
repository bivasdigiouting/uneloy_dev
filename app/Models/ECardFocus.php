<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ECardFocus extends Model
{
    protected $table = 'e_card_foci';

    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return null;
    }
}
