@extends('layouts.admin')

@section('title', 'Expense Management')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Expense Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Master Module
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Expenses</li>
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
                    <h4 class="card-title">Expense List</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                            <i class="ti ti-plus me-1"></i>Add Expense
                        </a>
                        <button type="button" class="btn btn-success d-inline-flex align-items-center" onclick="exportData('excel')">
                            <i class="ti ti-file-spreadsheet me-1"></i>Export Excel
                        </button>
                        <button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="exportData('pdf')">
                            <i class="ti ti-file-type-pdf me-1"></i>Export PDF
                        </button>
                        <button type="button" class="btn btn-info d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="ti ti-upload me-1"></i>Import
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="expenseTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Expense Name</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
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
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Expenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.expenses.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Select Excel File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">Supported formats: .xlsx, .xls, .csv (Max: 2MB)</small>
                    </div>
                    <div class="alert alert-info">
                        <strong>Note:</strong> The Excel file should contain columns: expense_name, description, amount, is_active
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
    $('#expenseTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.expenses.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'expense_name', name: 'expense_name' },
            { data: 'description', name: 'description' },
            { data: 'amount_formatted', name: 'amount' },
            { data: 'status', name: 'is_active' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        responsive: true,
        autoWidth: false
    });

    // Toggle Status
    $(document).on('click', '.toggle-status', function() {
        var url = $(this).data('url');
        var button = $(this);
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to change the status of this expense?',
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
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            $('#expenseTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });

    // Delete Expense
    $(document).on('click', '.delete-expense', function() {
        var url = $(this).data('url');
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
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
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('#expenseTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
});

// Export Functions
function exportData(type) {
    var url = type === 'excel' ? '{{ route("admin.expenses.export") }}' : '{{ route("admin.expenses.export-pdf") }}';
    window.location.href = url;
}
</script>
@endpush

@push('styles')
<style>
.btn-group .btn {
    margin-left: 2px;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.875em;
}

.btn-group[role="group"] .btn {
    margin-left: 1px;
    margin-right: 1px;
}
</style>
@endpush