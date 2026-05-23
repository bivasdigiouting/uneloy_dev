<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benefit - Blood Seva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: var(--bg-light); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--text-dark); }
        .blood-header { background: var(--card-bg); border-bottom: 1px solid var(--border-color); }
        .blood-header-inner { height: 56px; }
        .blood-back { width: 40px; height: 40px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-dark); display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
        .blood-title { font-weight: 800; letter-spacing: 0.2px; }
        .activity-card { border: 0; border-radius: 18px; background: var(--card-bg); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
        .activity-pill { border-radius: 16px; background: rgba(213, 63, 140, 0.10); color: var(--pink-highlight); font-weight: 700; padding: 4px 10px; font-size: 0.85rem; }
        .metric { border-radius: 16px; background: var(--bg-light); padding: 14px 14px; }
        .metric-value { font-weight: 900; font-size: 1.35rem; line-height: 1; }
        .metric-label { font-weight: 600; font-size: 0.85rem; color: var(--text-muted); }
        .action-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .action-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 18px; padding: 14px 10px; text-decoration: none; color: var(--text-dark); transition: transform 120ms ease, box-shadow 120ms ease; }
        .action-card:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(0,0,0,0.08); }
        .action-icon { width: 46px; height: 46px; border-radius: 16px; display: grid; place-items: center; margin: 0 auto 10px; background: rgba(128, 90, 213, 0.12); color: #805AD5; font-size: 18px; }
        .action-text { font-weight: 700; font-size: 0.85rem; text-align: center; line-height: 1.2; }
        .points-card { border-radius: 18px; background: var(--card-bg); border: 2px solid #f5c542; box-shadow: 0 10px 22px rgba(0,0,0,0.06); }
        .points-title { font-weight: 800; }
        .points-value { font-weight: 900; font-size: 1.8rem; color: #f5a623; line-height: 1; }
        .blood-hero { border-radius: 18px; overflow: hidden; background: var(--card-bg); border: 1px solid var(--border-color); }
        .blood-hero img { width: 100%; height: auto; display: block; }
        @media (min-width: 768px) {
            .blood-header-inner { height: 64px; }
            .action-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 16px; }
            .action-card { padding: 16px 10px; }
        }
    </style>
</head>
<body>
    <header class="blood-header sticky-top">
        <div class="container">
            <div class="blood-header-inner d-flex align-items-center justify-content-between">
                <a href="{{ url()->previous() ?: route('user.dashboard') }}" class="blood-back" aria-label="Back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="blood-title">Blood Seva</div>
                <div style="width: 40px;"></div>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <div class="activity-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="activity-pill">My Blood Activity</span>
                </div>
                <i class="fas fa-heart text-danger"></i>
            </div>

            <div class="row g-3">
                <div class="col-6">
                    <div class="metric">
                        <div class="metric-value">{{ (int) ($stats['donations'] ?? 0) }}</div>
                        <div class="metric-label">Donations</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="metric">
                        <div class="metric-value">{{ (int) ($stats['accepted'] ?? 0) }}</div>
                        <div class="metric-label">Accepted</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-grid mb-4">
            <a class="action-card" href="{{ route('user.benefit.blood.my.requests') }}">
                <div class="action-icon"><i class="fas fa-hands-holding-heart"></i></div>
                <div class="action-text">My Request</div>
            </a>
            <a class="action-card" href="{{ route('user.benefit.blood.other.requests') }}">
                <div class="action-icon"><i class="fas fa-users"></i></div>
                <div class="action-text">Other Request</div>
            </a>
            <a class="action-card" href="{{ route('user.benefit.blood.my.details') }}">
                <div class="action-icon"><i class="fas fa-droplet"></i></div>
                <div class="action-text">My Donate Detail</div>
            </a>
            <a class="action-card" href="{{ route('user.benefit.blood.self.report') }}">
                <div class="action-icon"><i class="fas fa-fingerprint"></i></div>
                <div class="action-text">Check In</div>
            </a>
            <a class="action-card" href="{{ route('user.benefit.blood.request.show') }}">
                <div class="action-icon"><i class="fas fa-droplet"></i></div>
                <div class="action-text">Blood Donate</div>
            </a>
            <a class="action-card" href="{{ route('user.benefit.blood.other.details') }}">
                <div class="action-icon"><i class="fas fa-droplet"></i></div>
                <div class="action-text">Other Donate Details</div>
            </a>
        </div>

        <div class="points-card p-4 mb-4 d-flex align-items-center justify-content-between">
            <div>
                <div class="points-title">Blood Donate Points</div>
                <div class="text-muted small">Total points earned</div>
            </div>
            <div class="points-value">{{ (int) ($points ?? 0) }}</div>
        </div>

        <div class="blood-hero">
            <img src="{{ asset('frontend-assets/images/WebsiteBenefits/Webbenefit_08082025110838.png') }}" alt="Blood Donation Benefit">
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
