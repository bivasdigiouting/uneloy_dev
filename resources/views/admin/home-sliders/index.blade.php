@extends('layouts.admin')

@section('title', 'Home Slider Management')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Home Slider Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Website Modules
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Home Slider</li>
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
                            <h4 class="card-title mb-0">Home Slider Images</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.home-sliders.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add New Slider
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="homeSlidersTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Image</th>
                                    <th>Header</th>
                                    <th>Description</th>
                                    <th>Show on Portal</th>
                                    <th>Sequence</th>
                                    <th>Status</th>
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
                Are you sure you want to delete this home slider? This action cannot be undone.
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
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.responsive.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.bootstrap5.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#homeSlidersTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.home-sliders.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image_preview', name: 'image_preview', orderable: false, searchable: false },
            { data: 'text_header_display', name: 'text_header' },
            { data: 'text_description_display', name: 'text_description' },
            { data: 'show_on_portal_badge', name: 'show_on_portal' },
            { data: 'sequence_no', name: 'sequence_no' },
            { data: 'is_active_badge', name: 'is_active' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[5, 'asc']], // Order by sequence number
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: "No home sliders available",
            zeroRecords: "No matching home sliders found"
        },
        drawCallback: function(settings) {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
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
                    toastr.success(response.success);
                    table.ajax.reload();
                } else {
                    toastr.error('Something went wrong!');
                }
            },
            error: function(xhr) {
                $('#deleteModal').modal('hide');
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
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