<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WebsiteBenefitRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WebsiteBenefitController extends Controller
{
    protected $websiteBenefitRepository;

    public function __construct(WebsiteBenefitRepositoryInterface $websiteBenefitRepository)
    {
        $this->websiteBenefitRepository = $websiteBenefitRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $benefits = $this->websiteBenefitRepository->getForDataTable();

            return DataTables::of($benefits)
                ->addIndexColumn()
                ->addColumn('icon_preview', function ($benefit) {
                    if ($benefit->icon_url) {
                        return '<img src="'.$benefit->icon_url.'" alt="Icon" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">';
                    }

                    return '<span class="text-muted">No Icon</span>';
                })
                ->addColumn('status_badge', function ($benefit) {
                    $badgeClass = $benefit->is_active ? 'bg-success' : 'bg-danger';
                    $status = $benefit->is_active ? 'Active' : 'Inactive';

                    return '<span class="badge '.$badgeClass.'">'.$status.'</span>';
                })
                ->addColumn('status_toggle', function ($benefit) {
                    $checked = $benefit->is_active ? 'checked' : '';

                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" data-id="'.$benefit->id.'" '.$checked.'>
                            </div>';
                })
                ->editColumn('created_at', function ($benefit) {
                    return $benefit->created_at->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($benefit) {
                    $editUrl = route('admin.website-benefits.edit', $benefit->id);
                    $deleteUrl = route('admin.website-benefits.destroy', $benefit->id);

                    return '
                        <div class="btn-group" role="group">
                            <a href="'.$editUrl.'" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                    data-url="'.$deleteUrl.'" 
                                    data-id="'.$benefit->id.'" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['icon_preview', 'status_badge', 'status_toggle', 'action'])
                ->make(true);
        }

        return view('admin.website-benefits.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.website-benefits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'benefit_name' => 'required|string|max:255',
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

            $this->websiteBenefitRepository->create($data);

            return redirect()->route('admin.website-benefits.index')
                ->with('success', 'Website benefit created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create website benefit. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $benefit = $this->websiteBenefitRepository->findById($id);

        return view('admin.website-benefits.show', compact('benefit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $benefit = $this->websiteBenefitRepository->findById($id);

        return view('admin.website-benefits.edit', compact('benefit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'benefit_name' => 'required|string|max:255',
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

            $this->websiteBenefitRepository->update($id, $data);

            return redirect()->route('admin.website-benefits.index')
                ->with('success', 'Website benefit updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update website benefit. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->websiteBenefitRepository->delete($id);

            return response()->json(['success' => true, 'message' => 'Website benefit deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete website benefit.']);
        }
    }

    /**
     * Toggle status of website benefit
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $benefit = $this->websiteBenefitRepository->findById($id);
            $this->websiteBenefitRepository->update($id, ['is_active' => ! $benefit->is_active]);

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status.']);
        }
    }
}
