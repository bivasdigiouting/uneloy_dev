<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeCommissionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'recharge_service_id',
        'recharge_operator_id',
        'department_level',
        'commission_type',
        'commission_value',
        'min_amount',
        'max_amount',
        'is_active',
        'created_by_user_id',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(RechargeService::class, 'recharge_service_id');
    }

    public function operator()
    {
        return $this->belongsTo(RechargeOperator::class, 'recharge_operator_id');
    }
}
