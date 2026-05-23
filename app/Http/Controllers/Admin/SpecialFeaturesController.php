<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SpecialFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $features = SpecialFeature::select(['id', 'features_name', 'sequence', 'icon', 'description', 'is_active', 'created_at']);

            return DataTables::of($features)
                ->addIndexColumn()
                ->addColumn('icon_preview', function ($feature) {
                    if ($feature->icon_url) {
                        return '<img src="'.$feature->icon_url.'" alt="Icon" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">';
                    }

                    return '<span class="text-muted">No Icon</span>';
                })
                ->addColumn('status_badge', function ($feature) {
                    $badgeClass = $feature->is_active ? 'bg-success' : 'bg-danger';
                    $status = $feature->is_active ? 'Active' : 'Inactive';

                    return '<span class="badge '.$badgeClass.'">'.$status.'</span>';
                })
                ->addColumn('status_toggle', function ($feature) {
                    $checked = $feature->is_active ? 'checked' : '';

                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" data-id="'.$feature->id.'" '.$checked.'>
                            </div>';
                })
                ->editColumn('description', function ($feature) {
                    return \Str::limit(strip_tags($feature->description), 50);
                })
                ->editColumn('created_at', function ($feature) {
                    return $feature->created_at->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($feature) {
                    $editUrl = route('admin.special-features.edit', $feature->id);
                    $deleteUrl = route('admin.special-features.destroy', $feature->id);

                    return '
                        <div class="btn-group" role="group">
                            <a href="'.$editUrl.'" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                    data-url="'.$deleteUrl.'" 
                                    data-id="'.$feature->id.'" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['icon_preview', 'status_badge', 'status_toggle', 'action'])
                ->make(true);
        }

        return view('admin.special-features.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.special-features.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'features_name' => 'required|string|max:255',
            'sequence' => 'required|integer|min:1',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Handle file upload
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('special-features/icons', 'public');
                $data['icon'] = $iconPath;
            }

            SpecialFeature::create($data);

            return redirect()->route('admin.special-features.index')
                ->with('success', 'Special feature created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create special feature. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feature = SpecialFeature::findOrFail($id);

        return view('admin.special-features.show', compact('feature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $feature = SpecialFeature::findOrFail($id);

        return view('admin.special-features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'features_name' => 'required|string|max:255',
            'sequence' => 'required|integer|min:1',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            $feature = SpecialFeature::findOrFail($id);
            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Handle file upload
            if ($request->hasFile('icon')) {
                // Delete old icon if exists
                if ($feature->icon && Storage::disk('public')->exists($feature->icon)) {
                    Storage::disk('public')->delete($feature->icon);
                }

                $iconPath = $request->file('icon')->store('special-features/icons', 'public');
                $data['icon'] = $iconPath;
            }

            $feature->update($data);

            return redirect()->route('admin.special-features.index')
                ->with('success', 'Special feature updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update special feature. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $feature = SpecialFeature::findOrFail($id);

            // Delete icon file if exists
            if ($feature->icon && Storage::disk('public')->exists($feature->icon)) {
                Storage::disk('public')->delete($feature->icon);
            }

            $feature->delete();

            return response()->json(['success' => true, 'message' => 'Special feature deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete special feature.']);
        }
    }

    /**
     * Toggle status of special feature
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $feature = SpecialFeature::findOrFail($id);
            $feature->update(['is_active' => ! $feature->is_active]);

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status.']);
        }
    }
}
