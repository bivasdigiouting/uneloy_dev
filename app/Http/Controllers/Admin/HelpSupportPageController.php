<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpSupportSetting;
use Illuminate\Http\Request;

class HelpSupportPageController extends Controller
{
    public function edit()
    {
        $settings = HelpSupportSetting::firstOrCreate([]);

        return view('admin.help-support.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = HelpSupportSetting::firstOrCreate([]);

        $request->validate([
            'page_title' => 'nullable|string|max:255',
            'intro_text' => 'nullable|string|max:500',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:50',
            'support_whatsapp' => 'nullable|string|max:50',
            'live_chat_url' => 'nullable|url|max:255',
            'support_address' => 'nullable|string|max:255',
            'working_hours' => 'nullable|string|max:255',
            'additional_info' => 'nullable|string',
        ]);

        $settings->update($request->only([
            'page_title',
            'intro_text',
            'support_email',
            'support_phone',
            'support_whatsapp',
            'live_chat_url',
            'support_address',
            'working_hours',
            'additional_info',
        ]));

        return redirect()->back()->with('success', 'Help & Support page updated successfully.');
    }
}
