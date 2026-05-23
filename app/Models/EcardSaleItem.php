<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcardSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecard_sale_id',
        'product_id',
        'quantity',
        'price',
        'tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(EcardSale::class, 'ecard_sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
