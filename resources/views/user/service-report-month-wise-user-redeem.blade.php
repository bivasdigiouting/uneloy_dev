@extends('user.layouts.app')

@section('title', 'M. Wise User Redeem Report')

@push('styles')
<style>
    /* Match dashboard navbar gradient */
    /* .navbar.bg-dark {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */
    .gradient-hero {
        background: var(--primary-gradient);
        color: #fff;
        padding: 24px 0;
    }
    .gradient-hero .page-icon {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(255,255,255,0.15);
        margin-right: 16px;
        font-size: 20px;
    }
    .info-card {
        border: none;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        border-radius: 12px;
        background-color: var(--card-bg);
        color: var(--text-dark);
    }
    .table thead th {
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
        font-weight: 600;
        color: var(--text-dark);
    }
    .table td {
        color: var(--text-dark);
        border-color: var(--border-color);
    }
    .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.65);
    }
    .breadcrumb .breadcrumb-item, .breadcrumb .breadcrumb-item a {
        color: rgba(255,255,255,0.9);
    }
    .form-control, .form-select { background-color: var(--card-bg); color: var(--text-dark); border-color: var(--muted-text); }
    .form-control:focus, .form-select:focus { background-color: var(--card-bg); color: var(--text-dark); border-color: var(--primary-color); }
    .form-label { color: var(--muted-text); }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Gradient Header / Hero -->
    <div class="gradient-hero">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="page-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <h3 class="mb-1">Month Wise User Redeem</h3>
                    <p class="mb-0">View total redeems grouped by month.</p>
                </div>
                <div class="ms-auto d-none d-md-block">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a class="text-white" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-white">Reports</li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Month Wise Redeem</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mt-4">
        <!-- Filters Card -->
        <div class="card info-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Month</label>
                        <select class="form-select">
                            <option selected>All Months</option>
                            <option>Jan</option>
                            <option>Feb</option>
                            <option>Mar</option>
                            <option>Apr</option>
                            <option>May</option>
                            <option>Jun</option>
                            <option>Jul</option>
                            <option>Aug</option>
                            <option>Sep</option>
                            <option>Oct</option>
                            <option>Nov</option>
                            <option>Dec</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-auto">
                        <button class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card info-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Redeem Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($redeems as $row)
                                <tr>
                                    <td>{{ $row['month'] }}</td>
                                    <td class="text-end">{{ number_format($row['count']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Content -->
 </div>
@endsection