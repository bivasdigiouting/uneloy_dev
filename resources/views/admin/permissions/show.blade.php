@extends('layouts.admin')

@section('title', 'Permission Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Permission Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.permissions.index') }}">Permissions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $permission->display_name ?: $permission->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Permissions
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit Permission
                </a>
            </div>
            <div class="dropdown mb-2">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="ti ti-dots-vertical me-1"></i>More Actions
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="duplicatePermission()"><i class="ti ti-copy me-2"></i>Duplicate</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportPermission()"><i class="ti ti-download me-2"></i>Export</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="deletePermission()"><i class="ti ti-trash me-2"></i>Delete</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Permission Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Permission Information</h5>
                    @if($permission->is_active)
                        <span class="badge bg-success fs-12"><i class="ti ti-check me-1"></i>Active</span>
                    @else
                        <span class="badge bg-danger fs-12"><i class="ti ti-x me-1"></i>Inactive</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Permission Name</label>
                                <div class="fw-medium">
                                    <code class="bg-light p-1 rounded">{{ $permission->name }}</code>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Display Name</label>
                                <div class="fw-medium">{{ $permission->display_name ?: 'Not set' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Module</label>
                                <div>
                                    @if($permission->module)
                                        <span class="badge bg-info fs-12">{{ ucfirst($permission->module) }}</span>
                                    @else
                                        <span class="text-muted">General</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    @if($permission->is_active)
                                        <span class="badge bg-success"><i class="ti ti-check me-1"></i>Active</span>
                                    @else
                                        <span class="badge bg-danger"><i class="ti ti-x me-1"></i>Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($permission->description)
                        <div class="mb-4">
                            <label class="form-label text-muted">Description</label>
                            <div class="fw-medium">{{ $permission->description }}</div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Created</label>
                                <div class="fw-medium">
                                    <i class="ti ti-calendar me-1"></i>
                                    {{ $permission->created_at->format('M d, Y \\a\\t g:i A') }}
                                </div>
                                <small class="text-muted">{{ $permission->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if($permission->updated_at && $permission->updated_at != $permission->created_at)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <div class="fw-medium">
                                        <i class="ti ti-edit me-1"></i>
                                        {{ $permission->updated_at->format('M d, Y \\a\\t g:i A') }}
                                    </div>
                                    <small class="text-muted">{{ $permission->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigned Roles -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assigned Roles ({{ $permission->roles->count() }})</h5>
                    @if($permission->roles->count() > 0)
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-settings me-1"></i>Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportRoles()"><i class="ti ti-download me-2"></i>Export Roles</a></li>
                                <li><a class="dropdown-item" href="#" onclick="bulkRemoveRoles()"><i class="ti ti-unlink me-2"></i>Remove All</a></li>
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($permission->roles->count() > 0)
                        <div class="row">
                            @foreach($permission->roles as $role)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ $role->display_name ?: $role->name }}</h6>
                                            @if($role->is_active)
                                                <span class="badge bg-success fs-10">Active</span>
                                            @else
                                                <span class="badge bg-danger fs-10">Inactive</span>
                                            @endif
                                        </div>
                                        <p class="text-muted small mb-0"><code>{{ $role->name }}</code></p>
                                        @if($role->description)
                                            <p class="text-muted small mb-2">{{ Str::limit($role->description, 80) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-muted">
                                                <i class="ti ti-users me-1"></i>{{ $role->users->count() ?? 0 }} users
                                            </small>
                                            <div>
                                                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-outline-primary me-1" title="View Role">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="removeRoleFromPermission({{ $role->id }}, '{{ $role->name }}')" title="Remove Permission">
                                                    <i class="ti ti-unlink"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-shield-x text-muted fs-48 mb-3"></i>
                            <h6 class="text-muted">No Roles Assigned</h6>
                            <p class="text-muted">This permission is not currently assigned to any roles.</p>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Assign to Roles
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Users with this Permission -->
            @if($permission->roles->count() > 0)
                @php
                    $users = collect();
                    foreach($permission->roles as $role) {
                        $users = $users->merge($role->users);
                    }
                    $users = $users->unique('id');
                @endphp
                @if($users->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Users with this Permission ({{ $users->count() }})</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportUsers()">
                                <i class="ti ti-download me-1"></i>Export Users
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Role(s)</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users->take(10) as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2">
                                                            @if($user->avatar)
                                                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle">
                                                            @else
                                                                <div class="avatar-initial rounded-circle bg-primary">
                                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @foreach($user->roles->intersect($permission->roles) as $role)
                                                        <span class="badge bg-info me-1">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($user->is_active ?? true)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary" title="View User">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($users->count() > 10)
                                <div class="text-center mt-3">
                                    <small class="text-muted">Showing 10 of {{ $users->count() }} users</small>
                                    <br>
                                    <a href="#" class="btn btn-sm btn-outline-primary mt-2" onclick="showAllUsers()">
                                        <i class="ti ti-eye me-1"></i>View All Users
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i>Edit Permission
                        </a>
                        <button type="button" class="btn btn-info" onclick="duplicatePermission()">
                            <i class="ti ti-copy me-1"></i>Duplicate Permission
                        </button>
                        <button type="button" class="btn btn-success" onclick="exportPermission()">
                            <i class="ti ti-download me-1"></i>Export Permission
                        </button>
                        <hr>
                        <button type="button" class="btn btn-danger" onclick="deletePermission()">
                            <i class="ti ti-trash me-1"></i>Delete Permission
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $permission->roles->count() }}</h4>
                                <small class="text-muted">Roles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            @php
                                $totalUsers = 0;
                                foreach($permission->roles as $role) {
                                    $totalUsers += $role->users->count();
                                }
                            @endphp
                            <h4 class="text-success mb-1">{{ $totalUsers }}</h4>
                            <small class="text-muted">Users</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-info mb-1">{{ $permission->roles->where('is_active', true)->count() }}</h6>
                                <small class="text-muted">Active Roles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning mb-1">{{ $permission->roles->where('is_active', false)->count() }}</h6>
                            <small class="text-muted">Inactive Roles</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Details -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Permission Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Permission ID</small>
                        <code class="bg-light p-1 rounded">{{ $permission->id }}</code>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">System Name</small>
                        <code class="bg-light p-1 rounded">{{ $permission->name }}</code>
                    </div>
                    @if($permission->module)
                        <div class="mb-3">
                            <small class="text-muted d-block">Module</small>
                            <span class="badge bg-info">{{ ucfirst($permission->module) }}</span>
                        </div>
                    @endif
                    <div class="mb-3">
                        <small class="text-muted d-block">Created</small>
                        <div class="small">{{ $permission->created_at->format('M d, Y g:i A') }}</div>
                        <small class="text-muted">{{ $permission->created_at->diffForHumans() }}</small>
                    </div>
                    @if($permission->updated_at && $permission->updated_at != $permission->created_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <div class="small">{{ $permission->updated_at->format('M d, Y g:i A') }}</div>
                            <small class="text-muted">{{ $permission->updated_at->diffForHumans() }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Permissions -->
            @if($permission->module)
                @php
                    $relatedPermissions = \App\Models\Permission::where('module', $permission->module)
                        ->where('id', '!=', $permission->id)
                        ->where('is_active', true)
                        ->limit(5)
                        ->get();
                @endphp
                @if($relatedPermissions->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Related Permissions</h5>
                        </div>
                        <div class="card-body">
                            @foreach($relatedPermissions as $relatedPermission)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <div class="fw-medium">{{ $relatedPermission->display_name ?: $relatedPermission->name }}</div>
                                        <small class="text-muted"><code>{{ $relatedPermission->name }}</code></small>
                                    </div>
                                    <a href="{{ route('admin.permissions.show', $relatedPermission->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>
                                @if(!$loop->last)<hr class="my-2">@endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
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
                    <h6>Are you sure you want to delete this permission?</h6>
                    <p class="text-muted">This action cannot be undone. All roles with this permission will lose access.</p>
                    @if($permission->roles->count() > 0)
                        <div class="alert alert-warning">
                            <strong>{{ $permission->roles->count() }}</strong> role(s) currently have this permission.
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="{{ route('admin.permissions.destroy', $permission->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Permission</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Remove Role Modal -->
<div class="modal fade" id="removeRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Permission from Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this permission from the role <strong id="roleNameToRemove"></strong>?</p>
                <p class="text-muted">Users with this role will lose access to this permission.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemoveRole">Remove Permission</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let roleToRemove = null;

    function deletePermission() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function duplicatePermission() {
        const params = new URLSearchParams({
            duplicate: {{ $permission->id }},
            name: '{{ $permission->name }}_copy',
            display_name: '{{ $permission->display_name }} (Copy)',
            module: '{{ $permission->module }}',
            description: '{{ $permission->description }}'
        });
        
        window.location.href = `{{ route('admin.permissions.create') }}?${params.toString()}`;
    }

    function exportPermission() {
        const permissionData = {
            id: {{ $permission->id }},
            name: '{{ $permission->name }}',
            display_name: '{{ $permission->display_name }}',
            module: '{{ $permission->module }}',
            description: '{{ $permission->description }}',
            is_active: {{ $permission->is_active ? 'true' : 'false' }},
            roles: @json($permission->roles->pluck('name')),
            created_at: '{{ $permission->created_at->toISOString() }}',
            updated_at: '{{ $permission->updated_at->toISOString() }}',
            exported_at: new Date().toISOString()
        };
        
        const dataStr = JSON.stringify(permissionData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        
        const link = document.createElement('a');
        link.href = URL.createObjectURL(dataBlob);
        link.download = `permission_${permissionData.name}.json`;
        link.click();
    }

    function exportRoles() {
        const roles = @json($permission->roles->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'description' => $role->description,
                'is_active' => $role->is_active,
                'users_count' => $role->users->count()
            ];
        }));
        
        const exportData = {
            permission: '{{ $permission->name }}',
            permission_display_name: '{{ $permission->display_name }}',
            roles: roles,
            exported_at: new Date().toISOString()
        };
        
        const dataStr = JSON.stringify(exportData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        
        const link = document.createElement('a');
        link.href = URL.createObjectURL(dataBlob);
        link.download = `permission_${exportData.permission}_roles.json`;
        link.click();
    }

    function exportUsers() {
        @php
            $users = collect();
            foreach($permission->roles as $role) {
                foreach($role->users as $user) {
                    if (!$users->contains('id', $user->id)) {
                        $users->push([
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'roles' => $role->users->where('id', $user->id)->first()->roles->pluck('name')->toArray()
                        ]);
                    }
                }
            }
        @endphp
        
        const users = @json($users);
        
        const exportData = {
            permission: '{{ $permission->name }}',
            permission_display_name: '{{ $permission->display_name }}',
            users: users,
            exported_at: new Date().toISOString()
        };
        
        const dataStr = JSON.stringify(exportData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        
        const link = document.createElement('a');
        link.href = URL.createObjectURL(dataBlob);
        link.download = `permission_${exportData.permission}_users.json`;
        link.click();
    }

    function removeRoleFromPermission(roleId, roleName) {
        roleToRemove = roleId;
        document.getElementById('roleNameToRemove').textContent = roleName;
        const modal = new bootstrap.Modal(document.getElementById('removeRoleModal'));
        modal.show();
    }

    document.getElementById('confirmRemoveRole').addEventListener('click', function() {
        if (roleToRemove) {
            // Here you would make an AJAX call to remove the permission from the role
            // For now, we'll just reload the page
            alert('Feature not implemented yet. This would remove the permission from the role.');
            bootstrap.Modal.getInstance(document.getElementById('removeRoleModal')).hide();
        }
    });

    function bulkRemoveRoles() {
        if (confirm('Are you sure you want to remove this permission from all roles? This action cannot be undone.')) {
            alert('Feature not implemented yet. This would remove the permission from all roles.');
        }
    }

    function showAllUsers() {
        alert('Feature not implemented yet. This would show all users with this permission.');
    }
</script>
@endpush