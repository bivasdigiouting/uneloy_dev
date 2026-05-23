<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExpensesExport;
use App\Http\Controllers\Controller;
use App\Imports\ExpensesImport;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    protected ExpenseRepositoryInterface $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $expenses = $this->expenseRepository->all();

            return datatables()->of($expenses)
                ->addIndexColumn()
                ->addColumn('status', function ($expense) {
                    return $expense->is_active ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('amount_formatted', function ($expense) {
                    return '₹'.number_format($expense->amount, 2);
                })
                ->addColumn('action', function ($expense) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="'.route('admin.expenses.show', $expense->id).'" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'.route('admin.expenses.edit', $expense->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning toggle-status" data-url="'.route('admin.expenses.toggle-status', $expense->id).'" title="Toggle Status"><i class="fas fa-toggle-on"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-expense" data-url="'.route('admin.expenses.destroy', $expense->id).'" title="Delete"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.expenses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expense_name' => 'required|string|max:255|unique:expenses,expense_name',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->expenseRepository->create($request->only([
                'expense_name',
                'description',
                'amount',
                'is_active',
            ]));

            return redirect()->route('admin.expenses.index')
                ->with('success', 'Expense created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create expense. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = $this->expenseRepository->find($id);

        if (! $expense) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'Expense not found.');
        }

        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = $this->expenseRepository->find($id);

        if (! $expense) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'Expense not found.');
        }

        return view('admin.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = $this->expenseRepository->find($id);

        if (! $expense) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'Expense not found.');
        }

        $validator = Validator::make($request->all(), [
            'expense_name' => 'required|string|max:255|unique:expenses,expense_name,'.$id,
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->expenseRepository->update($id, $request->only([
                'expense_name',
                'description',
                'amount',
                'is_active',
            ]));

            return redirect()->route('admin.expenses.index')
                ->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update expense. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->expenseRepository->delete($id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense cannot be deleted or not found.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expense.',
            ], 500);
        }
    }

    /**
     * Toggle expense status
     */
    public function toggleStatus(string $id)
    {
        try {
            $toggled = $this->expenseRepository->toggleStatus($id);

            if ($toggled) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense status updated successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expense status.',
            ], 500);
        }
    }

    /**
     * Export expenses to Excel
     */
    public function export()
    {
        return Excel::download(new ExpensesExport, 'expenses.xlsx');
    }

    /**
     * Export expenses to PDF
     */
    public function exportPdf()
    {
        return Excel::download(new ExpensesExport, 'expenses.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    /**
     * Import expenses from Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please select a valid Excel file.');
        }

        try {
            Excel::import(new ExpensesImport, $request->file('file'));

            return redirect()->route('admin.expenses.index')
                ->with('success', 'Expenses imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to import expenses. Please check the file format.');
        }
    }
}
