@extends('layouts.admin')

@section('title', 'Expense Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Expense Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.expenses.index') }}">Expenses</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $expense->expense_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Expenses
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit Expense
                </a>
            </div>
            <div class="me-2 mb-2">
                <button type="button" class="btn btn-warning d-inline-flex align-items-center toggle-status" data-url="{{ route('admin.expenses.toggle-status', $expense->id) }}">
                    <i class="ti ti-toggle-left me-1"></i>Toggle Status
                </button>
            </div>
            <div class="me-2 mb-2">
                <button type="button" class="btn btn-danger d-inline-flex align-items-center delete-expense" data-url="{{ route('admin.expenses.destroy', $expense->id) }}">
                    <i class="ti ti-trash me-1"></i>Delete
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0 d-flex align-items-center">
                                {{ $expense->expense_name }}
                                @if($expense->is_active)
                                    <span class="badge bg-success ms-2">
                                        <i class="ti ti-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-danger ms-2">
                                        <i class="ti ti-x-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Expense ID:</th>
                                    <td><span class="badge badge-secondary">#{{ $expense->id }}</span></td>
                                </tr>
                                <tr>
                                    <th>Expense Name:</th>
                                    <td><strong>{{ $expense->expense_name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>
                                        <span class="h5 text-success">
                                            <i class="fas fa-rupee-sign"></i> {{ number_format($expense->amount, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($expense->is_active)
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fas fa-times-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Created Date:</th>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                        {{ $expense->created_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $expense->created_at->format('h:i A') }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>
                                        <i class="fas fa-clock text-muted"></i>
                                        {{ $expense->updated_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $expense->updated_at->format('h:i A') }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Time Ago:</th>
                                    <td>
                                        <i class="fas fa-history text-muted"></i>
                                        {{ $expense->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($expense->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-file-alt"></i> Description
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $expense->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Activity Timeline -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-history"></i> Activity Timeline
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="time-label">
                                            <span class="bg-green">{{ $expense->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-plus bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time">
                                                    <i class="fas fa-clock"></i> {{ $expense->created_at->format('h:i A') }}
                                                </span>
                                                <h3 class="timeline-header">Expense Created</h3>
                                                <div class="timeline-body">
                                                    Expense "{{ $expense->expense_name }}" was created with amount ₹{{ number_format($expense->amount, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($expense->created_at != $expense->updated_at)
                                        <div class="time-label">
                                            <span class="bg-yellow">{{ $expense->updated_at->format('d M Y') }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-edit bg-yellow"></i>
                                            <div class="timeline-item">
                                                <span class="time">
                                                    <i class="fas fa-clock"></i> {{ $expense->updated_at->format('h:i A') }}
                                                </span>
                                                <h3 class="timeline-header">Expense Updated</h3>
                                                <div class="timeline-body">
                                                    Expense information was last modified
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').on('click', function() {
        var url = $(this).data('url');
        
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
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                location.reload();
                            });
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
    $('.delete-expense').on('click', function() {
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
                            Swal.fire('Deleted!', response.message, 'success').then(() => {
                                window.location.href = '{{ route("admin.expenses.index") }}';
                            });
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
</script>
@endpush

@push('styles')
<style>
.table th {
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 0.5rem 0;
}

.table td {
    border: none;
    padding: 0.5rem 0;
}

.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}

.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ddd;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}

.timeline > div {
    margin-bottom: 15px;
    position: relative;
}

.timeline > div > .timeline-item {
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    margin-top: 0;
    background: #fff;
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    padding: 0;
    position: relative;
}

.timeline > div > .fas {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #666;
    background: #d2d6de;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}

.timeline > .time-label > span {
    font-weight: 600;
    color: #fff;
    border-radius: 4px;
    display: inline-block;
    padding: 5px 10px;
}

.timeline-header {
    margin: 0;
    color: #555;
    border-bottom: 1px solid #f4f4f4;
    padding: 10px;
    font-size: 16px;
    line-height: 1.1;
}

.timeline-body, .timeline-footer {
    padding: 10px;
}

.bg-blue { background-color: #007bff !important; }
.bg-green { background-color: #28a745 !important; }
.bg-yellow { background-color: #ffc107 !important; }
.bg-gray { background-color: #6c757d !important; }

.btn-group .btn {
    margin-left: 2px;
}
</style>
@endpush