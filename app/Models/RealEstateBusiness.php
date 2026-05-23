<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealEstateBusiness extends Model
{
    protected $table = 'real_estate_businesses';

    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    /**
     * Get the image URL.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
