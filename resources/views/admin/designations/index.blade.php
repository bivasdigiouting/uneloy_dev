@extends('layouts.admin')

@section('title', 'Designation Management')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Designation Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Office Management
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Designations</li>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Designation List</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.designations.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Designation
                        </a>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.designations.export') }}"><i class="fas fa-file-excel"></i> Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.designations.export.pdf') }}"><i class="fas fa-file-pdf"></i> PDF</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-upload"></i> Import
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="designationsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Designation Name</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.designations.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Designations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Supported formats: Excel (.xlsx, .xls) and CSV (.csv)</div>
                    </div>
                    <div class="alert alert-info">
                        <strong>Note:</strong> The Excel file should have columns: Designation Name, Status
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#designationsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.designations.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'designation_name', name: 'designation_name' },
            { data: 'remarks', name: 'remarks', orderable: false },
            { data: 'status', name: 'is_active', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']], // Sort by designation_name
        pageLength: 25,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        language: {
            search: "Search designations:",
            lengthMenu: "Show _MENU_ designations per page",
            info: "Showing _START_ to _END_ of _TOTAL_ designations",
            infoEmpty: "No designations found",
            infoFiltered: "(filtered from _MAX_ total designations)",
            zeroRecords: "No matching designations found",
            emptyTable: "No designations available"
        }
    });
    
    // Handle status toggle
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var button = $(this);
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to change the status of this designation?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            table.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong!');
                    }
                });
            }
        });
    });
    
    // Handle delete
    $(document).on('click', '.delete-designation', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            table.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong!');
                    }
                });
            }
        });
    });
});
</script>
@endpush