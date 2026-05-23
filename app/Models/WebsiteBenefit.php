<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WebsiteBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefit_name',
        'sequence',
        'icon',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sequence' => 'integer',
    ];

    /**
     * Get the icon URL attribute
     */
    public function getIconUrlAttribute()
    {
        if ($this->icon && Storage::disk('public')->exists($this->icon)) {
            return Storage::disk('public')->url($this->icon);
        }

        return null;
    }

    /**
     * Scope to get active benefits
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sequence
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence', 'asc');
    }
}
