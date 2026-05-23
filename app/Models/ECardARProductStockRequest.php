<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ECardARProductStockRequest extends Model
{
    use HasFactory;

    protected $table = 'ecard_ar_product_stock_requests';

    protected $fillable = [
        'ecard_registration_id',
        'product_id',
        'product_name',
        'quantity',
        'unit',
        'status',
        'remark',
        'created_by_id',
        'approved_by_id',
    ];

    public function registration()
    {
        return $this->belongsTo(ECardRegistration::class, 'ecard_registration_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
