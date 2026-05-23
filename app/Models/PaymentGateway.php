<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'is_enabled',
        'active_mode',
        'test_config',
        'live_config',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'test_config' => 'array',
        'live_config' => 'array',
    ];
}
