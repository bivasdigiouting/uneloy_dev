<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_type',
        'image',
        'status',
        'link',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope for active banners
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive banners
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get banner type options
     */
    public static function getBannerTypes()
    {
        return [
            'home_1' => 'Home 1',
            'home_2' => 'Home 2',
            'home_3' => 'Home 3',
            'my_order' => 'My Order',
            'deposit' => 'Deposit',
            'withdrawal' => 'Withdrawal',
            'rewards' => 'Rewards',
        ];
    }

    /**
     * Get formatted banner type
     */
    public function getFormattedBannerTypeAttribute()
    {
        $types = self::getBannerTypes();

        return $types[$this->banner_type] ?? ucfirst(str_replace('_', ' ', $this->banner_type));
    }
}
