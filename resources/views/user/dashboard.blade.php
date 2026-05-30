<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>User Dashboard - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        :root {
            --header-gradient: linear-gradient(to right, #c42086, #b02995, #9b30a2, #8435ad, #6a39b6);
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        /* Common Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            padding-bottom: 80px; /* Space for bottom nav */
            margin: 0 auto;
            position: relative;
            color: var(--text-dark);
        }

        /* Header */
        .app-header {
            background: var(--header-gradient);
            padding: 15px 20px 20px;
            color: white;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            position: relative;
            z-index: 10;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .user-profile-btn {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pink-highlight);
            font-size: 20px;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-decoration: none;
        }

        .benefits-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 5px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(5px);
        }

        .header-actions .btn-icon {
            color: white;
            font-size: 20px;
            margin-left: 15px;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        /* e-Card */
        .ecard-section {
            padding: 0 15px;
            margin-top: -10px;
        }

        .ecard {
            background: linear-gradient(135deg, #000428 0%, #004e92 100%);
            border-radius: 20px;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            min-height: 200px;
        }

        .ecard::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .ecard-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
        }

        .ecard-logo {
            text-align: right;
        }

        .ecard-logo h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .ecard-logo small {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        .ecard-details label {
            font-size: 0.75rem;
            opacity: 0.8;
            display: block;
            margin-bottom: 2px;
        }

        .ecard-details h4 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .ecard-number {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .ecard-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 15px;
        }

        .ecard-val {
            text-align: center;
        }

        .ecard-val span {
            display: block;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Section Styles */
        .section-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-all {
            font-size: 0.85rem;
            color: var(--pink-highlight);
            text-decoration: none;
            font-weight: 600;
        }

        /* Grid Menus */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            text-align: center;
        }

        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-dark);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #d53f8c 0%, #9f7aea 100%);
            box-shadow: 0 4px 10px rgba(213, 63, 140, 0.3);
        }

        .menu-text {
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Ad Banner */
        .ad-banner {
            background: linear-gradient(135deg, #a0c4ff 0%, #cbf3f0 100%);
            border-radius: 20px;
            margin: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            min-height: 160px;
        }

        .ad-content {
            width: 60%;
            z-index: 2;
        }

        .ad-title {
            color: #2d3748;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .ad-subtitle {
            color: #4a5568;
            font-size: 0.85rem;
        }

        /* Support Cards */
        .support-header {
            background: linear-gradient(to right, #ec4899, #a855f7);
            color: white;
            padding: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
        }

        /* Vertical List */
        .list-item-card {
            background: white;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .list-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(213, 63, 140, 0.1);
            color: var(--pink-highlight);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .list-text {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        /* Horizontal Scroll Cards */
        .horizontal-scroll {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 5px 0 15px 0;
            scrollbar-width: none; /* Firefox */
        }

        .horizontal-scroll::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        .feature-card {
            min-width: 280px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .feature-img {
            height: 120px;
            width: 100%;
            object-fit: cover;
            background-color: #e2e8f0;
        }

        .feature-body {
            padding: 15px;
        }

        .feature-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            z-index: 1000;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .nav-item-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #718096;
            font-size: 0.75rem;
            gap: 4px;
        }

        .nav-item-link.active {
            color: #1a202c;
        }

        .nav-item-link i {
            font-size: 1.2rem;
        }

        .scan-btn {
            background: linear-gradient(135deg, #D53F8C 0%, #805AD5 100%);
            width: 60px;
            height: 60px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-top: -35px;
            box-shadow: 0 5px 15px rgba(213, 63, 140, 0.4);
            border: 4px solid white;
        }

        /* Rewards Banner */
        .rewards-banner {
            background: linear-gradient(to right, #a78bfa, #8b5cf6);
            margin: 15px;
            border-radius: 20px;
            padding: 20px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .rewards-banner h3 {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        /* Apps Grid */
        .apps-row {
            display: flex;
            gap: 20px;
        }

        .app-icon-box {
            text-align: center;
        }

        .app-img {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 5px;
            background: white;
            padding: 5px;
        }

        /* Custom Grid for Links */
        .links-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .link-card {
            background: white;
            padding: 15px;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 120px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-decoration: none;
            color: inherit;
        }

        .link-card-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .link-card-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        .link-card-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.2;
        }

        /* ==========================================
           DESKTOP OPTIMIZATIONS (Min-width: 992px)
           ========================================== */
        @media (min-width: 992px) {
            body {
                background-color: #e2e8f0; /* Professional darker bg for contrast */
                display: flex;
                justify-content: center;
                min-height: 100vh;
            }

            .mobile-wrapper {
                max-width: 450px; /* Phone width on desktop */
                box-shadow: 0 0 50px rgba(0,0,0,0.15);
                border-left: 1px solid rgba(0,0,0,0.05);
                border-right: 1px solid rgba(0,0,0,0.05);
                /* Center the bottom nav relative to this wrapper */
            }

            .bottom-nav {
                width: 450px; /* Match wrapper width */
                left: 50%;
                transform: translateX(-50%);
                bottom: 0;
                /* Remove rounded corners at very bottom if desired, or keep them */
            }

            /* Optional: Add a 'Desktop View' hint */
            body::before {
                content: 'Mobile View Mode';
                position: fixed;
                top: 20px;
                left: 20px;
                font-weight: 600;
                color: #718096;
                opacity: 0.5;
                pointer-events: none;
            }
        }

        .desk-sheen::after {
            content: '';
            position: absolute;
            top: -140px;
            left: -80px;
            width: 220px;
            height: 420px;
            background: linear-gradient(120deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.35) 45%, rgba(255,255,255,0) 70%);
            transform: rotate(18deg);
            opacity: 0.6;
            pointer-events: none;
        }

        .desk-card-glow {
            box-shadow: 0 18px 45px rgba(16, 24, 40, 0.10), 0 0 0 1px rgba(255,255,255,0.08) inset;
        }

        .desk-gradient-pink {
            background: linear-gradient(135deg, #ff4ecd 0%, #9f7aea 55%, #38b2ac 120%);
        }

        .desk-gradient-blue {
            background: linear-gradient(135deg, rgba(59,130,246,0.20), rgba(59,130,246,0.06));
        }

        .desk-gradient-green {
            background: linear-gradient(135deg, rgba(16,185,129,0.18), rgba(16,185,129,0.06));
        }

        .desk-gradient-purple {
            background: linear-gradient(to right, #a78bfa, #7c3aed);
        }

        .desk-pill {
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 700;
        }

        .desk-section-shadow {
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .hover-lift {
            transition: transform 160ms ease, box-shadow 160ms ease;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
        }
        .hover-shadow {
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

    <!-- Desktop Wrapper (redesigned) -->
    <div class="desktop-wrapper d-none d-lg-flex min-vh-100" style="width: 100%;margin-left: 294px;background: radial-gradient(circle at 10% 10%, rgba(255,78,205,0.18), rgba(255,78,205,0) 45%), radial-gradient(circle at 90% 20%, rgba(124,58,237,0.18), rgba(124,58,237,0) 50%), radial-gradient(circle at 40% 100%, rgba(56,178,172,0.14), rgba(56,178,172,0) 55%), linear-gradient(135deg, rgba(255,255,255,0.35), rgba(255,255,255,0.12));">
        @include('user.partials.desktop-sidebar')

        <div class="flex-grow-1 d-flex flex-column ms-auto" style="margin-left: 280px;">
            @include('user.partials.desktop-header')

            <main class="p-4" style="padding-bottom: 70px;">
                <div class="container-fluid">
                    {{-- Modern Desktop Layout (more attractive) --}}
                    <div class="row g-4 mb-4">
                        {{-- Left: Membership / e-Card --}}
                        <div class="col-lg-5">
                            <div class="card border-0 desk-sheen desk-card-glow hover-lift overflow-hidden" style="border-radius: 1.35rem; background: radial-gradient(circle at 15% 15%, rgba(255,78,205,0.55), rgba(255,78,205,0) 52%), radial-gradient(circle at 85% 30%, rgba(56,178,172,0.30), rgba(56,178,172,0) 55%), linear-gradient(135deg, #0b1020 0%, #004e92 70%, #7c3aed 120%); color:#fff;">
                                <div class="card-body p-4 position-relative">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <small class="opacity-75 d-block mb-1">Membership</small>
                                            <h5 class="fw-bold mb-0">{{ $user['full_name'] ?? 'User Name' }}</h5>
                                        </div>
                                        <div class="text-end">
                                            <div class="desk-pill desk-gradient-pink text-white" style="display:inline-flex; align-items:center; justify-content:center; box-shadow: 0 12px 30px rgba(255,78,205,0.20);">
                                                <h5 class="fw-bold mb-0" style="letter-spacing:0.2px;">e<span style="opacity:0.95">></span>card</h5>
                                            </div>
                                            <small class="opacity-75 d-block mt-2">Benefits Card</small>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <small class="opacity-75 d-block mb-1">Card Number</small>
                                        <div class="font-monospace fs-5 letter-spacing-2" style="font-weight:800; text-shadow: 0 8px 20px rgba(0,0,0,0.25);">
                                            @if(isset($user['user_id']))
                                                {{ substr($user['user_id'], 0, 4) }} **** **** {{ substr($user['user_id'], -4) }}
                                            @else
                                                **** **** **** ****
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between pt-4 mt-3" style="border-top: 1px solid rgba(255,255,255,0.22);">
                                        <div class="text-center">
                                            <span class="d-block fw-bold" style="letter-spacing:0.2px; font-size:1.05rem;">₹ {{ number_format((float)($user['ecard_limit'] ?? $user['wallet_limit'] ?? 50000), 0) }}</span>
                                            <small class="opacity-75" style="font-size: 0.7rem;">Limit</small>
                                        </div>
                                        <div class="text-center">
                                            <span class="d-block fw-bold" style="letter-spacing:0.2px; font-size:1.05rem;">₹ {{ number_format((float)($user['wallet_balance'] ?? $walletBalance ?? $user['wallet_balance_amount'] ?? 0), 2) }}</span>

                                            <small class="opacity-75" style="font-size: 0.7rem;">Balance</small>
                                        </div>
                                        <div class="text-center">
                                            <span class="d-block fw-bold" style="letter-spacing:0.2px; font-size:1.05rem;">{{ number_format((int)($user['reward_points'] ?? $user['points'] ?? 200), 0) }}</span>
                                            <small class="opacity-75" style="font-size: 0.7rem;">Points</small>
                                        </div>
                                    </div>

                                    <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 240px; height: 240px; top: -85px; right: -110px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Wallet + Rewards --}}
                        <div class="col-lg-7">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card border-0 desk-card-glow shadow-sm rounded-4 h-100 hover-lift" style="background: linear-gradient(135deg, rgba(59,130,246,0.22), rgba(59,130,246,0.06));">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold m-0 text-secondary">Wallet Balance</h6>
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(59,130,246,0.18); color:#2563eb; box-shadow: 0 10px 30px rgba(37,99,235,0.25);">
                                                    <i class="fas fa-wallet"></i>
                                                </div>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="letter-spacing:0.2px;">₹ {{ number_format((float)($user['wallet_balance'] ?? $walletBalance ?? $user['wallet_balance_amount'] ?? 0), 2) }}</h2>
                                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                                <a href="{{ route('user.wallet.show') }}" class="btn btn-sm text-white rounded-pill px-3" style="background: linear-gradient(135deg,#3b82f6,#7c3aed); border:none; box-shadow: 0 14px 30px rgba(124,58,237,0.20);">Add Money</a>
                                                <a href="{{ route('user.wallet.transfer.qr.show') }}" class="btn btn-sm rounded-pill px-3" style="border:1px solid rgba(59,130,246,0.35); color:#2563eb; background: rgba(59,130,246,0.05);">Transfer</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-0 desk-card-glow shadow-sm rounded-4 h-100 hover-lift" style="background: linear-gradient(135deg, rgba(16,185,129,0.20), rgba(16,185,129,0.06));">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold m-0 text-secondary">Reward Points</h6>
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(16,185,129,0.18); color:#059669; box-shadow: 0 10px 30px rgba(5,150,105,0.20);">
                                                    <i class="fas fa-gift"></i>
                                                </div>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="letter-spacing:0.2px;">{{ number_format((int)($user['reward_points'] ?? $user['points'] ?? 350), 0) }} Pts</h2>
                                            <p class="small text-muted mb-0">Equivalent to ₹ {{ number_format(((int)($user['reward_points'] ?? $user['points'] ?? 350)) / 10, 2) }}</p>



                                            <a href="{{ route('user.service.report.reward') }}" class="btn btn-link p-0 text-decoration-none small fw-semibold mt-2" style="color:#065f46;">Redeem Now <i class="fas fa-arrow-right small"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="card border-0 shadow-sm rounded-4 desk-sheen desk-section-shadow hover-lift" style="background: radial-gradient(circle at 10% 20%, rgba(255,255,255,0.22), rgba(255,255,255,0) 55%), linear-gradient(135deg, #ff4ecd 0%, #7c3aed 55%, #38b2ac 120%); color:#fff;">
                                    <div class="card-body p-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                        <div>
                                            <h5 class="fw-bold mb-1">+50 Rewards</h5>
                                            <small class="opacity-90">Your next reward awaits. Explore & earn cashback.</small>
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('user.service.report.reward') }}" class="btn btn-light btn-sm rounded-pill px-4 fw-semibold" style="background: rgba(255,255,255,0.95); color:#5b21b6; border:none;">Rewards View ></a>
                                            <a href="{{ route('user.service.report.reward') }}" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-semibold" style="border-color: rgba(255,255,255,0.75);">My Points ></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Quick services grid --}}
                    <div class="d-flex align-items-end justify-content-between mb-3">
                        <h5 class="fw-bold mb-0 text-dark">Quick Services</h5>
                        <a href="#" class="text-primary small text-decoration-none fw-semibold">View All</a>
                    </div>

                    <div class="row g-3 mb-4">
                        @php($services = [
                            ['route'=>'user.service.recharge.mobile','icon'=>'fa-mobile-alt','title'=>'Mobile'],
                            ['route'=>'user.service.recharge.dth','icon'=>'fa-tv','title'=>'DTH'],
                            ['route'=>'user.service.recharge.fastag','icon'=>'fa-car','title'=>'FASTag'],
                            ['route'=>'user.service.recharge.utility.link','icon'=>'fa-file-invoice','title'=>'Bills'],
                            ['route'=>'user.benefit.bookcamp.show','icon'=>'fa-campground','title'=>'Book Camp'],
                            ['route'=>'user.benefit.blood.dashboard','icon'=>'fa-droplet','title'=>'Blood Seva'],
                        ])
                        @foreach($services as $s)
                            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                                <a href="{{ route($s['route']) }}" class="card border-0 desk-card-glow h-100 text-decoration-none hover-lift" style="background: linear-gradient(135deg, rgba(124,58,237,0.05), rgba(255,78,205,0.04)); transition: transform 160ms ease, box-shadow 160ms ease;">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3 mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,78,205,0.10); color:#d53f8c; font-size:20px; box-shadow: 0 16px 35px rgba(213,63,140,0.18);">
                                            <i class="fas {{ $s['icon'] }}"></i>
                                        </div>
                                        <h6 class="text-dark fw-semibold mb-0">{{ $s['title'] }}</h6>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Apps + Refer --}}
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm rounded-4 hover-lift" style="background: linear-gradient(135deg, rgba(255,78,205,0.06), rgba(124,58,237,0.05), rgba(56,178,172,0.04));">
                                <div class="card-header bg-transparent border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold m-0" style="color:#1f2937;">Apps by Uonely</h6>
                                    <a href="#" class="text-primary small text-decoration-none fw-semibold">View All</a>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex gap-4">
                                        <div class="text-center">
                                            <div class="rounded-3 border-0 d-flex align-items-center justify-content-center mx-auto mb-2 text-white" style="width: 60px; height: 60px; font-size: 24px; font-weight: 800; background: linear-gradient(135deg,#3b82f6,#7c3aed); box-shadow: 0 16px 30px rgba(59,130,246,0.18);">U</div>
                                            <small class="fw-semibold">U Mart</small>
                                        </div>
                                        <div class="text-center">
                                            <div class="rounded-3 border-0 d-flex align-items-center justify-content-center mx-auto mb-2 text-white" style="width: 60px; height: 60px; font-size: 22px; background: linear-gradient(135deg,#f59e0b,#ef4444); box-shadow: 0 16px 30px rgba(245,158,11,0.16);">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <small class="fw-semibold">U Admiss</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #ff4ecd 0%, #7c3aed 55%, #3b82f6 120%); box-shadow: 0 24px 60px rgba(124,58,237,0.25);">
                                <div class="card-body p-4 position-relative">
                                    <h4 class="fw-bold mb-2" style="letter-spacing:0.2px;">Refer & Earn</h4>
                                    <p class="mb-3 opacity-90">Invite friends and earn up to ₹500 per referral!</p>
                                    <button class="btn btn-light rounded-pill px-4 fw-semibold" style="background: rgba(255,255,255,0.95); color:#5b21b6; border:none; box-shadow: 0 18px 40px rgba(255,255,255,0.10);">Invite Now</button>
                                    <i class="fas fa-users position-absolute opacity-25" style="font-size: 100px; bottom: -20px; right: -20px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- Mobile-First Wrapper -->
    <div class="mobile-wrapper d-lg-none">

        <!-- Header -->
        <header class="app-header">
            <div class="header-top">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('user.profile') }}" class="user-profile-btn">
                        <i class="fas fa-user"></i>
                    </a>
                    <div class="benefits-badge">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width:20px;height:20px;font-size:10px;">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <small style="font-size:0.6rem; display:block; line-height:1;">Benefits</small>
                            <span style="font-weight:700; font-size:0.9rem;">₹ {{ number_format($walletBalance ?? 0, 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn-icon"><i class="fas fa-bell"></i></button>
                    <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-icon"><i class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </div>
        </header>

        <!-- e-Card Section -->
        <div class="ecard-section">
            <div class="ecard">
                <div class="ecard-header">
                    <div class="ecard-details">
                        <label>Membership Name</label>
                        <h4>{{ $user['full_name'] ?? 'User Name' }}</h4>
                    </div>
                    <div class="ecard-logo">
                        <h5>e<span style="color:#38b2ac">></span>card</h5>
                        <small>the benefits card</small>
                    </div>
                </div>

                <div class="ecard-details">
                    <label>e-Card Number</label>
                    <div class="ecard-number">
                        @if(isset($user['user_id']))
                            {{ substr($user['user_id'], 0, 4) }} **** **** {{ substr($user['user_id'], -4) }}
                        @else
                            **** **** **** ****
                        @endif
                    </div>
                </div>

                <div class="ecard-footer">
                    <div class="ecard-val">
                        <span>₹ {{ number_format((float)($user['ecard_limit'] ?? $user['wallet_limit'] ?? 50000), 0) }}</span>
                        <small style="font-size: 0.65rem; opacity: 0.8; display: block;">Limit</small>
                    </div>
                    <div class="ecard-val">
                        <span>₹ {{ number_format($walletBalance ?? 0, 0) }}</span>
                        <small style="font-size: 0.65rem; opacity: 0.8; display: block;">Balance</small>
                    </div>
                    <div class="ecard-val">
                        <span>{{ number_format((int)($user['reward_points'] ?? $user['points'] ?? $points ?? 200), 0) }}</span>
                        <small style="font-size: 0.65rem; opacity: 0.8; display: block;">Points</small>
                    </div>
                </div>
                <i class="fas fa-wifi position-absolute" style="top: 20px; left: 50%; transform: translateX(-50%) rotate(90deg); opacity: 0.5; font-size: 24px;"></i>
            </div>
        </div>

        <!-- Recharge & Pay Bills -->
        <div class="section-container">
            <div class="section-title">
                Recharge & Pay Bills
            </div>
            <div class="menu-grid">
                <a href="{{ route('user.service.recharge.mobile') }}" class="menu-item">
                    <div class="icon-circle"><i class="fas fa-mobile-alt"></i></div>
                    <span class="menu-text">Mobile</span>
                </a>
                <a href="{{ route('user.service.recharge.dth') }}" class="menu-item">
                    <div class="icon-circle"><i class="fas fa-tv"></i></div>
                    <span class="menu-text">DTH</span>
                </a>
                <a href="{{ route('user.service.recharge.fastag') }}" class="menu-item">
                    <div class="icon-circle"><i class="fas fa-car"></i></div>
                    <span class="menu-text">FASTag</span>
                </a>
                <a href="{{ route('user.service.recharge.utility.link') }}" class="menu-item">
                    <div class="icon-circle"><i class="fas fa-file-invoice"></i></div>
                    <span class="menu-text">BBPS</span>
                </a>
            </div>
        </div>

        <!-- Ad Banner -->
        <div class="ad-banner">
            <div class="ad-content">
                <h3 class="ad-title" style="color: #553c9a;">One app, unlimited recharges!</h3>
                <p class="ad-subtitle">Fast | secure | reliable, anytime | anywhere</p>
            </div>
            <!-- Placeholder for hand image -->
            <div style="position: absolute; right: 10px; bottom: 10px; font-size: 80px; opacity: 0.2;">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
        </div>

        <!-- Main Content Split (Support & Services) -->
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-6">
                    <!-- Support Men Photos -->
                    <div class="section-container ms-3 me-2 mt-0 h-100">
                        <div class="support-header rounded-top bg-danger" style="background: linear-gradient(to right, #ec4899, #d53f8c);">
                            Support Men Photos
                        </div>
                        <div class="d-flex align-items-center justify-content-center p-4 text-center text-muted" style="height: 150px;">
                            No Support photos available
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <!-- Services List -->
                    <div class="d-flex flex-column gap-2 me-3 mt-0">
                        <div class="list-item-card">
                            <div class="list-icon" style="color:#805AD5; bg:#f3e8ff;"><i class="fas fa-ambulance"></i></div>
                            <span class="list-text">Ambulance</span>
                        </div>
                        <div class="list-item-card">
                            <div class="list-icon" style="color:#D53F8C;"><i class="fas fa-user-tie"></i></div>
                            <span class="list-text">Consultant</span>
                        </div>
                        <div class="list-item-card">
                            <div class="list-icon" style="color:#dd6b20;"><i class="fas fa-lightbulb"></i></div>
                            <span class="list-text">Talent Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apps by Uonely -->
        <div class="section-container">
            <div class="section-title">
                Apps by Uonely
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="apps-row">
                <div class="app-icon-box">
                    <div class="app-img d-flex align-items-center justify-content-center text-primary border">
                        <span class="fw-bold">U</span>
                    </div>
                    <small class="fw-bold d-block">U Mart</small>
                </div>
                <div class="app-icon-box">
                    <div class="app-img d-flex align-items-center justify-content-center text-warning border">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <small class="fw-bold d-block">U Admiss</small>
                </div>
            </div>
        </div>

        <!-- Our Links / Benefits & Support -->
        <div class="section-container bg-transparent shadow-none p-0">
            <div class="section-title ps-3">
                Benefits & Support
            </div>
            <div class="links-grid px-3">
                <!-- Benefits -->
                <a href="{{ route('user.benefit.eligible.report') }}" class="link-card">
                    <div>
                        <h5 class="link-card-title">Benefits</h5>
                        <p class="link-card-desc">Empowering Every Citizen with Digital Benefits.</p>
                    </div>
                    <div class="text-center mt-2">
                        <i class="fas fa-hands-holding-circle text-primary" style="font-size: 40px;"></i>
                    </div>
                </a>

                <!-- Blood Seva -->
                <a href="{{ route('user.benefit.blood.dashboard') }}" class="link-card">
                    <div>
                        <h5 class="link-card-title">Blood Seva</h5>
                        <p class="link-card-desc">Blood Seva today and become a part of India</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-droplet text-danger" style="font-size: 30px;"></i>
                    </div>
                </a>

                <!-- Emergency -->
                <a href="{{ route('user.benefit.emergency.dashboard') }}" class="link-card">
                    <div>
                        <h5 class="link-card-title">Emergency</h5>
                        <p class="link-card-desc">Instant Help When You Need It Most to click</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 30px;"></i>
                    </div>
                </a>

                <!-- My Order -->
                <a href="{{ route('user.service.orders.view') }}" class="link-card">
                    <div>
                        <h5 class="link-card-title">My Order</h5>
                        <p class="link-card-desc">View purchase history</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-shopping-cart text-info" style="font-size: 30px;"></i>
                    </div>
                </a>

                <!-- e Store -->
                <a href="{{ route('user.estore.categories') }}" class="link-card">
                    <div>
                        <h5 class="link-card-title">e Store</h5>
                        <p class="link-card-desc">Save money on all in one place. India</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-store text-danger" style="font-size: 30px;"></i>
                    </div>
                </a>

                 <!-- Reward -->
                 <a href="{{ route('user.service.report.reward') }}" class="link-card">
                    <div>
                        <h5 class="link-card-title">Reward</h5>
                        <p class="link-card-desc">The more you Save earn!</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-gift text-success" style="font-size: 30px;"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- Rewards Banner -->
        <div class="rewards-banner">
            <h3>+50</h3>
            <p>Your Next Reward Awaits!</p>
            <p class="small opacity-75">Explore & Earn Cashback</p>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <a href="{{ route('user.service.report.reward') }}" class="btn btn-sm btn-light rounded-pill px-3">Rewards View ></a>
                <a href="{{ route('user.service.report.reward') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">View Your Points ></a>
            </div>
        </div>

        <!-- Coming Soon / Health / Education Sections -->
        <div class="section-container">
            <div class="section-title">
                Health <a href="#" class="view-all">View All</a>
            </div>
            <div class="horizontal-scroll">
                <div class="feature-card">
                    <div class="feature-img d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-md fa-3x text-muted"></i>
                    </div>
                    <div class="feature-body">
                        <h6 class="feature-title">Health</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-container mb-5">
            <div class="section-title">
                Education <a href="#" class="view-all">View All</a>
            </div>
            <div class="horizontal-scroll">
                <div class="feature-card">
                    <div class="feature-img d-flex align-items-center justify-content-center">
                        <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                    </div>
                    <div class="feature-body">
                        <h6 class="feature-title">Education</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Spacer -->
        <div style="height: 50px;"></div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('user.dashboard') }}" class="nav-item-link active">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="#" class="nav-item-link">
                <i class="fas fa-search"></i>
                <span>Search</span>
            </a>
            <div class="nav-item-link">
                <a href="{{ route('user.wallet.transfer.qr.show') }}" class="scan-btn">
                    <i class="fas fa-qrcode"></i>
                </a>
            </div>
            <a href="{{ route('user.manage.transactions') }}" class="nav-item-link">
                <i class="fas fa-exchange-alt"></i>
                <span>Transaction</span>
            </a>
            <a href="#" class="nav-item-link">
                <i class="fas fa-th"></i>
                <span>Apps</span>
            </a>
        </nav>

    </div><!-- End Mobile Wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            function isLikelyMobile() {
                const coarse = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
                const smallScreen = window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches;
                return Boolean(coarse || smallScreen);
            }

            document.addEventListener('DOMContentLoaded', function () {
                const scanLink = document.querySelector('a.scan-btn');
                if (!scanLink) {
                    return;
                }

                scanLink.addEventListener('click', function (e) {
                    if (isLikelyMobile()) {
                        return;
                    }

                    e.preventDefault();

                    if (window.Swal && typeof window.Swal.fire === 'function') {
                        window.Swal.fire({
                            title: 'Scanner not supported on desktop',
                            text: 'Please use a mobile device to scan the QR code.',
                            icon: 'info',
                            confirmButtonText: 'OK',
                        });
                        return;
                    }

                    window.alert('Scanner not supported on desktop. Please use a mobile device.');
                });
            });
        })();
    </script>
    @include('user.partials.theme-script')
</body>
</html>
