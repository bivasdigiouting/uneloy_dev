<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\OurMissionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OurMissionController extends Controller
{
    protected OurMissionRepositoryInterface $ourMissionRepository;

    public function __construct(OurMissionRepositoryInterface $ourMissionRepository)
    {
        $this->ourMissionRepository = $ourMissionRepository;
    }

    /**
     * Show the form for editing the Our Mission content.
     */
    public function edit()
    {
        $ourMission = $this->ourMissionRepository->getOurMission();
        return view('admin.about-us.our-mission', compact('ourMission'));
    }

    /**
     * Update the Our Mission content in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'text_header' => 'nullable|string|max:255',
            'text_description' => 'nullable|string',
            'footer_short_description' => 'nullable|string',
        ]);

        try {
            $this->ourMissionRepository->updateOurMission($validated);
            return redirect()->route('admin.about-us.our-mission.edit')->with('success', 'Our Mission content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Our Mission content: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the content. Please try again.');
        }
    }
}
