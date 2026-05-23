<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\RealEstateBusinessRepositoryInterface;
use Illuminate\Http\Request;

class RealEstateBusinessController extends Controller
{
    protected RealEstateBusinessRepositoryInterface $realEstateBusinessRepository;

    public function __construct(RealEstateBusinessRepositoryInterface $realEstateBusinessRepository)
    {
        $this->realEstateBusinessRepository = $realEstateBusinessRepository;
    }

    /**
     * Show the form for editing the Real Estate Business data.
     */
    public function edit()
    {
        $realEstateBusiness = $this->realEstateBusinessRepository->getRealEstateBusiness();
        return view('admin.services.real-estate-business', compact('realEstateBusiness'));
    }

    /**
     * Update the Real Estate Business data.
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
            $this->realEstateBusinessRepository->update($request->all());
            return redirect()->back()->with('success', 'Real Estate Business content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Real Estate Business content: ' . $e->getMessage());
        }
    }
}
