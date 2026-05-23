<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_name',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relationship with Districts
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Relationship with Cities
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Scope for active states
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ordered states
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('state_name');
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
}
