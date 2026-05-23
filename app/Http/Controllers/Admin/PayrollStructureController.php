<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PayrollStructure;
use Illuminate\Http\Request;

class PayrollStructureController extends Controller
{
    public function __construct()
    {
        // Restrict access using Spatie permission middleware
        $this->middleware('permission:payroll-structure-list', ['only' => ['index']]);
        $this->middleware('permission:payroll-structure-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payroll-structure-edit', ['only' => ['edit', 'update']]);
    }

    public function index()
    {
        $structures = PayrollStructure::with('department')->orderBy('department_id')->get();

        return view('admin.payroll.structures.index', compact('structures'));
    }

    public function create()
    {
        $departments = Department::active()->orderBy('department_name')->get();

        return view('admin.payroll.structures.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'title' => ['nullable', 'string', 'max:255'],
            // Earnings
            'basic' => ['required', 'numeric', 'min:0'],
            'hra' => ['nullable', 'numeric', 'min:0'],
            'da' => ['nullable', 'numeric', 'min:0'],
            'ta' => ['nullable', 'numeric', 'min:0'],
            'medical' => ['nullable', 'numeric', 'min:0'],
            'special_allowance' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'eic' => ['nullable', 'numeric', 'min:0'],
            // Deductions
            'pf' => ['nullable', 'numeric', 'min:0'],
            'loan' => ['nullable', 'numeric', 'min:0'],
            'esic' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        PayrollStructure::create($data);

        return redirect()->route('admin.payroll.structures.index')
            ->with('success', 'Payroll Structure created successfully');
    }

    public function edit(PayrollStructure $structure)
    {
        $departments = Department::active()->orderBy('department_name')->get();

        return view('admin.payroll.structures.edit', compact('structure', 'departments'));
    }

    public function update(Request $request, PayrollStructure $structure)
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'title' => ['nullable', 'string', 'max:255'],
            // Earnings
            'basic' => ['required', 'numeric', 'min:0'],
            'hra' => ['nullable', 'numeric', 'min:0'],
            'da' => ['nullable', 'numeric', 'min:0'],
            'ta' => ['nullable', 'numeric', 'min:0'],
            'medical' => ['nullable', 'numeric', 'min:0'],
            'special_allowance' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'eic' => ['nullable', 'numeric', 'min:0'],
            // Deductions
            'pf' => ['nullable', 'numeric', 'min:0'],
            'loan' => ['nullable', 'numeric', 'min:0'],
            'esic' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $structure->update($data);

        return redirect()->route('admin.payroll.structures.index')
            ->with('success', 'Payroll Structure updated successfully');
    }
}
