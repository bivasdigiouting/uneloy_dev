@extends('layouts.admin')

@section('title', 'Edit Vendor Type')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Vendor Type</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.vendor-types.index') }}">Vendor Type Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.vendor-types.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Vendor Type Master
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Vendor Type Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vendor-types.update', $vendorType->id) }}" method="POST" id="vendor-type-edit-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Vendor Type Name -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="vendor_type" class="form-label">Vendor Type Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('vendor_type') is-invalid @enderror" 
                                           id="vendor_type" 
                                           name="vendor_type" 
                                           value="{{ old('vendor_type', $vendorType->vendor_type) }}" 
                                           placeholder="Enter vendor type name"
                                           required>
                                    @error('vendor_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="is_active" 
                                                   id="status_active" 
                                                   value="1" 
                                                   {{ old('is_active', $vendorType->is_active) == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">
                                                <span class="badge badge-success">Active</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="is_active" 
                                                   id="status_inactive" 
                                                   value="0" 
                                                   {{ old('is_active', $vendorType->is_active) == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_inactive">
                                                <span class="badge badge-danger">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('is_active')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.vendor-types.index') }}" class="btn btn-light">
                                        <i class="ri-arrow-left-line align-bottom me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line align-bottom me-1"></i> Update Vendor Type
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vendor Type Details</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium">ID:</td>
                                    <td>{{ $vendorType->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Current Name:</td>
                                    <td>{{ $vendorType->vendor_type }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Current Status:</td>
                                    <td>
                                        @if($vendorType->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Created:</td>
                                    <td>{{ $vendorType->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Last Updated:</td>
                                    <td>{{ $vendorType->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Instructions</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Editing Vendor Type</h6>
                        <p class="mb-0">Please follow these guidelines:</p>
                        <ul class="mb-0 mt-2">
                            <li>Update vendor type name if needed</li>
                            <li>Change status as required</li>
                            <li>Active vendor types will be available for selection</li>
                            <li>Inactive vendor types will be hidden from selection</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Note</h6>
                        <p class="mb-0">
                            <i class="ri-information-line"></i>
                            Vendor type names must be unique and cannot be duplicated.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#vendor-type-edit-form').on('submit', function(e) {
        var vendorType = $('#vendor_type').val().trim();
        var isActive = $('input[name="is_active"]:checked').val();
        
        // Validate vendor type
        if (!vendorType) {
            e.preventDefault();
            Swal.fire('Error!', 'Please enter vendor type name.', 'error');
            $('#vendor_type').focus();
            return false;
        }
        
        // Validate status
        if (isActive === undefined) {
            e.preventDefault();
            Swal.fire('Error!', 'Please select a status.', 'error');
            return false;
        }
        
        // Show loading
        $(this).find('button[type="submit"]').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...'
        );
    });
    
    // Real-time validation for vendor type
    $('#vendor_type').on('input', function() {
        var value = $(this).val().trim();
        if (value.length > 0) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').hide();
        }
    });
    
    // Status radio button change handler
    $('input[name="is_active"]').on('change', function() {
        $('input[name="is_active"]').removeClass('is-invalid');
        $('.invalid-feedback').hide();
    });
});

// Display success/error messages
@if(session('success'))
    Swal.fire('Success!', "{{ session('success') }}", 'success');
@endif

@if(session('error'))
    Swal.fire('Error!', "{{ session('error') }}", 'error');
@endif
</script>
@endpush