<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LegalRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LegalController extends Controller
{
    protected LegalRepositoryInterface $legalRepository;

    public function __construct(LegalRepositoryInterface $legalRepository)
    {
        $this->legalRepository = $legalRepository;
    }

    /**
     * Show the form for editing the Legals content.
     */
    public function edit()
    {
        $legal = $this->legalRepository->getLegal();
        return view('admin.about-us.legals', compact('legal'));
    }

    /**
     * Update the Legals content in storage.
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
            $this->legalRepository->updateLegal($validated);
            return redirect()->route('admin.about-us.legals.edit')->with('success', 'Legals content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Legals content: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the content. Please try again.');
        }
    }
}
