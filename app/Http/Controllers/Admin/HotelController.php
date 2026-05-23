<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\HotelRepositoryInterface;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected HotelRepositoryInterface $hotelRepository;

    public function __construct(HotelRepositoryInterface $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    /**
     * Show the form for editing the Hotel data.
     */
    public function edit()
    {
        $hotel = $this->hotelRepository->getHotel();
        return view('admin.e-store.hotels', compact('hotel'));
    }

    /**
     * Update the Hotel data.
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
            $this->hotelRepository->update($request->all());
            return redirect()->back()->with('success', 'Hotel content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Hotel content: ' . $e->getMessage());
        }
    }
}
