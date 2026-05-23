<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'product_id',
        'quantity',
        'from_level_type', 'from_state_id', 'from_district_id', 'from_city_id', 'from_panchayat_name', 'from_village_name',
        'to_level_type', 'to_state_id', 'to_district_id', 'to_city_id', 'to_panchayat_name', 'to_village_name',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function fromState()
    {
        return $this->belongsTo(State::class, 'from_state_id');
    }

    public function fromDistrict()
    {
        return $this->belongsTo(District::class, 'from_district_id');
    }

    public function fromCity()
    {
        return $this->belongsTo(City::class, 'from_city_id');
    }

    public function toState()
    {
        return $this->belongsTo(State::class, 'to_state_id');
    }

    public function toDistrict()
    {
        return $this->belongsTo(District::class, 'to_district_id');
    }

    public function toCity()
    {
        return $this->belongsTo(City::class, 'to_city_id');
    }
}
