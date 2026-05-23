<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcardSevaProductCommission extends Model
{
    protected $fillable = [
        'inhouse_product_id',
        'state_member_commission',
        'district_member_commission',
        'block_member_commission',
        'panchayat_member_commission',
        'village_member_commission',
        'customer_commission',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'state_member_commission' => 'decimal:2',
        'district_member_commission' => 'decimal:2',
        'block_member_commission' => 'decimal:2',
        'panchayat_member_commission' => 'decimal:2',
        'village_member_commission' => 'decimal:2',
        'customer_commission' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(InhouseProduct::class, 'inhouse_product_id');
    }
}
