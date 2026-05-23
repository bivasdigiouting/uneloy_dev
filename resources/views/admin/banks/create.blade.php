@extends('layouts.admin')

@section('title', 'Add New Bank')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Master Data
                </div>
                <h2 class="page-title">
                    Add New Bank
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.banks.index') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12,19 5,12 12,5"/>
                        </svg>
                        Back to Banks
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bank Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.banks.store') }}" method="POST" id="bank-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Bank Name</label>
                                        <input type="text" 
                                               class="form-control @error('bank_name') is-invalid @enderror" 
                                               name="bank_name" 
                                               value="{{ old('bank_name') }}" 
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
                                                       {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
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
                                                       {{ old('status') == 'inactive' ? 'checked' : '' }}>
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
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Create Bank
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