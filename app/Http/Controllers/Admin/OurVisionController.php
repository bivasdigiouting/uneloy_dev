<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\OurVisionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OurVisionController extends Controller
{
    protected OurVisionRepositoryInterface $ourVisionRepository;

    public function __construct(OurVisionRepositoryInterface $ourVisionRepository)
    {
        $this->ourVisionRepository = $ourVisionRepository;
    }

    /**
     * Show the form for editing the Our Vision content.
     */
    public function edit()
    {
        $ourVision = $this->ourVisionRepository->getOurVision();
        return view('admin.about-us.our-vision', compact('ourVision'));
    }

    /**
     * Update the Our Vision content in storage.
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
            $this->ourVisionRepository->updateOurVision($validated);
            return redirect()->route('admin.about-us.our-vision.edit')->with('success', 'Our Vision content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Our Vision content: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the content. Please try again.');
        }
    }
}
