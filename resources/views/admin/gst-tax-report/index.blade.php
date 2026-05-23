@extends('layouts.admin')

@section('title', 'GST Tax Report')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">GST Tax Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Banking & Finance Module</li>
                    <li class="breadcrumb-item active" aria-current="page">GST Tax Report</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.gst-tax-report.export', $exportParams) }}" class="btn btn-outline-success btn-sm">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.gst-tax-report.export.pdf', $exportParams) }}" class="btn btn-outline-danger btn-sm">
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
            <form method="GET" action="{{ route('admin.gst-tax-report.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" value="{{ $filters['search'] ?? '' }}" placeholder="Tax name">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Min GST %</label>
                        <input type="number" step="0.01" min="0" max="100" name="min_rate" class="form-control form-control-sm" value="{{ $filters['min_rate'] ?? '' }}" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Max GST %</label>
                        <input type="number" step="0.01" min="0" max="100" name="max_rate" class="form-control form-control-sm" value="{{ $filters['max_rate'] ?? '' }}" placeholder="28">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="ti ti-filter"></i> Apply
                        </button>
                        <a href="{{ route('admin.gst-tax-report.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
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
                <div class="text-muted small">Active</div>
                <div class="fs-6 fw-semibold">{{ $summary['active'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-body py-2">
                <div class="text-muted small">Inactive</div>
                <div class="fs-6 fw-semibold">{{ $summary['inactive'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Report Data</h5>
            <span class="text-muted">CGST/SGST split for intra-state; IGST for inter-state</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width:80px;">S.No.</th>
                            <th>Tax Name</th>
                            <th class="text-end">GST %</th>
                            <th class="text-end">CGST %</th>
                            <th class="text-end">SGST %</th>
                            <th class="text-end">IGST %</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $row)
                            @php
                                $rate = (float) ($row->rate_percent ?? 0);
                                $cgst = $rate / 2;
                                $sgst = $rate / 2;
                                $igst = $rate;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->tax_name ?? '' }}</td>
                                <td class="text-end">{{ number_format($rate, 2) }}</td>
                                <td class="text-end">{{ number_format($cgst, 2) }}</td>
                                <td class="text-end">{{ number_format($sgst, 2) }}</td>
                                <td class="text-end">{{ number_format($igst, 2) }}</td>
                                <td>{{ ($row->is_active ?? false) ? 'Active' : 'Inactive' }}</td>
                                <td>{{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('Y-m-d H:i:s') : '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

