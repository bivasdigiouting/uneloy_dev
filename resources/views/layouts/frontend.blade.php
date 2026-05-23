<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'U Only - Infinite Solutions')</title>
    <meta name="description" content="@yield('description', 'Uonely Solutions Pvt. Ltd. - A Company that provide, infinite solutions')">
    <meta name="keywords" content="@yield('keywords', '')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico') }}">
    
    <!-- CSS Styles -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/custom_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/responsive_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/popup_display.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/browser.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/font-awesome.min.css') }}">
    
    <!-- Navigation Styles -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/resnav.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-bar">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/logo.png') }}" alt="{{ $settings->site_name ?? 'Uonely Solutions Pvt. Ltd.' }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="enquire-btn-outer">
                            <nav class="contact-nav">
                                <ul>
                                    <li><a href="#" class="join"><img src="{{ asset('frontend-assets/design_img/join-us-icon.png') }}">Registration</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        @include('partials.mobile-menu')
        
        <!-- Primary Menu -->
        <div class="menu-bar d-none d-md-block">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="nav-bar">
                            @php
                                $menuService = app(\App\Services\MenuService::class);
                                $primaryMenuHtml = $menuService->renderPrimaryMenu();
                            @endphp
                            {!! $primaryMenuHtml !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @include('partials.breadcrumbs')
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-bar">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <a href="{{ url('/') }}" class="logo">
                            <img src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/footer-logo.png') }}" alt="{{ $settings->site_name ?? 'Uonely Solutions Pvt. Ltd.' }}">
                        </a>
                        <p>{{ $settings->footer_text ?? 'Uonely Solutions Pvt. Ltd. - A Company that provide, infinite solutions' }}</p>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <h3>Quick Links</h3>
                        @php
                            $footerMenuHtml = $menuService->renderFooterMenu();
                        @endphp
                        {!! $footerMenuHtml !!}
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <h3>Contact Info</h3>
                        <div class="contact-info">
                            <p><i class="fa fa-map-marker"></i> Your Address Here</p>
                            <p><i class="fa fa-phone"></i> +91 XXXXXXXXXX</p>
                            <p><i class="fa fa-envelope"></i> info@uonly.com</p>
                        </div>
                        
                        <div class="app-download">
                            <h4>Download Our App</h4>
                            <a href="#" class="app-link">
                                <img src="{{ asset('frontend-assets/design_img/play-store.png') }}" alt="Google Play Store">
                            </a>
                            <a href="#" class="app-link">
                                <img src="{{ asset('frontend-assets/design_img/app-store.png') }}" alt="App Store">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bar-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="copy">&copy; <span class="copy-year">{{ date('Y') }}</span> All Rights Reserved at <span class="co-name">Uonely Solutions Pvt. Ltd.</span></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('frontend-assets/design_js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/design_js/bootstrap.min.js') }}"></script>
    
    @stack('scripts')
    
    <script>
        // Update copyright year
        document.addEventListener('DOMContentLoaded', function() {
            const yearSpan = document.querySelector('.copy-year');
            if (yearSpan) {
                yearSpan.textContent = new Date().getFullYear();
            }
        });
    </script>
</body>
</html>