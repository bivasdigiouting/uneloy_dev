@extends('ecard.ecard')

@section('title', 'Digital Wallet')

@section('content')
<div class="content-inner">
    <div class="container-fluid py-3">
        
        <!-- Admin Notice -->
        <div class="alert alert-light border-0 shadow-sm rounded-3 mb-4 d-flex flex-wrap align-items-center justify-content-between position-relative overflow-hidden gap-3">
            <div class="d-flex align-items-center position-relative z-1">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                    <i class="ti ti-shield-check fs-4"></i>
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center mb-1 gap-2">
                        <span class="badge bg-light text-primary border border-primary" style="font-size: 0.65rem;">ADMIN NOTICE</span>
                        <h6 class="fw-bold mb-0 text-dark">UPGRADE TO SEVA GOLD TIER</h6>
                    </div>
                    <small class="text-muted d-block">Get 5% extra cash-back on all medical transactions and priority blood donor matching.</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 position-relative z-1 ms-auto ms-lg-0 w-100 w-lg-auto justify-content-between justify-content-lg-start mt-2 mt-lg-0">
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="font-size: 0.85rem;">LEARN MORE <i class="ti ti-external-link ms-1"></i></button>
                <!-- <button type="button" class="btn-close" aria-label="Close"></button> -->
            </div>
            <!-- Decorative background element -->
            <div class="position-absolute top-0 end-0 h-100 w-25 bg-primary opacity-10" style="transform: skewX(-20deg); right: -10% !important;"></div>
        </div>

        <!-- Wallet Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
            <h4 class="fw-bold mb-0 text-dark">My Digital Wallet</h4>
            <div class="d-flex align-items-center gap-3">
                <h5 class="fw-bold text-success mb-0 d-none d-md-block">₹ {{ number_format($user->wallet_balance ?? 0, 2) }}</h5>
                <button class="btn btn-link text-primary text-decoration-none fw-bold p-0" onclick="window.location.reload()"><i class="ti ti-refresh me-1"></i> Sync Balance</button>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Digital E-Card -->
            <div class="col-lg-6 col-xl-5">
                <!-- Professional E-Card Design -->
                <div class="ecard-professional position-relative shadow-lg overflow-hidden" 
                     style="background: linear-gradient(110deg, #121c4b 0%, #15276e 35%, #0e729a 80%, #35afc5 100%); border-radius: 12px; color: #fff; aspect-ratio: 1.586/1; width: 100%; max-width: 480px; margin: 0 auto; padding: 6%;">
                    
                    <!-- Decorative Background Layer (World Map / Circles Simulation) -->
                    <div class="position-absolute w-100 h-100 top-0 start-0 pointer-events-none" style="opacity: 0.15; z-index: 1; pointer-events: none;">
                        <!-- Concentric circles on the left simulating the map backdrop abstract logic -->
                        <svg class="position-absolute top-50 translate-middle-y" style="left: -10%; height: 140%;" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid slice"><circle cx="20" cy="50" r="40" fill="transparent" stroke="#fff" stroke-width="2"/><circle cx="20" cy="50" r="60" fill="transparent" stroke="#fff" stroke-width="2"/><circle cx="20" cy="50" r="80" fill="transparent" stroke="#fff" stroke-width="2"/></svg>
                        <!-- Geometric matrix dots -->
                        <svg class="position-absolute top-0 start-50" style="width: 50px; height: 50px; transform: translateX(-50%); margin-top: 5%;" viewBox="0 0 40 40"><circle cx="10" cy="10" r="2" fill="#fff"/><circle cx="20" cy="10" r="2" fill="#fff"/><circle cx="30" cy="10" r="2" fill="#fff"/><circle cx="10" cy="20" r="2" fill="#fff"/><circle cx="20" cy="20" r="2" fill="#fff"/><circle cx="30" cy="20" r="2" fill="#fff"/><circle cx="10" cy="30" r="2" fill="#fff"/><circle cx="20" cy="30" r="2" fill="#fff"/><circle cx="30" cy="30" r="2" fill="#fff"/></svg>
                        <!-- World Map overlay logic -> replaced with subtle abstract geometry for cross browser perfect fidelity if map missing -->
                        <div class="map-bg"></div>
                    </div>

                    <!-- Content Layer -->
                    <div class="position-relative w-100 h-100 d-flex flex-column justify-content-between" style="z-index: 2;">
                        <!-- Top Header Area -->
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-wifi fs-4" style="transform: rotate(90deg) scaleX(-1);"></i>
                                <div class="text-end lh-1">
                                    <h5 class="mb-0 fw-bold fst-italic position-relative d-inline-block" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">e<span class="text-warning">►</span>card</h5>
                                    <div class="small fw-normal text-white-50" style="font-size: 0.6rem;">International Card</div>
                                </div>
                            </div>
                        </div>

                        <!-- User Info Area -->
                        <div class="d-flex align-items-end mt-4">
                            <!-- User Image -->
                            <div class="flex-shrink-0 bg-white p-1 shadow-sm me-3" style="width: 25%; aspect-ratio: 0.8/1;">
                                <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/img/default-avatar.png') }}" class="w-100 h-100 object-fit-cover" alt="User Image">
                            </div>

                            <div class="flex-grow-1 pb-1">
                                <h6 class="mb-1 text-uppercase fw-bold text-white" style="letter-spacing: 1px; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">{{ $user->full_name }}</h6>
                                <p class="mb-0 text-white-50 fs-xs" style="font-size: 0.8rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">D.O.B. {{ $user->date_of_birth ? $user->date_of_birth->format('d.m.Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Card Number Area -->
                        <div class="mt-4">
                            @php
                                // Format user_id or unique id to 16 digits
                                $num = preg_replace('/[^0-9]/', '', $user->user_id);
                                if(empty($num)) $num = str_pad($user->id, 16, substr(crc32($user->email ?? $user->mobile_no), 0, 8), STR_PAD_RIGHT);
                                $paddedNum = str_pad($num, 16, '0', STR_PAD_RIGHT);
                                $formattedNum = trim(chunk_split(substr($paddedNum, 0, 16), 4, ' '));
                            @endphp
                            <h4 class="mb-2 fw-semibold text-white" style="letter-spacing: 3px; font-family: monospace; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); font-size: 1.4rem;">{{ $formattedNum }}</h4>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="d-flex align-items-center line-height-1">
                                    <div class="small text-uppercase me-2 lh-1" style="font-size: 0.6rem; transform: scale(0.9);">
                                        <div>Valid</div>
                                        <div>Thru</div>
                                    </div>
                                    <div class="fw-bold text-white" style="font-family: monospace; font-size: 0.9rem;">
                                        {{ $user->created_at ? $user->created_at->addYears(10)->format('m/y') : '12/35' }}
                                    </div>
                                </div>
                                <div class="text-uppercase fw-bold text-white text-end lh-1" style="font-size: 0.75rem;">
                                    LIFE TIME <br> ACCESS <span class="text-warning">►</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="col-lg-6 col-xl-7 mt-4 mt-lg-0">
                <div class="row g-3 h-100 align-content-start">
                    <!-- Scan to Pay -->
                    <div class="col-6 col-sm-6 col-md-3 col-lg-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10 transition-hover btn-action cursor-pointer" onclick="showScanToPay()">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center p-3 text-center">
                                <div class="icon-circle bg-white text-primary shadow-sm mb-3" style="width: 50px; height: 50px;">
                                    <i class="ti ti-qrcode fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 fs-sm" style="font-size: 0.85rem;">Scan to Pay</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Transfer -->
                    <div class="col-6 col-sm-6 col-md-3 col-lg-6 col-xl-3">
                        <a href="{{ route('ecard.wallet.settlement.index') }}" class="text-decoration-none h-100 d-block">
                            <div class="card border-0 shadow-sm h-100 bg-purple bg-opacity-10 transition-hover" style="background-color: #f3e8ff;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3 text-center">
                                    <div class="icon-circle bg-white text-purple shadow-sm mb-3" style="width: 50px; height: 50px; color: #9333ea;">
                                        <i class="ti ti-send fs-4"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0 fs-sm" style="font-size: 0.85rem;">Transfer</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Card to Card -->
                    <div class="col-6 col-sm-6 col-md-3 col-lg-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10 transition-hover btn-action cursor-pointer" onclick="showCardToCard()">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center p-3 text-center">
                                <div class="icon-circle bg-white text-warning shadow-sm mb-3" style="width: 50px; height: 50px;">
                                    <i class="ti ti-credit-card fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 fs-sm" style="font-size: 0.85rem;">Card to Card</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Top Up -->
                    <div class="col-6 col-sm-6 col-md-3 col-lg-6 col-xl-3">
                        <a href="{{ route('ecard.wallet.request.index') }}" class="text-decoration-none h-100 d-block">
                            <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10 transition-hover">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3 text-center">
                                    <div class="icon-circle bg-white text-success shadow-sm mb-3" style="width: 50px; height: 50px;">
                                        <i class="ti ti-arrow-down-right fs-4"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0 fs-sm" style="font-size: 0.85rem;">Top Up</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Dynamic -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                <h5 class="fw-bold mb-0">Recent Activity</h5>
                <a href="{{ route('ecard.wallet.transactions.index') }}" class="text-primary fw-bold text-decoration-none small">View All History</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($transactions as $txn)
                        <div class="list-group-item border-0 px-4 py-3 d-flex flex-wrap align-items-center justify-content-between hover-bg-light gap-3 border-bottom">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <!-- Determine icon based on transaction type -->
                                @if($txn->transaction_type == 'add')
                                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                        <i class="ti ti-arrow-down-right fs-4"></i>
                                    </div>
                                @else
                                    <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                        <i class="ti ti-arrow-up-right fs-4"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $txn->narration ?? 'Wallet Transaction' }}</h6>
                                    <p class="mb-0 text-muted small">
                                        <span class="me-2"><i class="ti ti-clock me-1"></i>{{ $txn->created_at->format('M d, Y h:i A') }}</span> 
                                        @if($txn->payment_status)
                                            <span class="badge bg-light text-secondary border">{{ strtoupper($txn->payment_status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-end ms-auto">
                                @if($txn->transaction_type == 'add')
                                    <h6 class="fw-bold text-success mb-1">+ ₹{{ number_format($txn->amount, 2) }}</h6>
                                @else
                                    <h6 class="fw-bold text-danger mb-1">- ₹{{ number_format($txn->amount, 2) }}</h6>
                                @endif
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Bal: ₹{{ number_format($txn->new_balance, 2) }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center">
                            <div class="mb-3">
                                <i class="ti ti-receipt fs-1 text-muted opacity-25"></i>
                            </div>
                            <h6 class="fw-bold text-muted">No recent transactions found</h6>
                            <p class="text-muted small">Top up your wallet to get started or receive money.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .hover-bg-light:hover {
        background-color: #f8f9fa !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .ecard-professional {
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.175)!important;
    }
    .map-bg {
        position: absolute;
        width: 150%;
        height: 150%;
        top: -25%;
        right: -25%;
        background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg"><path d="M200,150 Q250,100 300,120 T400,180 Q450,160 500,190 T600,210 Q650,180 700,200" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="20"/><path d="M150,200 Q200,150 250,180 T350,220 Q400,200 450,250 T550,260 Q600,230 650,270" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="30"/><path d="M250,250 Q300,200 350,210 T450,270 Q500,250 550,280 T650,290 Q700,250 750,280" fill="none" stroke="rgba(255,255,255,0.02)" stroke-width="40"/></svg>');
        background-size: cover;
        background-position: center;
    }
</style>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    function showScanToPay() {
        Swal.fire({
            title: 'Scan to Pay',
            text: 'This feature is currently being integrated for E-Card wallet users.',
            icon: 'info',
            confirmButtonText: 'Understood',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    }

    function showCardToCard() {
        Swal.fire({
            title: 'Card to Card Transfer',
            text: 'Secure peer-to-peer card transfers are under development and will be available soon.',
            icon: 'info',
            confirmButtonText: 'Got It!',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    }
</script>
@endpush
