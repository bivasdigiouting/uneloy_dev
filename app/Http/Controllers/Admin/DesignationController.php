<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DesignationsExport;
use App\Http\Controllers\Controller;
use App\Imports\DesignationsImport;
use App\Repositories\Interfaces\DesignationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    protected DesignationRepositoryInterface $designationRepository;

    public function __construct(DesignationRepositoryInterface $designationRepository)
    {
        $this->designationRepository = $designationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $designations = $this->designationRepository->all();

            return DataTables::of($designations)
                ->addIndexColumn()
                ->addColumn('remarks', function ($designation) {
                    return $designation->remarks ?? '-';
                })
                ->addColumn('status', function ($designation) {
                    return $designation->is_active ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($designation) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="'.route('admin.designations.show', $designation->id).'" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'.route('admin.designations.edit', $designation->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a href="'.route('admin.designations.toggle-status', $designation->id).'" class="btn btn-sm btn-warning toggle-status" title="Toggle Status"><i class="fas fa-toggle-on"></i></a>';
                    $btn .= '<a href="'.route('admin.designations.destroy', $designation->id).'" class="btn btn-sm btn-danger delete-designation" title="Delete"><i class="fas fa-trash"></i></a>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.designations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.designations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designation_name' => 'required|string|max:255|unique:designations,designation_name',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->designationRepository->create($request->only([
                'designation_name',
                'is_active',
            ]));

            return redirect()->route('admin.designations.index')
                ->with('success', 'Designation created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create designation. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $designation = $this->designationRepository->find($id);

        if (! $designation) {
            return redirect()->route('admin.designations.index')
                ->with('error', 'Designation not found.');
        }

        return view('admin.designations.show', compact('designation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $designation = $this->designationRepository->find($id);

        if (! $designation) {
            return redirect()->route('admin.designations.index')
                ->with('error', 'Designation not found.');
        }

        return view('admin.designations.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $designation = $this->designationRepository->find($id);

        if (! $designation) {
            return redirect()->route('admin.designations.index')
                ->with('error', 'Designation not found.');
        }

        $validator = Validator::make($request->all(), [
            'designation_name' => 'required|string|max:255|unique:designations,designation_name,'.$id,
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->designationRepository->update($id, $request->only([
                'designation_name',
                'is_active',
            ]));

            return redirect()->route('admin.designations.index')
                ->with('success', 'Designation updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update designation. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->designationRepository->delete((int) $id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Designation deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete designation. It may have related records.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the designation.',
            ], 500);
        }
    }

    /**
     * Toggle designation status
     */
    public function toggleStatus(string $id)
    {
        try {
            $updated = $this->designationRepository->toggleStatus($id);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Designation status updated successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update designation status.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the designation status.',
            ], 500);
        }
    }

    /**
     * Import designations from Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please select a valid Excel file.');
        }

        try {
            Excel::import(new DesignationsImport, $request->file('file'));

            return redirect()->route('admin.designations.index')
                ->with('success', 'Designations imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to import designations. Please check the file format.');
        }
    }

    /**
     * Export designations to Excel
     */
    public function export()
    {
        return Excel::download(new DesignationsExport, 'designations.xlsx');
    }

    /**
     * Export designations to PDF
     */
    public function exportPdf()
    {
        return Excel::download(new DesignationsExport, 'designations.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
}
