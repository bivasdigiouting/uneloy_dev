<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentCommission extends Model
{
    protected $fillable = [
        'department_id',
        'security_amount',
        'plan1_commission_percent',
        'plan2_commission_percent',
        'service_charge',
        'admin_charge',
        'tds_charge',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
