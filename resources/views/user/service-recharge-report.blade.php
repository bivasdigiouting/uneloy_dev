@extends('user.layouts.app')

@section('title', $title ?? 'Recharge Report')

@push('styles')
<style>
    .navbar-gradient {
        background: linear-gradient(90deg, #4c5bd4, #8f43dd) !important;
    }
    .gradient-hero {
        background: linear-gradient(90deg, #4c5bd4, #8f43dd);
        color: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 10px 20px rgba(76, 91, 212, 0.2);
    }
    .gradient-hero .breadcrumb .breadcrumb-item a,
    .gradient-hero .breadcrumb .breadcrumb-item.active {
        color: #f0f0f0;
    }
    .info-card {
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(76, 91, 212, 0.08);
        border: 1px solid var(--border-color);
        background-color: var(--card-bg);
        color: var(--text-dark);
    }
    .table thead th {
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color) !important;
        color: var(--text-dark);
    }
    .table td {
        color: var(--text-dark);
        border-color: var(--border-color);
    }
    .card-title {
        font-weight: 600;
        color: var(--text-dark);
    }
</style>
@endpush

@section('content')
    <div class="container-fluid py-3">
        <div class="gradient-hero mb-3">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-bolt me-2"></i>
                <h4 class="mb-0">{{ $title ?? 'Recharge Report' }}</h4>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.service.recharge.utility.link') }}">Recharge</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Recharge Report</li>
                </ol>
            </nav>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="card info-card">
                    <div class="card-body">
                        <h6 class="card-title">Filters</h6>
                        <div class="mb-2">
                            <label class="form-label">Service</label>
                            <select class="form-select">
                                <option value="">All</option>
                                <option>Mobile</option>
                                <option>DTH</option>
                                <option>Electricity</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Number / ID</label>
                            <input type="text" class="form-control" placeholder="Enter number or ID">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="">Any</option>
                                <option>Success</option>
                                <option>Pending</option>
                                <option>Failed</option>
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">From</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">To</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card info-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">Recharge Details</h6>
                            <input type="text" class="form-control" style="max-width: 240px;" placeholder="Search...">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Number / ID</th>
                                        <th>Date</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($recharges ?? []) as $row)
                                        <tr>
                                            <td>{{ $row['service'] }}</td>
                                            <td>{{ $row['number'] }}</td>
                                            <td>{{ $row['date'] }}</td>
                                            <td class="text-end">₹ {{ number_format($row['amount'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection