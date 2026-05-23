<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings->maintenance_title ?? ($settings->site_title ?? 'Under Maintenance') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/custom_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/responsive_styles.css') }}">
</head>
<body class="bg-light">
    <main class="container">
        <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
            <div class="card shadow-sm" style="max-width: 720px; width: 100%;">
                <div class="card-body p-4 p-md-5 text-center">
                    <h1 class="h3 mb-3">{{ $settings->maintenance_title ?? 'Under Maintenance' }}</h1>
                    <p class="text-muted mb-0">
                        {{ $settings->maintenance_message ?? 'We are currently performing scheduled maintenance. Please check back soon.' }}
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
