<div class="desktop-sidebar bg-white border-end h-100 d-flex flex-column" style="width: 280px; position: fixed; top: 0; left: 0; z-index: 1030;">
    <div class="sidebar-header p-4 d-flex align-items-center gap-3 border-bottom">
        <div class="logo-icon bg-transparent d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <img src="{{ $settings && $settings->member_app_logo ? asset('storage/'.$settings->member_app_logo) : ($settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/logo.png')) }}" alt="Logo" class="img-fluid rounded-3">
        </div>
        <div>
            <h5 class="m-0 fw-bold text-primary">{{ $settings->site_name ?? 'UOnly' }}</h5>
            <small class="text-muted">User Panel</small>
        </div>
    </div>

    <div class="sidebar-menu flex-grow-1 overflow-auto py-3 px-2">
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-home me-3" style="width: 20px;"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item mt-3 mb-2 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Benefits & Cards</li>
            
            <li class="nav-item">
                <a href="{{ route('user.ecard.show') }}" class="nav-link {{ request()->routeIs('user.ecard.*') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-id-card me-3" style="width: 20px;"></i> My e-Card
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.wallet.show') }}" class="nav-link {{ request()->routeIs('user.wallet.*') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-wallet me-3" style="width: 20px;"></i> My Wallet
                </a>
            </li>

            <li class="nav-item mt-3 mb-2 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Services</li>

            <li class="nav-item">
                <a href="#rechargeSubmenu" data-bs-toggle="collapse" class="nav-link text-secondary px-3 py-2 rounded-3 d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-bolt me-3" style="width: 20px;"></i> Recharge & Bills</span>
                    <i class="fas fa-chevron-down small"></i>
                </a>
                <div class="collapse {{ request()->routeIs('user.service.recharge.*') ? 'show' : '' }}" id="rechargeSubmenu">
                    <ul class="nav flex-column ms-3 mt-1 ps-3 border-start">
                        <li class="nav-item">
                            <a href="{{ route('user.service.recharge.mobile') }}" class="nav-link {{ request()->routeIs('user.service.recharge.mobile') ? 'text-primary fw-semibold' : 'text-secondary' }} py-1 small">Mobile Recharge</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.service.recharge.dth') }}" class="nav-link {{ request()->routeIs('user.service.recharge.dth') ? 'text-primary fw-semibold' : 'text-secondary' }} py-1 small">DTH Recharge</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.service.recharge.fastag') }}" class="nav-link {{ request()->routeIs('user.service.recharge.fastag') ? 'text-primary fw-semibold' : 'text-secondary' }} py-1 small">FASTag</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.service.recharge.utility.link') }}" class="nav-link {{ request()->routeIs('user.service.recharge.utility.link') ? 'text-primary fw-semibold' : 'text-secondary' }} py-1 small">Bill Payments</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="#benefitsSubmenu" data-bs-toggle="collapse" class="nav-link text-secondary px-3 py-2 rounded-3 d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-hand-holding-heart me-3" style="width: 20px;"></i> Benefits</span>
                    <i class="fas fa-chevron-down small"></i>
                </a>
                <div class="collapse {{ request()->routeIs('user.benefit.*') ? 'show' : '' }}" id="benefitsSubmenu">
                    <ul class="nav flex-column ms-3 mt-1 ps-3 border-start">
                        <li class="nav-item">
                            <a href="{{ route('user.benefit.eligible.report') }}" class="nav-link {{ request()->routeIs('user.benefit.eligible.report') ? 'text-primary fw-semibold' : 'text-secondary' }} py-1 small">Eligibility Report</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.benefit.bookcamp.show') }}" class="nav-link {{ request()->routeIs('user.benefit.bookcamp.*') ? 'text-primary fw-semibold' : 'text-secondary' }} py-1 small">Book Camp</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.benefit.blood.dashboard') }}" class="nav-link {{ request()->routeIs('user.benefit.blood.*') ? 'text-primary fw-semibold' : 'text-secondary' }} py-1 small">Blood Seva</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mt-3 mb-2 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Account</li>

            <li class="nav-item">
                <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-user-circle me-3" style="width: 20px;"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.upgrade.show') }}" class="nav-link {{ request()->routeIs('user.upgrade.*') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-arrow-circle-up me-3" style="width: 20px;"></i> Upgrade ID
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.kyc.show') }}" class="nav-link {{ request()->routeIs('user.kyc.*') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-file-upload me-3" style="width: 20px;"></i> Upload KYC
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.password.show') }}" class="nav-link {{ request()->routeIs('user.password.*') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-key me-3" style="width: 20px;"></i> Change Password
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.pin.change.show') }}" class="nav-link {{ request()->routeIs('user.pin.*') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-th me-3" style="width: 20px;"></i> Change PIN
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.login_history') }}" class="nav-link {{ request()->routeIs('user.login_history') ? 'active bg-primary-subtle text-primary fw-semibold' : 'text-secondary' }} px-3 py-2 rounded-3">
                    <i class="fas fa-history me-3" style="width: 20px;"></i> Login History
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('user.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-danger w-100 text-start border-0 bg-transparent px-3 py-2 rounded-3">
                        <i class="fas fa-sign-out-alt me-3" style="width: 20px;"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer p-3 border-top">
        <div class="d-flex align-items-center gap-3 p-2 rounded-3 bg-light">
            <div class="avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="fas fa-user"></i>
            </div>
            <div class="overflow-hidden">
                <h6 class="m-0 text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name ?? 'User' }}</h6>
                <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->user_id ?? '' }}</small>
            </div>
        </div>
    </div>
</div>
