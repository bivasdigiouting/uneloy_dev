@extends('layouts.admin')

@section('title', 'Commission TDS Charge Report')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Commission TDS Charge Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">System Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Commission TDS Charge Report</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.commission-tds-charge-report.export', $exportParams) }}" class="btn btn-outline-success btn-sm">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.commission-tds-charge-report.export.pdf', $exportParams) }}" class="btn btn-outline-danger btn-sm">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.commission-tds-charge-report.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Level</label>
                        <select name="level" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach ($levelDepartmentNames as $name)
                                <option value="{{ $name }}" @selected(($filters['level'] ?? '') === $name)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="ti ti-filter"></i> Apply
                        </button>
                        <a href="{{ route('admin.commission-tds-charge-report.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="ti ti-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card card-body py-2">
                <div class="text-muted small">Rows</div>
                <div class="fs-6 fw-semibold">{{ $summary['count'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-body py-2">
                <div class="text-muted small">Total TDS Charge</div>
                <div class="fs-6 fw-semibold">{{ number_format((float) ($summary['total_tds_charge'] ?? 0), 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Report Data</h5>
            <span class="text-muted">Based on Department Module Commission master values</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width:80px;">S.No.</th>
                            <th>Department Level</th>
                            <th class="text-end">TDS Charge</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->department_name ?? '' }}</td>
                                <td class="text-end">{{ number_format((float) ($row->tds_charge ?? 0), 2) }}</td>
                                <td>{{ $row->commission_updated_at ? \Carbon\Carbon::parse($row->commission_updated_at)->format('Y-m-d H:i:s') : '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

