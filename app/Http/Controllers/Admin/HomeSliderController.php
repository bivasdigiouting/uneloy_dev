<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\HomeSliderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HomeSliderController extends Controller
{
    protected HomeSliderRepositoryInterface $homeSliderRepository;

    public function __construct(HomeSliderRepositoryInterface $homeSliderRepository)
    {
        $this->homeSliderRepository = $homeSliderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $homeSliders = $this->homeSliderRepository->getForDataTables();

            return DataTables::of($homeSliders)
                ->addIndexColumn()
                ->addColumn('image_preview', function ($homeSlider) {
                    if ($homeSlider->image) {
                        return '<img src="'.asset('storage/'.$homeSlider->image).'" alt="Slider Image" class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">';
                    }

                    return '<span class="text-muted">No Image</span>';
                })
                ->addColumn('text_header_display', function ($homeSlider) {
                    return $homeSlider->text_header ? \Str::limit($homeSlider->text_header, 30) : '-';
                })
                ->addColumn('text_description_display', function ($homeSlider) {
                    return $homeSlider->text_description ? \Str::limit($homeSlider->text_description, 50) : '-';
                })
                ->addColumn('show_on_portal_badge', function ($homeSlider) {
                    $badgeClass = $homeSlider->show_on_portal === 'yes' ? 'bg-success' : 'bg-secondary';

                    return '<span class="badge '.$badgeClass.'">'.ucfirst($homeSlider->show_on_portal).'</span>';
                })
                ->addColumn('is_active_badge', function ($homeSlider) {
                    $badgeClass = $homeSlider->is_active ? 'bg-success' : 'bg-danger';
                    $status = $homeSlider->is_active ? 'Active' : 'Inactive';

                    return '<span class="badge '.$badgeClass.'">'.$status.'</span>';
                })
                ->editColumn('created_at', function ($homeSlider) {
                    return $homeSlider->created_at->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($homeSlider) {
                    $editUrl = route('admin.home-sliders.edit', $homeSlider->id);
                    $deleteUrl = route('admin.home-sliders.destroy', $homeSlider->id);

                    return '
                        <div class="btn-group" role="group">
                            <a href="'.$editUrl.'" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                    data-url="'.$deleteUrl.'" 
                                    data-id="'.$homeSlider->id.'" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['image_preview', 'show_on_portal_badge', 'is_active_badge', 'action'])
                ->make(true);
        }

        return view('admin.home-sliders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextSequence = $this->homeSliderRepository->getNextSequence();

        return view('admin.home-sliders.create', compact('nextSequence'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'nullable|string|max:255',
            'text_description' => 'nullable|string',
            'show_on_portal' => 'required|in:0,1',
            'sequence_no' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('home-sliders', 'public');
        }

        $this->homeSliderRepository->create($data);

        return redirect()->route('admin.home-sliders.index')
            ->with('success', 'Home slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $homeSlider = $this->homeSliderRepository->find($id);

        if (! $homeSlider) {
            return redirect()->route('admin.home-sliders.index')
                ->with('error', 'Home slider not found.');
        }

        return view('admin.home-sliders.show', compact('homeSlider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $homeSlider = $this->homeSliderRepository->find($id);

        if (! $homeSlider) {
            return redirect()->route('admin.home-sliders.index')
                ->with('error', 'Home slider not found.');
        }

        return view('admin.home-sliders.edit', compact('homeSlider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $homeSlider = $this->homeSliderRepository->find($id);

        if (! $homeSlider) {
            return redirect()->route('admin.home-sliders.index')
                ->with('error', 'Home slider not found.');
        }

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'nullable|string|max:255',
            'text_description' => 'nullable|string',
            'show_on_portal' => 'required|in:0,1',
            'sequence_no' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($homeSlider->image && Storage::disk('public')->exists($homeSlider->image)) {
                Storage::disk('public')->delete($homeSlider->image);
            }

            $data['image'] = $request->file('image')->store('home-sliders', 'public');
        }

        $this->homeSliderRepository->update($id, $data);

        return redirect()->route('admin.home-sliders.index')
            ->with('success', 'Home slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $homeSlider = $this->homeSliderRepository->find($id);

        if (! $homeSlider) {
            return response()->json(['error' => 'Home slider not found.'], 404);
        }

        // Delete image if exists
        if ($homeSlider->image && Storage::disk('public')->exists($homeSlider->image)) {
            Storage::disk('public')->delete($homeSlider->image);
        }

        $this->homeSliderRepository->delete($id);

        return response()->json(['success' => 'Home slider deleted successfully.']);
    }

    /**
     * Toggle home slider status
     */
    public function toggleStatus(string $id)
    {
        $result = $this->homeSliderRepository->toggleStatus($id);

        if (! $result) {
            return response()->json(['error' => 'Home slider not found.'], 404);
        }

        return response()->json(['success' => 'Status updated successfully.']);
    }
}
