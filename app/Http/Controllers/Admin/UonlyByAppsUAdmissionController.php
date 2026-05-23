<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UonlyByAppsUAdmissionRepositoryInterface;
use Illuminate\Http\Request;

class UonlyByAppsUAdmissionController extends Controller
{
    protected UonlyByAppsUAdmissionRepositoryInterface $uAdmissionRepository;

    public function __construct(UonlyByAppsUAdmissionRepositoryInterface $uAdmissionRepository)
    {
        $this->uAdmissionRepository = $uAdmissionRepository;
    }

    public function edit()
    {
        $uAdmission = $this->uAdmissionRepository->getUAdmission();

        return view('admin.uonly-by-apps.u-admission', compact('uAdmission'));
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
            $this->uAdmissionRepository->update($request->all());

            return redirect()->back()->with('success', 'Uonly By Apps - U-Admission content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Uonly By Apps - U-Admission content: '.$e->getMessage());
        }
    }
}
