<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ECardWalletRequest extends Model
{
    use HasFactory;

    protected $table = 'ecard_wallet_requests';

    protected $fillable = [
        'ecard_registration_id',
        'amount',
        'payment_mode',
        'reference_number',
        'status',
        'remark',
        'created_by_id',
        'approved_by_id',
    ];

    public function registration()
    {
        return $this->belongsTo(ECardRegistration::class, 'ecard_registration_id');
    }
}
