<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DepartmentsExport;
use App\Http\Controllers\Controller;
use App\Imports\DepartmentsImport;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    protected DepartmentRepositoryInterface $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = $this->departmentRepository->all();

            return DataTables::of($departments)
                ->addIndexColumn()
                ->addColumn('status', function ($department) {
                    return $department->is_active ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($department) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="'.route('admin.departments.show', $department->id).'" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'.route('admin.departments.edit', $department->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a href="'.route('admin.departments.toggle-status', $department->id).'" class="btn btn-sm btn-warning toggle-status" title="Toggle Status"><i class="fas fa-toggle-on"></i></a>';
                    $btn .= '<a href="'.route('admin.departments.destroy', $department->id).'" class="btn btn-sm btn-danger delete-department" title="Delete"><i class="fas fa-trash"></i></a>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.departments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|max:255|unique:departments,department_name',
            'remarks' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $departmentData = $request->only(['department_name', 'remarks']);
        $departmentData['is_active'] = $request->has('is_active');

        $this->departmentRepository->create($departmentData);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = $this->departmentRepository->find($id);

        if (! $department) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Department not found.');
        }

        return view('admin.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = $this->departmentRepository->find($id);

        if (! $department) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Department not found.');
        }

        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = $this->departmentRepository->find($id);

        if (! $department) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Department not found.');
        }

        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|max:255|unique:departments,department_name,'.$id,
            'remarks' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $departmentData = $request->only(['department_name', 'remarks']);
        $departmentData['is_active'] = $request->has('is_active');

        $this->departmentRepository->update($id, $departmentData);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = $this->departmentRepository->find($id);

        if (! $department) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found.',
            ], 404);
        }

        $deleted = $this->departmentRepository->delete((int) $id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete department.',
        ], 500);
    }

    /**
     * Toggle department status
     */
    public function toggleStatus(string $id)
    {
        $success = $this->departmentRepository->toggleStatus($id);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Department status updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to update department status.',
        ], 500);
    }

    /**
     * Export departments to Excel
     */
    public function export()
    {
        return Excel::download(new DepartmentsExport, 'departments.xlsx');
    }

    /**
     * Export departments to PDF
     */
    public function exportPdf()
    {
        return Excel::download(new DepartmentsExport, 'departments.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    /**
     * Import departments from Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please select a valid Excel file.');
        }

        try {
            Excel::import(new DepartmentsImport, $request->file('file'));

            return redirect()->route('admin.departments.index')
                ->with('success', 'Departments imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing departments: '.$e->getMessage());
        }
    }
}
