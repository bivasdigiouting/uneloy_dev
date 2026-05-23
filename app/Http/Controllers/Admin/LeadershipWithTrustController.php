<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LeadershipWithTrustRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadershipWithTrustController extends Controller
{
    protected LeadershipWithTrustRepositoryInterface $leadershipWithTrustRepository;

    public function __construct(LeadershipWithTrustRepositoryInterface $leadershipWithTrustRepository)
    {
        $this->leadershipWithTrustRepository = $leadershipWithTrustRepository;
    }

    /**
     * Show the form for editing the Leadership With Trust content.
     */
    public function edit()
    {
        $leadershipWithTrust = $this->leadershipWithTrustRepository->getLeadershipWithTrust();
        return view('admin.about-us.leadership-with-trust', compact('leadershipWithTrust'));
    }

    /**
     * Update the Leadership With Trust content in storage.
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
            $this->leadershipWithTrustRepository->updateLeadershipWithTrust($validated);
            return redirect()->route('admin.about-us.leadership-with-trust.edit')->with('success', 'Leadership With Trust content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Leadership With Trust content: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the content. Please try again.');
        }
    }
}
