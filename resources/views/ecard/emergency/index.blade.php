@extends('ecard.ecard')

@section('title', 'Emergency Command Center')

@section('content')
<div class="content-inner">
    <div class="container-fluid py-3">
        <!-- Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>EMERGENCY COMMAND CENTER</h4>
                <p class="mb-0 text-muted">Master Hub / Emergency Desk</p>
            </div>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <button class="btn btn-danger pulsate w-100 w-md-auto"><i class="fas fa-broadcast-tower me-2"></i>LIVE ALERTS</button>
            </div>
        </div>

        <!-- Emergency Stats / Quick Actions -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white h-100">
                    <div class="card-body text-center p-3 p-md-4">
                        <i class="fas fa-ambulance fa-2x fa-md-3x mb-2 mb-md-3"></i>
                        <h5 class="fs-6 fs-md-5">AMBULANCE</h5>
                        <p class="small opacity-75 d-none d-sm-block">Dispatch Immediate Help</p>
                        <button class="btn btn-light btn-sm text-danger fw-bold w-100 mt-2">REQUEST</button>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body text-center p-3 p-md-4">
                        <i class="fas fa-user-shield fa-2x fa-md-3x mb-2 mb-md-3"></i>
                        <h5 class="fs-6 fs-md-5">POLICE</h5>
                        <p class="small opacity-75 d-none d-sm-block">Report Security Incident</p>
                        <button class="btn btn-light btn-sm text-primary fw-bold w-100 mt-2">CONTACT</button>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body text-center p-3 p-md-4">
                        <i class="fas fa-fire-extinguisher fa-2x fa-md-3x mb-2 mb-md-3"></i>
                        <h5 class="fs-6 fs-md-5">FIRE DEPT</h5>
                        <p class="small opacity-75 d-none d-sm-block">Fire & Rescue Services</p>
                        <button class="btn btn-light btn-sm text-warning fw-bold w-100 mt-2">ALERT</button>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white h-100">
                    <div class="card-body text-center p-3 p-md-4">
                        <i class="fas fa-heartbeat fa-2x fa-md-3x mb-2 mb-md-3"></i>
                        <h5 class="fs-6 fs-md-5">BLOOD BANK</h5>
                        <p class="small opacity-75 d-none d-sm-block">Urgent Blood Request</p>
                        <button class="btn btn-light btn-sm text-info fw-bold w-100 mt-2">FIND</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="row g-4">
            <!-- Live Map / Location -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0"><i class="fas fa-map-marked-alt me-2 text-primary"></i>LIVE INCIDENT MAP</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px; border-radius: 0 0 20px 20px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-map-marked-alt fa-4x mb-3 opacity-25"></i>
                                <p>Map Interface Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Alerts List -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-bell me-2 text-warning"></i>RECENT ALERTS</h6>
                        <span class="badge bg-danger">3 New</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-4 py-3 border-light">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold text-danger">SOS Signal Received</h6>
                                    <small class="text-muted">2 mins ago</small>
                                </div>
                                <p class="mb-1 small text-muted">Location: Sector 4, Main Market</p>
                            </div>
                            <div class="list-group-item px-4 py-3 border-light">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">Medical Emergency</h6>
                                    <small class="text-muted">15 mins ago</small>
                                </div>
                                <p class="mb-1 small text-muted">Location: Green Valley Apts</p>
                            </div>
                            <div class="list-group-item px-4 py-3 border-light">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">Fire Alarm</h6>
                                    <small class="text-muted">1 hr ago</small>
                                </div>
                                <p class="mb-1 small text-muted">Location: Industrial Zone B</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 text-center py-3">
                        <a href="#" class="text-decoration-none fw-bold small">VIEW ALL ALERTS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pulsate {
        animation: pulsate 1.5s ease-out infinite;
    }
    @keyframes pulsate {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endsection
