@extends('layouts.admin')

@section('title', 'Edit State')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit State</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.states.index') }}">State Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.states.show', $state->id) }}" class="btn btn-info d-inline-flex align-items-center">
                    <i class="ti ti-eye me-1"></i>View Details
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.states.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to State Master
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
                            <h4 class="card-title mb-0">Edit State: {{ $state->state_name }}</h4>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.states.show', $state->id) }}" class="btn btn-info btn-sm">
                                    <i class="ti ti-eye me-1"></i> View
                                </a>
                                <a href="{{ route('admin.states.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
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

                    <form action="{{ route('admin.states.update', $state->id) }}" method="POST" id="stateForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- State Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_name" class="form-label">State Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state_name') is-invalid @enderror" 
                                           id="state_name" name="state_name" 
                                           value="{{ old('state_name', $state->state_name) }}" 
                                           placeholder="Enter state name" required>
                                    @error('state_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                   id="status_active" name="status" value="active"
                                                   {{ old('status', $state->status) == 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">
                                                <span class="badge bg-success">Active</span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                   id="status_inactive" name="status" value="inactive"
                                                   {{ old('status', $state->status) == 'inactive' ? 'checked' : '' }}>
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

                        <!-- State Info -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Created:</strong> {{ $state->created_at->format('d M Y, h:i A') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Last Updated:</strong> {{ $state->updated_at->format('d M Y, h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.states.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-1"></i> Update State
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
    $('#stateForm').on('submit', function(e) {
        const stateName = $('#state_name').val().trim();
        
        if (!stateName) {
            e.preventDefault();
            $('#state_name').addClass('is-invalid');
            
            // Show error message
            if (!$('#state_name').next('.invalid-feedback').length) {
                $('#state_name').after('<div class="invalid-feedback">State name is required.</div>');
            }
            
            $('#state_name').focus();
            return false;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="ti ti-loader ti-spin me-1"></i> Updating...').prop('disabled', true);
        
        // Re-enable button after 10 seconds (fallback)
        setTimeout(function() {
            submitBtn.html(originalText).prop('disabled', false);
        }, 10000);
    });

    // Remove validation error on input
    $('#state_name').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert:not(.alert-info)').fadeOut();
    }, 5000);
});
</script>
@endpush