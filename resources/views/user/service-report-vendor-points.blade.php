@extends('user.layouts.app')

@section('title', 'Vendor by Points Report - UOnly')

@push('styles')
<style>
    /* Match dashboard look and feel */
    .welcome-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 15px;
        margin-bottom: 2rem;
        border: none;
    }
    .info-card {
        background: var(--card-bg);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        transition: transform .3s ease;
        color: var(--text-dark);
    }
    .info-card:hover { transform: translateY(-3px); }
    .btn-primary-custom {
        background: var(--primary-gradient);
        border: none;
        color: #fff;
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 600;
    }
    .page-icon {
        width: 60px; height: 60px; border-radius: 50%;
        display:flex; align-items:center; justify-content:center;
        background: rgba(255,255,255,.15);
        font-size: 26px; color: #fff;
    }
    .breadcrumb .breadcrumb-item a { text-decoration: none; }

    /* Top navbar: match dashboard gradient */
    /* .navbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */
    .table { color: var(--text-dark); }
    .form-control, .form-select { background-color: var(--card-bg); color: var(--text-dark); border-color: var(--muted-text); }
    .form-control:focus, .form-select:focus { background-color: var(--card-bg); color: var(--text-dark); border-color: var(--primary-color); }
    .form-label { color: var(--muted-text); }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="card welcome-card">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="page-icon"><i class="fas fa-store"></i></div>
                <div>
                    <h3 class="mb-1">Vendor by Points Report</h3>
                    <p class="mb-0">View points accumulated by vendors in the selected period.</p>
                </div>
                <div class="ms-auto d-none d-md-block">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a class="text-white" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-white">Reports</li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Vendor by Points</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card info-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label">Search Vendor</label>
                    <input type="text" class="form-control" placeholder="Name or ID">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label d-block">&nbsp;</label>
                    <button class="btn btn-primary-custom w-100"><i class="fas fa-filter me-1"></i>Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Table Card -->
    <div class="card info-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th class="text-end">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row['vendor'] }}</td>
                                <td class="text-end">{{ number_format($row['points']) }}</td>
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
@endsection