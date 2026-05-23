<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_name',
        'state_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'state_id' => 'integer',
    ];

    /**
     * Relationship with State
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Relationship with Cities
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Scope for active districts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ordered districts
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('district_name');
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
