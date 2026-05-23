@extends('layouts.admin')

@section('title', 'Product Categories')

@section('content')

    <div class="content">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Product Categories</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            Product Management
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Product Categories</li>
                    </ol>
                </nav>
            </div>
            
                <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i>Add Product Category
                </a>
            
        </div>
        <!-- /Page Header -->

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total Product Categories -->
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="card flex-fill dashboard-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center overflow-hidden">
                            <span class="avatar avatar-lg bg-primary flex-shrink-0">
                                <i class="ti ti-category fs-16"></i>
                            </span>
                            <div class="ms-2 overflow-hidden">
                                <p class="fs-12 fw-medium mb-1 text-truncate">Total Categories</p>
                                <h4 class="mb-1 counter-animation" id="total-categories">{{ $stats['total'] ?? 0 }}</h4>
                                <span class="badge badge-primary-transparent fs-10">
                                    <i class="ti ti-list me-1"></i>
                                    All Categories
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Total Product Categories -->

            <!-- Active Product Categories -->
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="card flex-fill dashboard-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center overflow-hidden">
                            <span class="avatar avatar-lg bg-success flex-shrink-0">
                                <i class="ti ti-circle-check fs-16"></i>
                            </span>
                            <div class="ms-2 overflow-hidden">
                                <p class="fs-12 fw-medium mb-1 text-truncate">Active Categories</p>
                                <h4 class="mb-1 counter-animation" id="active-categories">{{ $stats['active'] ?? 0 }}</h4>
                                <span class="badge badge-success-transparent fs-10">
                                    <i class="ti ti-check me-1"></i>
                                    Published
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Active Product Categories -->

            <!-- Inactive Product Categories -->
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="card flex-fill dashboard-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center overflow-hidden">
                            <span class="avatar avatar-lg bg-danger flex-shrink-0">
                                <i class="ti ti-circle-x fs-16"></i>
                            </span>
                            <div class="ms-2 overflow-hidden">
                                <p class="fs-12 fw-medium mb-1 text-truncate">Inactive Categories</p>
                                <h4 class="mb-1 counter-animation" id="inactive-categories">{{ $stats['inactive'] ?? 0 }}</h4>
                                <span class="badge badge-danger-transparent fs-10">
                                    <i class="ti ti-x me-1"></i>
                                    Unpublished
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Inactive Product Categories -->

            <!-- Average Commission -->
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="card flex-fill dashboard-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center overflow-hidden">
                            <span class="avatar avatar-lg bg-warning flex-shrink-0">
                                <i class="ti ti-percentage fs-16"></i>
                            </span>
                            <div class="ms-2 overflow-hidden">
                                <p class="fs-12 fw-medium mb-1 text-truncate">Avg Commission</p>
                                <h4 class="mb-1 counter-animation" id="avg-commission">{{ $stats['avg_commission'] ?? '0.00' }}%</h4>
                                <span class="badge badge-warning-transparent fs-10">
                                    <i class="ti ti-coins me-1"></i>
                                    Commission Rate
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Average Commission -->
        </div>
        <!-- /Statistics Cards -->

        <!-- Product Categories Table -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <h5>Product Categories List</h5>
                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="me-3">
                        <div class="input-icon-end position-relative">
                            <input type="text" class="form-control date-range bookingrange" placeholder="Select Date Range">
                            <span class="input-icon-addon">
                                <i class="ti ti-chevron-down"></i>
                            </span>
                        </div>
                    </div>
                    <div class="dropdown me-3">
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
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Sort By : Last 7 Days
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recently Added</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Ascending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="product-categories-table">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Sequence</th>
                                <th>Commission(%)</th>
                                <th>Commission(%) for Level</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Product Categories Table -->
    </div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel">Delete Product Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product category? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#product-categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.product-categories.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { 
                data: 'icon_display', 
                name: 'icon_display',
                orderable: false,
                searchable: false
            },
            { data: 'name', name: 'name' },
            { data: 'sequence', name: 'sequence' },
            { 
                data: 'commission', 
                name: 'commission',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return data + '%';
                }
            },
            { 
                data: 'commission_level', 
                name: 'commission_level',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return data + '%';
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    if (data == 1) {
                        return '<span class="badge badge-success-transparent">Active</span>';
                    } else {
                        return '<span class="badge badge-danger-transparent">Inactive</span>';
                    }
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data, type, row) {
                    return moment(data).format('DD MMM YYYY');
                }
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    var actions = '<div class="action-icon d-inline-flex">';
                    
                    // View action
                    actions += '<a href="' + "{{ route('admin.product-categories.show', ':id') }}".replace(':id', row.id) + '" class="me-2">';
                    actions += '<i class="ti ti-eye"></i>';
                    actions += '</a>';
                    
                    // Edit action
                    actions += '<a href="' + "{{ route('admin.product-categories.edit', ':id') }}".replace(':id', row.id) + '" class="me-2">';
                    actions += '<i class="ti ti-edit"></i>';
                    actions += '</a>';
                    
                    // Status toggle action
                    var statusIcon = row.status == 1 ? 'ti-toggle-right text-success' : 'ti-toggle-left text-danger';
                    var statusTitle = row.status == 1 ? 'Deactivate' : 'Activate';
                    actions += '<a href="javascript:void(0);" class="me-2 toggle-status" data-id="' + row.id + '" title="' + statusTitle + '">';
                    actions += '<i class="ti ' + statusIcon + '"></i>';
                    actions += '</a>';
                    
                    // Delete action
                    actions += '<a href="javascript:void(0);" class="delete-btn" data-id="' + row.id + '">';
                    actions += '<i class="ti ti-trash"></i>';
                    actions += '</a>';
                    
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        columnDefs: [
            { targets: 'no-sort', orderable: false }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search product categories...",
            lengthMenu: "_MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)"
        }
    });

    // Status toggle functionality
    $(document).on('click', '.toggle-status', function() {
        var id = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: "{{ route('admin.product-categories.toggle-status', ':id') }}".replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update the icon and title
                    var icon = button.find('i');
                    if (response.status == 1) {
                        icon.removeClass('ti-toggle-left text-danger').addClass('ti-toggle-right text-success');
                        button.attr('title', 'Deactivate');
                    } else {
                        icon.removeClass('ti-toggle-right text-success').addClass('ti-toggle-left text-danger');
                        button.attr('title', 'Activate');
                    }
                    
                    // Refresh the table to update status badge
                    table.ajax.reload(null, false);
                    
                    // Update statistics
                    updateStatistics();
                    
                    // Show success message
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Failed to update status');
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while updating status');
            }
        });
    });

    // Delete functionality
    var deleteId = null;
    
    $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        $('#delete_modal').modal('show');
    });
    
    $('#confirm-delete').click(function() {
        if (deleteId) {
            $.ajax({
                url: "{{ route('admin.product-categories.destroy', ':id') }}".replace(':id', deleteId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#delete_modal').modal('hide');
                    if (response.success) {
                        table.ajax.reload();
                        updateStatistics();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message || 'Failed to delete product category');
                    }
                },
                error: function(xhr) {
                    $('#delete_modal').modal('hide');
                    toastr.error('An error occurred while deleting the product category');
                }
            });
        }
    });

    // Function to toggle product category status
    function toggleProductCategoryStatus(id) {
        $.ajax({
            url: "{{ route('admin.product-categories.toggle-status', ':id') }}".replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    updateStatistics();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Failed to update status');
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while updating the status');
            }
        });
    }

    // Function to update statistics
    function updateStatistics() {
        $.ajax({
            url: "{{ route('admin.product-categories.index') }}",
            type: 'GET',
            data: { stats_only: true },
            success: function(response) {
                if (response.stats) {
                    $('#total-categories').text(response.stats.total || 0);
                    $('#active-categories').text(response.stats.active || 0);
                    $('#inactive-categories').text(response.stats.inactive || 0);
                    $('#avg-commission').text((response.stats.avg_commission || 0) + '%');
                }
            }
        });
    }
});
</script>
@endpush