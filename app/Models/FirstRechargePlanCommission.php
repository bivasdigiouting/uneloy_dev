<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirstRechargePlanCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_recharge_plan_id',
        'department_id',
        'commission_amount',
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(FirstRechargePlan::class, 'first_recharge_plan_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
