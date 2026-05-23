<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('user.dashboard') }}">
            <img src="{{ $settings && $settings->member_app_logo ? asset('storage/'.$settings->member_app_logo) : ($settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/logo.png')) }}" alt="Logo" height="30" class="me-2">
            <span>{{ $settings->site_name ?? 'UOnly Portal' }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user me-1"></i>My Profile</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenu">
                        <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile Update</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.password.show') }}">Change Password</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.pin.change.show') }}">Change PIN</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.upgrade.show') }}">Upgrade ID</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.kyc.show') }}">Upload KYC</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.login_history') }}">Login History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST" class="px-3">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger w-100" type="submit"><i class="fas fa-sign-out-alt me-1"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="walletMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-wallet me-1"></i>Wallet</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="walletMenu">
                        <li><a class="dropdown-item" href="{{ route('user.wallet.request.show') }}">Wallet Request</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.wallet.transactions') }}">Wallet Transactions</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('user.wallet.settlement.show') }}">Bank Settlement</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.wallet.transfer.qr.show') }}">QR to QR Transfer</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.wallet.transfer.user.show') }}">User to User Transfer</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="advertisementMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bullhorn me-1"></i>Advertisement</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="advertisementMenu">
                        <li><a class="dropdown-item" href="{{ route('user.advertisement.show') }}">Advertisement</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.advertisement.report') }}">Advertisement Report</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="benefitMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-hand-holding-heart me-1"></i>Benefit</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="benefitMenu">
                        <li><a class="dropdown-item" href="{{ route('user.benefit.bookcamp.show') }}">Book Camp</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.bookcamp.report') }}">Book Camp Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.eligible.report') }}">Eligible Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.schemefund.report') }}">Scheme Fund Report</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.ecard.seva.request.show') }}">E-Card Seva Request</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.ecard.seva.self.report') }}">E-Card Seva Self Req. Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.ecard.seva.other.details') }}">E-Card Seva Other Req. Details</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.blood.dashboard') }}">Blood Seva</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.blood.self.report') }}">Blood Donate Self Req. Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.blood.other.details') }}">Blood Donate Other Req. Details</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.emergency.ecard.request.show') }}">Emergency E-Card Seva Request</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.emergency.ecard.self.report') }}">Emergency E-Card Self Req. Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.emergency.ecard.other.details') }}">Emergency E-Card Other Req. Details</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.benefit.emergency.family.contacts') }}">Emergency Family Contact List</a></li>
                    </ul>
                </li>
                <!-- Service Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="serviceMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-concierge-bell me-1"></i>Service</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="serviceMenu">
                        <li><a class="dropdown-item" href="{{ route('user.service.orders.view') }}">View Orders</a></li>
                    </ul>
                </li>
                <!-- Report Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-chart-line me-1"></i>Report</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reportMenu">
                        <li><a class="dropdown-item" href="{{ route('user.service.report.admin.points') }}">Admin by Points Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.report.vendor.points') }}">Vendor by Points Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.report.coupon.summary') }}">Coupon Summary Detail Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.report.voucher.detail') }}">Voucher Detail Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.report.global.disbursement.fund') }}">Global Disbur. Fund Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.report.physically.challenged.fund') }}">Physically Challenged Fund Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.report.monthwise.user.redeem') }}">M. Wise User Redeem Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.report.reward') }}">Reward Report</a></li>
                    </ul>
                </li>
                <!-- Recharge Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="rechargeMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bolt me-1"></i>Recharge</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="rechargeMenu">
                        <li><a class="dropdown-item" href="{{ route('user.service.recharge.mobile') }}">Mobile Recharge</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.recharge.dth') }}">DTH Recharge</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.recharge.fastag') }}">FASTag Recharge</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.recharge.report') }}">Recharge Report</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.service.recharge.utility.link') }}">Utility Link</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
