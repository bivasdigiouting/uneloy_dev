<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Product model",
 *
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="iPhone 14 Pro"),
 *     @OA\Property(property="description", type="string", example="Latest iPhone with advanced features"),
 *     @OA\Property(property="price", type="number", format="float", example=999.99),
 *     @OA\Property(property="stock_quantity", type="integer", example=50),
 *     @OA\Property(property="sku", type="string", example="IPH14PRO001"),
 *     @OA\Property(property="category", type="string", example="Electronics"),
 *     @OA\Property(property="brand", type="string", example="Apple"),
 *     @OA\Property(property="images", type="array", @OA\Items(type="string"), example={"image1.jpg", "image2.jpg"}),
 *     @OA\Property(property="weight", type="number", format="float", example=0.206),
 *     @OA\Property(property="dimensions", type="object", example={"length": 14.7, "width": 7.15, "height": 0.79}),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="is_featured", type="boolean", example=false),
 *     @OA\Property(property="attributes", type="object", example={"color": "Space Black", "storage": "128GB"}),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'detail', // matches existing database field
        'price',
        'mrp',
        'distributor_price',
        'stock', // matches existing database field
        'image', // matches existing database field
        'vendor_id',
        'admin_status',
        // Additional fields for API (will be added via migration)
        'description',
        'sku',
        'category',
        'brand',
        'images',
        'weight',
        'dimensions',
        'is_active',
        'is_featured',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'distributor_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'images' => 'array',
        'dimensions' => 'array',
        'attributes' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Accessor to provide description from detail field for backward compatibility
    public function getDescriptionAttribute()
    {
        return $this->attributes['description'] ?? $this->attributes['detail'] ?? null;
    }

    // Accessor to provide stock_quantity from stock field for backward compatibility
    public function getStockQuantityAttribute()
    {
        return $this->attributes['stock_quantity'] ?? $this->attributes['stock'] ?? 0;
    }

    protected $hidden = [
        // Add any fields you want to hide from JSON responses
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function gstTax()
    {
        return $this->belongsTo(\App\Models\GstTax::class, 'gst_tax_id');
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class, 'vendor_id');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('admin_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('admin_status', 'approved');
    }
}
