<div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3 dashboard-section">
	<div class="my-auto mb-2">
		<h2 class="mb-1">User Details</h2>						
	</div>					
</div>
<div class="row">

    <!-- Total Users -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-primary flex-shrink-0">
                        <i class="ti ti-users fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">Total Users</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['total_users'] ?? 0 }}</h4>
                        @if(isset($userStats['user_growth_percentage']) && $userStats['user_growth_percentage'] != 0)
                            <span class="badge {{ $userStats['user_growth_percentage'] > 0 ? 'badge-success-transparent' : 'badge-danger-transparent' }} fs-10">
                                <i class="ti ti-trending-{{ $userStats['user_growth_percentage'] > 0 ? 'up' : 'down' }} me-1"></i>
                                {{ abs($userStats['user_growth_percentage']) }}%
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Total Users -->

    <!-- Total Balance -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-success flex-shrink-0">
                        <i class="ti ti-wallet fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">Total Balance</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['balance_formatted'] ?? '₹0.00' }}</h4>
                        <span class="badge badge-success-transparent fs-10">
                            <i class="ti ti-currency-rupee me-1"></i>
                            Active Wallets
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Total Balance -->

    <!-- Total Bonus -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-warning flex-shrink-0">
                        <i class="ti ti-gift fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">Total Bonus</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['bonus_formatted'] ?? '₹0.00' }}</h4>
                        <span class="badge badge-warning-transparent fs-10">
                            <i class="ti ti-star me-1"></i>
                            Rewards
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Total Bonus -->

    <!-- Total Cashback -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-info flex-shrink-0">
                        <i class="ti ti-cash-banknote fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">Total Cashback</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['cashback_formatted'] ?? '₹0.00' }}</h4>
                        <span class="badge badge-info-transparent fs-10">
                            <i class="ti ti-percentage me-1"></i>
                            Earned
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Total Cashback -->

</div>

<!-- Second Row for Total Orders and Additional Metrics -->
<div class="row mt-3">
    
    <!-- Total Orders -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-purple flex-shrink-0">
                        <i class="ti ti-shopping-cart fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">Total Orders</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['total_orders'] ?? 0 }}</h4>
                        <span class="badge badge-purple-transparent fs-10">
                            <i class="ti ti-package me-1"></i>
                            Processed
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Total Orders -->

    <!-- Active Users -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-success flex-shrink-0">
                        <i class="ti ti-user-check fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">Active Users</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['active_users'] ?? 0 }}</h4>
                        <span class="badge badge-success-transparent fs-10">
                            <i class="ti ti-circle-check me-1"></i>
                            Online
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Active Users -->

    <!-- Verified Users -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-primary flex-shrink-0">
                        <i class="ti ti-shield-check fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">Verified Users</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['verified_users'] ?? 0 }}</h4>
                        <span class="badge badge-primary-transparent fs-10">
                            <i class="ti ti-mail-check me-1"></i>
                            Verified
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Verified Users -->

    <!-- Recent Registrations -->
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill dashboard-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                    <span class="avatar avatar-lg bg-orange flex-shrink-0">
                        <i class="ti ti-user-plus fs-16"></i>
                    </span>
                    <div class="ms-2 overflow-hidden">
                        <p class="fs-12 fw-medium mb-1 text-truncate">New Users (30d)</p>
                        <h4 class="mb-1 counter-animation">{{ $userStats['recent_registrations'] ?? 0 }}</h4>
                        <span class="badge badge-orange-transparent fs-10">
                            <i class="ti ti-calendar me-1"></i>
                            This Month
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Recent Registrations -->

</div>