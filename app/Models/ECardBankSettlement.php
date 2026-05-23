<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ECardBankSettlement extends Model
{
    use HasFactory;

    protected $table = 'ecard_bank_settlements';

    protected $fillable = [
        'ecard_registration_id',
        'amount',
        'settlement_mode',
        'bank_name',
        'account_number',
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
