@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Permission</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.permissions.index') }}">Permissions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                <a href="{{ route('admin.permissions.show', $permission->id) }}" class="btn btn-info d-inline-flex align-items-center">
                    <i class="ti ti-eye me-1"></i>View Permission
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Permission Form -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" id="permissionForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Permission Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Permission Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $permission->name) }}" 
                                                   placeholder="e.g., users.create, posts.edit" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Unique identifier (use dot notation: module.action)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="display_name" class="form-label">Display Name</label>
                                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                                   id="display_name" name="display_name" value="{{ old('display_name', $permission->display_name) }}" 
                                                   placeholder="e.g., Create Users, Edit Posts">
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Human-readable name for the permission</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="module" class="form-label">Module</label>
                                            <select class="form-select @error('module') is-invalid @enderror" id="module" name="module">
                                                <option value="">Select Module</option>
                                                <option value="user" {{ old('module', $permission->module) == 'user' ? 'selected' : '' }}>User Management</option>
                                                <option value="role" {{ old('module', $permission->module) == 'role' ? 'selected' : '' }}>Role Management</option>
                                                <option value="permission" {{ old('module', $permission->module) == 'permission' ? 'selected' : '' }}>Permission Management</option>
                                                <option value="dashboard" {{ old('module', $permission->module) == 'dashboard' ? 'selected' : '' }}>Dashboard</option>
                                                <option value="settings" {{ old('module', $permission->module) == 'settings' ? 'selected' : '' }}>Settings</option>
                                                <option value="reports" {{ old('module', $permission->module) == 'reports' ? 'selected' : '' }}>Reports</option>
                                                <option value="content" {{ old('module', $permission->module) == 'content' ? 'selected' : '' }}>Content Management</option>
                                                <option value="system" {{ old('module', $permission->module) == 'system' ? 'selected' : '' }}>System</option>
                                                <option value="other" {{ old('module', $permission->module) == 'other' || (!in_array(old('module', $permission->module), ['user', 'role', 'permission', 'dashboard', 'settings', 'reports', 'content', 'system']) && old('module', $permission->module)) ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('module')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Group permissions by module for better organization</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="customModuleDiv" style="{{ old('module', $permission->module) == 'other' || (!in_array(old('module', $permission->module), ['user', 'role', 'permission', 'dashboard', 'settings', 'reports', 'content', 'system']) && old('module', $permission->module)) ? 'display: block;' : 'display: none;' }}">
                                        <div class="mb-3">
                                            <label for="custom_module" class="form-label">Custom Module Name</label>
                                            <input type="text" class="form-control" id="custom_module" name="custom_module" 
                                                   value="{{ old('custom_module', (!in_array($permission->module, ['user', 'role', 'permission', 'dashboard', 'settings', 'reports', 'content', 'system']) && $permission->module) ? $permission->module : '') }}"
                                                   placeholder="Enter custom module name">
                                            <small class="form-text text-muted">Enter custom module name if 'Other' is selected</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Describe what this permission allows users to do">{{ old('description', $permission->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Clear description helps administrators understand the permission's purpose</small>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $permission->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable or disable this permission</small>
                                </div>
                            </div>
                        </div>

                        <!-- Roles with this Permission -->
                        @if($permission->roles && $permission->roles->count() > 0)
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Roles with this Permission ({{ $permission->roles->count() }})</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($permission->roles as $role)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="border rounded p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-0">{{ $role->display_name ?: $role->name }}</h6>
                                                        @if($role->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </div>
                                                    @if($role->description)
                                                        <p class="text-muted small mb-2">{{ $role->description }}</p>
                                                    @endif
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">{{ $role->users->count() ?? 0 }} users</small>
                                                        <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Actions & Statistics -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>Update Permission
                                    </button>
                                    <a href="{{ route('admin.permissions.show', $permission->id) }}" class="btn btn-info">
                                        <i class="ti ti-eye me-1"></i>View Permission
                                    </a>
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-light">
                                        <i class="ti ti-x me-1"></i>Cancel
                                    </a>
                                </div>
                                <hr>
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <strong>Warning:</strong> Changing this permission may affect {{ $permission->roles->count() }} role(s) and their users.
                                </div>
                            </div>
                        </div>

                        <!-- Permission Statistics -->
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
                                        <h4 class="text-success mb-1">{{ $permission->roles->sum(function($role) { return $role->users->count(); }) }}</h4>
                                        <small class="text-muted">Users</small>
                                    </div>
                                </div>
                                <hr>
                                <small class="text-muted">
                                    <i class="ti ti-calendar me-1"></i>
                                    Created: {{ $permission->created_at->format('M d, Y') }}
                                </small>
                                @if($permission->updated_at && $permission->updated_at != $permission->created_at)
                                    <br>
                                    <small class="text-muted">
                                        <i class="ti ti-edit me-1"></i>
                                        Updated: {{ $permission->updated_at->format('M d, Y') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Permission Preview -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Preview</h5>
                            </div>
                            <div class="card-body">
                                <div id="permissionPreview">
                                    <!-- Preview will be updated by JavaScript -->
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
                                    <button type="button" class="btn btn-warning btn-sm" onclick="duplicatePermission()">
                                        <i class="ti ti-copy me-1"></i>Duplicate Permission
                                    </button>
                                    @if($permission->roles->count() > 0)
                                        <button type="button" class="btn btn-info btn-sm" onclick="exportRoles()">
                                            <i class="ti ti-download me-1"></i>Export Roles
                                        </button>
                                    @endif
                                    <hr>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deletePermission()">
                                        <i class="ti ti-trash me-1"></i>Delete Permission
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Show/hide custom module field
        $('#module').change(function() {
            const selectedModule = $(this).val();
            const isOther = selectedModule === 'other';
            const isCustomModule = !['user', 'role', 'permission', 'dashboard', 'settings', 'reports', 'content', 'system'].includes(selectedModule) && selectedModule;
            
            if (isOther || isCustomModule) {
                $('#customModuleDiv').show();
                $('#custom_module').attr('required', true);
                if (isCustomModule) {
                    $('#custom_module').val(selectedModule);
                }
            } else {
                $('#customModuleDiv').hide();
                $('#custom_module').attr('required', false);
                if (!isCustomModule) {
                    $('#custom_module').val('');
                }
            }
            updatePreview();
        });

        // Update preview when form fields change
        $('#name, #display_name, #module, #custom_module, #description, #is_active').on('input change', updatePreview);

        // Initialize preview
        updatePreview();

        // Form validation
        $('#permissionForm').on('submit', function(e) {
            const permissionName = $('#name').val().trim();
            
            if (!permissionName) {
                e.preventDefault();
                $('#name').addClass('is-invalid');
                $('#name').focus();
                return false;
            }

            // Validate permission name format
            if (!/^[a-z0-9._-]+$/i.test(permissionName)) {
                e.preventDefault();
                alert('Permission name can only contain letters, numbers, dots, hyphens, and underscores.');
                $('#name').addClass('is-invalid').focus();
                return false;
            }
        });
    });

    function updatePreview() {
        const name = $('#name').val();
        const displayName = $('#display_name').val();
        const moduleSelect = $('#module').val();
        const customModule = $('#custom_module').val();
        const module = moduleSelect === 'other' ? customModule : moduleSelect;
        const description = $('#description').val();
        const isActive = $('#is_active').is(':checked');

        const statusBadge = isActive 
            ? '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Active</span>'
            : '<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Inactive</span>';

        const moduleBadge = module 
            ? `<span class="badge bg-info">${module.charAt(0).toUpperCase() + module.slice(1)}</span>`
            : '<span class="badge bg-secondary">General</span>';

        $('#permissionPreview').html(`
            <div class="border rounded p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">${displayName || name || 'Permission Name'}</h6>
                    ${statusBadge}
                </div>
                <p class="text-muted small mb-2"><code>${name || 'permission.name'}</code></p>
                <div class="mb-2">${moduleBadge}</div>
                ${description ? `<p class="small text-muted mb-0">${description}</p>` : ''}
            </div>
        `);
    }

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

    function exportRoles() {
        const roles = @json($permission->roles->pluck('name'));
        const permissionData = {
            permission: '{{ $permission->name }}',
            display_name: '{{ $permission->display_name }}',
            module: '{{ $permission->module }}',
            description: '{{ $permission->description }}',
            roles: roles,
            exported_at: new Date().toISOString()
        };
        
        const dataStr = JSON.stringify(permissionData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        
        const link = document.createElement('a');
        link.href = URL.createObjectURL(dataBlob);
        link.download = `permission_${permissionData.permission}_roles.json`;
        link.click();
    }
</script>
@endpush