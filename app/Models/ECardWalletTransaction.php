<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ECardWalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'ecard_wallet_transactions';

    protected $fillable = [
        'ecard_registration_id',
        'transaction_type', // add | remove
        'amount',
        'previous_balance',
        'new_balance',
        'narration',
        'performed_by_id',
        'reference_type',
        'reference_id',
        'gateway_transaction_id',
        'gateway_name',
        'payment_status',
        'payment_meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
        'payment_meta' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(ECardRegistration::class, 'ecard_registration_id');
    }
}
