<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UonlyByAppsUMartRepositoryInterface;
use Illuminate\Http\Request;

class UonlyByAppsUMartController extends Controller
{
    protected UonlyByAppsUMartRepositoryInterface $uMartRepository;

    public function __construct(UonlyByAppsUMartRepositoryInterface $uMartRepository)
    {
        $this->uMartRepository = $uMartRepository;
    }

    public function edit()
    {
        $uMart = $this->uMartRepository->getUMart();

        return view('admin.uonly-by-apps.u-mart', compact('uMart'));
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
            $this->uMartRepository->update($request->all());

            return redirect()->back()->with('success', 'Uonly By Apps - U-Mart content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Uonly By Apps - U-Mart content: '.$e->getMessage());
        }
    }
}
