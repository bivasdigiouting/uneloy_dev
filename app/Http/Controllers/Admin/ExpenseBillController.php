<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ExpenseBillRepositoryInterface;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExpenseBillController extends Controller
{
    protected ExpenseBillRepositoryInterface $expenseBillRepository;

    protected ExpenseRepositoryInterface $expenseRepository;

    public function __construct(
        ExpenseBillRepositoryInterface $expenseBillRepository,
        ExpenseRepositoryInterface $expenseRepository
    ) {
        $this->expenseBillRepository = $expenseBillRepository;
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Show the form for creating a new expense bill.
     */
    public function create()
    {
        $expenses = $this->expenseRepository->getActive();
        $indianBanks = $this->getIndianBanks();

        return view('admin.expense-bills.create', compact('expenses', 'indianBanks'));
    }

    /**
     * Store a newly created expense bill in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'expense_id' => 'required|exists:expenses,id',
            'amount' => 'required|numeric|min:0',
            'bill_no' => 'required|string|max:255',
            'payment_mode' => 'required|in:cash,bank,upi',
            'bank_account_no' => 'required_if:payment_mode,bank|nullable|string|max:255',
            'ifsc_code' => 'required_if:payment_mode,bank|nullable|string|max:11',
            'bank_name' => 'required_if:payment_mode,bank|nullable|string|max:255',
            'branch_name' => 'required_if:payment_mode,bank|nullable|string|max:255',
            'upi_id' => 'required_if:payment_mode,upi|nullable|string|max:255',
            'supplier' => 'required|string|max:255',
            'bill_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            $data = $request->only([
                'date',
                'expense_id',
                'amount',
                'bill_no',
                'payment_mode',
                'bank_account_no',
                'ifsc_code',
                'bank_name',
                'branch_name',
                'upi_id',
                'supplier',
                'description',
            ]);

            // Handle file upload
            if ($request->hasFile('bill_file')) {
                $file = $request->file('bill_file');
                $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs('expense-bills', $fileName, 'public');
                $data['bill_file'] = $filePath;
            }

            // Set default status
            $data['status'] = true;

            $this->expenseBillRepository->create($data);

            return redirect()->route('admin.expense-bills.create')
                ->with('success', 'Expense bill created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create expense bill. Please try again.')
                ->withInput();
        }
    }

    /**
     * Get list of Indian banks
     */
    private function getIndianBanks(): array
    {
        return [
            'State Bank of India',
            'HDFC Bank',
            'ICICI Bank',
            'Punjab National Bank',
            'Bank of Baroda',
            'Canara Bank',
            'Union Bank of India',
            'Bank of India',
            'Indian Bank',
            'Central Bank of India',
            'Indian Overseas Bank',
            'UCO Bank',
            'Bank of Maharashtra',
            'Punjab & Sind Bank',
            'Axis Bank',
            'Kotak Mahindra Bank',
            'IndusInd Bank',
            'Yes Bank',
            'IDFC First Bank',
            'Federal Bank',
            'South Indian Bank',
            'Karur Vysya Bank',
            'Tamilnad Mercantile Bank',
            'Lakshmi Vilas Bank',
            'Dhanlaxmi Bank',
            'RBL Bank',
            'Bandhan Bank',
            'ESAF Small Finance Bank',
            'Equitas Small Finance Bank',
            'Jana Small Finance Bank',
            'Ujjivan Small Finance Bank',
            'AU Small Finance Bank',
            'Suryoday Small Finance Bank',
            'North East Small Finance Bank',
            'Capital Small Finance Bank',
            'Fincare Small Finance Bank',
        ];
    }
}
