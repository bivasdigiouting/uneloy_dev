<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcardSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecard_registration_id',
        'user_id',
        'customer_name',
        'billing_date',
        'purchase_value',
        'tax_amount',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'payment_details',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'purchase_value' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_details' => 'array',
    ];

    public function ecardRegistration()
    {
        return $this->belongsTo(ECardRegistration::class, 'ecard_registration_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(EcardSaleItem::class, 'ecard_sale_id');
    }
}
