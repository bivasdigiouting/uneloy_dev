<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BusinessFocusRepositoryInterface;
use Illuminate\Http\Request;

class BusinessFocusController extends Controller
{
    protected $businessFocusRepository;

    public function __construct(BusinessFocusRepositoryInterface $businessFocusRepository)
    {
        $this->businessFocusRepository = $businessFocusRepository;
    }

    /**
     * Show the form for editing the Business Focus information.
     */
    public function edit()
    {
        $businessFocus = $this->businessFocusRepository->getBusinessFocus();

        return view('admin.about-us.business-focus', compact('businessFocus'));
    }

    /**
     * Update the Business Focus information in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'required|string|max:255',
            'text_description' => 'required|string',
            'footer_short_description' => 'required|string',
        ]);

        try {
            $this->businessFocusRepository->update($request->all());

            return redirect()->route('admin.about-us.business-focus.edit')
                ->with('success', 'Business Focus information updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.about-us.business-focus.edit')
                ->with('error', 'Error updating Business Focus information: '.$e->getMessage());
        }
    }
}
