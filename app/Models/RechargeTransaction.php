<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeTransaction extends Model
{
    use HasFactory;

    protected $table = 'recharge_transactions';

    protected $fillable = [
        'user_id',
        'service_code',
        'operator_id',
        'biller_code',
        'recharge_no',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'response',
    ];

    protected $casts = [
        'amount' => 'float',
        'response' => 'array',
    ];
}
