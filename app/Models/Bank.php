<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'bank_name',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Scope for active banks
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for inactive banks
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Get status badge HTML
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';
    }
}
