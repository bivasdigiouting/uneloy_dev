@extends('layouts.admin')

@section('title', 'Retailer / Employee Permission')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Retailer / Employee Permission</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">E-Card Seva Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Retailer / Employee Permission</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <span class="badge bg-info-transparent d-inline-flex align-items-center"><i class="ti ti-info-circle me-1"></i>Setup mapping of roles to service levels</span>
            </div>
        </div>
    </div>

    <!-- Intro -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>
                Use this module to map retailer/employee roles to E-Card Seva service levels and assign permissions. This is a foundation page; actions will be added in the next step.
            </div>
        </div>
    </div>

    <!-- Roles & Levels Matrix (placeholder) -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Roles & Levels</h5>
                    <div class="d-flex align-items-center">
                        <span class="text-muted">Preview of available roles and commission levels</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-2">Roles</h6>
                            <ul class="list-group">
                                @forelse($roles as $role)
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <span><i class="ti ti-shield-check me-2"></i>{{ $role->display_name ?? $role->name }}</span>
                                        <span class="badge bg-success-transparent">{{ $role->is_active ? 'Active' : 'Inactive' }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item">No roles found.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-2">Commission Levels</h6>
                            <ul class="list-group">
                                @forelse($commissionLevels as $level)
                                    <li class="list-group-item"><i class="ti ti-hierarchy-2 me-2"></i>{{ $level }}</li>
                                @empty
                                    <li class="list-group-item">No commission levels configured.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Summary (placeholder) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Available Permissions</h5>
                    <div class="d-flex align-items-center">
                        <span class="text-muted">Grouped by module</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    <th>Display Name</th>
                                    <th>Module</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                    <tr>
                                        <td><i class="ti ti-key me-2"></i>{{ $permission->name }}</td>
                                        <td>{{ $permission->display_name ?? '-' }}</td>
                                        <td><span class="badge bg-secondary-transparent">{{ $permission->module ?? 'General' }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No permissions defined.</td>
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

<!-- Role Permission Actions -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Role Permission Actions</h5>
                <span class="text-muted">Assign permissions to roles</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr data-role-id="{{ $role->id }}">
                                    <td><i class="ti ti-shield-check me-2"></i>{{ $role->display_name ?? $role->name }}</td>
                                    <td><span class="badge {{ $role->is_active ? 'bg-success-transparent' : 'bg-danger-transparent' }}">{{ $role->is_active ? 'Active' : 'Inactive' }}</span></td>
                                    <td><span class="assigned-count">{{ $role->permissions()->count() }}</span></td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-primary btn-sm btn-set-permissions"
                                            data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->display_name ?? $role->name }}"
                                            data-fetch-url="{{ route('admin.retailer-employee-permissions.role-permissions', $role) }}"
                                            data-save-url="{{ route('admin.retailer-employee-permissions.assign', $role) }}">
                                            <i class="ti ti-adjustments"></i> Set Permissions
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No roles found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Permissions Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Permissions — <span id="modalRoleName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $groupedPermissions = $permissions->groupBy(function($perm){ return $perm->module ?? 'General'; });
                @endphp
                <div class="accordion" id="permissionAccordion">
                    @foreach($groupedPermissions as $module => $list)
                        @php $moduleId = 'module-'.md5($module ?? 'general'); @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $moduleId }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $moduleId }}" aria-expanded="false" aria-controls="{{ $moduleId }}">
                                    {{ $module ?? 'General' }}
                                </button>
                            </h2>
                            <div id="{{ $moduleId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $moduleId }}" data-bs-parent="#permissionAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        @foreach($list as $permission)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm-{{ $permission->id }}" data-permission-id="{{ $permission->id }}">
                                                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                        {{ $permission->display_name ?? $permission->name }}
                                                        <span class="text-muted small">({{ $permission->name }})</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePermissions">Save Changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
    var currentRoleId = null;
    var currentSaveUrl = null;

    $('.btn-set-permissions').on('click', function(){
        var btn = $(this);
        currentRoleId = btn.data('role-id');
        var roleName = btn.data('role-name');
        var fetchUrl = btn.data('fetch-url');
        currentSaveUrl = btn.data('save-url');

        $('#modalRoleName').text(roleName);
        $('.permission-checkbox').prop('checked', false);

        var saveBtn = $('#savePermissions');
        saveBtn.prop('disabled', true).text('Loading...');

        $.get(fetchUrl)
            .done(function(resp){
                if(resp && resp.success){
                    var ids = resp.permission_ids || [];
                    ids.forEach(function(id){
                        $('#perm-' + id).prop('checked', true);
                    });
                    saveBtn.prop('disabled', false).text('Save Changes');

                    var modalEl = document.getElementById('permissionModal');
                    var bsModal = new bootstrap.Modal(modalEl);
                    bsModal.show();
                } else {
                    toastr.error((resp && resp.message) || 'Failed to load permissions');
                    saveBtn.prop('disabled', false).text('Save Changes');
                }
            })
            .fail(function(){
                toastr.error('Failed to load permissions');
                saveBtn.prop('disabled', false).text('Save Changes');
            });
    });

    $('#savePermissions').on('click', function(){
        var saveBtn = $(this);
        var ids = $('.permission-checkbox:checked').map(function(){
            return $(this).data('permission-id');
        }).get();

        saveBtn.prop('disabled', true).text('Saving...');

        $.post(currentSaveUrl, { permission_ids: ids })
            .done(function(resp){
                if(resp && resp.success){
                    toastr.success(resp.message || 'Permissions updated successfully');
                    $('tr[data-role-id="' + currentRoleId + '"] .assigned-count').text(ids.length);

                    var modalEl = document.getElementById('permissionModal');
                    var bsModal = bootstrap.Modal.getInstance(modalEl);
                    bsModal && bsModal.hide();
                } else {
                    toastr.error((resp && resp.message) || 'Failed to save permissions');
                }
            })
            .fail(function(xhr){
                var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Failed to save permissions';
                toastr.error(msg);
            })
            .always(function(){
                saveBtn.prop('disabled', false).text('Save Changes');
            });
    });
});
</script>
@endpush