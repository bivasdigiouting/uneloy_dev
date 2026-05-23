@extends('layouts.admin')

@section('title', 'Role Management')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Role Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Administration
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add New Role
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Roles Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <h5 class="mb-0">All Roles</h5>
                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                <i class="ti ti-file-export me-1"></i>Export
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-3">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1"><i class="ti ti-file-type-pdf me-1"></i>Export as PDF</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1"><i class="ti ti-file-type-xls me-1"></i>Export as Excel</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $key => $role)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary-transparent rounded-circle me-2">
                                                <i class="ti ti-shield-check"></i>
                                            </div>
                                            <span class="fw-medium">{{ $role->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $role->display_name ?? '-' }}</td>
                                    <td>
                                        <span class="text-truncate" style="max-width: 200px; display: inline-block;" title="{{ $role->description }}">
                                            {{ $role->description ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-transparent">{{ $role->permissions->count() }} permissions</span>
                                    </td>
                                    <td>
                                        @if($role->is_active)
                                            <span class="badge bg-success-transparent"><i class="ti ti-check me-1"></i>Active</span>
                                        @else
                                            <span class="badge bg-danger-transparent"><i class="ti ti-x me-1"></i>Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $role->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <a href="javascript:void(0);" class="btn btn-white btn-icon btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end p-3">
                                                <li>
                                                    <a class="dropdown-item rounded-1" href="{{ route('admin.roles.show', $role->id) }}">
                                                        <i class="ti ti-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item rounded-1" href="{{ route('admin.roles.edit', $role->id) }}">
                                                        <i class="ti ti-edit me-2"></i>Edit Role
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item rounded-1 text-danger" href="javascript:void(0);" onclick="deleteRole({{ $role->id }})">
                                                        <i class="ti ti-trash me-2"></i>Delete Role
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-shield-off fs-48 text-muted mb-2"></i>
                                            <h6 class="text-muted">No roles found</h6>
                                            <p class="text-muted mb-3">Create your first role to get started</p>
                                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                                                <i class="ti ti-plus me-1"></i>Add New Role
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="ti ti-alert-triangle fs-48 text-warning mb-3"></i>
                    <h6>Are you sure you want to delete this role?</h6>
                    <p class="text-muted">This action cannot be undone. All users assigned to this role will lose their permissions.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
    // Initialize DataTable
    $(document).ready(function() {
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [7] } // Disable sorting for Actions column
            ],
            language: {
                search: "Search roles:",
                lengthMenu: "Show _MENU_ roles per page",
                info: "Showing _START_ to _END_ of _TOTAL_ roles",
                infoEmpty: "No roles available",
                emptyTable: "No roles found"
            }
        });
    });

    // Delete role function
    function deleteRole(roleId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `{{ url('admin/roles') }}/${roleId}`;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endpush