<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CityDevelopmentRepositoryInterface;
use Illuminate\Http\Request;

class CityDevelopmentController extends Controller
{
    protected CityDevelopmentRepositoryInterface $cityDevelopmentRepository;

    public function __construct(CityDevelopmentRepositoryInterface $cityDevelopmentRepository)
    {
        $this->cityDevelopmentRepository = $cityDevelopmentRepository;
    }

    /**
     * Show the form for editing the City Development data.
     */
    public function edit()
    {
        $cityDevelopment = $this->cityDevelopmentRepository->getCityDevelopment();
        return view('admin.services.city-development', compact('cityDevelopment'));
    }

    /**
     * Update the City Development data.
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
            $this->cityDevelopmentRepository->update($request->all());
            return redirect()->back()->with('success', 'City Development content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating City Development content: ' . $e->getMessage());
        }
    }
}
