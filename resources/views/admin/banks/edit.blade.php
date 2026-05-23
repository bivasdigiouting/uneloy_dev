@extends('layouts.admin')

@section('title', 'Edit Bank')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Bank</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.banks.index') }}">Bank Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.banks.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Bank Master
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Bank Information</h4>
                        </div>
                    </div>
                </div>
                    <div class="card-body">
                        <form action="{{ route('admin.banks.update', $bank->id) }}" method="POST" id="bank-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Bank Name</label>
                                        <input type="text" 
                                               class="form-control @error('bank_name') is-invalid @enderror" 
                                               name="bank_name" 
                                               value="{{ old('bank_name', $bank->bank_name) }}" 
                                               placeholder="Enter bank name"
                                               required>
                                        @error('bank_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Status</label>
                                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                            <label class="form-selectgroup-item flex-fill">
                                                <input type="radio" 
                                                       name="status" 
                                                       value="active" 
                                                       class="form-selectgroup-input" 
                                                       {{ old('status', $bank->status) == 'active' ? 'checked' : '' }}>
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div>
                                                        <strong>Active</strong>
                                                        <div class="text-muted">Bank is active and available</div>
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="form-selectgroup-item flex-fill">
                                                <input type="radio" 
                                                       name="status" 
                                                       value="inactive" 
                                                       class="form-selectgroup-input" 
                                                       {{ old('status', $bank->status) == 'inactive' ? 'checked' : '' }}>
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div>
                                                        <strong>Inactive</strong>
                                                        <div class="text-muted">Bank is inactive and not available</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                        <path d="M16 5l3 3"/>
                                    </svg>
                                    Update Bank
                                </button>
                                <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary ms-2">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-control, .form-select {
    border: 1px solid #d0d7de !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    background-color: #ffffff !important;
}

.form-control:focus, .form-select:focus {
    border-color: #0969da !important;
    box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.1) !important;
    outline: none !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#bank-form').on('submit', function(e) {
        var bankName = $('input[name="bank_name"]').val().trim();
        
        if (!bankName) {
            e.preventDefault();
            Swal.fire('Error!', 'Please enter bank name.', 'error');
            return false;
        }
        
        if (bankName.length < 2) {
            e.preventDefault();
            Swal.fire('Error!', 'Bank name must be at least 2 characters long.', 'error');
            return false;
        }
    });
    
    // Auto-focus on bank name field
    $('input[name="bank_name"]').focus();
});
</script>
@endpush