<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonate extends Model
{
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
    }}
