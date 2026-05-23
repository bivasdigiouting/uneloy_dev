@extends('layouts.admin')

@section('title', 'Special Features Management')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Special Features Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Website Modules
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Special Features</li>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Special Features</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.special-features.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add New Feature
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="specialFeaturesTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Icon</th>
                                    <th>Feature Name</th>
                                    <th>Sequence</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Toggle</th>
                                    <th>Created</th>
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
                Are you sure you want to delete this special feature? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#specialFeaturesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.special-features.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'icon_preview', name: 'icon_preview', orderable: false, searchable: false },
            { data: 'features_name', name: 'features_name' },
            { data: 'sequence', name: 'sequence' },
            { data: 'description', name: 'description' },
            { data: 'status_badge', name: 'status' },
            { data: 'status_toggle', name: 'status_toggle', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[3, 'asc']], // Order by sequence number
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: "No special features available",
            zeroRecords: "No matching special features found"
        },
        drawCallback: function(settings) {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Status toggle functionality
     $(document).on('change', '.status-toggle', function() {
         var featureId = $(this).data('id');
         var isActive = $(this).is(':checked') ? 1 : 0;
         
         $.ajax({
             url: "{{ route('admin.special-features.toggle-status', ':id') }}".replace(':id', featureId),
             type: 'POST',
             data: {
                 _token: '{{ csrf_token() }}'
             },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    table.ajax.reload(null, false);
                } else {
                    toastr.error('Something went wrong!');
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('Something went wrong!');
                }
                // Revert the toggle
                $(this).prop('checked', !isActive);
            }
        });
    });

    // Delete functionality
    var deleteUrl = '';
    var deleteId = '';

    $(document).on('click', '.delete-btn', function() {
        deleteUrl = $(this).data('url');
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    table.ajax.reload();
                } else {
                    toastr.error('Something went wrong!');
                }
            },
            error: function(xhr) {
                $('#deleteModal').modal('hide');
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });

    // Refresh table on window focus
    $(window).focus(function() {
        table.ajax.reload(null, false);
    });
});
</script>
@endpush