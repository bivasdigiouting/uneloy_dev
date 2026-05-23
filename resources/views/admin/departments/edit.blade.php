@extends('layouts.admin')

@section('title', 'Edit Department')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Department</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.departments.index') }}">Departments</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Departments
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-info d-inline-flex align-items-center">
                    <i class="ti ti-eye me-1"></i>View Details
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Department Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('department_name') is-invalid @enderror" 
                                           id="department_name" 
                                           name="department_name" 
                                           value="{{ old('department_name', $department->department_name) }}" 
                                           placeholder="Enter department name"
                                           required>
                                    @error('department_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" 
                                            id="is_active" 
                                            name="is_active">
                                        <option value="1" {{ old('is_active', $department->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $department->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                              id="remarks" 
                                              name="remarks" 
                                              rows="4" 
                                              placeholder="Enter remarks (optional)">{{ old('remarks', $department->remarks) }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Department
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
    // Auto-focus on department name field
    $('#department_name').focus();
    
    // Form validation
    $('form').on('submit', function(e) {
        let departmentName = $('#department_name').val().trim();
        
        if (departmentName === '') {
            e.preventDefault();
            $('#department_name').addClass('is-invalid');
            toastr.error('Department name is required!');
            return false;
        }
        
        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
    });
    
    // Remove validation error on input
    $('#department_name').on('input', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush