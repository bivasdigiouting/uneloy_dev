<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpsUserFund extends Model
{
    protected $table = 'eps_user_funds';

    protected $fillable = [
        'fund_type',
        'user_type',
        'amount',
        'added_by_user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
