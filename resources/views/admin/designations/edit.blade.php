@extends('layouts.admin')

@section('title', 'Edit Designation')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Designation</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Office Management
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.designations.index') }}">Designations</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Designation</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.designations.index') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Designation: {{ $designation->designation_name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.designations.update', $designation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="designation_name" class="form-label">Designation Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('designation_name') is-invalid @enderror" 
                                           id="designation_name" name="designation_name" 
                                           value="{{ old('designation_name', $designation->designation_name) }}" 
                                           placeholder="Enter designation name" required>
                                    @error('designation_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" 
                                            id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $designation->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $designation->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.designations.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Designation
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
    $('form').on('submit', function(e) {
        var designationName = $('#designation_name').val().trim();
        
        if (designationName === '') {
            e.preventDefault();
            Swal.fire({
                title: 'Validation Error',
                text: 'Designation name is required.',
                icon: 'error'
            });
            $('#designation_name').focus();
            return false;
        }
        
        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
    });
    
    // Auto-focus on designation name field
    $('#designation_name').focus();
});
</script>
@endpush