<?php

namespace App\Repositories;

use App\Models\WebsiteSettings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingsRepository
{
    /**
     * Get website settings
     */
    public function getSettings()
    {
        return WebsiteSettings::first();
    }

    /**
     * Update website settings
     */
    public function updateSettings(array $data)
    {
        $settings = WebsiteSettings::first();

        if (! $settings) {
            $settings = WebsiteSettings::create($data);
        } else {
            $settings->update($data);
        }

        return $settings;
    }

    /**
     * Handle logo upload
     */
    public function uploadLogo(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/logos', $settings->logo ?? null);
    }

    /**
     * Handle favicon upload
     */
    public function uploadFavicon(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/favicons', $settings->favicon ?? null);
    }

    /**
     * Handle Member App Logo upload
     */
    public function uploadMemberAppLogo(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/logos', $settings->member_app_logo ?? null);
    }

    /**
     * Handle Member App Favicon upload
     */
    public function uploadMemberAppFavicon(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/favicons', $settings->member_app_favicon ?? null);
    }

    /**
     * Handle Ecardseva Logo upload
     */
    public function uploadEcardsevaLogo(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/logos', $settings->ecardseva_logo ?? null);
    }

    /**
     * Handle Ecardseva Favicon upload
     */
    public function uploadEcardsevaFavicon(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/favicons', $settings->ecardseva_favicon ?? null);
    }

    /**
     * Handle Estore App Logo upload
     */
    public function uploadEstoreAppLogo(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/logos', $settings->estore_app_logo ?? null);
    }

    /**
     * Handle Estore App Favicon upload
     */
    public function uploadEstoreAppFavicon(UploadedFile $file)
    {
        $settings = $this->getSettings();
        return $this->handleUpload($file, 'website/favicons', $settings->estore_app_favicon ?? null);
    }

    /**
     * Generic upload handler
     */
    private function handleUpload(UploadedFile $file, string $path, ?string $oldFile = null)
    {
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }
        return $file->store($path, 'public');
    }

    /**
     * Remove logo
     */
    public function removeLogo()
    {
        $settings = $this->getSettings();
        $this->handleRemove($settings->logo);
        if ($settings->exists) {
            $settings->update(['logo' => null]);
        }
        return true;
    }

    /**
     * Remove favicon
     */
    public function removeFavicon()
    {
        $settings = $this->getSettings();
        $this->handleRemove($settings->favicon);
        if ($settings->exists) {
            $settings->update(['favicon' => null]);
        }
        return true;
    }

    /**
     * Generic remove handler
     */
    private function handleRemove(?string $path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get logo URL
     */
    public function getLogoUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->logo ?? null);
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->favicon ?? null);
    }

    public function getMemberAppLogoUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->member_app_logo ?? null);
    }

    public function getMemberAppFaviconUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->member_app_favicon ?? null);
    }

    public function getEcardsevaLogoUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->ecardseva_logo ?? null);
    }

    public function getEcardsevaFaviconUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->ecardseva_favicon ?? null);
    }

    public function getEstoreAppLogoUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->estore_app_logo ?? null);
    }

    public function getEstoreAppFaviconUrl()
    {
        $settings = $this->getSettings();
        return $this->getUrl($settings->estore_app_favicon ?? null);
    }

    /**
     * Generic get URL handler
     */
    private function getUrl(?string $path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        return null;
    }

    /**
     * Update only notification-related Firebase settings.
     */
    public function updateNotificationSettings(array $data)
    {
        $settings = WebsiteSettings::first();

        $only = [
            'firebase_server_key',
            'firebase_api_key',
            'firebase_project_id',
            'firebase_sender_id',
            'firebase_app_id',
        ];

        $filtered = array_intersect_key($data, array_flip($only));

        if (! $settings) {
            $settings = WebsiteSettings::create($filtered);
        } else {
            $settings->update($filtered);
        }

        return $settings;
    }

    /**
     * Update only third-party API settings.
     */
    public function updateThirdPartyApiSettings(array $data)
    {
        $settings = WebsiteSettings::first();

        $only = [
            'third_party_api_username',
            'third_party_api_token',
            'third_party_api_url',
        ];

        $filtered = array_intersect_key($data, array_flip($only));

        if (! $settings) {
            $settings = WebsiteSettings::create($filtered);
        } else {
            $settings->update($filtered);
        }

        return $settings;
    }

    /**
     * Update only recharge API settings.
     */
    public function updateRechargeApiSettings(array $data)
    {
        $settings = WebsiteSettings::first();

        $only = [
            'recharge_api_username',
            'recharge_api_token',
            'recharge_callback_url',
            'recharge_pan_redirect_url',
            'recharge_api_url',
        ];

        $filtered = array_intersect_key($data, array_flip($only));

        if (! $settings) {
            $settings = WebsiteSettings::create($filtered);
        } else {
            $settings->update($filtered);
        }

        return $settings;
    }
}
