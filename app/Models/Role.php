<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active roles only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get role statistics
     */
    public function getStatsAttribute()
    {
        return [
            'permissions_count' => $this->permissions()->count(),
            'users_count' => $this->users()->count(),
        ];
    }

    /**
     * Check if role can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->users()->count() === 0;
    }

    /**
     * Get users count for this role
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }
}
