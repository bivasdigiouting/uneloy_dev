<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorGlobalDistribution extends Model
{
    protected $fillable = [
        'total_amount',
        'vendor_count',
        'created_by_user_id',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'vendor_count' => 'integer',
    ];

    public function allocations(): HasMany
    {
        return $this->hasMany(VendorGlobalVendorDistribution::class, 'distribution_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
