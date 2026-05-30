<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('ecard.dashboard') }}" class="logo logo-normal">
            @if($settings && $settings->ecardseva_logo)
                <img src="{{ asset('storage/'.$settings->ecardseva_logo) }}" alt="Logo">
            @elseif($settings && $settings->logo)
                <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo">
            @else
                <span class="text-white fw-bold fs-4">E-Card</span>
            @endif
        </a>
        <a href="{{ route('ecard.dashboard') }}" class="logo logo-white">
            @if($settings && $settings->ecardseva_logo)
                <img src="{{ asset('storage/'.$settings->ecardseva_logo) }}" alt="Logo">
            @elseif($settings && $settings->logo)
                <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo">
            @else
                <span class="text-white fw-bold fs-4">E-Card</span>
            @endif
        </a>
        <a href="{{ route('ecard.dashboard') }}" class="logo-small">
                @if($settings && $settings->ecardseva_favicon)
                <img src="{{ asset('storage/'.$settings->ecardseva_favicon) }}" alt="Logo">
            @elseif($settings && $settings->favicon)
                <img src="{{ asset('storage/'.$settings->favicon) }}" alt="Logo">
            @else
                <span class="text-white fw-bold fs-4">E</span>
            @endif
        </a>
        <a href="{{ route('ecard.dashboard') }}" class="logo-small-white">
            @if($settings && $settings->ecardseva_favicon)
                <img src="{{ asset('storage/'.$settings->ecardseva_favicon) }}" alt="Logo">
            @elseif($settings && $settings->favicon)
                <img src="{{ asset('storage/'.$settings->favicon) }}" alt="Logo">
            @else
                <span class="text-white fw-bold fs-4">E</span>
            @endif
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @php
                    $userModuleVisible =
                        \App\Helpers\ECardPermission::canView('ecard.registration.create') ||
                        \App\Helpers\ECardPermission::canView('ecard.users.my') ||
                        \App\Helpers\ECardPermission::canView('ecard.kyc.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.users.report.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.users.report.print');
                    $kycModuleVisible = \App\Helpers\ECardPermission::canView('ecard.kyc.approve.index');
                    $upgradeModuleVisible =
                        \App\Helpers\ECardPermission::canView('ecard.upgrade.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.upgrade.report.index');
                    $walletModuleVisible =
                        \App\Helpers\ECardPermission::canView('ecard.wallet.settlement.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.wallet.request.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.wallet.transactions.index');
                    $productModuleVisible =
                        \App\Helpers\ECardPermission::canView('ecard.product.stock.request.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.product.ar.stock.request.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.product.ar.stock.report.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.product.stock.report.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.sales.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.products.index');
                    $reportModuleVisible =
                        \App\Helpers\ECardPermission::canView('ecard.report.level-commission.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.report.login-history.index');
                    $benefitsModuleVisible =
                        \App\Helpers\ECardPermission::canView('ecard.benefit.schemefund.report') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.bookcamp.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.bookcamp.report') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.ecardseva.request.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.ecs.self.report') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.ecs.other.details') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.blooddonate.request.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.bd.self.report') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.bd.other.details') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.emergency.ecs.request.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.emergency.ecs.report') ||
                        \App\Helpers\ECardPermission::canView('ecard.benefit.emergency.eco.details');
                    $advertModuleVisible =
                        \App\Helpers\ECardPermission::canView('ecard.advertisement.index') ||
                        \App\Helpers\ECardPermission::canView('ecard.advertisement.report.index');
                @endphp

                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="{{ request()->routeIs('ecard.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('ecard.dashboard') }}">
                                <i data-feather="grid"></i><span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="submenu-open">
                    <h6 class="submenu-hdr">MASTER HUB</h6>
                    <ul>
                        <li class="{{ request()->routeIs('ecard.emergency.index') ? 'active' : '' }}">
                            <a href="{{ route('ecard.emergency.index') }}">
                                <i data-feather="activity"></i><span>Emergency Desk</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('ecard.blood.index') ? 'active' : '' }}">
                            <a href="{{ route('ecard.blood.index') }}">
                                <i data-feather="heart"></i><span>Blood Support</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('ecard.digital-wallet.index') ? 'active' : '' }}">
                            <a href="{{ route('ecard.digital-wallet.index') }}">
                                <i data-feather="credit-card"></i><span>Digital Wallet</span>
                            </a>
                        </li>

                        @if(\App\Helpers\ECardPermission::canView('ecard.recharge.mobile') || \App\Helpers\ECardPermission::canView('ecard.recharge.bbps'))
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i data-feather="file-invoice"></i><span>Recharge &amp; Bills</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                @if(\App\Helpers\ECardPermission::canView('ecard.recharge.mobile'))
                                <li class="{{ request()->routeIs('ecard.recharge.mobile') ? 'active' : '' }}"><a href="{{ route('ecard.recharge.mobile') }}">Mobile Recharge</a></li>
                                @endif
                                @if(\App\Helpers\ECardPermission::canView('ecard.recharge.dth'))
                                <li class="{{ request()->routeIs('ecard.recharge.dth') ? 'active' : '' }}"><a href="{{ route('ecard.recharge.dth') }}">DTH Recharge</a></li>
                                @endif
                                @if(\App\Helpers\ECardPermission::canView('ecard.recharge.fastag'))
                                <li class="{{ request()->routeIs('ecard.recharge.fastag') ? 'active' : '' }}"><a href="{{ route('ecard.recharge.fastag') }}">FASTag Recharge</a></li>
                                @endif
                                @if(\App\Helpers\ECardPermission::canView('ecard.recharge.bbps'))
                                <li class="{{ request()->routeIs('ecard.recharge.bbps') ? 'active' : '' }}"><a href="{{ route('ecard.recharge.bbps') }}?category=electricity">Bill Payments (BBPS)</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif



                @if($userModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="users"></i><span>User Module</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.registration.create'))
                        <li><a href="{{ route('ecard.registration.create') }}">New Registration</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.users.my'))
                        <li><a href="{{ route('ecard.users.my') }}">My User Details</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.kyc.index'))
                        <li><a href="{{ route('ecard.kyc.index') }}">Upload KYC Documents</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.users.report.index'))
                        <li><a href="{{ route('ecard.users.report.index') }}">User e-card Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.users.report.print'))
                            @php
                                $canIndex = \App\Helpers\ECardPermission::canView('ecard.users.report.index');
                                $printId = auth('ecard')->id() ?? auth()->id();
                            @endphp
                            @if($canIndex)
                                <li><a href="{{ route('ecard.users.report.index') }}">User e-card Print Report</a></li>
                            @elseif($printId)
                                <li><a href="{{ route('ecard.users.report.print', ['id' => $printId]) }}">My e-card Print</a></li>
                            @endif
                        @endif
                        <li><a href="#">ID Card List</a></li>
                        <li><a href="#">Certificate</a></li>
                        <li><a href="#">E Card Authorization Letter</a></li>
                        <li><a href="#">Accept Agreement Letter</a></li>
                    </ul>
                </li>
                @endif

                @if($kycModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="file-text"></i><span>KYC Module</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.kyc.approve.index'))
                        <li><a href="{{ route('ecard.kyc.approve.index') }}">Approve KYC Documents</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if($upgradeModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="trending-up"></i><span>Upgrade Module</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.upgrade.index'))
                        <li><a href="{{ route('ecard.upgrade.index') }}">User Upgrade Id</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.upgrade.report.index'))
                        <li><a href="{{ route('ecard.upgrade.report.index') }}">Upgrade Report</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if($walletModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="dollar-sign"></i><span>Wallet System</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.wallet.settlement.index'))
                        <li><a href="{{ route('ecard.wallet.settlement.index') }}">Bank Settlement Request</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.wallet.request.index'))
                        <li><a href="{{ route('ecard.wallet.request.index') }}">Wallet Request</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.wallet.transactions.index'))
                        <li><a href="{{ route('ecard.wallet.transactions.index') }}">Wallet Transaction Detail</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if($productModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="shopping-cart"></i><span>Product Management</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.product.stock.request.index'))
                        <li><a href="{{ route('ecard.product.stock.request.index') }}">Product Stock Request</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.product.ar.stock.request.index'))
                        <li><a href="{{ route('ecard.product.ar.stock.request.index') }}">A &amp; R Product Stock Request</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.product.ar.stock.report.index'))
                        <li><a href="{{ route('ecard.product.ar.stock.report.index') }}">A &amp; R Product Stock Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.product.stock.report.index'))
                        <li><a href="{{ route('ecard.product.stock.report.index') }}">Stock Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.sales.index'))
                        <li><a href="{{ route('ecard.sales.index') }}">Sales</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.products.index'))
                        <li><a href="{{ route('ecard.products.index') }}">Products</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @php
                    $isCommissionActive = request()->routeIs('ecard.report.registration-commission.*') ||
                                          request()->routeIs('ecard.report.wallet-commission.*') ||
                                          request()->routeIs('ecard.report.purchase-commission.*') ||
                                          request()->routeIs('ecard.report.eps-commission.*');
                @endphp
                <li class="submenu {{ $isCommissionActive ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="{{ $isCommissionActive ? 'active' : '' }}">
                        <i data-feather="file-minus"></i><span>Specific Commissions </span><span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ $isCommissionActive ? 'display: block;' : '' }}">
                        <li><a href="{{ route('ecard.report.registration-commission.index') }}" class="{{ request()->routeIs('ecard.report.registration-commission.*') ? 'active' : '' }}">Registration Commission</a></li>
                        <li><a href="{{ route('ecard.report.wallet-commission.index') }}" class="{{ request()->routeIs('ecard.report.wallet-commission.*') ? 'active' : '' }}">Wallet Commission</a></li>
                        <li><a href="{{ route('ecard.report.purchase-commission.index') }}" class="{{ request()->routeIs('ecard.report.purchase-commission.*') ? 'active' : '' }}">Purchase Commission</a></li>
                        <li><a href="{{ route('ecard.report.eps-commission.index') }}" class="{{ request()->routeIs('ecard.report.eps-commission.*') ? 'active' : '' }}">E.P.S Commission</a></li>
                    </ul>
                </li>

                @if($reportModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="file-minus"></i><span>Report Module</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.report.tds-report.index'))
                        <li><a href="{{ route('ecard.report.tds-report.index') }}" class="{{ request()->routeIs('ecard.report.tds-report.*') ? 'active' : '' }}">TDS report</a></li>
                        @else
                        <li><a href="{{ route('ecard.report.tds-report.index') }}" class="{{ request()->routeIs('ecard.report.tds-report.*') ? 'active' : '' }}">TDS report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.report.level-commission.index'))
                        <li><a href="{{ route('ecard.report.level-commission.index') }}">Level Commission Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.report.login-history.index'))
                        <li><a href="{{ route('ecard.report.login-history.index') }}">My Login History</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if($benefitsModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="gift"></i><span>Benefits Module</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.schemefund.report'))
                        <li><a href="{{ route('ecard.benefit.schemefund.report') }}">Global Disbur. Fund Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.bookcamp.index'))
                        <li><a href="{{ route('ecard.benefit.bookcamp.index') }}">Book Camp</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.bookcamp.report'))
                        <li><a href="{{ route('ecard.benefit.bookcamp.report') }}">Book Camp Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.ecardseva.request.index'))
                        <li><a href="{{ route('ecard.benefit.ecardseva.request.index') }}">E-Card Seva Request</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.ecs.self.report'))
                        <li><a href="{{ route('ecard.benefit.ecs.self.report') }}">ECS Self Req. Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.ecs.other.details'))
                        <li><a href="{{ route('ecard.benefit.ecs.other.details') }}">ECS Other Req. Details</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.blooddonate.request.index'))
                        <li><a href="{{ route('ecard.benefit.blooddonate.request.index') }}">Blood Donate Request</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.bd.self.report'))
                        <li><a href="{{ route('ecard.benefit.bd.self.report') }}">BD Self Req. Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.bd.other.details'))
                        <li><a href="{{ route('ecard.benefit.bd.other.details') }}">BD Other Req. Details</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.emergency.ecs.request.index'))
                        <li><a href="{{ route('ecard.benefit.emergency.ecs.request.index') }}">Emergency ECS Request</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.emergency.ecs.report'))
                        <li><a href="{{ route('ecard.benefit.emergency.ecs.report') }}">Emergency ECS Req. Report</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.benefit.emergency.eco.details'))
                        <li><a href="{{ route('ecard.benefit.emergency.eco.details') }}">Emergency ECO Req. Details</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if($advertModuleVisible)
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="tv"></i><span>Advertisement Module</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if(\App\Helpers\ECardPermission::canView('ecard.advertisement.index'))
                        <li><a href="{{ route('ecard.advertisement.index') }}">Advertisement</a></li>
                        @endif
                        @if(\App\Helpers\ECardPermission::canView('ecard.advertisement.report.index'))
                        <li><a href="{{ route('ecard.advertisement.report.index') }}">Advertisement Report</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
