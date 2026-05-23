<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ECardRegistration extends Authenticatable
{
    protected $table = 'ecard_registrations';

    use HasApiTokens, Notifiable;

    protected $fillable = [
        'parent_id',
        // Business Details
        'business_name',
        'business_mobile',
        'business_whatsapp',
        'business_gmail',
        'business_address',
        'business_gst',
        'business_upi',
        'business_location_map',
        'department_level',
        'business_category',

        // Personal Details
        'first_name',
        'middle_name',
        'last_name',
        'father_name',
        'mother_name',
        'profile_image',
        'qr_code',
        'blood_group',
        'date_of_birth',
        'gender',
        'marital_status',

        // Contact Details
        'current_address',
        'permanent_address',
        'nationality',
        'state',
        'district',
        'city',
        'pin_code',
        'mobile_no',
        'whatsapp_no',
        'phone_no',
        'email_id',
        'gmail_id',
        'user_id',
        'password',
        'live_location_map',

        // Bank Details
        'ifsc_code',
        'bank_name',
        'branch_name',
        'account_no',
        'pan_no',
        'aadhaar_no',

        // Qualification & Experience Details
        'last_qualification',
        'work_type',
        'work_experience',

        // KYC Details
        'aadhaar_front',
        'aadhaar_back',
        'pan_card',
        'cheque_book',
        'business_document',
        'gst_document',
        'business_photo',
        'signature',
        'user_photo',
        'kyc_status',

        'status',
        'mpin',
        'wallet_balance',
        'theme_settings',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'wallet_balance' => 'decimal:2',
        'theme_settings' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        // Keep session user_id column numeric by using primary key
        return 'id';
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name.' '.$this->middle_name.' '.$this->last_name);
    }
}
