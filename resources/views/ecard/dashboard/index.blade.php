@extends('ecard.ecard')
@section('title', 'Dashboard')

@section('content')
    <div class="welcome d-lg-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center welcome-text">
            <h3 class="d-flex align-items-center"><img src="{{ asset('backend_assets/assets/img/icons/hi.svg') }}" alt="img">&nbsp;Hi {{ $user->first_name ?? 'User' }},</h3>&nbsp;<h6>here's what's happening with your E-Card today.</h6>
        </div>
        <div class="d-flex align-items-center">
            <div class="input-icon-start position-relative me-2">
                <span class="input-icon-addon fs-16 text-gray-9">
                    <i class="ti ti-calendar"></i>
                </span>
                <input type="text" class="form-control date-range bookingrange" placeholder="Select Date Range">
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="ti ti-circle-check me-2"></i>{{ session('success') }}</div>
    @endif

    <!-- Banners -->
    <div class="row g-3 mb-4">
        <!-- Upgrade Banner -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(to right, #eff6ff, #ffffff);">
                <div class="card-body d-flex align-items-center justify-content-between p-3 flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">PREMIUM BENEFITS</h6>
                            <p class="mb-0 text-muted small">Unlock exclusive medical discounts.</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-primary px-3 rounded-pill fw-semibold" style="font-size: 0.8rem;">LEARN MORE</a>
                </div>
            </div>
        </div>

        <!-- Emergency Banner -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: #dc2626;">
                <div class="card-body d-flex align-items-center justify-content-between p-3 flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-white bg-opacity-25 text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="ti ti-alert-triangle fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Emergency Support</h6>
                            <p class="mb-0 opacity-75">Quick access to emergency services.</p>
                        </div>
                    </div>
                    <a href="{{ route('ecard.benefit.ecardseva.request.index') }}" class="btn btn-light text-danger fw-bold px-3 rounded-pill shadow-sm" style="font-size: 0.8rem;">REQUEST HELP</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row sales-cards">
        <div class="col-xl-6 col-sm-12 col-12 d-flex">
            <div class="card d-flex align-items-center justify-content-between flex-fill mb-4">
                <div>
                    <h6>Wallet Balance</h6>
                    <h3>₹<span class="counters" data-count="{{ $user->wallet_balance ?? 0 }}">{{ number_format($user->wallet_balance ?? 0, 2) }}</span></h3>
                    <p class="sales-range">Available Funds</p>
                </div>
                <img src="{{ asset('backend_assets/assets/img/icons/weekly-earning.svg') }}" alt="img">
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card color-info bg-primary flex-fill mb-4">
                <div class="mb-2">
                    <img src="{{ asset('backend_assets/assets/img/icons/total-sales.svg') }}" alt="img">
                </div>
                <h3 class="counters" data-count="0">0</h3>
                <p>Family Members</p>
                <i data-feather="users" class="feather-16" data-bs-toggle="tooltip" data-bs-placement="top" title="View"></i>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card color-info bg-secondary flex-fill mb-4">
                <div class="mb-2">
                    <img src="{{ asset('backend_assets/assets/img/icons/purchased-earnings.svg') }}" alt="img">
                </div>
                <h3 class="counters">{{ ucfirst($user->status ?? 'Pending') }}</h3>
                <p>KYC Status</p>
                <i data-feather="file-text" class="feather-16" data-bs-toggle="tooltip" data-bs-placement="top" title="View"></i>
            </div>
        </div>
    </div>

    <!-- Profile and Charts -->
    <div class="row">
        <!-- Profile Section -->
        <div class="col-sm-12 col-md-12 col-xl-4 d-flex">
            <div class="card flex-fill w-100 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">My Profile</h4>
                    <a href="{{ route('ecard.profile.index') }}" class="btn btn-outline-light btn-sm">View Full</a>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block mb-3">
                            @if($user->profile_image)
                                <img src="{{ asset($user->profile_image) }}" class="rounded-circle object-fit-cover border border-4 border-light shadow-sm" style="width: 100px; height: 100px;" alt="Profile">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold mx-auto" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    {{ substr($user->first_name ?? 'U', 0, 1) }}
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-1 border border-white border-2">
                                <i class="ti ti-circle-check" style="font-size: 0.9rem;"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                        <p class="text-muted small text-uppercase fw-bold">E-Card Holder</p>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <small class="text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">BLOOD GROUP</small>
                                <span class="fw-bold text-danger h5 mb-0">{{ $user->blood_group ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <small class="text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">USER ID</small>
                                <span class="fw-bold text-primary h5 mb-0">{{ $user->user_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ecosystem Chart (Service Usage) -->
        <div class="col-sm-12 col-md-12 col-xl-8 d-flex">
            <div class="card flex-fill w-100 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Service Usage</h4>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="view-all d-flex align-items-center" data-bs-toggle="dropdown">
                            View All<span class="ps-2 d-flex align-items-center"><i class="ti ti-chevron-down fs-12"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Daily</a>
                            <a href="javascript:void(0);" class="dropdown-item">Weekly</a>
                            <a href="javascript:void(0);" class="dropdown-item">Monthly</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="ecosystemChart" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
