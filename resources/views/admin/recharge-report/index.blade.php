@extends('layouts.admin')

@section('title', 'Recharge Report')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Recharge Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Recharge Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Recharge Report</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.recharge-report.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                    <i class="ti ti-refresh me-1"></i>Reset Filters
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" action="{{ route('admin.recharge-report.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" name="from_date" id="from_date" value="{{ $filters['from_date'] }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" name="to_date" id="to_date" value="{{ $filters['to_date'] }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="service_id" class="form-label">Select Service</label>
                    <select name="service_id" id="service_id" class="form-select">
                        <option value="">-- All Services --</option>
                        @foreach($services as $svc)
                            <option value="{{ $svc->id }}" {{ (string)$svc->id === (string)$filters['service_id'] ? 'selected' : '' }}>
                                {{ $svc->service_name ?? $svc->name ?? ('Service #' . $svc->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="operator_id" class="form-label">Select Operator</label>
                    <select name="operator_id" id="operator_id" class="form-select" {{ empty($filters['service_id']) ? 'disabled' : '' }}>
                        <option value="">-- All Operators --</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id }}" {{ (string)$op->id === (string)$filters['operator_id'] ? 'selected' : '' }}>
                                {{ $op->operator_name ?? $op->name ?? ('Operator #' . $op->id) }}
                            </option>
                        @endforeach
                    </select>
                    @if(empty($filters['service_id']))
                        <small class="text-muted">Select a service to enable operators.</small>
                    @endif
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        @php($statusVal = strtolower($filters['status'] ?? 'all'))
                        <option value="all" {{ $statusVal === 'all' ? 'selected' : '' }}>All</option>
                        <option value="pending" {{ $statusVal === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ $statusVal === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ $statusVal === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label for="search" class="form-label">Search (ID/Name/Email/Mobile)</label>
                    <input type="text" name="search" id="search" value="{{ $filters['search'] }}" class="form-control" placeholder="e.g. 42 or alice@example.com">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit"><i class="ti ti-filter me-1"></i>Apply Filters</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.recharge-report.index') }}">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Transactions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Member Name</th>
                            <th>Operator Name</th>
                            <th>Recharge No</th>
                            <th class="text-end">Amount</th>
                            <th>Recharge Status</th>
                            <th>Recharge Date</th>
                            <th>Transaction Id</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $row)
                            <tr>
                                <td>{{ $results->firstItem() + $loop->index }}</td>
                                <td>{{ $row->customer_name ?? '-' }}</td>
                                <td>{{ $row->operator_name ?? '-' }}</td>
                                <td>{{ $row->recharge_no ?? '-' }}</td>
                                <td class="text-end">{{ isset($row->amount) ? number_format($row->amount, 2) : '0.00' }}</td>
                                <td class="text-capitalize">{{ $row->recharge_status ?? $row->status ?? '-' }}</td>
                                <td>{{ $row->recharge_date ? \Carbon\Carbon::parse($row->recharge_date)->format('d M Y, h:i A') : '-' }}</td>
                                <td>{{ $row->transaction_id ?? $row->txnid ?? $row->reference_no ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No transactions found for selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} entries
                </div>
                <div>
                    {{ $results->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        const form = document.getElementById('filterForm');
        if (serviceSelect && form) {
            serviceSelect.addEventListener('change', function() {
                const operatorSelect = document.getElementById('operator_id');
                if (operatorSelect) {
                    operatorSelect.value = '';
                }
                form.submit();
            });
        }
    });
</script>
@endpush
@endsection
