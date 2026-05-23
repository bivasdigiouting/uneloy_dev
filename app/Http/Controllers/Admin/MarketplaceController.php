<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MarketplaceRepositoryInterface;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    protected MarketplaceRepositoryInterface $marketplaceRepository;

    public function __construct(MarketplaceRepositoryInterface $marketplaceRepository)
    {
        $this->marketplaceRepository = $marketplaceRepository;
    }

    /**
     * Show the form for editing the Marketplace data.
     */
    public function edit()
    {
        $marketplace = $this->marketplaceRepository->getMarketplace();
        return view('admin.services.marketplace', compact('marketplace'));
    }

    /**
     * Update the Marketplace data.
     */
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'nullable|string|max:255',
            'text_description' => 'nullable|string',
            'footer_short_description' => 'nullable|string',
        ]);

        try {
            $this->marketplaceRepository->update($request->all());
            return redirect()->back()->with('success', 'Marketplace content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Marketplace content: ' . $e->getMessage());
        }
    }
}
