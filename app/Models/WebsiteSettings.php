<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSettings extends Model
{
    private const CACHE_KEY = 'website_settings';

    protected $fillable = [
        'site_name',
        'site_title',
        'site_description',
        'logo',
        'favicon',
        'member_app_logo',
        'member_app_favicon',
        'ecardseva_logo',
        'ecardseva_favicon',
        'estore_app_logo',
        'estore_app_favicon',
        'admin_email',
        'contact_email',
        'contact_phone',
        'contact_address',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'footer_text',
        'timezone',
        'currency',
        'maintenance_mode',
        'maintenance_title',
        'maintenance_message',
        // Payment status flags
        'ecard_payment_enabled',
        'ewallet_payment_enabled',
        'eqr_payment_enabled',
        // Payment sharing percentages
        'ecard_share_percent',
        'ewallet_share_percent',
        'eqr_share_percent',
        // Firebase Notification Settings
        'firebase_server_key',
        'firebase_api_key',
        'firebase_project_id',
        'firebase_sender_id',
        'firebase_app_id',
        // Third Party API Settings
        'third_party_api_username',
        'third_party_api_token',
        'third_party_api_url',
        // Recharge API Settings
        'recharge_api_username',
        'recharge_api_token',
        'recharge_callback_url',
        'recharge_pan_redirect_url',
        'recharge_api_url',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'ecard_payment_enabled' => 'boolean',
        'ewallet_payment_enabled' => 'boolean',
        'eqr_payment_enabled' => 'boolean',
        'ecard_share_percent' => 'float',
        'ewallet_share_percent' => 'float',
        'eqr_share_percent' => 'float',
    ];

    /**
     * Get validation rules for website settings
     */
    public static function validationRules()
    {
        return [
            'site_name' => 'nullable|string|max:255',
            'site_title' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
            'member_app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'member_app_favicon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
            'ecardseva_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ecardseva_favicon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
            'estore_app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'estore_app_favicon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
            'admin_email' => 'nullable|email|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'maintenance_mode' => 'boolean',
            'maintenance_title' => 'nullable|string|max:255',
            'maintenance_message' => 'nullable|string|max:1000',
            // Payment status flags
            'ecard_payment_enabled' => 'boolean',
            'ewallet_payment_enabled' => 'boolean',
            'eqr_payment_enabled' => 'boolean',
            // Payment sharing percentages (0-100)
            'ecard_share_percent' => 'numeric|min:0|max:100',
            'ewallet_share_percent' => 'numeric|min:0|max:100',
            'eqr_share_percent' => 'numeric|min:0|max:100',
            // Firebase Notification Settings
            'firebase_server_key' => 'nullable|string',
            'firebase_api_key' => 'nullable|string|max:255',
            'firebase_project_id' => 'nullable|string|max:255',
            'firebase_sender_id' => 'nullable|string|max:255',
            'firebase_app_id' => 'nullable|string|max:255',
        ];
    }

    /**
     * Validation rules specific to Notification Settings update.
     */
    public static function notificationRules()
    {
        return [
            'firebase_server_key' => 'nullable|string',
            'firebase_api_key' => 'nullable|string|max:255',
            'firebase_project_id' => 'nullable|string|max:255',
            'firebase_sender_id' => 'nullable|string|max:255',
            'firebase_app_id' => 'nullable|string|max:255',
        ];
    }

    /**
     * Validation rules for Third Party API settings.
     */
    public static function thirdPartyApiRules()
    {
        return [
            'third_party_api_username' => 'nullable|string|max:100',
            'third_party_api_token' => 'nullable|string|max:255',
            'third_party_api_url' => 'nullable|url|max:255',
        ];
    }

    /**
     * Validation rules for Recharge API settings.
     */
    public static function rechargeApiRules()
    {
        return [
            'recharge_api_username' => 'nullable|string|max:100',
            'recharge_api_token' => 'nullable|string|max:255',
            'recharge_callback_url' => 'nullable|url|max:255',
            'recharge_pan_redirect_url' => 'nullable|url|max:255',
            'recharge_api_url' => 'nullable|url|max:255',
        ];
    }

    /**
     * Get the first settings record or create a new one
     */
    public static function getSettings()
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(60), function () {
            return static::first() ?: new static;
        });
    }

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function (): void {
            Cache::forget(self::CACHE_KEY);
        });
    }
}
