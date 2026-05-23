<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'vendor_wallet_transactions';

    protected $fillable = [
        'vendor_id',
        'transaction_type',
        'amount',
        'previous_balance',
        'new_balance',
        'narration',
        'performed_by_user_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }
}
