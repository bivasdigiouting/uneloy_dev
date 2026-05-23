<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $appends = ['commission', 'commission_level'];

    protected $fillable = [
        'name',
        'icon',
        'sequence',
        'description',
        'status',
        'commission_level',
    ];

    protected $casts = [
        'sequence' => 'integer',
    ];

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for inactive categories
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Get formatted commission
    public function getFormattedCommissionAttribute()
    {
        return number_format($this->commission, 2).'%';
    }

    // Get formatted commission level
    public function getFormattedCommissionLevelAttribute()
    {
        return number_format($this->commission_level, 2).'%';
    }

    // Relation to LevelWiseProductCommission (one-to-one)
    public function levelWiseCommission()
    {
        return $this->hasOne(\App\Models\LevelWiseProductCommission::class, 'product_category_id');
    }

    // Relation to Products
    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'name');
    }

    // Derived commission attribute (customer commission)
    public function getCommissionAttribute()
    {
        $commission = $this->levelWiseCommission;

        return $commission ? (float) $commission->customer_commission : 0.0;
    }

    // Derived commission_level attribute (average of member levels)
    public function getCommissionLevelAttribute()
    {
        $c = $this->levelWiseCommission;
        if (! $c) {
            return 0.0;
        }
        $levels = [
            (float) $c->state_member_commission,
            (float) $c->district_member_commission,
            (float) $c->block_member_commission,
            (float) $c->panchayat_member_commission,
            (float) $c->village_member_commission,
        ];

        return array_sum($levels) / count($levels);
    }
}
