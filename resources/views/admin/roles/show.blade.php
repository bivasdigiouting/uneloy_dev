@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Role Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.roles.index') }}">Roles</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $role->display_name ?: $role->name }}</li>
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
                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit Role
                </a>
            </div>
            <div class="me-2 mb-2">
                <button type="button" class="btn btn-danger d-inline-flex align-items-center" 
                        onclick="deleteRole({{ $role->id }})">
                    <i class="ti ti-trash me-1"></i>Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Role Information -->
    <div class="row">
        <!-- Basic Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Role Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Role Name</label>
                                <p class="fw-medium">{{ $role->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Display Name</label>
                                <p class="fw-medium">{{ $role->display_name ?: 'Not set' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Description</label>
                        <p class="fw-medium">{{ $role->description ?: 'No description provided' }}</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    @if($role->is_active)
                                        <span class="badge bg-success"><i class="ti ti-check me-1"></i>Active</span>
                                    @else
                                        <span class="badge bg-danger"><i class="ti ti-x me-1"></i>Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Created Date</label>
                                <p class="fw-medium">{{ $role->created_at->format('M d, Y \\a\\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    @if($role->updated_at && $role->updated_at != $role->created_at)
                        <div class="mb-3">
                            <label class="form-label text-muted">Last Updated</label>
                            <p class="fw-medium">{{ $role->updated_at->format('M d, Y \\a\\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics & Quick Actions -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h3 class="text-primary mb-1">{{ $role->permissions->count() }}</h3>
                                <small class="text-muted">Permissions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success mb-1">{{ $role->users->count() ?? 0 }}</h3>
                            <small class="text-muted">Users</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-edit me-1"></i>Edit Role
                        </a>
                        @if($role->permissions->count() > 0)
                            <button type="button" class="btn btn-info btn-sm" onclick="exportPermissions()">
                                <i class="ti ti-download me-1"></i>Export Permissions
                            </button>
                        @endif
                        <button type="button" class="btn btn-warning btn-sm" onclick="duplicateRole()">
                            <i class="ti ti-copy me-1"></i>Duplicate Role
                        </button>
                        <hr>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteRole({{ $role->id }})">
                            <i class="ti ti-trash me-1"></i>Delete Role
                        </button>
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
                    <h5 class="mb-0">Assigned Permissions ({{ $role->permissions->count() }})</h5>
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-edit me-1"></i>Manage Permissions
                    </a>
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                        @php
                            $groupedPermissions = $role->permissions->groupBy('module');
                        @endphp
                        
                        <div class="row">
                            @foreach($groupedPermissions as $module => $modulePermissions)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="permission-group">
                                        <h6 class="text-primary mb-3">
                                            <i class="ti ti-folder me-1"></i>{{ ucfirst($module ?: 'General') }}
                                            <span class="badge bg-primary ms-2">{{ $modulePermissions->count() }}</span>
                                        </h6>
                                        <div class="permission-list">
                                            @foreach($modulePermissions as $permission)
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ti ti-check text-success me-2"></i>
                                                    <div>
                                                        <span class="fw-medium">{{ $permission->display_name ?: $permission->name }}</span>
                                                        @if($permission->description)
                                                            <br><small class="text-muted">{{ $permission->description }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-shield-off fs-48 text-muted mb-3"></i>
                            <h6 class="text-muted mb-2">No permissions assigned</h6>
                            <p class="text-muted mb-3">This role doesn't have any permissions assigned yet.</p>
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Assign Permissions
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Users with this Role -->
    @if(method_exists($role, 'users') && $role->users->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Users with this Role ({{ $role->users->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Assigned Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-title bg-primary rounded-circle">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <span class="fw-medium">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->is_active ?? true)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->pivot->created_at ?? $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-light">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="ti ti-alert-triangle text-warning fs-48 mb-3"></i>
                    <h6>Are you sure you want to delete this role?</h6>
                    <p class="text-muted">This action cannot be undone. All users assigned to this role will lose their permissions.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Role</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteRole(roleId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `{{ route('admin.roles.index') }}/${roleId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function duplicateRole() {
        // Redirect to create page with current role data as query parameters
        const params = new URLSearchParams({
            duplicate: {{ $role->id }},
            name: '{{ $role->name }}_copy',
            display_name: '{{ $role->display_name }} (Copy)',
            description: '{{ $role->description }}'
        });
        
        window.location.href = `{{ route('admin.roles.create') }}?${params.toString()}`;
    }

    function exportPermissions() {
        const permissions = @json($role->permissions->pluck('name'));
        const roleData = {
            role: '{{ $role->name }}',
            display_name: '{{ $role->display_name }}',
            description: '{{ $role->description }}',
            permissions: permissions,
            exported_at: new Date().toISOString()
        };
        
        const dataStr = JSON.stringify(roleData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        
        const link = document.createElement('a');
        link.href = URL.createObjectURL(dataBlob);
        link.download = `role_${roleData.role}_permissions.json`;
        link.click();
    }
</script>
@endpush