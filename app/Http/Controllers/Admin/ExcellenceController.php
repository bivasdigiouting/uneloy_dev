<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ExcellenceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExcellenceController extends Controller
{
    protected ExcellenceRepositoryInterface $excellenceRepository;

    public function __construct(ExcellenceRepositoryInterface $excellenceRepository)
    {
        $this->excellenceRepository = $excellenceRepository;
    }

    /**
     * Show the form for editing the Excellence content.
     */
    public function edit()
    {
        $excellence = $this->excellenceRepository->getExcellence();
        return view('admin.about-us.excellence', compact('excellence'));
    }

    /**
     * Update the Excellence content in storage.
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
            $this->excellenceRepository->updateExcellence($validated);
            return redirect()->route('admin.about-us.excellence.edit')->with('success', 'Excellence content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Excellence content: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the content. Please try again.');
        }
    }
}
