<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstRechargePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'plan_value',
        'bonus_value',
        'total_value',
        'benefit_amount',
        'benefit_duration_years',
        'is_active',
    ];

    protected $casts = [
        'plan_value' => 'decimal:2',
        'bonus_value' => 'decimal:2',
        'total_value' => 'decimal:2',
        'benefit_amount' => 'decimal:2',
        'benefit_duration_years' => 'integer',
        'is_active' => 'boolean',
    ];
}
