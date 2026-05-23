<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\OnDemandServiceRepositoryInterface;
use Illuminate\Http\Request;

class OnDemandServiceController extends Controller
{
    protected OnDemandServiceRepositoryInterface $onDemandServiceRepository;

    public function __construct(OnDemandServiceRepositoryInterface $onDemandServiceRepository)
    {
        $this->onDemandServiceRepository = $onDemandServiceRepository;
    }

    /**
     * Show the form for editing the On Demand Service data.
     */
    public function edit()
    {
        $onDemandService = $this->onDemandServiceRepository->getOnDemandService();
        return view('admin.services.on-demand-service', compact('onDemandService'));
    }

    /**
     * Update the On Demand Service data.
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
            $this->onDemandServiceRepository->update($request->all());
            return redirect()->back()->with('success', 'On Demand Service content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating On Demand Service content: ' . $e->getMessage());
        }
    }
}
