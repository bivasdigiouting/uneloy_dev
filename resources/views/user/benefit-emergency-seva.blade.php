<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Seva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #eef1f6; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 900; font-size: 1.55rem; margin: 0; text-align: center; flex: 1; }
        .page-shell { max-width: 760px; margin: 0 auto; padding: 12px 14px 28px; }

        .activity-card { border-radius: 18px; overflow: hidden; box-shadow: 0 18px 30px rgba(15, 23, 42, 0.10); background: #fff; }
        .activity-head { background: var(--primary-gradient); padding: 14px 18px; color: #fff; font-weight: 900; font-size: 1.35rem; }
        .activity-body { padding: 18px; }
        .metric-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .metric-box { background: #fff; border-radius: 18px; padding: 18px; box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08); display: grid; place-items: center; min-height: 140px; }
        .metric-icon { width: 70px; height: 70px; border-radius: 18px; display: grid; place-items: center; margin-bottom: 12px; font-size: 26px; }
        .metric-icon.support { background: #ffe6ee; color: #ff4d73; }
        .metric-icon.accepted { background: #e7f7ea; color: #2faa4f; }
        .metric-label { font-weight: 800; font-size: 1.25rem; color: #0f172a; }
        .metric-value { font-weight: 900; font-size: 2.2rem; line-height: 1; margin-top: 4px; color: #0f172a; }

        .quick-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 16px; }
        .quick-card { background: #fff; border-radius: 16px; padding: 16px; text-decoration: none; color: #0f172a; box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08); display: flex; align-items: center; gap: 12px; }
        .quick-icon { width: 44px; height: 44px; border-radius: 14px; display: grid; place-items: center; background: rgba(213, 63, 140, 0.12); color: var(--pink-highlight); font-size: 18px; }
        .quick-text { font-weight: 900; font-size: 1.15rem; line-height: 1.1; }

        .checkin { margin: 18px 0 6px; display: grid; place-items: center; }
        .checkin-btn { width: 150px; height: 150px; border-radius: 999px; background: #fff; box-shadow: 0 18px 30px rgba(15, 23, 42, 0.10); display: grid; place-items: center; text-decoration: none; color: var(--pink-highlight); }
        .checkin-btn i { font-size: 46px; }
        .checkin-label { margin-top: 10px; font-weight: 900; letter-spacing: 1px; }

        .points-card { margin-top: 18px; border-radius: 18px; background: #fff; border: 2px solid #f5c542; box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08); padding: 16px; display: flex; align-items: center; justify-content: space-between; gap: 14px; }
        .points-left { display: flex; align-items: center; gap: 12px; }
        .points-icon { width: 42px; height: 42px; border-radius: 14px; background: rgba(245, 197, 66, 0.22); display: grid; place-items: center; color: #b7791f; }
        .points-title { font-weight: 900; font-size: 1.2rem; line-height: 1.1; }
        .points-value { font-weight: 900; font-size: 1.9rem; color: #22c55e; line-height: 1; }

        @media (min-width: 992px) {
            .topbar-inner { padding-left: 0; padding-right: 0; max-width: 760px; margin: 0 auto; }
            .page-shell { padding-left: 0; padding-right: 0; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <a class="icon-btn" href="{{ url()->previous() ?: route('user.dashboard') }}" aria-label="Back">
                <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
            </a>
            <h1 class="page-title">Emergency Seva</h1>
            <div style="width: 44px;"></div>
        </div>
    </div>

    <div class="page-shell">
        <div class="activity-card">
            <div class="activity-head">My Emergency Activity</div>
            <div class="activity-body">
                <div class="metric-row">
                    <div class="metric-box">
                        <div class="metric-icon support"><i class="fas fa-hand-holding-heart"></i></div>
                        <div class="metric-label">Support</div>
                        <div class="metric-value">{{ (int) ($stats['support'] ?? 0) }}</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-icon accepted"><i class="fas fa-circle-check"></i></div>
                        <div class="metric-label">Accepted</div>
                        <div class="metric-value">{{ (int) ($stats['accepted'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="quick-row">
            <a class="quick-card" href="{{ route('user.benefit.emergency.my.requests') }}">
                <div class="quick-icon"><i class="fas fa-hand-holding-medical"></i></div>
                <div class="quick-text">My Request</div>
            </a>
            <a class="quick-card" href="{{ route('user.benefit.emergency.ecard.other.details') }}">
                <div class="quick-icon"><i class="fas fa-users"></i></div>
                <div class="quick-text">Other Request Details</div>
            </a>
        </div>

        <div class="checkin">
            <a class="checkin-btn" href="{{ route('user.benefit.emergency.ecard.request.show') }}" aria-label="Check In">
                <i class="fas fa-hand-point-up"></i>
            </a>
            <div class="checkin-label">CHECK IN</div>
        </div>

        <div class="quick-row">
            <a class="quick-card" href="{{ route('user.benefit.emergency.family.contacts') }}">
                <div class="quick-icon"><i class="fas fa-address-book"></i></div>
                <div class="quick-text">Emergency Family Contact</div>
            </a>
            <a class="quick-card" href="{{ route('user.qr.show') }}">
                <div class="quick-icon"><i class="fas fa-qrcode"></i></div>
                <div class="quick-text">Family Contact QR</div>
            </a>
        </div>

        <div class="points-card">
            <div class="points-left">
                <div class="points-icon"><i class="fas fa-trophy"></i></div>
                <div class="points-title">Emergency Support<br>Points</div>
            </div>
            <div class="points-value">{{ (int) ($points ?? 0) }}</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
