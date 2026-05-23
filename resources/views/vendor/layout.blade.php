<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vendor Portal')</title>
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->estore_app_favicon ? asset('storage/'.$settings->estore_app_favicon) : ($settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico')) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.2);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
            color: #1e293b;
        }
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
            border: 2px solid #f0f2f5;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .text-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $activePage = $activePage ?? null;

    $pageTitleMap = [
        'vendor.dashboard' => 'Dashboard',
        'vendor.billing' => 'Billing',
        'vendor.products' => 'Products',
        'vendor.inventory' => 'Inventory',
        'vendor.payments' => 'Payments',
        'vendor.ads' => 'Ads & Promotions',
        'vendor.camping' => 'Free Camping',
        'vendor.settlements' => 'Settlements',
        'vendor.staff' => 'Staff',
        'vendor.payroll' => 'Payroll',
        'vendor.reports' => 'Reports',
        'vendor.profile' => 'Profile',
        'vendor.settings' => 'Settings',
    ];

    $resolvedTitle = $activePage ?: ($pageTitleMap[$currentRoute] ?? 'Vendor Portal');
@endphp

<div class="flex h-screen overflow-hidden" id="vendorShell">
    <div id="vendorOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden"></div>

    <aside id="vendorSidebar" class="fixed inset-y-0 left-0 bg-slate-900 text-white transition-all duration-500 ease-in-out w-72 flex flex-col z-50 -translate-x-full md:translate-x-0 md:relative md:inset-auto">
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-900/20 to-transparent pointer-events-none"></div>

        <div class="p-6 md:p-8 flex items-center justify-between relative z-10">
            <a href="{{ route('vendor.dashboard') }}" class="text-2xl font-extrabold tracking-tighter text-white flex items-center">
                E-SERVICE <span class="text-indigo-400 ml-1">MALL</span>
            </a>
            <button type="button" id="vendorSidebarToggle" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                <i data-lucide="x" class="w-[18px] h-[18px]"></i>
            </button>
        </div>

        <nav class="flex-1 mt-4 px-4 space-y-2 overflow-y-auto custom-scrollbar relative z-10">
            <p class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Main Menu</p>

            @php
                $navItems = [
                    ['name' => 'Dashboard', 'route' => 'vendor.dashboard', 'icon' => 'layout-dashboard'],
                    ['name' => 'Billing', 'route' => 'vendor.billing', 'icon' => 'shopping-cart'],
                    ['name' => 'Products', 'route' => 'vendor.products', 'icon' => 'package'],
                    ['name' => 'Inventory', 'route' => 'vendor.inventory', 'icon' => 'bar-chart-3'],
                    ['name' => 'Payments', 'route' => 'vendor.payments', 'icon' => 'credit-card'],
                    ['name' => 'Ads & Promotions', 'route' => 'vendor.ads', 'icon' => 'megaphone'],
                    ['name' => 'Free Camping', 'route' => 'vendor.camping', 'icon' => 'tent'],
                    ['name' => 'Settlements', 'route' => 'vendor.settlements', 'icon' => 'wallet'],
                    ['name' => 'Staff', 'route' => 'vendor.staff', 'icon' => 'users'],
                    ['name' => 'Payroll', 'route' => 'vendor.payroll', 'icon' => 'banknote'],
                    ['name' => 'Reports', 'route' => 'vendor.reports', 'icon' => 'file-text'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a
                    href="{{ route($item['route']) }}"
                    class="flex items-center p-3.5 rounded-2xl transition-all group relative {{ $isActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/40' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                    data-vendor-sidebar-label
                >
                    <span class="shrink-0 transition-transform duration-300 {{ $isActive ? 'scale-110' : 'group-hover:scale-110' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                    </span>
                    <span class="ml-4 font-semibold tracking-tight vendor-label">{{ $item['name'] }}</span>
                    @if($isActive)
                        <div class="ml-auto w-1.5 h-1.5 bg-white rounded-full vendor-label"></div>
                    @endif
                </a>
            @endforeach

            <p class="px-4 py-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Account</p>

            <a
                href="{{ route('vendor.profile') }}"
                class="flex items-center p-3.5 rounded-2xl transition-all group {{ request()->routeIs('vendor.profile') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
            >
                <i data-lucide="user-circle" class="w-5 h-5"></i>
                <span class="ml-4 font-semibold tracking-tight vendor-label">Profile</span>
            </a>

            <a
                href="{{ route('vendor.settings') }}"
                class="flex items-center p-3.5 rounded-2xl transition-all group {{ request()->routeIs('vendor.settings') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
            >
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span class="ml-4 font-semibold tracking-tight vendor-label">Settings</span>
            </a>
        </nav>

        <div class="p-6 relative z-10">
            <div class="flex items-center p-3 rounded-2xl bg-slate-800/50 border border-slate-700/50">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center font-bold shadow-inner shrink-0 text-slate-900">
                    {{ strtoupper(substr($vendor->business_name ?? $vendor->first_name ?? 'V', 0, 1).substr($vendor->last_name ?? 'P', 0, 1)) }}
                </div>
                <div class="ml-3 overflow-hidden vendor-label">
                    <p class="text-sm font-bold truncate">{{ $vendor->business_name ?? trim(($vendor->first_name ?? '').' '.($vendor->last_name ?? '')) }}</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight truncate">{{ $vendor->vendor_number ?? 'Vendor' }}</p>
                </div>
                <form method="POST" action="{{ route('vendor.logout') }}" class="ml-auto vendor-label">
                    @csrf
                    <button type="submit" class="text-slate-500 hover:text-rose-400 p-1">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden relative">
        <header class="glass border-b border-slate-200/50 flex items-center justify-between px-6 md:px-10 sticky top-0 z-40 h-auto md:h-24 py-4 md:py-0 gap-6">
            <div class="flex items-start gap-4 min-w-0">
                <button type="button" id="vendorMobileToggle" class="md:hidden shrink-0 p-3 bg-white rounded-2xl border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition-all card-shadow">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="flex flex-col min-w-0">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight truncate">{{ $resolvedTitle }}</h1>
                    <p class="text-xs text-slate-400 font-medium hidden sm:block">Welcome back. Here's what's happening today.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-6 shrink-0">
                <div class="hidden lg:flex items-center relative group">
                    <i data-lucide="search" class="absolute left-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors w-[18px] h-[18px]"></i>
                    <input
                        type="text"
                        placeholder="Search..."
                        class="pl-12 pr-6 py-3 bg-white/80 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 w-80 outline-none transition-all card-shadow"
                    />
                </div>

                <button type="button" class="relative p-3 bg-white rounded-2xl border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition-all card-shadow">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                </button>

                <a href="{{ route('vendor.billing') }}" class="bg-slate-900 text-white px-6 py-3.5 rounded-2xl font-bold shadow-xl shadow-slate-900/20 flex items-center space-x-3 transition-all active:scale-95 hover:bg-indigo-600 group">
                    <i data-lucide="shopping-cart" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                    <span class="hidden sm:inline">Create Invoice</span>
                    <i data-lucide="chevron-right" class="hidden sm:inline w-[18px] h-[18px] opacity-50"></i>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            @yield('content')
        </div>
    </main>
</div>

<script src="https://unpkg.com/lucide@0.541.0/dist/umd/lucide.min.js"></script>
<script>
    (function () {
        const sidebar = document.getElementById('vendorSidebar');
        const toggle = document.getElementById('vendorSidebarToggle');
        const mobileToggle = document.getElementById('vendorMobileToggle');
        const overlay = document.getElementById('vendorOverlay');
        const storageKey = 'vendor_sidebar_open';
        const md = window.matchMedia('(min-width: 768px)');

        const setMobileOpen = (open) => {
            if (!sidebar || !overlay) return;
            if (open) {
                sidebar.classList.add('translate-x-0');
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.documentElement.classList.add('overflow-hidden');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.documentElement.classList.remove('overflow-hidden');
            }
        };

        const setCollapsed = (collapsed) => {
            if (!sidebar) return;
            if (collapsed) {
                sidebar.classList.remove('w-72');
                sidebar.classList.add('w-24');
                document.querySelectorAll('.vendor-label').forEach((el) => el.classList.add('hidden'));
            } else {
                sidebar.classList.remove('w-24');
                sidebar.classList.add('w-72');
                document.querySelectorAll('.vendor-label').forEach((el) => el.classList.remove('hidden'));
            }
            localStorage.setItem(storageKey, collapsed ? '0' : '1');
        };

        const initial = localStorage.getItem(storageKey);
        if (initial === '0' && md.matches) {
            setCollapsed(true);
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                if (md.matches) {
                    const isCollapsed = sidebar.classList.contains('w-24');
                    setCollapsed(!isCollapsed);
                } else {
                    setMobileOpen(false);
                }
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            });
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function () {
                if (!sidebar) return;
                const isOpen = sidebar.classList.contains('translate-x-0');
                setMobileOpen(!isOpen);
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function () {
                setMobileOpen(false);
            });
        }

        md.addEventListener('change', function (e) {
            if (e.matches) {
                if (overlay) overlay.classList.add('hidden');
                document.documentElement.classList.remove('overflow-hidden');
                const initial2 = localStorage.getItem(storageKey);
                setCollapsed(initial2 === '0');
            } else {
                setMobileOpen(false);
            }
        });

        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    })();
</script>
</body>
</html>
