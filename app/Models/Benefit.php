<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefit_name',
        'icon',
        'schema_type',
        'schema_type_name',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getIconUrlAttribute(): ?string
    {
        if ($this->icon && Storage::disk('public')->exists($this->icon)) {
            return Storage::disk('public')->url($this->icon);
        }

        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
