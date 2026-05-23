@extends('layouts.admin')

@section('title', 'Create Permission')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create New Permission</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.permissions.index') }}">Permissions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Permissions
                </a>
            </div>
        </div>
    </div>

    <!-- Create Permission Form -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.permissions.store') }}" method="POST" id="permissionForm">
                @csrf
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
                                                   id="name" name="name" value="{{ old('name') }}" 
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
                                                   id="display_name" name="display_name" value="{{ old('display_name') }}" 
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
                                                <option value="user" {{ old('module') == 'user' ? 'selected' : '' }}>User Management</option>
                                                <option value="role" {{ old('module') == 'role' ? 'selected' : '' }}>Role Management</option>
                                                <option value="permission" {{ old('module') == 'permission' ? 'selected' : '' }}>Permission Management</option>
                                                <option value="dashboard" {{ old('module') == 'dashboard' ? 'selected' : '' }}>Dashboard</option>
                                                <option value="settings" {{ old('module') == 'settings' ? 'selected' : '' }}>Settings</option>
                                                <option value="reports" {{ old('module') == 'reports' ? 'selected' : '' }}>Reports</option>
                                                <option value="content" {{ old('module') == 'content' ? 'selected' : '' }}>Content Management</option>
                                                <option value="system" {{ old('module') == system' ? 'selected' : '' }}>System</option>
                                                <option value="other" {{ old('module') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('module')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Group permissions by module for better organization</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="customModuleDiv" style="display: none;">
                                        <div class="mb-3">
                                            <label for="custom_module" class="form-label">Custom Module Name</label>
                                            <input type="text" class="form-control" id="custom_module" name="custom_module" 
                                                   placeholder="Enter custom module name">
                                            <small class="form-text text-muted">Enter custom module name if 'Other' is selected</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Describe what this permission allows users to do">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Clear description helps administrators understand the permission's purpose</small>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable or disable this permission</small>
                                </div>
                            </div>
                        </div>

                        <!-- Permission Templates -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Templates</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Use these templates to quickly create common permissions:</p>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h6 class="text-primary mb-2">CRUD Operations</h6>
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyTemplate('create')">
                                                    <i class="ti ti-plus me-1"></i>Create Permission
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm" onclick="applyTemplate('read')">
                                                    <i class="ti ti-eye me-1"></i>Read Permission
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="applyTemplate('update')">
                                                    <i class="ti ti-edit me-1"></i>Update Permission
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="applyTemplate('delete')">
                                                    <i class="ti ti-trash me-1"></i>Delete Permission
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h6 class="text-success mb-2">Special Actions</h6>
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-outline-success btn-sm" onclick="applyTemplate('manage')">
                                                    <i class="ti ti-settings me-1"></i>Manage Permission
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm" onclick="applyTemplate('export')">
                                                    <i class="ti ti-download me-1"></i>Export Permission
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="applyTemplate('import')">
                                                    <i class="ti ti-upload me-1"></i>Import Permission
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyTemplate('approve')">
                                                    <i class="ti ti-check me-1"></i>Approve Permission
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions & Guidelines -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>Create Permission
                                    </button>
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-light">
                                        <i class="ti ti-x me-1"></i>Cancel
                                    </a>
                                </div>
                                <hr>
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Tip:</strong> Use descriptive names and organize permissions by modules for better management.
                                </div>
                            </div>
                        </div>

                        <!-- Naming Guidelines -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Naming Guidelines</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-primary mb-2">Recommended Format:</h6>
                                    <code>module.action</code>
                                    <small class="d-block text-muted mt-1">e.g., users.create, posts.edit</small>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-success mb-2">Good Examples:</h6>
                                    <ul class="list-unstyled small">
                                        <li><code>users.create</code></li>
                                        <li><code>roles.manage</code></li>
                                        <li><code>reports.export</code></li>
                                        <li><code>settings.update</code></li>
                                    </ul>
                                </div>
                                <div>
                                    <h6 class="text-warning mb-2">Avoid:</h6>
                                    <ul class="list-unstyled small text-muted">
                                        <li>Spaces in names</li>
                                        <li>Special characters</li>
                                        <li>Very long names</li>
                                        <li>Ambiguous terms</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Permission Preview -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Preview</h5>
                            </div>
                            <div class="card-body">
                                <div id="permissionPreview">
                                    <div class="text-center text-muted">
                                        <i class="ti ti-eye-off fs-48 mb-2"></i>
                                        <p>Fill the form to see preview</p>
                                    </div>
                                </div>
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
        // Show/hide custom module field
        $('#module').change(function() {
            if ($(this).val() === 'other') {
                $('#customModuleDiv').show();
                $('#custom_module').attr('required', true);
            } else {
                $('#customModuleDiv').hide();
                $('#custom_module').attr('required', false).val('');
            }
            updatePreview();
        });

        // Auto-generate display name from permission name
        $('#name').on('input', function() {
            const name = $(this).val();
            if (name && !$('#display_name').val()) {
                const displayName = name.split('.').map(part => 
                    part.charAt(0).toUpperCase() + part.slice(1)
                ).join(' ');
                $('#display_name').val(displayName);
            }
            updatePreview();
        });

        // Update preview when form fields change
        $('#name, #display_name, #module, #custom_module, #description').on('input change', updatePreview);

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
        const module = $('#module').val() === 'other' ? $('#custom_module').val() : $('#module').val();
        const description = $('#description').val();
        const isActive = $('#is_active').is(':checked');

        if (!name) {
            $('#permissionPreview').html(`
                <div class="text-center text-muted">
                    <i class="ti ti-eye-off fs-48 mb-2"></i>
                    <p>Fill the form to see preview</p>
                </div>
            `);
            return;
        }

        const statusBadge = isActive 
            ? '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Active</span>'
            : '<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Inactive</span>';

        const moduleBadge = module 
            ? `<span class="badge bg-info">${module.charAt(0).toUpperCase() + module.slice(1)}</span>`
            : '<span class="badge bg-secondary">General</span>';

        $('#permissionPreview').html(`
            <div class="border rounded p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">${displayName || name}</h6>
                    ${statusBadge}
                </div>
                <p class="text-muted small mb-2"><code>${name}</code></p>
                <div class="mb-2">${moduleBadge}</div>
                ${description ? `<p class="small text-muted mb-0">${description}</p>` : ''}
            </div>
        `);
    }

    function applyTemplate(action) {
        const templates = {
            create: {
                suffix: '.create',
                display: 'Create',
                description: 'Allows creating new records'
            },
            read: {
                suffix: '.read',
                display: 'View',
                description: 'Allows viewing records'
            },
            update: {
                suffix: '.update',
                display: 'Update',
                description: 'Allows updating existing records'
            },
            delete: {
                suffix: '.delete',
                display: 'Delete',
                description: 'Allows deleting records'
            },
            manage: {
                suffix: '.manage',
                display: 'Manage',
                description: 'Full management access'
            },
            export: {
                suffix: '.export',
                display: 'Export',
                description: 'Allows exporting data'
            },
            import: {
                suffix: '.import',
                display: 'Import',
                description: 'Allows importing data'
            },
            approve: {
                suffix: '.approve',
                display: 'Approve',
                description: 'Allows approving requests'
            }
        };

        const template = templates[action];
        if (!template) return;

        // Get current module or prompt for one
        let module = $('#module').val();
        if (!module) {
            module = prompt('Enter module name (e.g., users, posts, settings):');
            if (!module) return;
            
            // Set module in dropdown if it exists, otherwise set to 'other'
            const moduleOption = $(`#module option[value="${module}"]`);
            if (moduleOption.length) {
                $('#module').val(module);
            } else {
                $('#module').val('other');
                $('#custom_module').val(module);
                $('#customModuleDiv').show();
            }
        }

        // Apply template
        const permissionName = module + template.suffix;
        const displayName = template.display + ' ' + module.charAt(0).toUpperCase() + module.slice(1);
        
        $('#name').val(permissionName);
        $('#display_name').val(displayName);
        $('#description').val(template.description + ' in ' + module + ' module.');
        
        updatePreview();
    }
</script>
@endpush