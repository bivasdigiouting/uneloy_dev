<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyFamilyContact extends Model
{
    use HasFactory;

    protected $table = 'emergency_family_contacts';

    protected $fillable = [
        'registration_id',
        'name',
        'mobile_no',
        'relation',
        'age',
        'gender',
        'live_location',
        'description',
        'image',
    ];

    protected $casts = [
        'registration_id' => 'integer',
        'age' => 'integer',
    ];
}
