<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayroll extends Model
{
    use HasFactory;

    protected $table = 'vendor_payrolls';

    protected $fillable = [
        'vendor_id',
        'vendor_staff_id',
        'month_year',
        'base_salary',
        'incentive',
        'status',
    ];

    protected $casts = [
        'month_year' => 'date',
        'base_salary' => 'decimal:2',
        'incentive' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class);
    }

    public function staff()
    {
        return $this->belongsTo(VendorStaff::class, 'vendor_staff_id');
    }
}
