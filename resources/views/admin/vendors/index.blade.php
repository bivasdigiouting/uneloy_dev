@extends('layouts.admin')

@section('title', 'Vendor Master')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Vendor Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Master Modules
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Master</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Vendors List</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add New Vendor
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="vendorsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Business Name</th>
                                    <th>Registration Category</th>
                                    <th>Contact Person</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this vendor?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link href="{{ asset('backend-assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('backend-assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('backend-assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
<!-- DataTables JS -->
<script src="{{ asset('backend-assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend-assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('backend-assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend-assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('backend-assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend-assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#vendorsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.vendors.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'business_name', name: 'business_name' },
            { data: 'business_registration_category', name: 'business_registration_category' },
            { data: 'contact_person', name: 'contact_person' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'gmail_id', name: 'gmail_id' },
            { data: 'business_location', name: 'business_location' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']], // Sort by business_name
        pageLength: 25,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        language: {
            search: "Search vendors:",
            lengthMenu: "Show _MENU_ vendors per page",
            info: "Showing _START_ to _END_ of _TOTAL_ vendors",
            infoEmpty: "No vendors found",
            infoFiltered: "(filtered from _MAX_ total vendors)",
            zeroRecords: "No matching vendors found",
            emptyTable: "No vendors available"
        }
    });

    // Delete vendor functionality
    let vendorIdToDelete = null;

    window.deleteVendor = function(id) {
        vendorIdToDelete = id;
        $('#deleteModal').modal('show');
    };

    $('#confirmDelete').on('click', function() {
        if (vendorIdToDelete) {
            $.ajax({
                url: "{{ route('admin.vendors.index') }}/" + vendorIdToDelete,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        // Show success message
                        showAlert('success', response.message);
                        // Reload DataTable
                        table.ajax.reload();
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr) {
                    $('#deleteModal').modal('hide');
                    let message = 'An error occurred while deleting the vendor.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showAlert('error', message);
                }
            });
        }
        vendorIdToDelete = null;
    });

    // Toggle status functionality
    window.toggleVendorStatus = function(id) {
        $.ajax({
            url: "{{ route('admin.vendors.toggle-status', ':id') }}".replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    table.ajax.reload();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while updating the status.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
            }
        });
    };

    // Alert function
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of the card
        $('.card-body').prepend(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
@endpush