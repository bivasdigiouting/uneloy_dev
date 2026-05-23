@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Role</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.roles.index') }}">Roles</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Roles
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-info d-inline-flex align-items-center">
                    <i class="ti ti-eye me-1"></i>View Role
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Role Form -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" id="roleForm">
                @csrf
                @method('PUT')
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
                                                   id="name" name="name" value="{{ old('name', $role->name) }}" 
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
                                                   id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}" 
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
                                              placeholder="Enter role description">{{ old('description', $role->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Brief description of the role's purpose</small>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $role->is_active) ? 'checked' : '' }}>
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
                                        <i class="ti ti-device-floppy me-1"></i>Update Role
                                    </button>
                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-info">
                                        <i class="ti ti-eye me-1"></i>View Role
                                    </a>
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                                        <i class="ti ti-x me-1"></i>Cancel
                                    </a>
                                </div>
                                <hr>
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <strong>Warning:</strong> Changing permissions may affect users assigned to this role.
                                </div>
                            </div>
                        </div>

                        <!-- Role Statistics -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Role Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <h4 class="text-primary mb-1">{{ $role->permissions->count() }}</h4>
                                            <small class="text-muted">Permissions</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success mb-1">{{ $role->users->count() ?? 0 }}</h4>
                                        <small class="text-muted">Users</small>
                                    </div>
                                </div>
                                <hr>
                                <small class="text-muted">
                                    <i class="ti ti-calendar me-1"></i>
                                    Created: {{ $role->created_at->format('M d, Y') }}
                                </small>
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
                                    @php
                                        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                                    @endphp
                                    
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
                                                                       {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}>
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
        // Check if all permissions are selected on page load
        updateSelectAllState();

        // Select All functionality
        $('#selectAll').change(function() {
            $('.permission-checkbox').prop('checked', this.checked);
        });

        // Update Select All when individual checkboxes change
        $('.permission-checkbox').change(function() {
            updateSelectAllState();
        });

        function updateSelectAllState() {
            const totalCheckboxes = $('.permission-checkbox').length;
            const checkedCheckboxes = $('.permission-checkbox:checked').length;
            
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
            $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        }

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

        // Auto-generate display name from role name (only if display name is empty)
        $('#name').on('input', function() {
            const name = $(this).val();
            const displayName = name.charAt(0).toUpperCase() + name.slice(1).replace(/[_-]/g, ' ');
            const currentDisplayName = $('#display_name').val();
            
            // Only auto-fill if display name is empty or matches the previous auto-generated value
            if (!currentDisplayName || currentDisplayName === $(this).data('prev-display-name')) {
                $('#display_name').val(displayName);
            }
            
            $(this).data('prev-display-name', displayName);
        });
    });
</script>
@endpush