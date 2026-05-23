@extends('user.layouts.app')

@section('title', 'Advertisement Report - UOnly')

@push('styles')
<style>

/* Match dashboard background */
    body { background-color: var(--bg-light); color: var(--text-dark); }

    /* Match dashboard navbar gradient - handled by global theme styles */
    /* .navbar.bg-dark {
        background: var(--primary-gradient) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */

    .card { border: none; border-radius: 14px; box-shadow: 0 6px 20px rgba(13,110,253,.12); background-color: var(--card-bg); color: var(--text-dark); }
    .table { background: var(--card-bg); color: var(--text-dark); }
    .stat-pill { background: var(--card-bg); border-radius:12px; box-shadow:0 4px 14px rgba(0,0,0,.06); color: var(--text-dark); }
    .page-header { margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-chart-line me-2"></i>Advertisement Report</h4>
            <p class="text-muted mb-0">Performance metrics for your campaigns.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="p-3 stat-pill">
                <div class="text-muted small">Impressions</div>
                <div class="h5 mb-0">{{ number_format($report['impressions'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 stat-pill">
                <div class="text-muted small">Clicks</div>
                <div class="h5 mb-0">{{ number_format($report['clicks'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 stat-pill">
                <div class="text-muted small">CTR (%)</div>
                <div class="h5 mb-0">{{ number_format($report['ctr'] ?? 0, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 stat-pill">
                <div class="text-muted small">Spend (₹)</div>
                <div class="h5 mb-0">{{ number_format($report['spend'] ?? 0, 0) }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="card-title mb-0"><i class="fa-solid fa-chart-column text-primary"></i> Performance by Campaign</h5>
                <div>
                    <input type="text" class="form-control" placeholder="Search campaign">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Impressions</th>
                            <th>Clicks</th>
                            <th>CTR (%)</th>
                            <th>Spend (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Diwali Offer</td>
                            <td>8,000</td>
                            <td>640</td>
                            <td>8.0</td>
                            <td>₹ 2,800</td>
                        </tr>
                        <tr>
                            <td>Winter Sale</td>
                            <td>4,000</td>
                            <td>310</td>
                            <td>7.75</td>
                            <td>₹ 1,400</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection