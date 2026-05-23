<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemSetting extends Model
{
    protected $table = 'redeem_settings';

    protected $fillable = [
        'total_user_points',
        'redeem_amount',
        'redeem_value',
    ];
}
