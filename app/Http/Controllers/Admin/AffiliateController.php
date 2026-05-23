<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AffiliateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AffiliateController extends Controller
{
    protected AffiliateRepositoryInterface $affiliateRepository;

    public function __construct(AffiliateRepositoryInterface $affiliateRepository)
    {
        $this->affiliateRepository = $affiliateRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $affiliates = $this->affiliateRepository->getForDataTables();

            return DataTables::of($affiliates)
                ->addIndexColumn()
                ->addColumn('icon_preview', function ($affiliate) {
                    if ($affiliate->icon) {
                        return '<img src="'.asset('storage/'.$affiliate->icon).'" alt="Icon" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">';
                    }

                    return '<span class="text-muted">No Icon</span>';
                })
                ->addColumn('status', function ($affiliate) {
                    return $affiliate->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($affiliate) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.affiliates.edit', $affiliate->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteAffiliate('.$affiliate->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($affiliate) {
                    return $affiliate->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['icon_preview', 'status', 'action'])
                ->make(true);
        }

        return view('admin.affiliates.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.affiliates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:affiliates,service_name',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $data = $request->only(['service_name', 'status']);

            // Handle icon upload
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('affiliates/icons', 'public');
                $data['icon'] = $iconPath;
            }

            $this->affiliateRepository->createAffiliate($data);

            return redirect()->route('admin.affiliates.index')
                ->with('success', 'Affiliate created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create affiliate. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $affiliate = $this->affiliateRepository->findAffiliate($id);

        if (! $affiliate) {
            abort(404, 'Affiliate not found');
        }

        return view('admin.affiliates.edit', compact('affiliate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:affiliates,service_name,'.$id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $affiliate = $this->affiliateRepository->findAffiliate($id);

            if (! $affiliate) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Affiliate not found.');
            }

            $data = $request->only(['service_name', 'status']);

            // Handle icon upload
            if ($request->hasFile('icon')) {
                // Delete old icon if exists
                if ($affiliate->icon && Storage::disk('public')->exists($affiliate->icon)) {
                    Storage::disk('public')->delete($affiliate->icon);
                }

                $iconPath = $request->file('icon')->store('affiliates/icons', 'public');
                $data['icon'] = $iconPath;
            }

            $updated = $this->affiliateRepository->updateAffiliate($id, $data);

            if (! $updated) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Affiliate not found.');
            }

            return redirect()->route('admin.affiliates.index')
                ->with('success', 'Affiliate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update affiliate. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $affiliate = $this->affiliateRepository->findAffiliate($id);

            if (! $affiliate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Affiliate not found.',
                ], 404);
            }

            // Delete icon file if exists
            if ($affiliate->icon && Storage::disk('public')->exists($affiliate->icon)) {
                Storage::disk('public')->delete($affiliate->icon);
            }

            $deleted = $this->affiliateRepository->deleteAffiliate($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Affiliate not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Affiliate deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete affiliate. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle affiliate status
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $updated = $this->affiliateRepository->toggleStatus($id);

            if (! $updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Affiliate not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Affiliate status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update affiliate status. Please try again.',
            ], 500);
        }
    }
}
