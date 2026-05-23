@extends('layouts.admin')

@section('title', 'Edit Company UPI')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Company UPI</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.company-upis.index') }}">Company UPI Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.company-upis.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Company UPI Master
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
                            <h4 class="card-title mb-0">Company UPI Information</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.company-upis.update', $companyUpi->id) }}" method="POST" id="company-upi-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">UPI ID</label>
                                    <input type="text" 
                                           class="form-control @error('upi_id') is-invalid @enderror" 
                                           name="upi_id" 
                                           value="{{ old('upi_id', $companyUpi->upi_id) }}" 
                                           placeholder="Enter UPI ID (e.g., user@paytm)"
                                           required>
                                    @error('upi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">QR Code</label>
                                    <input type="file" 
                                           class="form-control @error('qr_code') is-invalid @enderror" 
                                           name="qr_code" 
                                           accept="image/*"
                                           id="qr_code_input">
                                    <div class="form-text">Upload new QR code image (JPEG, PNG, JPG, GIF - Max: 2MB). Leave empty to keep current QR code.</div>
                                    @error('qr_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current QR Code Display -->
                        @if($companyUpi->qr_code)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Current QR Code</label>
                                    <div class="border rounded p-3 text-center">
                                        <img src="{{ asset('storage/' . $companyUpi->qr_code) }}" 
                                             alt="Current QR Code" 
                                             style="max-width: 200px; max-height: 200px;"
                                             id="current_qr_code">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Status</label>
                                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" 
                                                   name="status" 
                                                   value="active" 
                                                   class="form-selectgroup-input" 
                                                   {{ old('status', $companyUpi->status) == 'active' ? 'checked' : '' }}>
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Active</strong>
                                                    <div class="text-muted">UPI is active and available</div>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" 
                                                   name="status" 
                                                   value="inactive" 
                                                   class="form-selectgroup-input" 
                                                   {{ old('status', $companyUpi->status) == 'inactive' ? 'checked' : '' }}>
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Inactive</strong>
                                                    <div class="text-muted">UPI is inactive and not available</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                              name="remarks" 
                                              rows="4" 
                                              placeholder="Enter any remarks or notes">{{ old('remarks', $companyUpi->remarks) }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- New QR Code Preview -->
                        <div class="row" id="qr_preview_section" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">New QR Code Preview</label>
                                    <div class="border rounded p-3 text-center">
                                        <img id="qr_preview" src="" alt="QR Code Preview" style="max-width: 200px; max-height: 200px;">
                                    </div>
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
                                Update Company UPI
                            </button>
                            <a href="{{ route('admin.company-upis.index') }}" class="btn btn-secondary ms-2">
                                Cancel
                            </a>
                        </div>
                    </form>
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
    // QR Code preview
    $('#qr_code_input').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#qr_preview').attr('src', e.target.result);
                $('#qr_preview_section').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#qr_preview_section').hide();
        }
    });
    
    // Form validation
    $('#company-upi-form').on('submit', function(e) {
        var upiId = $('input[name="upi_id"]').val().trim();
        
        if (!upiId) {
            e.preventDefault();
            Swal.fire('Error!', 'Please enter UPI ID.', 'error');
            return false;
        }
        
        // Basic UPI ID validation
        var upiPattern = /^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/;
        if (!upiPattern.test(upiId)) {
            e.preventDefault();
            Swal.fire('Error!', 'Please enter a valid UPI ID (e.g., user@paytm).', 'error');
            return false;
        }
    });
    
    // Auto-focus on UPI ID field
    $('input[name="upi_id"]').focus();
});
</script>
@endpush