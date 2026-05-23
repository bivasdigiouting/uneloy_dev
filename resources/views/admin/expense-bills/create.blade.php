@extends('layouts.admin')

@section('title', 'Create Expense Bill')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create Expense Bill</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Master Module
                    </li>
                    <li class="breadcrumb-item">
                        Expense Bills
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="mb-2">
                <a href="{{ route('admin.expense-bills.report') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-list me-2"></i>View All Bills
                </a>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <h5>Create New Expense Bill</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.expense-bills.store') }}" method="POST" enctype="multipart/form-data" id="expenseBillForm">
                        @csrf
                        <div class="row">
                            <!-- Date -->
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Expense Particular -->
                            <div class="col-md-6 mb-3">
                                <label for="expense_id" class="form-label">Expense Particular <span class="text-danger">*</span></label>
                                <select class="form-select @error('expense_id') is-invalid @enderror" 
                                        id="expense_id" name="expense_id" required>
                                    <option value="">Select Expense</option>
                                    @foreach($expenses as $expense)
                                        <option value="{{ $expense->id }}" {{ old('expense_id') == $expense->id ? 'selected' : '' }}>
                                            {{ $expense->expense_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('expense_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount', '0.00') }}" 
                                       step="0.01" min="0" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bill No -->
                            <div class="col-md-6 mb-3">
                                <label for="bill_no" class="form-label">Bill No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bill_no') is-invalid @enderror" 
                                       id="bill_no" name="bill_no" value="{{ old('bill_no') }}" 
                                       placeholder="Enter bill number" required>
                                @error('bill_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Mode -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_mode" class="form-label">Payment Mode <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_mode') is-invalid @enderror" 
                                        id="payment_mode" name="payment_mode" required>
                                    <option value="">Select Payment Mode</option>
                                    <option value="cash" {{ old('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank" {{ old('payment_mode') == 'bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="upi" {{ old('payment_mode') == 'upi' ? 'selected' : '' }}>UPI</option>
                                </select>
                                @error('payment_mode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div class="col-md-6 mb-3">
                                <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                       id="supplier" name="supplier" value="{{ old('supplier') }}" 
                                       placeholder="Enter supplier name" required>
                                @error('supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bank Details (Hidden by default) -->
                        <div id="bankDetails" class="row" style="display: none;">
                            <div class="col-12 mb-3">
                                <h6 class="text-primary"><i class="ti ti-building-bank me-2"></i>Bank Details</h6>
                                <hr>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="bank_account_no" class="form-label">Bank Account No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bank_account_no') is-invalid @enderror" 
                                       id="bank_account_no" name="bank_account_no" value="{{ old('bank_account_no') }}" 
                                       placeholder="Enter bank account number">
                                @error('bank_account_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ifsc_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" 
                                       id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" 
                                       placeholder="Enter IFSC code" maxlength="11">
                                @error('ifsc_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <select class="form-select @error('bank_name') is-invalid @enderror" 
                                        id="bank_name" name="bank_name">
                                    <option value="">Select Bank</option>
                                    @foreach($indianBanks as $bank)
                                        <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>
                                            {{ $bank }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('branch_name') is-invalid @enderror" 
                                       id="branch_name" name="branch_name" value="{{ old('branch_name') }}" 
                                       placeholder="Enter branch name">
                                @error('branch_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- UPI Details (Hidden by default) -->
                        <div id="upiDetails" class="row" style="display: none;">
                            <div class="col-12 mb-3">
                                <h6 class="text-warning"><i class="ti ti-device-mobile me-2"></i>UPI Details</h6>
                                <hr>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="upi_id" class="form-label">UPI ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('upi_id') is-invalid @enderror" 
                                       id="upi_id" name="upi_id" value="{{ old('upi_id') }}" 
                                       placeholder="Enter UPI ID (e.g., user@paytm)">
                                @error('upi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Upload Bill/Invoice -->
                            <div class="col-md-6 mb-3">
                                <label for="bill_file" class="form-label">Upload Bill/Invoice</label>
                                <input type="file" class="form-control @error('bill_file') is-invalid @enderror" 
                                       id="bill_file" name="bill_file" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Allowed formats: PDF, JPG, JPEG, PNG (Max: 2MB)</small>
                                @error('bill_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-end gap-3 flex-wrap">
                                    <button type="button" class="btn btn-light" onclick="resetForm()">
                                        <i class="ti ti-refresh me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Create Expense Bill
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle payment mode change
    $('#payment_mode').change(function() {
        var paymentMode = $(this).val();
        
        // Hide all conditional sections
        $('#bankDetails').hide();
        $('#upiDetails').hide();
        
        // Remove required attributes
        $('#bank_account_no, #ifsc_code, #bank_name, #branch_name, #upi_id').removeAttr('required');
        
        // Show relevant section and add required attributes
        if (paymentMode === 'bank') {
            $('#bankDetails').show();
            $('#bank_account_no, #ifsc_code, #bank_name, #branch_name').attr('required', true);
        } else if (paymentMode === 'upi') {
            $('#upiDetails').show();
            $('#upi_id').attr('required', true);
        }
    });
    
    // Trigger change event on page load to handle old input
    $('#payment_mode').trigger('change');
    
    // Form submission with SweetAlert
    $('#expenseBillForm').submit(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Create Expense Bill?',
            text: 'Are you sure you want to create this expense bill?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Create!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Creating...',
                    text: 'Please wait while we create the expense bill.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                this.submit();
            }
        });
    });
});

// Reset form function
function resetForm() {
    Swal.fire({
        title: 'Reset Form?',
        text: 'This will clear all form data. Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Reset!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('expenseBillForm').reset();
            $('#bankDetails').hide();
            $('#upiDetails').hide();
            $('#bank_account_no, #ifsc_code, #bank_name, #branch_name, #upi_id').removeAttr('required');
            
            Swal.fire({
                title: 'Reset!',
                text: 'Form has been reset successfully.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}
</script>

@if(session('success'))
<script>
$(document).ready(function() {
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        timer: 3000,
        showConfirmButton: false
    });
});
</script>
@endif
@endpush
