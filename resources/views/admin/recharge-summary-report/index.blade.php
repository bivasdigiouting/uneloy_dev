@extends('layouts.admin')

@section('title', 'Recharge Summary Report')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Recharge Summary Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Recharge Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Recharge Summary Report</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.recharge-summary-report.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
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
            <form action="{{ route('admin.recharge-summary-report.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <label for="search" class="form-label">Search (ID/Name/Email/Mobile)</label>
                    <input type="text" name="search" id="search" value="{{ $filters['search'] }}" class="form-control" placeholder="e.g. 42 or alice@example.com">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit"><i class="ti ti-filter me-1"></i>Apply Filters</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.recharge-summary-report.index') }}">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Summary</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>User Name</th>
                            <th>Operator Name</th>
                            <th class="text-end">Total Recharge Amt.</th>
                            <th class="text-end">Total Commission Amt.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $row)
                            <tr>
                                <td>{{ $results->firstItem() + $loop->index }}</td>
                                <td>{{ $row->user_name ?? '-' }}</td>
                                <td>{{ $row->operator_name ?? '-' }}</td>
                                <td class="text-end">{{ isset($row->total_recharge_amt) ? number_format($row->total_recharge_amt, 2) : '0.00' }}</td>
                                <td class="text-end">{{ isset($row->total_commission_amt) ? number_format($row->total_commission_amt, 2) : '0.00' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No data found for selected filters.</td>
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

{{-- Submit form when service changes to load related operators --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        if (serviceSelect) {
            serviceSelect.addEventListener('change', function() {
                // Clear operator selection when service changes and submit form
                const operatorSelect = document.getElementById('operator_id');
                if (operatorSelect) {
                    operatorSelect.value = '';
                }
                this.form && this.form.submit();
            });
        }
    });
</script>
@endpush
@endsection