<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecard_registration_id',
        'type', // 'mobile' or 'email'
        'contact', // mobile number or email address
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function ecardRegistration()
    {
        return $this->belongsTo(ECardRegistration::class);
    }
}
