<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorStaff extends Model
{
    use HasFactory;
    
    protected $table = 'vendor_staff';
    
    protected $fillable = [
        'vendor_id',
        'name',
        'role',
        'phone',
        'shift_start',
        'shift_end',
        'performance_score',
        'is_online',
        'base_salary'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'shift_start' => 'datetime:H:i',
        'shift_end' => 'datetime:H:i',
        'performance_score' => 'integer',
        'base_salary' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function payrolls()
    {
        return $this->hasMany(VendorPayroll::class, 'vendor_staff_id');
    }
}
