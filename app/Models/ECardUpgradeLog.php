<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ECardUpgradeLog extends Model
{
    protected $table = 'ecard_upgrade_logs';

    protected $fillable = [
        'ecard_registration_id',
        'from_level',
        'to_level',
        'upgraded_by_id',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo(ECardRegistration::class, 'ecard_registration_id');
    }

    public function upgradedBy()
    {
        return $this->belongsTo(ECardRegistration::class, 'upgraded_by_id');
    }
}
