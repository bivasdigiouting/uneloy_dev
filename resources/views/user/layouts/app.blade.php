<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'User Portal - UOnly')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->member_app_favicon ? asset('storage/'.$settings->member_app_favicon) : ($settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico')) }}">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    @stack('styles')
    <style>
        body { background: var(--bg-light); }
        .page-footer { border-top: 1px solid #e9ecef; background: var(--card-bg); color: var(--text-dark); }
        .page-footer a { color: var(--pink-highlight); }
        
        /* Desktop Layout Adjustments */
        @media (min-width: 992px) {
            .desktop-layout-wrapper {
                margin-left: 280px; /* Matches sidebar width */
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Navbar (Hidden on Desktop) -->
    @if(trim($__env->yieldContent('hide_mobile_navbar')) === '')
        <div class="d-lg-none">
            @include('user.partials.navbar')
        </div>
    @endif

    <!-- Desktop Sidebar (Hidden on Mobile) -->
    <div class="d-none d-lg-block">
        @include('user.partials.desktop-sidebar')
    </div>

    <!-- Main Content Wrapper -->
    <div class="desktop-layout-wrapper">
        
        <!-- Desktop Header (Hidden on Mobile) -->
        @if(trim($__env->yieldContent('hide_desktop_header')) === '')
            <div class="d-none d-lg-block">
                @include('user.partials.desktop-header')
            </div>
        @endif

        <!-- Page Content -->
        <main class="py-4 flex-grow-1">
            <div class="container-fluid px-4">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="page-footer py-3 mt-auto">
            <div class="container-fluid px-4 d-sm-flex align-items-center justify-content-between">
                <p class="mb-0">&copy; {{ date('Y') }} Uonly Solutions.</p>
                <p class="mb-0">Designed &amp; Developed By <a href="javascript:void(0);" class="text-primary">Digiouting LLP</a></p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
    @stack('scripts')
</body>
</html>
