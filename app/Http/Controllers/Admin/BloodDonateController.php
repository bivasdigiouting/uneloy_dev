<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BloodDonateRepositoryInterface;
use Illuminate\Http\Request;

class BloodDonateController extends Controller
{
    protected BloodDonateRepositoryInterface $bloodDonateRepository;

    public function __construct(BloodDonateRepositoryInterface $bloodDonateRepository)
    {
        $this->bloodDonateRepository = $bloodDonateRepository;
    }

    /**
     * Show the form for editing the Blood Donate data.
     */
    public function edit()
    {
        $bloodDonate = $this->bloodDonateRepository->getBloodDonate();

        return view('admin.benefits.blood-donate', compact('bloodDonate'));
    }

    /**
     * Update the Blood Donate data.
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
            $this->bloodDonateRepository->update($request->all());

            return redirect()->back()->with('success', 'Blood Donate content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Blood Donate content: '.$e->getMessage());
        }
    }
}
