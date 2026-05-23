<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedeemValueHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_user_points',
        'redeem_amount',
        'redeem_value',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
