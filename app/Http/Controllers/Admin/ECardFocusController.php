<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ECardFocusRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ECardFocusController extends Controller
{
    protected ECardFocusRepositoryInterface $eCardFocusRepository;

    public function __construct(ECardFocusRepositoryInterface $eCardFocusRepository)
    {
        $this->eCardFocusRepository = $eCardFocusRepository;
    }

    public function edit()
    {
        $ecardFocus = $this->eCardFocusRepository->getECardFocus();
        return view('admin.about-us.ecard-focus', compact('ecardFocus'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'text_header' => 'nullable|string|max:255',
            'text_description' => 'nullable|string',
            'footer_short_description' => 'nullable|string',
        ]);

        try {
            $this->eCardFocusRepository->updateECardFocus($validated);
            return redirect()->route('admin.about-us.ecard-focus.edit')->with('success', 'e-Card Focus content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update e-Card Focus content: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the content. Please try again.');
        }
    }
}
