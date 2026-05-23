<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id', 'title',
        'basic', 'hra', 'da', 'ta', 'medical', 'special_allowance', 'bonus', 'eic',
        'pf', 'loan', 'esic', 'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getGrossAttribute(): float
    {
        return (float) ($this->basic + $this->hra + $this->da + $this->ta + $this->medical + $this->special_allowance + $this->bonus + $this->eic);
    }

    public function getDeductionTotalAttribute(): float
    {
        return (float) ($this->pf + $this->loan + $this->esic);
    }

    public function getNetAttribute(): float
    {
        return (float) ($this->gross - $this->deduction_total);
    }
}
