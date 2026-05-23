<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'show_on_portal',
        'sequence_no',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sequence_no' => 'integer',
    ];

    /**
     * Get the image URL attribute
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    /**
     * Scope for active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for portal display
     */
    public function scopeShowOnPortal($query)
    {
        return $query->where('show_on_portal', 'yes');
    }

    /**
     * Scope for ordering by sequence
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence_no', 'asc');
    }
}
