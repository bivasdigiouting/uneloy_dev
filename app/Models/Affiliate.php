<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope for active affiliates
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ordered affiliates
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('service_name');
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';
    }

    /**
     * Get icon URL
     */
    public function getIconUrlAttribute()
    {
        return $this->icon ? asset('storage/'.$this->icon) : null;
    }
}
