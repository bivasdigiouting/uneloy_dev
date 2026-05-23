<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCamp extends Model
{
    protected $table = 'book_camps';

    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    /**
     * Get the image URL attribute.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return null;
    }
}
