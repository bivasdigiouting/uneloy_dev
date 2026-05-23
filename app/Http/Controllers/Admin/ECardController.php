<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ECardRepositoryInterface;
use Illuminate\Http\Request;

class ECardController extends Controller
{
    protected ECardRepositoryInterface $eCardRepository;

    public function __construct(ECardRepositoryInterface $eCardRepository)
    {
        $this->eCardRepository = $eCardRepository;
    }

    /**
     * Show the form for editing the E-Card service data.
     */
    public function edit()
    {
        $eCard = $this->eCardRepository->getECardService();
        return view('admin.services.e-card', compact('eCard'));
    }

    /**
     * Update the E-Card service data.
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
            $this->eCardRepository->update($request->all());
            return redirect()->back()->with('success', 'E-Card content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating E-Card content: ' . $e->getMessage());
        }
    }
}
