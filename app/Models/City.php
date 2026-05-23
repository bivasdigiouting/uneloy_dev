<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_name',
        'district_id',
        'state_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'district_id' => 'integer',
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
     * Relationship with District
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Scope for active cities
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ordered cities
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('city_name');
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
     * Get formatted name with state and district
     */
    public function getFullNameAttribute()
    {
        return $this->city_name.', '.$this->district->district_name.', '.$this->state->state_name;
    }
}
