<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseBill;
use Illuminate\Http\Request;

class ExpenseBillReportController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $expenses = Expense::active()->orderBy('expense_name')->get();

        $expenseId = $request->get('expense_id');
        $paymentMode = $request->get('payment_mode');
        $supplier = $request->get('supplier');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = ExpenseBill::with('expense')->orderBy('date', 'desc');

        if ($expenseId) {
            $query->where('expense_id', (int) $expenseId);
        }
        if ($paymentMode) {
            $query->where('payment_mode', $paymentMode);
        }
        if ($supplier) {
            $query->where('supplier', 'like', "%$supplier%");
        }
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $bills = $query->get();

        $total = $bills->sum('amount');

        return view('admin.expense-bills.report', compact('expenses', 'bills', 'total', 'expenseId', 'paymentMode', 'supplier', 'startDate', 'endDate'));
    }
}
