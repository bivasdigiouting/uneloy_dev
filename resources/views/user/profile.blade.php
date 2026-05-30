<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>User Profile - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Common Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
        }

        /* Header */
        .profile-header {
            background: var(--bg-light);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .back-btn {
            font-size: 24px;
            color: var(--text-dark);
            text-decoration: none;
            margin-right: 15px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            flex-grow: 1;
            text-align: center;
            margin-right: 24px; /* Balance the back button spacing */
        }

        /* Profile Card */
        .profile-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            margin: 10px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            position: relative;
        }

        .profile-info-row {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .profile-avatar {
            width: 60px;
            height: 60px;
            background-color: var(--bg-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #a0aec0;
        }

        .profile-details {
            flex: 1;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .profile-contact {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .verify-link {
            color: #3182ce;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
            text-transform: uppercase;
        }

        .manage-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: var(--pink-highlight);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
        }

        /* Section Cards */
        .section-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            margin: 15px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        /* Icon Grid */
        .icon-grid {
            display: flex;
            justify-content: space-between; /* Distribute evenly */
            text-align: center;
            padding: 0 10px;
        }

        .grid-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-dark);
            width: 30%; /* Ensure 3 items fit perfectly */
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            background-color: var(--bg-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--pink-highlight);
        }

        .icon-label {
            font-size: 13px;
            font-weight: 500;
            line-height: 1.2;
        }

        /* List Items */
        .settings-group-title {
            padding: 10px 20px 5px;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .settings-list {
            background: transparent;
        }

        .settings-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background: transparent;
            text-decoration: none;
            color: var(--text-dark);
        }

        .settings-icon {
            width: 30px;
            font-size: 20px;
            color: var(--pink-highlight); /* Default pink color */
            display: flex;
            justify-content: center;
            margin-right: 15px;
        }
        
        .settings-text {
            flex: 1;
            font-size: 15px;
            font-weight: 500;
        }

        .settings-text span {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .settings-arrow {
            color: var(--pink-highlight);
            font-size: 14px;
        }

        /* Toggle Switch */
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }
        .form-switch .form-check-input:checked {
            background-color: #718096;
            border-color: #718096;
        }

        /* Manage Payments Link */
        .manage-payments-link {
            color: var(--text-dark) !important;
        }

        /* Desktop Optimizations */
        @media (min-width: 992px) {
            body {
                background-color: #e2e8f0;
                display: flex;
                justify-content: center;
                min-height: 100vh;
            }

            .mobile-wrapper {
                max-width: 450px;
                box-shadow: 0 0 50px rgba(0,0,0,0.15);
                border-left: 1px solid rgba(0,0,0,0.05);
                border-right: 1px solid rgba(0,0,0,0.05);
                background-color: #f8f9fa; /* Keep light bg inside wrapper */
            }
        }
    </style>
</head>
<body>

    <!-- Desktop Wrapper -->
    <div class="desktop-wrapper d-none d-lg-flex bg-light min-vh-100" style="width: 100%; margin-left: 294px;">
        @include('user.partials.desktop-sidebar')
        <div class="flex-grow-1 d-flex flex-column ms-auto" style="margin-left: 280px;">
             @section('page_title', 'My Profile')
             @include('user.partials.desktop-header')
             <main class="p-4">
                 <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                                <div class="position-relative d-inline-block mx-auto mb-3">
                                     <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 40px;">
                                         {{ substr($user->name ?? 'U', 0, 1) }}
                                     </div>
                                </div>
                                <h5 class="fw-bold mb-1">{{ $user->full_name ?? 'User Name' }}</h5>
                                <p class="text-muted small mb-3">{{ $user->email ?? 'email@example.com' }}</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">Active</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">Member</span>
                                </div>
                                
                                <div class="mt-4 text-start">
                                     <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light mb-2">
                                         <span class="small fw-semibold text-muted">Wallet Balance</span>
                                         <span class="fw-bold text-dark">₹ {{ number_format($user->wallet_balance ?? 0, 2) }}</span>
                                     </div>
                                     <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light">
                                         <span class="small fw-semibold text-muted">Reward Points</span>
                                         <span class="fw-bold text-dark">{{ number_format($points ?? 200, 0) }}</span>
                                     </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white border-bottom py-3 px-4">
                                    <h6 class="fw-bold m-0">Profile Settings</h6>
                                </div>
                                <div class="card-body p-4">
                                     <div class="row g-4">
                                         <div class="col-md-6">
                                             <label class="form-label small text-muted">Full Name</label>
                                             <div class="form-control bg-light border-0 fw-semibold">{{ $user->full_name ?? '-' }}</div>
                                         </div>
                                         <div class="col-md-6">
                                             <label class="form-label small text-muted">Mobile Number</label>
                                             <div class="form-control bg-light border-0 fw-semibold">{{ $user->mobile ?? '-' }}</div>
                                         </div>
                                         <div class="col-md-6">
                                             <label class="form-label small text-muted">Email Address</label>
                                             <div class="form-control bg-light border-0 fw-semibold">{{ $user->email ?? '-' }}</div>
                                         </div>
                                         <div class="col-md-6">
                                             <label class="form-label small text-muted">Date of Birth</label>
                                             <div class="form-control bg-light border-0 fw-semibold">{{ $user->dob ?? 'Not Set' }}</div>
                                         </div>
                                         <div class="col-md-12">
                                             <label class="form-label small text-muted">Address</label>
                                             <div class="form-control bg-light border-0 fw-semibold">{{ $user->address ?? 'No address provided' }}</div>
                                         </div>
                                     </div>
                                     
                                     <div class="mt-4 pt-3 border-top d-flex gap-3">
                                         <a href="{{ route('user.profile.manage') }}" class="btn btn-primary rounded-pill px-4">Edit Profile</a>
                                         <a href="{{ route('user.security.settings') }}" class="btn btn-outline-secondary rounded-pill px-4">Security Settings</a>
                                     </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm rounded-4 mt-4">
                                 <div class="card-header bg-white border-bottom py-3 px-4">
                                    <h6 class="fw-bold m-0">Quick Actions</h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <a href="{{ route('user.qr.show') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 border text-decoration-none text-dark hover-bg-light">
                                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-qrcode"></i>
                                                </div>
                                                <span class="fw-semibold small">My QR Code</span>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                             <a href="{{ route('user.manage.transactions') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 border text-decoration-none text-dark hover-bg-light">
                                                <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-history"></i>
                                                </div>
                                                <span class="fw-semibold small">Transactions</span>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                             <a href="{{ route('help-support.index') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 border text-decoration-none text-dark hover-bg-light">
                                                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-headset"></i>
                                                </div>
                                                <span class="fw-semibold small">Help & Support</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
             </main>
        </div>
    </div>

    <!-- Mobile Wrapper -->
    <div class="mobile-wrapper d-lg-none">
        
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">{{ __('messages.profile') }}</div>
        </div>

        <!-- User Profile Card -->
        <div class="profile-card">
            <a href="{{ route('user.profile.manage') }}" class="manage-link">{{ __('messages.manage') }}</a>
            <div class="profile-info-row">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-details">
                    <div class="profile-name">{{ $user->full_name ?? 'User Name' }}</div>
                    <div class="profile-contact">
                        <span>{{ $user->mobile ?? '+91 XXXXXXXXXX' }}</span>
                        <a href="#" class="verify-link">{{ __('messages.verify') }}</a>
                    </div>
                    <div class="profile-contact">
                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                            {{ $user->email ?? 'email@example.com' }}
                        </span>
                        <a href="#" class="verify-link">{{ __('messages.verify') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Section -->
        <div class="section-card">
            <div class="section-title">My</div>
            <div class="icon-grid">
                <a href="{{ route('user.qr.show') }}" class="grid-item">
                    <div class="icon-circle" style="background-color: #f3e8ff; color: var(--pink-highlight);">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <span class="icon-label">{{ __('messages.my_qr') }}</span>
                </a>
                <a href="{{ route('user.wallet.show') }}" class="grid-item">
                    <div class="icon-circle" style="background-color: #f3e8ff; color: var(--pink-highlight);">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <span class="icon-label">{{ __('messages.wallet') }}</span>
                </a>
                <a href="{{ route('user.ecard.details') }}" class="grid-item">
                    <div class="icon-circle" style="background-color: #f3e8ff; color: var(--pink-highlight);">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span class="icon-label">{{ __('messages.card') }}</span>
                </a>
            </div>
        </div>

        <!-- Manage Section -->
        <div class="section-card">
            <div class="section-title">{{ __('messages.manage') }}</div>
            <div class="icon-grid">
                <a href="{{ route('user.manage.transactions') }}" class="grid-item">
                    <div class="icon-circle" style="background-color: #f3f4f6; color: var(--pink-highlight);">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <span class="icon-label">{{ __('messages.transactions') }}</span>
                </a>
                
                <a href="{{ route('user.manage.device-permission') }}" class="grid-item">
                    <div class="icon-circle" style="background-color: #f3f4f6; color: var(--pink-highlight);">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <span class="icon-label">{{ __('messages.device_permission') }}</span>
                </a>

                <a href="{{ route('user.manage.login-history') }}" class="grid-item">
                    <div class="icon-circle" style="background-color: #f3f4f6; color: var(--pink-highlight);">
                        <i class="fas fa-history"></i>
                    </div>
                    <span class="icon-label">{{ __('messages.login_history') }}</span>
                </a>
            </div>
        </div>

        <!-- Manage Payments (Single Item Card) -->
        <div class="section-card" style="padding: 15px;">
            <a href="{{ route('user.manage.payments') }}" class="d-flex align-items-center text-decoration-none manage-payments-link">
                <div class="icon-circle me-3" style="width: 40px; height: 40px; font-size: 18px;">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="flex-grow-1">
                    <div style="font-weight: 600;">{{ __('messages.manage_payments') }}</div>
                    <small class="text-muted">{{ __('messages.card_wallet_qr') }}</small>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--pink-highlight);"></i>
            </a>
        </div>

        <!-- General Settings -->
        <div class="settings-group-title">{{ __('messages.general_settings') }}</div>
        
        <div class="settings-list">
            <!-- Mode -->
            <div class="settings-item">
                <div class="settings-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="settings-text">
                    {{ __('messages.mode') }}
                    <span>{{ __('messages.dark_light') }}</span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                </div>
            </div>

            <!-- Security -->
            <a href="{{ route('user.security.settings') }}" class="settings-item">
                <div class="settings-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="settings-text">{{ __('messages.security') }}</div>
                <i class="fas fa-chevron-right settings-arrow"></i>
            </a>

            <!-- Language -->
            <a href="{{ route('user.language.show') }}" class="settings-item">
                <div class="settings-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="settings-text">{{ __('messages.language') }}</div>
                <i class="fas fa-chevron-right settings-arrow"></i>
            </a>

            <!-- Refer & Earn -->
            <a href="{{ route('user.refer.earn') }}" class="settings-item">
                <div class="settings-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="settings-text">{{ __('messages.refer_earn') }}</div>
                <i class="fas fa-chevron-right settings-arrow"></i>
            </a>

            <!-- Help & Support -->
            <a href="{{ route('help-support.index') }}" class="settings-item">
                <div class="settings-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="settings-text">{{ __('messages.help_support') }}</div>
                <i class="fas fa-chevron-right settings-arrow"></i>
            </a>

            <!-- About E-Card -->
            <a href="{{ route('about.organization-profile') }}" class="settings-item">
                <div class="settings-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="settings-text">{{ __('messages.about_ecard') }}</div>
                <i class="fas fa-chevron-right settings-arrow"></i>
            </a>

            <!-- Logout -->
             <a href="#" onclick="confirmLogout(event)" class="settings-item mb-4">
                <div class="settings-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="settings-text">{{ __('messages.logout') }}</div>
                <i class="fas fa-arrow-right settings-arrow"></i>
            </a>
            
            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: '{{ __("messages.logout_confirmation_title") ?? "Are you sure?" }}',
                text: '{{ __("messages.logout_confirmation_text") ?? "You will be logged out of your account." }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __("messages.logout_confirm_button") ?? "Yes, Logout" }}',
                cancelButtonText: '{{ __("messages.cancel") ?? "Cancel" }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    @include('user.partials.theme-script')
</body>
</html>
