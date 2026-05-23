<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PayrollCredit;
use App\Models\PayrollStructure;
use App\Models\Staff;
use Illuminate\Http\Request;

class SalaryCreditController extends Controller
{
    public function __construct()
    {
        // Restrict access using Spatie permission middleware
        $this->middleware('permission:salary-credit-list', ['only' => ['index']]);
        $this->middleware('permission:salary-credit-create', ['only' => ['create', 'store']]);
    }

    public function index(Request $request)
    {
        $departments = Department::active()->orderBy('department_name')->get();
        $month = $request->get('month');
        $year = $request->get('year');
        $departmentId = $request->get('department_id');

        $query = PayrollCredit::with(['staff', 'department'])->orderByDesc('credited_at');
        if ($month) {
            $query->where('month', (int) $month);
        }
        if ($year) {
            $query->where('year', (int) $year);
        }
        if ($departmentId) {
            $query->where('department_id', (int) $departmentId);
        }

        $credits = $query->paginate(25);

        return view('admin.payroll.credits.index', compact('departments', 'credits', 'month', 'year', 'departmentId'));
    }

    public function create(Request $request)
    {
        $departments = Department::active()->orderBy('department_name')->get();
        $staff = Staff::active()->orderBy('staff_name')->get();
        $selectedDepartment = $request->get('department_id');
        $structure = null;
        if ($selectedDepartment) {
            $structure = PayrollStructure::where('department_id', $selectedDepartment)->where('is_active', true)->first();
        }

        return view('admin.payroll.credits.create', compact('departments', 'staff', 'structure', 'selectedDepartment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'staff_id' => ['required', 'exists:staff,id'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2000'],
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
        ]);

        $gross = ($data['basic'] ?? 0) + ($data['hra'] ?? 0) + ($data['da'] ?? 0) + ($data['ta'] ?? 0) + ($data['medical'] ?? 0) + ($data['special_allowance'] ?? 0) + ($data['bonus'] ?? 0) + ($data['eic'] ?? 0);
        $deductions = ($data['pf'] ?? 0) + ($data['loan'] ?? 0) + ($data['esic'] ?? 0);
        $net = $gross - $deductions;

        // Prevent duplicate crediting for the same staff in the same month/year
        $exists = PayrollCredit::where('staff_id', $data['staff_id'])
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->exists();
        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Salary already credited for this staff in the selected period.');
        }

        $credit = PayrollCredit::create([
            'staff_id' => $data['staff_id'],
            'department_id' => $data['department_id'],
            'month' => $data['month'],
            'year' => $data['year'],
            'basic' => $data['basic'],
            'hra' => $data['hra'] ?? 0,
            'da' => $data['da'] ?? 0,
            'ta' => $data['ta'] ?? 0,
            'medical' => $data['medical'] ?? 0,
            'special_allowance' => $data['special_allowance'] ?? 0,
            'bonus' => $data['bonus'] ?? 0,
            'eic' => $data['eic'] ?? 0,
            'pf' => $data['pf'] ?? 0,
            'loan' => $data['loan'] ?? 0,
            'esic' => $data['esic'] ?? 0,
            'gross_earnings' => $gross,
            'total_deductions' => $deductions,
            'net_pay' => $net,
            'status' => 'processed',
            'credited_at' => now(),
        ]);

        return redirect()->route('admin.payroll.credits.index')
            ->with('success', 'Salary credited successfully for '.$credit->staff->staff_name);
    }
}
