<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Vendor extends Model
{
    use HasFactory, HasApiTokens;


    protected $fillable = [
        'vendor_number',
        'vendor_name',
        'status',
        'password',
        'email_verified_at',
        // Business Details
        'business_registration_category',
        'business_name',
        'mobile_country_code',
        'mobile_no',
        'whatsapp_country_code',
        'whatsapp_no',
        'gmail_id',
        'business_full_address',
        'business_gst_no',
        'contact_person',
        'contact_person_designation',
        'facility',
        'about_us',
        'business_location',
        // Business Product
        'product_categories',
        // Business Bank Details
        'bank_name',
        'branch_name',
        'account_holder_name',
        'account_no',
        'ifsc_code',
        'pan_no',
        'aadhar_no',
        'upi_no',
        // Personal Details
        'vendor_type',
        'first_name',
        'middle_name',
        'last_name',
        'fathers_name',
        'mothers_name',
        'blood_group',
        'date_of_birth',
        'gender',
        'marital_status',
        // Contact Details
        'current_address',
        'permanent_address',
        'nationality',
        'state_id',
        'district_id',
        'city_id',
        'pincode',
        'contact_mobile_country_code',
        'contact_mobile_no',
        'contact_whatsapp_country_code',
        'contact_whatsapp_no',
        'contact_gmail_id',
        'current_live_location',
        // Education & Qualification Details
        'last_qualification',
        'work_type',
        'work_experience',
        'terms_accepted',
        'settings',
    ];

    protected $casts = [
        'status' => 'string',
        'product_categories' => 'array',
        'settings' => 'array',
        'date_of_birth' => 'date',
        'terms_accepted' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Scope to get active vendors
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get inactive vendors
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'badge-success' : 'badge-danger';
    }

    /**
     * Get available status options
     */
    public static function getStatusOptions()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }

    /**
     * Relationships
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get business registration category options
     */
    public static function getBusinessRegistrationCategoryOptions()
    {
        return [
            'Private Limited' => 'Private Limited',
            'Proprietorship' => 'Proprietorship',
            'Partnership' => 'Partnership',
            'Limited' => 'Limited',
            'NGO' => 'NGO',
        ];
    }

    /**
     * Get product category options
     */
    public static function getProductCategoryOptions()
    {
        return [
            'Medical' => 'Medical',
            'Gold' => 'Gold',
        ];
    }

    /**
     * Get blood group options
     */
    public static function getBloodGroupOptions()
    {
        return [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
            'O+' => 'O+',
            'O-' => 'O-',
        ];
    }

    /**
     * Get gender options
     */
    public static function getGenderOptions()
    {
        return [
            'Male' => 'Male',
            'Female' => 'Female',
            'Other' => 'Other',
        ];
    }

    /**
     * Get marital status options
     */
    public static function getMaritalStatusOptions()
    {
        return [
            'Single' => 'Single',
            'Married' => 'Married',
            'Others' => 'Others',
        ];
    }

    /**
     * Get vendor type options
     */
    public static function getVendorTypeOptions()
    {
        return [
            'Self Vendor' => 'Self Vendor',
        ];
    }
}
