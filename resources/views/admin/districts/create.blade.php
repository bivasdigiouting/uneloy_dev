@extends('layouts.admin')

@section('title', 'Add District')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create New District</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.districts.index') }}">District Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.districts.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to District Master
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
                            <h4 class="card-title mb-0">District Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.districts.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.districts.store') }}" method="POST" id="districtForm">
                        @csrf
                        <div class="row">
                            <!-- State Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">State Name <span class="text-danger">*</span></label>
                                    <select class="form-select @error('state_id') is-invalid @enderror" 
                                            id="state_id" name="state_id" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                {{ $state->state_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- District Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="district_name" class="form-label">District Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('district_name') is-invalid @enderror" 
                                           id="district_name" name="district_name" 
                                           value="{{ old('district_name') }}" 
                                           placeholder="Enter district name" required>
                                    @error('district_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                   id="status_active" name="status" value="active"
                                                   {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">
                                                <span class="badge bg-success">Active</span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                   id="status_inactive" name="status" value="inactive"
                                                   {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_inactive">
                                                <span class="badge bg-danger">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.districts.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-1"></i> Create District
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
    // Form validation
    $('#districtForm').on('submit', function(e) {
        const stateId = $('#state_id').val();
        const districtName = $('#district_name').val().trim();
        
        let hasError = false;
        
        // Validate state selection
        if (!stateId) {
            e.preventDefault();
            $('#state_id').addClass('is-invalid');
            
            if (!$('#state_id').next('.invalid-feedback').length) {
                $('#state_id').after('<div class="invalid-feedback">Please select a state.</div>');
            }
            
            hasError = true;
        }
        
        // Validate district name
        if (!districtName) {
            e.preventDefault();
            $('#district_name').addClass('is-invalid');
            
            if (!$('#district_name').next('.invalid-feedback').length) {
                $('#district_name').after('<div class="invalid-feedback">District name is required.</div>');
            }
            
            hasError = true;
        }
        
        if (hasError) {
            // Focus on first error field
            if (!stateId) {
                $('#state_id').focus();
            } else if (!districtName) {
                $('#district_name').focus();
            }
            return false;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="ti ti-loader ti-spin me-1"></i> Creating...').prop('disabled', true);
        
        // Re-enable button after 10 seconds (fallback)
        setTimeout(function() {
            submitBtn.html(originalText).prop('disabled', false);
        }, 10000);
    });

    // Remove validation error on input/change
    $('#state_id').on('change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    $('#district_name').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endpush