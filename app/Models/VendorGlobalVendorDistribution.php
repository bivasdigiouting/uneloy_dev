<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorGlobalVendorDistribution extends Model
{
    protected $fillable = [
        'distribution_id',
        'vendor_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(VendorGlobalDistribution::class, 'distribution_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
