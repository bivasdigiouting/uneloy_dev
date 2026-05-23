<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BannerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    protected BannerRepositoryInterface $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banners = $this->bannerRepository->getForDataTables();

            return DataTables::of($banners)
                ->addIndexColumn()
                ->addColumn('image_preview', function ($banner) {
                    if ($banner->image) {
                        return '<img src="'.asset('storage/'.$banner->image).'" alt="Banner" class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">';
                    }

                    return '<span class="text-muted">No Image</span>';
                })
                ->addColumn('banner_type', function ($banner) {
                    return '<span class="badge bg-info">'.$banner->formatted_banner_type.'</span>';
                })
                ->addColumn('status', function ($banner) {
                    return $banner->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('link', function ($banner) {
                    if ($banner->link) {
                        return '<a href="'.$banner->link.'" target="_blank" class="text-primary" title="'.$banner->link.'">'.
                               (strlen($banner->link) > 30 ? substr($banner->link, 0, 30).'...' : $banner->link).'</a>';
                    }

                    return '<span class="text-muted">No Link</span>';
                })
                ->addColumn('action', function ($banner) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.banners.edit', $banner->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteBanner('.$banner->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($banner) {
                    return $banner->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['image_preview', 'banner_type', 'status', 'link', 'action'])
                ->make(true);
        }

        return view('admin.banners.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'banner_type' => 'required|in:home_1,home_2,home_3,my_order,deposit,withdrawal,rewards',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $data = $request->only(['banner_type', 'link', 'status']);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('banners/images', 'public');
                $data['image'] = $imagePath;
            }

            $this->bannerRepository->createBanner($data);

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create banner. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $banner = $this->bannerRepository->findBanner($id);

        if (! $banner) {
            abort(404, 'Banner not found');
        }

        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'banner_type' => 'required|in:home_1,home_2,home_3,my_order,deposit,withdrawal,rewards',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $banner = $this->bannerRepository->findBanner($id);

            if (! $banner) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Banner not found.');
            }

            $data = $request->only(['banner_type', 'link', 'status']);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                    Storage::disk('public')->delete($banner->image);
                }

                $imagePath = $request->file('image')->store('banners/images', 'public');
                $data['image'] = $imagePath;
            }

            $updated = $this->bannerRepository->updateBanner($id, $data);

            if (! $updated) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Banner not found.');
            }

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update banner. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $banner = $this->bannerRepository->findBanner($id);

            if (! $banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found.',
                ], 404);
            }

            // Delete image file if exists
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $deleted = $this->bannerRepository->deleteBanner($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Banner deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete banner. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $updated = $this->bannerRepository->toggleStatus($id);

            if (! $updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Banner status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner status. Please try again.',
            ], 500);
        }
    }
}
