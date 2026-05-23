<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityAmountMaster extends Model
{
    protected $fillable = [
        'state_level_amount',
        'district_level_amount',
        'block_level_amount',
        'panchayat_level_amount',
        'village_level_amount',
        'is_active',
    ];

    protected $casts = [
        'state_level_amount' => 'decimal:2',
        'district_level_amount' => 'decimal:2',
        'block_level_amount' => 'decimal:2',
        'panchayat_level_amount' => 'decimal:2',
        'village_level_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
