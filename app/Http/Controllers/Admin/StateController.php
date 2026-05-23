<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\StateRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StateController extends Controller
{
    protected StateRepositoryInterface $stateRepository;

    public function __construct(StateRepositoryInterface $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $states = $this->stateRepository->getForDataTables();

            return DataTables::of($states)
                ->addIndexColumn()
                ->addColumn('status', function ($state) {
                    return $state->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($state) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.states.show', $state->id).'" class="btn btn-sm btn-info" title="View"><i class="ti ti-eye"></i></a>';
                    $actions .= '<a href="'.route('admin.states.edit', $state->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteState('.$state->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($state) {
                    return $state->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.states.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.states.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'state_name' => 'required|string|max:255|unique:states,state_name',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $this->stateRepository->createState($request->only(['state_name', 'status']));

            return redirect()->route('admin.states.index')
                ->with('success', 'State created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create state. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $state = $this->stateRepository->findState($id);

        if (! $state) {
            return redirect()->route('admin.states.index')
                ->with('error', 'State not found.');
        }

        return view('admin.states.show', compact('state'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $state = $this->stateRepository->findState($id);

        if (! $state) {
            return redirect()->route('admin.states.index')
                ->with('error', 'State not found.');
        }

        return view('admin.states.edit', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'state_name' => 'required|string|max:255|unique:states,state_name,'.$id,
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $updated = $this->stateRepository->updateState($id, $request->only(['state_name', 'status']));

            if (! $updated) {
                return redirect()->route('admin.states.index')
                    ->with('error', 'State not found.');
            }

            return redirect()->route('admin.states.index')
                ->with('success', 'State updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update state. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->stateRepository->deleteState($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'State not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'State deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete state. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle state status
     */
    public function toggleStatus(string $id)
    {
        try {
            $toggled = $this->stateRepository->toggleStatus($id);

            if (! $toggled) {
                return response()->json([
                    'success' => false,
                    'message' => 'State not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'State status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update state status. Please try again.',
            ], 500);
        }
    }
}
