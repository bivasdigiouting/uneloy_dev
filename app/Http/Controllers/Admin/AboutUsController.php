<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AboutUsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    protected $aboutUsRepository;

    public function __construct(AboutUsRepositoryInterface $aboutUsRepository)
    {
        $this->aboutUsRepository = $aboutUsRepository;
    }

    /**
     * Show the form for editing the About Us information.
     */
    public function edit()
    {
        $aboutUs = $this->aboutUsRepository->getAboutUs();

        return view('admin.about-us.edit', compact('aboutUs'));
    }

    /**
     * Update the About Us information in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'required|string|max:255',
            'text_description' => 'required|string',
            'footer_short_description' => 'required|string',
        ]);

        try {
            $this->aboutUsRepository->update($request->all());

            return redirect()->route('admin.about-us.edit')
                ->with('success', 'About Us information updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.about-us.edit')
                ->with('error', 'Error updating About Us information: '.$e->getMessage());
        }
    }

    /**
     * Show the Organization Profile edit page (uses same About Us data).
     */
    public function organizationProfileEdit()
    {
        $aboutUs = $this->aboutUsRepository->getAboutUs();

        return view('admin.about-us.organization-profile', compact('aboutUs'));
    }

    /**
     * Update Organization Profile content (persists to About Us storage).
     */
    public function organizationProfileUpdate(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'required|string|max:255',
            'text_description' => 'required|string',
            'footer_short_description' => 'required|string',
        ]);

        try {
            $this->aboutUsRepository->update($request->all());

            return redirect()->route('admin.about-us.organization-profile.edit')
                ->with('success', 'Organization Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.about-us.organization-profile.edit')
                ->with('error', 'Error updating Organization Profile: '.$e->getMessage());
        }
    }
}
