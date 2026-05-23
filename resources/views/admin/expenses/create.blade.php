@extends('layouts.admin')

@section('title', 'Add New Expense')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create New Expense</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.expenses.index') }}">Expenses</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Expenses
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Expense Information</h4>
                </div>
                <form action="{{ route('admin.expenses.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_name">Expense Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('expense_name') is-invalid @enderror" 
                                           id="expense_name" 
                                           name="expense_name" 
                                           value="{{ old('expense_name') }}" 
                                           placeholder="Enter expense name"
                                           required>
                                    @error('expense_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount') }}" 
                                               placeholder="0.00"
                                               step="0.01"
                                               min="0"
                                               required>
                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Enter expense description (optional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" 
                                            id="is_active" 
                                            name="is_active" 
                                            required>
                                        <option value="">Select Status</option>
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Expense
                                </button>
                                <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var expenseName = $('#expense_name').val().trim();
        var amount = $('#amount').val();
        var status = $('#is_active').val();
        
        if (!expenseName) {
            e.preventDefault();
            Swal.fire('Error!', 'Please enter expense name.', 'error');
            $('#expense_name').focus();
            return false;
        }
        
        if (!amount || parseFloat(amount) < 0) {
            e.preventDefault();
            Swal.fire('Error!', 'Please enter a valid amount.', 'error');
            $('#amount').focus();
            return false;
        }
        
        if (!status) {
            e.preventDefault();
            Swal.fire('Error!', 'Please select status.', 'error');
            $('#is_active').focus();
            return false;
        }
    });
    
    // Auto-format amount field
    $('#amount').on('blur', function() {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            $(this).val(value.toFixed(2));
        }
    });
    
    // Character counter for description
    $('#description').on('input', function() {
        var maxLength = 500;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        if (!$('#char-counter').length) {
            $(this).after('<small id="char-counter" class="form-text text-muted"></small>');
        }
        
        $('#char-counter').text(remaining + ' characters remaining');
        
        if (remaining < 0) {
            $('#char-counter').removeClass('text-muted').addClass('text-danger');
        } else {
            $('#char-counter').removeClass('text-danger').addClass('text-muted');
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.form-group label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

.input-group-text {
    background-color: #e9ecef;
    border-color: #ced4da;
}

.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.btn {
    border-radius: 0.25rem;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
@endpush