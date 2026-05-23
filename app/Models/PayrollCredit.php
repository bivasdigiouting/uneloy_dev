<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id', 'department_id', 'month', 'year',
        'basic', 'hra', 'da', 'ta', 'medical', 'special_allowance', 'bonus', 'eic',
        'pf', 'loan', 'esic',
        'gross_earnings', 'total_deductions', 'net_pay', 'status', 'credited_at',
    ];

    protected $casts = [
        'credited_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
