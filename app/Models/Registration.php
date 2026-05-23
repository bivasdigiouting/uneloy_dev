<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'parent_id',
        // Auto-generated credentials
        'user_id',
        'password',

        // Department & Business Category
        'department_level',
        'business_category',

        // Business Details
        'business_name',
        'business_mobile',
        'business_whatsapp',
        'business_gmail',
        'business_address',
        'business_gst',
        'business_upi',
        'business_location_map',

        // Personal Details
        'first_name',
        'middle_name',
        'last_name',
        'father_name',
        'mother_name',
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
        'phone_no',
        'email_id',
        'gmail_id',
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

        // OTP fields
        'otp_required',
        'otp_code',
        'otp_verified',

        'status',
        'wallet_balance',
        'share_profile_to_ecard_seva',
        // Device sharing fields
        'device_number',
        'device_sharing_enabled',
        'max_device_shares',
        // Area fields
        'area',
        'panchayat',
        'municipality',
        'village_name',
        'ward_no',
        'profile_image',
        'aadhaar_front_image',
        'aadhaar_back_image',

        // E-Card Details
        'ecard_number',
        'ecard_cvv',
        'ecard_security_pin',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'wallet_balance' => 'decimal:2',
        'otp_required' => 'boolean',
        'otp_verified' => 'boolean',
        'device_sharing_enabled' => 'boolean',
        'max_device_shares' => 'integer',
        'share_profile_to_ecard_seva' => 'boolean',
    ];

    protected $hidden = [
        'password',
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name.' '.$this->middle_name.' '.$this->last_name);
    }
}
