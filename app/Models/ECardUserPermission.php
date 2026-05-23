<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ECardUserPermission extends Model
{
    protected $fillable = [
        'ecard_registration_id',
        'module_id',
        'can_view',
        'can_create',
        'can_update',
        'can_delete',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ];
}
