@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create New Role</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.roles.index') }}">Roles</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Roles
                </a>
            </div>
        </div>
    </div>

    <!-- Create Role Form -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.roles.store') }}" method="POST" id="roleForm">
                @csrf
                <div class="row">
                    <!-- Role Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Role Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="Enter role name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Unique identifier for the role (e.g., admin, manager)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="display_name" class="form-label">Display Name</label>
                                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                                   id="display_name" name="display_name" value="{{ old('display_name') }}" 
                                                   placeholder="Enter display name">
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Human-readable name for the role</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Enter role description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Brief description of the role's purpose</small>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable or disable this role</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role Status & Actions -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>Create Role
                                    </button>
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                                        <i class="ti ti-x me-1"></i>Cancel
                                    </a>
                                </div>
                                <hr>
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Note:</strong> After creating the role, you can assign permissions to it from the role details page.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Assign Permissions</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll">
                                        Select All
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($permissions->count() > 0)
                                    <div class="row">
                                        @foreach($permissions as $module => $modulePermissions)
                                            <div class="col-md-6 col-lg-4 mb-4">
                                                <div class="permission-group">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="ti ti-folder me-1"></i>{{ ucfirst($module ?: 'General') }}
                                                        <small class="text-muted">({{ $modulePermissions->count() }})</small>
                                                    </h6>
                                                    <div class="permission-list">
                                                        @foreach($modulePermissions as $permission)
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox" 
                                                                       name="permissions[]" 
                                                                       value="{{ $permission->id }}" 
                                                                       id="permission_{{ $permission->id }}"
                                                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                    <span class="fw-medium">{{ $permission->display_name ?: $permission->name }}</span>
                                                                    @if($permission->description)
                                                                        <br><small class="text-muted">{{ $permission->description }}</small>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="ti ti-shield-off fs-48 text-muted mb-2"></i>
                                        <h6 class="text-muted">No permissions available</h6>
                                        <p class="text-muted">Create permissions first to assign them to roles.</p>
                                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ti ti-plus me-1"></i>Create Permission
                                        </a>
                                    </div>
                                @endif
                                
                                @error('permissions')
                                    <div class="alert alert-danger mt-3">
                                        <i class="ti ti-alert-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Select All functionality
        $('#selectAll').change(function() {
            $('.permission-checkbox').prop('checked', this.checked);
        });

        // Update Select All when individual checkboxes change
        $('.permission-checkbox').change(function() {
            const totalCheckboxes = $('.permission-checkbox').length;
            const checkedCheckboxes = $('.permission-checkbox:checked').length;
            
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
            $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        });

        // Form validation
        $('#roleForm').on('submit', function(e) {
            const roleName = $('#name').val().trim();
            
            if (!roleName) {
                e.preventDefault();
                $('#name').addClass('is-invalid');
                $('#name').focus();
                return false;
            }
        });

        // Auto-generate display name from role name
        $('#name').on('input', function() {
            const name = $(this).val();
            const displayName = name.charAt(0).toUpperCase() + name.slice(1).replace(/[_-]/g, ' ');
            
            if (!$('#display_name').val()) {
                $('#display_name').val(displayName);
            }
        });
    });
</script>
@endpush