<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'recharge_transaction_id',
        'registration_id',
        'recharge_commission_rule_id',
        'recharge_service_id',
        'recharge_operator_id',
        'department_level',
        'commission_type',
        'commission_value',
        'recharge_amount',
        'commission_amount',
        'status',
        'wallet_transaction_id',
        'meta',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'recharge_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function transaction()
    {
        return $this->belongsTo(RechargeTransaction::class, 'recharge_transaction_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function rule()
    {
        return $this->belongsTo(RechargeCommissionRule::class, 'recharge_commission_rule_id');
    }

    public function service()
    {
        return $this->belongsTo(RechargeService::class, 'recharge_service_id');
    }

    public function operator()
    {
        return $this->belongsTo(RechargeOperator::class, 'recharge_operator_id');
    }
}
