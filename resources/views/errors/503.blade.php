@php
    $settingsModel = $settings ?? null;
    $requestPath = request()->path();
    $isUserPortal = request()->is('user/*');
    $isVendorPortal = request()->is('vendor/*');
    $isEcardPortal = request()->is('ecard/*');

    $portalLabel = $isUserPortal ? 'User Portal' : ($isVendorPortal ? 'Vendor Portal' : ($isEcardPortal ? 'E-Card Seva Portal' : config('app.name')));

    if ($isUserPortal) {
        $dashboardUrl = route(session()->has('user_auth') ? 'user.dashboard' : 'user.login');
    } elseif ($isVendorPortal) {
        $dashboardUrl = route(session()->has('vendor_id') ? 'vendor.dashboard' : 'vendor.login');
    } elseif ($isEcardPortal) {
        $dashboardUrl = route(auth('ecard')->check() ? 'ecard.dashboard' : 'ecard.login');
    } else {
        $dashboardUrl = url('/');
    }

    $siteName = $settingsModel->site_name ?? config('app.name');

    $logoPath = null;
    if ($isUserPortal) {
        $logoPath = $settingsModel->member_app_logo ?? null;
    } elseif ($isVendorPortal) {
        $logoPath = $settingsModel->estore_app_logo ?? null;
    } elseif ($isEcardPortal) {
        $logoPath = $settingsModel->ecardseva_logo ?? null;
    }
    $logoPath = $logoPath ?: ($settingsModel->logo ?? null);
    $logoUrl = $logoPath ? asset('storage/'.$logoPath) : asset('frontend-assets/design_img/logo.png');

    $pageTitle = $siteName.' · '.$portalLabel.' · 503 Service Unavailable';
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --surface: rgba(255, 255, 255, 0.85);
            --surface-border: rgba(148, 163, 184, 0.35);
            --text: #0f172a;
            --muted: #475569;
            --primary: #4f46e5;
            --primary-2: #7c3aed;
        }
        body {
            font-family: Poppins, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--text);
            min-height: 100vh;
            background:
                radial-gradient(1200px 600px at 15% 10%, rgba(79, 70, 229, 0.14), transparent 55%),
                radial-gradient(900px 500px at 85% 20%, rgba(124, 58, 237, 0.12), transparent 60%),
                radial-gradient(1000px 600px at 50% 90%, rgba(14, 165, 233, 0.10), transparent 55%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }
        .error-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 40px 16px;
        }
        .error-card {
            width: 100%;
            max-width: 980px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.10);
            backdrop-filter: blur(14px);
        }
        .brand {
            text-decoration: none;
            color: inherit;
        }
        .brand-logo {
            height: 38px;
            width: auto;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.30);
            padding: 6px;
        }
        .brand-name {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.2px;
            line-height: 1.1;
        }
        .brand-sub {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.1;
        }
        .badge-portal {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(79, 70, 229, 0.10);
            color: #3730a3;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.2px;
        }
        .error-code {
            font-size: 88px;
            font-weight: 800;
            letter-spacing: -2px;
            line-height: 1;
            margin: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 55%, #0ea5e9 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .error-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin: 14px 0 8px;
        }
        .error-desc {
            color: var(--muted);
            margin: 0;
            font-size: 15px;
            line-height: 1.7;
        }
        .hint {
            border: 1px dashed rgba(148, 163, 184, 0.6);
            background: rgba(248, 250, 252, 0.75);
            border-radius: 18px;
            padding: 14px 16px;
            color: #334155;
            font-size: 13px;
        }
        .btn-soft {
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: rgba(255, 255, 255, 0.85);
            color: #0f172a;
            font-weight: 600;
            border-radius: 14px;
            padding: 12px 16px;
        }
        .btn-soft:hover {
            background: #ffffff;
            border-color: rgba(148, 163, 184, 0.55);
        }
        .btn-primary-grad {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 65%);
            border: 0;
            font-weight: 700;
            border-radius: 14px;
            padding: 12px 16px;
            box-shadow: 0 16px 30px rgba(79, 70, 229, 0.22);
        }
        .btn-primary-grad:hover {
            filter: brightness(1.03);
        }
        .error-illustration {
            border-radius: 20px;
            background:
                radial-gradient(250px 200px at 20% 20%, rgba(79, 70, 229, 0.25), transparent 55%),
                radial-gradient(220px 180px at 70% 35%, rgba(124, 58, 237, 0.20), transparent 60%),
                radial-gradient(260px 220px at 50% 80%, rgba(14, 165, 233, 0.18), transparent 65%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.75) 0%, rgba(248, 250, 252, 0.75) 100%);
            border: 1px solid rgba(148, 163, 184, 0.25);
            padding: 22px;
            height: 100%;
        }
        .orbit {
            width: 210px;
            height: 210px;
            border-radius: 999px;
            margin: 10px auto 0;
            position: relative;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.35);
            box-shadow: inset 0 0 0 10px rgba(99, 102, 241, 0.06);
        }
        .orbit:before, .orbit:after {
            content: "";
            position: absolute;
            inset: 18px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.35);
        }
        .orbit:after {
            inset: 44px;
            border-color: rgba(148, 163, 184, 0.28);
        }
        .dot {
            position: absolute;
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.25);
            top: 18px;
            left: 50%;
            transform: translateX(-50%);
        }
        .dot.secondary {
            width: 10px;
            height: 10px;
            background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);
            top: auto;
            bottom: 22px;
            left: 20%;
            transform: none;
        }
        .path-pill {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: rgba(255, 255, 255, 0.65);
            color: #334155;
            font-size: 12px;
            max-width: 100%;
        }
        .path-pill span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="error-shell">
        <div class="error-card">
            <div class="row g-0">
                <div class="col-lg-7 p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <a href="{{ $dashboardUrl }}" class="brand d-inline-flex align-items-center gap-3">
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }} Logo" class="brand-logo">
                            <div class="d-flex flex-column">
                                <div class="brand-name">{{ $siteName }}</div>
                                <div class="brand-sub">{{ $portalLabel }}</div>
                            </div>
                        </a>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="badge-portal">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span>Error 503</span>
                        </div>
                        <div class="path-pill">
                            <i class="fa-solid fa-link text-secondary"></i>
                            <span>{{ '/'.ltrim($requestPath, '/') }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h1 class="error-code">503</h1>
                        <div class="error-title">Service unavailable</div>
                        <p class="error-desc">
                            The portal is temporarily unavailable (maintenance or high traffic).
                            Please go back and try again, or return to the dashboard.
                        </p>
                    </div>

                    <div class="mt-4 d-flex flex-column flex-sm-row gap-3">
                        <button type="button" class="btn btn-soft" id="goBackBtn">
                            <i class="fa-solid fa-arrow-left me-2"></i>
                            Back
                        </button>
                        <a href="{{ $dashboardUrl }}" class="btn btn-primary text-white btn-primary-grad">
                            <i class="fa-solid fa-house me-2"></i>
                            Dashboard
                        </a>
                    </div>

                    <div class="mt-4 hint">
                        Please wait a few minutes and try again.
                    </div>
                </div>
                <div class="col-lg-5 p-4 p-md-5">
                    <div class="error-illustration d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="fw-bold">Availability</div>
                            <div class="text-secondary fw-semibold">Status: 503</div>
                        </div>
                        <div class="orbit">
                            <div class="dot"></div>
                            <div class="dot secondary"></div>
                        </div>
                        <div class="text-secondary" style="font-size: 13px;">
                            Thanks for your patience. Service will be back shortly.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const dashboardUrl = @json($dashboardUrl);
            const backBtn = document.getElementById('goBackBtn');
            if (!backBtn) return;
            backBtn.addEventListener('click', function () {
                if (window.history.length > 1) {
                    window.history.back();
                    return;
                }
                window.location.href = dashboardUrl;
            });
        })();
    </script>
</body>
</html>
