<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UonlyByAppsEducationRepositoryInterface;
use Illuminate\Http\Request;

class UonlyByAppsEducationController extends Controller
{
    protected UonlyByAppsEducationRepositoryInterface $educationRepository;

    public function __construct(UonlyByAppsEducationRepositoryInterface $educationRepository)
    {
        $this->educationRepository = $educationRepository;
    }

    public function edit()
    {
        $education = $this->educationRepository->getEducation();

        return view('admin.uonly-by-apps.education', compact('education'));
    }

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

            return redirect()->back()->with('success', 'Uonly By Apps - Education content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Uonly By Apps - Education content: '.$e->getMessage());
        }
    }
}
