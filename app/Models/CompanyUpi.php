<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'upi_id',
        'qr_code',
        'status',
        'remarks',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope for active UPIs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive UPIs
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }
}
