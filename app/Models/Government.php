<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    protected $table = 'governments';

    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the image URL attribute.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        return null;
    }
}
