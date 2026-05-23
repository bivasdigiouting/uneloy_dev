<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContactDetail extends Model
{
    use HasFactory;

    protected $table = 'emergency_contact_details';

    protected $fillable = [
        'registration_id',
        'self_name',
        'self_mobile_no',
        'blood_group',
        'family_contact_1',
        'family_contact_2',
        'family_contact_3',
        'best_friend_contact_1',
        'best_friend_contact_2',
        'best_friend_contact_3',
    ];

    protected $casts = [
        'registration_id' => 'integer',
    ];
}
