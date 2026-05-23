@extends('layouts.admin')

@section('title', 'Add/Remove Vendor Wallet')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add/Remove Vendor Wallet</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Vendor Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Add/Remove Vendor Wallet</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to Dashboard">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Search Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Vendor</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vendor.wallet.search') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="search_id" class="form-label">Search by ID/Vendor No/Mobile/Name/Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('search_id') is-invalid @enderror" 
                                           id="search_id" name="search_id" 
                                           value="{{ old('search_id', request('search_id')) }}" 
                                           placeholder="Enter ID/Vendor No/Mobile/Name/Email" required>
                                    @error('search_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Search Vendor
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($vendor))
    <!-- Vendor Details and Wallet Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vendor Wallet Management</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vendor.wallet.transaction') }}" method="POST" id="walletForm">
                        @csrf
                        <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                        
                        <div class="row">
                            <!-- Vendor Information Section -->
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Vendor Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Vendor ID <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ $vendor->id }}" readonly>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Business Name</label>
                                            <input type="text" class="form-control" value="{{ $vendor->business_name }}" readonly>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Contact Person</label>
                                            <input type="text" class="form-control" value="{{ $vendor->contact_person }}" readonly>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control" value="{{ $vendor->gmail_id ?? $vendor->contact_gmail_id ?? '' }}" readonly>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Mobile</label>
                                            <input type="text" class="form-control" value="{{ $vendor->mobile_no }}" readonly>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Available Amount</label>
                                            <input type="text" class="form-control" value="₹{{ number_format($vendor->wallet_balance ?? 0, 2) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Transaction Section -->
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Wallet Transaction</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Wallet Amount Add/Remove <span class="text-danger">*</span></label>
                                            <select class="form-select @error('transaction_type') is-invalid @enderror" name="transaction_type" id="transaction_type" required>
                                                <option value="" disabled {{ old('transaction_type') ? '' : 'selected' }}>Select</option>
                                                <option value="add" {{ old('transaction_type') == 'add' ? 'selected' : '' }}>Add Amount</option>
                                                <option value="remove" {{ old('transaction_type') == 'remove' ? 'selected' : '' }}>Remove Amount</option>
                                            </select>
                                            @error('transaction_type')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="transaction_amount" class="form-label">Transaction Amount <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control @error('transaction_amount') is-invalid @enderror" 
                                                       id="transaction_amount" name="transaction_amount" 
                                                       value="{{ old('transaction_amount', '0') }}" 
                                                       min="0.01" step="0.01" placeholder="0.00" required>
                                            </div>
                                            @error('transaction_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="narration" class="form-label">Narration</label>
                                            <textarea class="form-control @error('narration') is-invalid @enderror" 
                                                      id="narration" name="narration" rows="3" 
                                                      placeholder="Enter transaction description (optional)">{{ old('narration') }}</textarea>
                                            @error('narration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Process Transaction
                                            </button>
                                            <a href="{{ route('admin.vendor.wallet.management') }}" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!isset($vendor) && request()->has('search_id'))
    <!-- No Vendor Found Message -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No vendor found with the provided search criteria.
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#walletForm').on('submit', function(e) {
        let isValid = true;
        let errors = [];
        
        // Validate transaction type
        const transactionType = $('#transaction_type').val();
        if (!transactionType) {
            errors.push('Please select Add or Remove option.');
            isValid = false;
        }
        
        // Validate transaction amount
        const amount = parseFloat($('#transaction_amount').val());
        if (!amount || amount <= 0) {
            errors.push('Please enter a valid transaction amount greater than 0.');
            isValid = false;
        }
        
        // Show errors if any
        if (!isValid) {
            e.preventDefault();
            alert('Please fix the following errors:\n\n' + errors.join('\n'));
            return false;
        }
        
        // Confirm transaction
        const transactionAmount = $('#transaction_amount').val();
        const vendorName = '{{ isset($vendor) ? ($vendor->business_name ?? '') : "" }}';
        
        const confirmMessage = `Are you sure you want to ${transactionType} ₹${transactionAmount} ${transactionType === 'add' ? 'to' : 'from'} ${vendorName}'s wallet?`;
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }
    });
    
    // Auto-focus on search input
    $('#search_id').focus();
    
    // Format amount input
    $('#transaction_amount').on('input', function() {
        let value = $(this).val();
        if (value && !isNaN(value)) {
            $(this).val(parseFloat(value).toFixed(2));
        }
    });
});
</script>
@endpush
@endsection