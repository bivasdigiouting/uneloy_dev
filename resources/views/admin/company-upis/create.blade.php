@extends('layouts.admin')

@section('title', 'Add New Company UPI')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Master Data
                </div>
                <h2 class="page-title">
                    Add New Company UPI
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.company-upis.index') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12,19 5,12 12,5"/>
                        </svg>
                        Back to Company UPIs
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
                        <h3 class="card-title">Company UPI Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.company-upis.store') }}" method="POST" id="company-upi-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">UPI ID</label>
                                        <input type="text" 
                                               class="form-control @error('upi_id') is-invalid @enderror" 
                                               name="upi_id" 
                                               value="{{ old('upi_id') }}" 
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
                                        <div class="form-text">Upload QR code image (JPEG, PNG, JPG, GIF - Max: 2MB)</div>
                                        @error('qr_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
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
                                                       {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
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
                                                       {{ old('status') == 'inactive' ? 'checked' : '' }}>
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
                                                  placeholder="Enter any remarks or notes">{{ old('remarks') }}</textarea>
                                        @error('remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- QR Code Preview -->
                            <div class="row" id="qr_preview_section" style="display: none;">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">QR Code Preview</label>
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
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Create Company UPI
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