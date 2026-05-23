<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeOperator extends Model
{
    use HasFactory;

    protected $fillable = [
        'recharge_service_id',
        'operator_name',
        'operator_code',
        'operator_logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(RechargeService::class, 'recharge_service_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
