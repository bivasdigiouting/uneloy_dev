<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    protected HospitalRepositoryInterface $hospitalRepository;

    public function __construct(HospitalRepositoryInterface $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
    }

    public function edit()
    {
        $hospital = $this->hospitalRepository->getHospital();

        return view('admin.e-store.hospitals', compact('hospital'));
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
            $this->hospitalRepository->update($request->all());

            return redirect()->back()->with('success', 'Hospital content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Hospital content: '.$e->getMessage());
        }
    }
}
