<header class="desktop-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between sticky-top">
    <div class="d-flex align-items-center gap-3">
        <h4 class="m-0 fw-bold text-dark">
            @yield('page_title', 'Dashboard')
        </h4>
        @hasSection('page_subtitle')
            <span class="text-muted border-start ps-3">@yield('page_subtitle')</span>
        @endif
    </div>

    <div class="header-actions d-flex align-items-center gap-3">
        <!-- Search -->
        <div class="input-group" style="width: 250px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control bg-light border-start-0 ps-0" placeholder="Search services...">
        </div>

        <!-- Notifications -->
        <button class="btn btn-light rounded-circle position-relative" style="width: 40px; height: 40px;">
            <i class="far fa-bell text-muted"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                3
            </span>
        </button>

        <!-- Help -->
        <a href="{{ route('help-support.index') }}" class="btn btn-light rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;" title="Help & Support">
            <i class="far fa-question-circle text-muted"></i>
        </a>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-white border border-light shadow-sm rounded-pill px-3 py-1 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 14px;">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <span class="small fw-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                <i class="fas fa-chevron-down small text-muted"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3" style="min-width: 200px;">
                <li><h6 class="dropdown-header">Account</h6></li>
                <li><a class="dropdown-item rounded-2" href="{{ route('user.profile') }}"><i class="fas fa-user me-2 text-muted"></i> My Profile</a></li>
                <li><a class="dropdown-item rounded-2" href="{{ route('user.wallet.show') }}"><i class="fas fa-wallet me-2 text-muted"></i> Wallet</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('user.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-2 text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
