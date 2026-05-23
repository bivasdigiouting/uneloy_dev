<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Card Dashboard - UOnly">
    <meta name="keywords" content="ecard, dashboard, uonly">
    <meta name="author" content="UOnly">
    <meta name="robots" content="index, follow">
    <title>@yield('title', 'E-Card Dashboard - UOnly')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->ecardseva_favicon ? asset('storage/'.$settings->ecardseva_favicon) : ($settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico')) }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/bootstrap.min.css') }}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/bootstrap-datetimepicker.min.css') }}">

    <!-- animation CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/animate.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/select2/css/select2.min.css') }}">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/dataTables.bootstrap5.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Daterangepikcer CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Map CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/jvectormap/jquery-jvectormap-2.0.5.css') }}">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/@simonwep/pickr/themes/nano.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/style.css') }}">

    <style>
        @media (max-width: 1199.98px) {
            .header .main-header {
                display: flex;
                align-items: center;
            }

            .header .main-header .user-menu {
                margin-left: auto;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                flex-wrap: nowrap;
                gap: 10px;
                padding-right: 12px;
            }

            .header .main-header .user-menu > li.nav-searchinputs,
            .header .main-header .user-menu > li.select-store-dropdown,
            .header .main-header .user-menu > li.nav-item-box:not(.dropdown) {
                display: none !important;
            }

            .header .main-header .user-menu > li.dropdown.nav-item-box,
            .header .main-header .user-menu > li.profile-nav {
                display: flex;
                align-items: center;
            }

            .header .main-header .user-menu > li.profile-nav .dropdown-menu,
            .header .main-header .user-menu > li.dropdown.nav-item-box .dropdown-menu {
                left: auto !important;
                right: 0 !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="global-loader" >
        <div class="whirly-loader"> </div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        @include('ecard._partials.nav-bar')

        @include('ecard._partials.sidebar')

        <div class="page-wrapper">
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('backend_assets/assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('backend_assets/assets/js/feather.min.js') }}"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('backend_assets/assets/js/jquery.slimscroll.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('backend_assets/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('backend_assets/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/plugins/apexchart/chart-data.js') }}"></script>

    <!-- Datatable JS -->
    <script src="{{ asset('backend_assets/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Datetimepicker JS -->
    <script src="{{ asset('backend_assets/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- Daterangepikcer JS -->
    <script src="{{ asset('backend_assets/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('backend_assets/assets/plugins/select2/js/select2.min.js') }}"></script>

    <!-- Map JS -->
    <script src="{{ asset('backend_assets/assets/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/plugins/jvectormap/jvectormap.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/plugins/jvectormap/us-merc-en.js') }}"></script>

    <!-- SweetAlert JS -->
    <script src="{{ asset('backend_assets/assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

    <!-- Custom JS -->
    <script>
        window.userThemeSettings = @json(optional(auth('ecard')->user())->theme_settings);
        window.saveThemeRoute = "{{ route('ecard.theme.save') }}";
        window.csrfToken = "{{ csrf_token() }}";

        if (window.userThemeSettings) {
            const settings = window.userThemeSettings;
            const setItem = (key, value) => {
                if (value) localStorage.setItem(key, value);
            };
            
            setItem('ecard_theme', settings.theme);
            setItem('ecard_sidebarTheme', settings.sidebarTheme);
            setItem('ecard_color', settings.color);
            setItem('ecard_topbar', settings.topbar);
            setItem('ecard_layout', settings.layout);
            setItem('ecard_width', settings.width);
            setItem('ecard_sidebarBg', settings.sidebarBg);
            setItem('ecard_topbarbg', settings.topbarbg);
            setItem('ecard_primaryRGB', settings.primaryRGB);
            setItem('ecard_darkMode', settings.darkMode);
        }
    </script>
    <script src="{{ asset('backend_assets/assets/js/theme-script.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/js/script.js') }}"></script>

    @stack('scripts')
</body>
</html>
