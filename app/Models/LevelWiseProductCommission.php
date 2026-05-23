<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelWiseProductCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'state_member_commission',
        'district_member_commission',
        'block_member_commission',
        'panchayat_member_commission',
        'village_member_commission',
        'customer_commission',
        'is_active',
    ];

    protected $casts = [
        'state_member_commission' => 'decimal:2',
        'district_member_commission' => 'decimal:2',
        'block_member_commission' => 'decimal:2',
        'panchayat_member_commission' => 'decimal:2',
        'village_member_commission' => 'decimal:2',
        'customer_commission' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the product category that owns the commission.
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Get all commission levels as an array.
     */
    public function getCommissionLevels(): array
    {
        return [
            'State e-Card Seva' => $this->state_member_commission,
            'District e-Card Seva' => $this->district_member_commission,
            'Block - e-Card Seva' => $this->block_member_commission,
            'G P M e-Card Seva' => $this->panchayat_member_commission,
            'e-Card Seva' => $this->village_member_commission,
            'Member' => $this->customer_commission,
        ];
    }

    /**
     * Scope to get active commissions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
