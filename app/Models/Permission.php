<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'module',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to filter by module
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope to filter active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get permission statistics
     */
    public function getStatsAttribute()
    {
        return [
            'roles_count' => $this->roles()->count(),
            'users_count' => $this->users()->count(),
        ];
    }

    /**
     * Check if permission can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->roles()->count() === 0;
    }

    /**
     * Get users count for this permission
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }
}
