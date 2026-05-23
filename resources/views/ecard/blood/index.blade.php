@extends('ecard.ecard')

@section('title', 'Blood Support')

@section('content')
<div class="content-inner">
    <div class="container-fluid py-3">
        
        <!-- Top Admin Notice (Optional based on image, but good for UI) -->
        <div class="alert alert-light border-0 shadow-sm rounded-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">UPGRADE TO SEVA GOLD TIER</h6>
                    <small class="text-muted d-block">Get 5% extra cash-back on all medical transactions and priority blood donor matching.</small>
                </div>
            </div>
            <button class="btn btn-primary btn-sm rounded-pill px-3 ms-auto ms-md-0">LEARN MORE <i class="fas fa-external-link-alt ms-1"></i></button>
        </div>

        <!-- Main Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1">Blood & Emergency Support</h4>
                <p class="mb-0 text-muted">Connecting donors and seekers in real-time.</p>
            </div>
            <button class="btn btn-outline-danger fw-bold w-100 w-md-auto"><i class="fas fa-exclamation-triangle me-2"></i>Trigger Emergency</button>
        </div>

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- Find Donors Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4"><i class="fas fa-search me-2 text-primary"></i>Find Donors</h5>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase">Blood Group</label>
                            <div class="row g-2">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                <div class="col-3">
                                    <input type="radio" class="btn-check" name="blood_group" id="bg_{{ $bg }}" autocomplete="off" {{ $bg == 'O+' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary w-100 btn-sm fw-bold" for="bg_{{ $bg }}">{{ $bg }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase">Location / Pincode</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" placeholder="E.g. Mumbai 400076">
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 fw-bold py-2">Search Database</button>
                    </div>
                </div>

                <!-- Seva Points Card -->
                <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-tint me-2 opacity-75"></i>
                            <h6 class="fw-bold mb-0">Seva Points</h6>
                        </div>
                        <p class="small opacity-75 mb-4">Earn points for every donation and redeem them at our ecosystem vendors.</p>
                        
                        <h2 class="fw-bold mb-1">12,450</h2>
                        <small class="opacity-75">Total lives saved this year</small>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Matching Donors Header -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-user-friends me-2 text-dark"></i>Matching Donors (2)</h6>
                    <small class="text-muted fst-italic">Showing nearby matches first</small>
                </div>

                <!-- Donor Cards -->
                <div class="row g-3 mb-4">
                    <!-- Donor 1 -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded p-2 text-center" style="width: 50px;">
                                            <span class="d-block fw-bold small">O+</span>
                                            <span style="font-size: 0.6rem;">DONOR</span>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">John Doe</h6>
                                            <p class="small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i>Powai, Mumbai</p>
                                            <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.7rem;">AVAILABLE</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-light btn-sm rounded-circle text-primary" style="width: 40px; height: 40px;"><i class="fas fa-phone-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Donor 2 -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded p-2 text-center" style="width: 50px;">
                                            <span class="d-block fw-bold small">AB+</span>
                                            <span style="font-size: 0.6rem;">DONOR</span>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Sara Khan</h6>
                                            <p class="small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i>Andheri, Mumbai</p>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.7rem;">AWAY</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-light btn-sm rounded-circle text-primary" style="width: 40px; height: 40px;"><i class="fas fa-phone-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Map -->
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0 position-relative" style="height: 300px; background: #e5e7eb;">
                        <!-- Placeholder Background Image mimicking map -->
                        <div style="position: absolute; inset: 0; background-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg'); background-size: cover; opacity: 0.3;"></div>
                        
                        <div class="position-absolute top-50 start-50 translate-middle text-center bg-white p-4 rounded-3 shadow-sm" style="min-width: 250px;">
                            <i class="fas fa-map-marked-alt fa-2x text-primary mb-2"></i>
                            <h6 class="fw-bold mb-1">Interactive Map View</h6>
                            <p class="small text-muted mb-0">Visualizing 24 donors in your area</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-check:checked + .btn-outline-secondary {
        background-color: #2563eb;
        color: white;
        border-color: #2563eb;
    }
</style>
@endsection
