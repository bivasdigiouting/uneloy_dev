@extends('layouts.admin')

@section('title', 'Permissions Management')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Permissions Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                        <i class="ti ti-file-export me-1"></i>Export
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1" onclick="exportData('excel')">
                                <i class="ti ti-file-type-xls me-2"></i>Export as Excel
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1" onclick="exportData('pdf')">
                                <i class="ti ti-file-type-pdf me-2"></i>Export as PDF
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1" onclick="exportData('csv')">
                                <i class="ti ti-file-type-csv me-2"></i>Export as CSV
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add Permission
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Permissions Table -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
            <h5 class="mb-0">All Permissions</h5>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <select class="form-select form-select-sm" id="moduleFilter">
                        <option value="">All Modules</option>
                        <option value="user">User Management</option>
                        <option value="role">Role Management</option>
                        <option value="permission">Permission Management</option>
                        <option value="dashboard">Dashboard</option>
                        <option value="settings">Settings</option>
                        <option value="reports">Reports</option>
                    </select>
                </div>
                <div class="me-3">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="permissionsTable">
                    <thead>
                        <tr>
                            <th width="5%">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Permission Name</th>
                            <th>Display Name</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Roles Count</th>
                            <th>Created Date</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via DataTables AJAX -->
                    </tbody>
                </table>
            </div>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Permission</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select class="form-select" name="action" required>
                            <option value="">Choose action...</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <span id="selectedCount">0</span> permission(s) selected
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#permissionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.permissions.index") }}',
                data: function(d) {
                    d.module = $('#moduleFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `<div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" value="${data}">
                                </div>`;
                    }
                },
                { data: 'name', name: 'name' },
                { 
                    data: 'display_name', 
                    name: 'display_name',
                    render: function(data, type, row) {
                        return data || '<span class="text-muted">Not set</span>';
                    }
                },
                { 
                    data: 'module', 
                    name: 'module',
                    render: function(data) {
                        if (!data) return '<span class="badge bg-secondary">General</span>';
                        return `<span class="badge bg-info">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                    }
                },
                { 
                    data: 'description', 
                    name: 'description',
                    render: function(data) {
                        if (!data) return '<span class="text-muted">No description</span>';
                        return data.length > 50 ? data.substring(0, 50) + '...' : data;
                    }
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data) {
                        return data 
                            ? '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Active</span>'
                            : '<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Inactive</span>';
                    }
                },
                {
                    data: 'roles_count',
                    name: 'roles_count',
                    render: function(data) {
                        return `<span class="badge bg-primary">${data || 0}</span>`;
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/admin/permissions/${row.id}"><i class="ti ti-eye me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="/admin/permissions/${row.id}/edit"><i class="ti ti-edit me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deletePermission(${row.id})"><i class="ti ti-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ],
            order: [[1, 'asc']],
            pageLength: 25,
            responsive: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l<"ms-3"f>><"d-flex align-items-center"<"me-3"B>p>>rtip',
            buttons: [
                {
                    text: '<i class="ti ti-settings me-1"></i>Bulk Actions',
                    className: 'btn btn-outline-primary btn-sm',
                    action: function() {
                        const selected = $('.permission-checkbox:checked').length;
                        if (selected === 0) {
                            alert('Please select at least one permission.');
                            return;
                        }
                        $('#selectedCount').text(selected);
                        $('#bulkActionModal').modal('show');
                    }
                }
            ]
        });

        // Filter handlers
        $('#moduleFilter, #statusFilter').change(function() {
            table.draw();
        });

        // Select all functionality
        $('#selectAll').change(function() {
            $('.permission-checkbox').prop('checked', this.checked);
        });

        // Update select all when individual checkboxes change
        $(document).on('change', '.permission-checkbox', function() {
            const totalCheckboxes = $('.permission-checkbox').length;
            const checkedCheckboxes = $('.permission-checkbox:checked').length;
            
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
            $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        });
    });

    function deletePermission(permissionId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `{{ route('admin.permissions.index') }}/${permissionId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function executeBulkAction() {
        const form = document.getElementById('bulkActionForm');
        const action = form.querySelector('select[name="action"]').value;
        const selectedIds = Array.from(document.querySelectorAll('.permission-checkbox:checked')).map(cb => cb.value);
        
        if (!action) {
            alert('Please select an action.');
            return;
        }
        
        if (selectedIds.length === 0) {
            alert('Please select at least one permission.');
            return;
        }
        
        if (action === 'delete' && !confirm('Are you sure you want to delete the selected permissions?')) {
            return;
        }
        
        // Create and submit form
        const bulkForm = document.createElement('form');
        bulkForm.method = 'POST';
        bulkForm.action = '{{ route("admin.permissions.bulk-action") }}';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        bulkForm.appendChild(csrfInput);
        
        // Add action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        bulkForm.appendChild(actionInput);
        
        // Add selected IDs
        selectedIds.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ids[]';
            idInput.value = id;
            bulkForm.appendChild(idInput);
        });
        
        document.body.appendChild(bulkForm);
        bulkForm.submit();
    }

    function exportData(format) {
        const params = new URLSearchParams({
            format: format,
            module: $('#moduleFilter').val(),
            status: $('#statusFilter').val()
        });
        
        window.location.href = `{{ route('admin.permissions.export') }}?${params.toString()}`;
    }
</script>
@endpush