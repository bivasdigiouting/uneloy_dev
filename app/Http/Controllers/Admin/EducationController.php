<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    protected EducationRepositoryInterface $educationRepository;

    public function __construct(EducationRepositoryInterface $educationRepository)
    {
        $this->educationRepository = $educationRepository;
    }

    /**
     * Show the form for editing the Education data.
     */
    public function edit()
    {
        $education = $this->educationRepository->getEducation();
        return view('admin.services.education', compact('education'));
    }

    /**
     * Update the Education data.
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
            $this->educationRepository->update($request->all());
            return redirect()->back()->with('success', 'Education content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Education content: ' . $e->getMessage());
        }
    }
}
