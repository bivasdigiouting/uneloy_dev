<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ECardLoginHistory extends Model
{
    protected $table = 'ecard_login_histories';

    protected $fillable = [
        'ecard_registration_id',
        'ip_address',
        'platform',
        'user_agent',
        'logged_in_at',
        'logged_out_at',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(ECardRegistration::class, 'ecard_registration_id');
    }
}
