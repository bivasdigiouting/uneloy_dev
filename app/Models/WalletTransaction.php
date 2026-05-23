<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    protected $fillable = [
        'registration_id',
        'transaction_type',
        'amount',
        'previous_balance',
        'new_balance',
        'narration',
        'credit_note',
        'debit_note',
        'performed_by_user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }
}
