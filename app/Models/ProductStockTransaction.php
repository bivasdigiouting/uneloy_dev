<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockTransaction extends Model
{
    use HasFactory;

    protected $table = 'product_stock_transactions';

    protected $fillable = [
        'product_category_id',
        'product_id',
        'quantity',
        'type', // 'in' or 'out'
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
