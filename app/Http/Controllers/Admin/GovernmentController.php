<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Government;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GovernmentController extends Controller
{
    /**
     * Show the form for editing the Government information.
     */
    public function edit()
    {
        $government = Government::first();
        return view('admin.government.edit', compact('government'));
    }

    /**
     * Update the Government information in storage.
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
            $government = Government::first() ?? new Government();
            
            $data = $request->only(['text_header', 'text_description', 'footer_short_description']);
            
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($government->image && Storage::disk('public')->exists($government->image)) {
                    Storage::disk('public')->delete($government->image);
                }

                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('governments', $filename, 'public');
                $data['image'] = $path;
            }

            $government->fill($data);
            $government->save();

            return redirect()->route('admin.government.edit')
                ->with('success', 'Government information updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.government.edit')
                ->with('error', 'Error updating Government information: '.$e->getMessage());
        }
    }
}
