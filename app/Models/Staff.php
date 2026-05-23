<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Staff extends Model
{
    protected $fillable = [
        // Personal Details
        'staff_name',
        'profile_image',
        'date_of_joining',
        'date_of_birth',
        'designation_id',
        'gender',

        // Contact Details
        'address_1',
        'address_2',
        'state',
        'district',
        'city',
        'pincode',
        'mobile_no',
        'email_id',
        'location',

        // Bank Details
        'ifsc_code',
        'bank_name',
        'branch_name',
        'account_no',
        'pan_no',
        'aadhar_no',
        'salary',

        // Login Details
        'user_id',
        'password',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_of_joining' => 'date',
        'date_of_birth' => 'date',
        'salary' => 'decimal:2',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Automatically hash password when setting
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get active staff only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relationship with Designation
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Check if staff can be deleted
     */
    public function canBeDeleted(): bool
    {
        // Add logic here if staff has related records
        return true;
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address_1;
        if ($this->address_2) {
            $address .= ', '.$this->address_2;
        }
        $address .= ', '.$this->city.', '.$this->district.', '.$this->state.' - '.$this->pincode;

        return $address;
    }
}
