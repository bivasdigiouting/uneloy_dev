<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{
    protected DistrictRepositoryInterface $districtRepository;

    protected StateRepositoryInterface $stateRepository;

    public function __construct(
        DistrictRepositoryInterface $districtRepository,
        StateRepositoryInterface $stateRepository
    ) {
        $this->districtRepository = $districtRepository;
        $this->stateRepository = $stateRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $districts = $this->districtRepository->getForDataTables();

            return DataTables::of($districts)
                ->addIndexColumn()
                ->addColumn('state_name', function ($district) {
                    return $district->state ? $district->state->state_name : 'N/A';
                })
                ->addColumn('status', function ($district) {
                    return $district->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($district) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.districts.show', $district->id).'" class="btn btn-sm btn-info" title="View"><i class="ti ti-eye"></i></a>';
                    $actions .= '<a href="'.route('admin.districts.edit', $district->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteDistrict('.$district->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($district) {
                    return $district->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.districts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = $this->stateRepository->getActiveStates();

        return view('admin.districts.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'district_name' => 'required|string|max:255|unique:districts,district_name',
            'state_id' => 'required|exists:states,id',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $this->districtRepository->createDistrict($request->only(['district_name', 'state_id', 'status']));

            return redirect()->route('admin.districts.index')
                ->with('success', 'District created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create district. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = $this->districtRepository->findDistrict($id);

        if (! $district) {
            return redirect()->route('admin.districts.index')
                ->with('error', 'District not found.');
        }

        return view('admin.districts.show', compact('district'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $district = $this->districtRepository->findDistrict($id);

        if (! $district) {
            return redirect()->route('admin.districts.index')
                ->with('error', 'District not found.');
        }

        $states = $this->stateRepository->getActiveStates();

        return view('admin.districts.edit', compact('district', 'states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'district_name' => 'required|string|max:255|unique:districts,district_name,'.$id,
            'state_id' => 'required|exists:states,id',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $updated = $this->districtRepository->updateDistrict($id, $request->only(['district_name', 'state_id', 'status']));

            if (! $updated) {
                return redirect()->route('admin.districts.index')
                    ->with('error', 'District not found.');
            }

            return redirect()->route('admin.districts.index')
                ->with('success', 'District updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update district. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->districtRepository->deleteDistrict($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'District not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'District deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete district. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle district status
     */
    public function toggleStatus(string $id)
    {
        try {
            $toggled = $this->districtRepository->toggleStatus($id);

            if (! $toggled) {
                return response()->json([
                    'success' => false,
                    'message' => 'District not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'District status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update district status. Please try again.',
            ], 500);
        }
    }

    /**
     * Get districts by state (for AJAX)
     */
    public function getDistrictsByState(Request $request, $state_id = null)
    {
        $stateId = $request->get('state_id') ?? $state_id ?? $request->route('state_id');

        if (! $stateId) {
            return response()->json([
                'success' => false,
                'message' => 'State ID is required.',
            ], 400);
        }

        try {
            $districts = $this->districtRepository->getDistrictsByState((int) $stateId);

            return response()->json([
                'success' => true,
                'districts' => $districts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch districts.',
            ], 500);
        }
    }
}
