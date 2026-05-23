<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSettings;
use App\Repositories\SettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    protected $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Display website settings form
     */
    public function show()
    {
        $settings = $this->settingsRepository->getSettings();
        $logoUrl = $this->settingsRepository->getLogoUrl();
        $faviconUrl = $this->settingsRepository->getFaviconUrl();
        $memberAppLogoUrl = $this->settingsRepository->getMemberAppLogoUrl();
        $memberAppFaviconUrl = $this->settingsRepository->getMemberAppFaviconUrl();
        $ecardsevaLogoUrl = $this->settingsRepository->getEcardsevaLogoUrl();
        $ecardsevaFaviconUrl = $this->settingsRepository->getEcardsevaFaviconUrl();
        $estoreAppLogoUrl = $this->settingsRepository->getEstoreAppLogoUrl();
        $estoreAppFaviconUrl = $this->settingsRepository->getEstoreAppFaviconUrl();

        return view('admin.settings.website', compact(
            'settings',
            'logoUrl',
            'faviconUrl',
            'memberAppLogoUrl',
            'memberAppFaviconUrl',
            'ecardsevaLogoUrl',
            'ecardsevaFaviconUrl',
            'estoreAppLogoUrl',
            'estoreAppFaviconUrl'
        ));
    }

    /**
     * Update website settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), WebsiteSettings::validationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except([
                'logo', 'favicon',
                'member_app_logo', 'member_app_favicon',
                'ecardseva_logo', 'ecardseva_favicon',
                'estore_app_logo', 'estore_app_favicon',
                '_token', '_method'
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $data['logo'] = $this->settingsRepository->uploadLogo($request->file('logo'));
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                $data['favicon'] = $this->settingsRepository->uploadFavicon($request->file('favicon'));
            }

            // Handle Member App Logo upload
            if ($request->hasFile('member_app_logo')) {
                $data['member_app_logo'] = $this->settingsRepository->uploadMemberAppLogo($request->file('member_app_logo'));
            }

            // Handle Member App Favicon upload
            if ($request->hasFile('member_app_favicon')) {
                $data['member_app_favicon'] = $this->settingsRepository->uploadMemberAppFavicon($request->file('member_app_favicon'));
            }

            // Handle Ecardseva Logo upload
            if ($request->hasFile('ecardseva_logo')) {
                $data['ecardseva_logo'] = $this->settingsRepository->uploadEcardsevaLogo($request->file('ecardseva_logo'));
            }

            // Handle Ecardseva Favicon upload
            if ($request->hasFile('ecardseva_favicon')) {
                $data['ecardseva_favicon'] = $this->settingsRepository->uploadEcardsevaFavicon($request->file('ecardseva_favicon'));
            }

            // Handle Estore App Logo upload
            if ($request->hasFile('estore_app_logo')) {
                $data['estore_app_logo'] = $this->settingsRepository->uploadEstoreAppLogo($request->file('estore_app_logo'));
            }

            // Handle Estore App Favicon upload
            if ($request->hasFile('estore_app_favicon')) {
                $data['estore_app_favicon'] = $this->settingsRepository->uploadEstoreAppFavicon($request->file('estore_app_favicon'));
            }

            // Convert maintenance_mode checkbox to boolean
            $data['maintenance_mode'] = $request->has('maintenance_mode');

            $this->settingsRepository->updateSettings($data);

            return redirect()->route('admin.settings.website')
                ->with('success', 'Website settings updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update settings: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove logo
     */
    public function removeLogo()
    {
        try {
            $this->settingsRepository->removeLogo();

            return response()->json([
                'success' => true,
                'message' => 'Logo removed successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove logo: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove favicon
     */
    public function removeFavicon()
    {
        try {
            $this->settingsRepository->removeFavicon();

            return response()->json([
                'success' => true,
                'message' => 'Favicon removed successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove favicon: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display Notification Settings (Firebase) form
     */
    public function showNotification()
    {
        $settings = $this->settingsRepository->getSettings();

        return view('admin.settings.notification', compact('settings'));
    }

    /**
     * Update Notification Settings (Firebase keys)
     */
    public function updateNotification(Request $request)
    {
        $validator = Validator::make($request->all(), WebsiteSettings::notificationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only([
                'firebase_server_key',
                'firebase_api_key',
                'firebase_project_id',
                'firebase_sender_id',
                'firebase_app_id',
            ]);

            $this->settingsRepository->updateNotificationSettings($data);

            return redirect()->route('admin.settings.notification')
                ->with('success', 'Notification settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update notification settings: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display Third Party API Settings form
     */
    public function showThirdPartyApi()
    {
        $settings = $this->settingsRepository->getSettings();

        return view('admin.settings.third_party_api', compact('settings'));
    }

    /**
     * Update Third Party API Settings
     */
    public function updateThirdPartyApi(Request $request)
    {
        $validator = Validator::make($request->all(), WebsiteSettings::thirdPartyApiRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only([
                'third_party_api_username',
                'third_party_api_token',
                'third_party_api_url',
            ]);

            $this->settingsRepository->updateThirdPartyApiSettings($data);

            return redirect()->route('admin.settings.third-party-api.show')
                ->with('success', 'Third Party API settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update third-party API settings: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display Recharge API Settings form
     */
    public function showRechargeApi()
    {
        $settings = $this->settingsRepository->getSettings();

        return view('admin.settings.recharge_api', compact('settings'));
    }

    /**
     * Update Recharge API Settings
     */
    public function updateRechargeApi(Request $request)
    {
        $validator = Validator::make($request->all(), WebsiteSettings::rechargeApiRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only([
                'recharge_api_username',
                'recharge_api_token',
                'recharge_callback_url',
                'recharge_pan_redirect_url',
                'recharge_api_url',
            ]);

            $this->settingsRepository->updateRechargeApiSettings($data);

            return redirect()->route('admin.settings.recharge-api.show')
                ->with('success', 'Recharge API settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update Recharge API settings: '.$e->getMessage())
                ->withInput();
        }
    }

    public function showMaintenance()
    {
        $settings = $this->settingsRepository->getSettings();

        return view('admin.settings.maintenance', compact('settings'));
    }

    public function updateMaintenance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maintenance_mode' => 'boolean',
            'maintenance_title' => 'nullable|string|max:255',
            'maintenance_message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'maintenance_mode' => $request->has('maintenance_mode'),
                'maintenance_title' => $request->input('maintenance_title'),
                'maintenance_message' => $request->input('maintenance_message'),
            ];

            $this->settingsRepository->updateSettings($data);

            return redirect()->route('admin.settings.maintenance.show')
                ->with('success', 'Maintenance mode updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update maintenance mode: '.$e->getMessage())
                ->withInput();
        }
    }
}
