<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseProduct extends Model
{
    use HasFactory;

    protected $table = 'inhouse_products';

    protected $fillable = [
        'inhouse_product_category_id',
        'gst_tax_id',
        'name',
        'sku',
        'mrp',
        'price',
        'stock',
        'thumbnail',
        'images',
        'description',
        'is_active',
    ];

    protected $casts = [
        'mrp' => 'decimal:2',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(InhouseProductCategory::class, 'inhouse_product_category_id');
    }

    public function gstTax()
    {
        return $this->belongsTo(GstTax::class, 'gst_tax_id');
    }
}
