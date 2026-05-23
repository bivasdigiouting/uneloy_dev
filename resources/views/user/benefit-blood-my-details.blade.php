<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blood Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #f6f7fb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #f6f7fb; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 900; font-size: 1.45rem; margin: 0; text-align: center; flex: 1; }
        .page-shell { min-height: calc(100vh - 64px); display: grid; place-items: center; padding: 18px 14px 32px; }
        .empty-wrap { width: 100%; max-width: 520px; display: grid; place-items: center; }
        .empty-illustration { width: min(520px, 92vw); height: auto; }
        .card-list { width: 100%; max-width: 760px; margin: 0 auto; padding: 0 14px 28px; }
        .request-card { background: #fff; border-radius: 18px; padding: 18px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
        .name { font-weight: 900; font-size: 1.15rem; margin: 0; color: #0f172a; }
        .status-pill { border-radius: 999px; padding: 7px 14px; font-weight: 800; font-size: 0.9rem; background: #cfead3; color: #1f7a3d; }
        .kv { font-size: 1.05rem; line-height: 1.8; color: #111827; }
        .kv b { font-weight: 900; }
        .two-col { display: flex; align-items: baseline; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .divider-gap { height: 16px; }
        @media (min-width: 992px) {
            body { background: var(--bg-light); }
            .topbar-inner { padding-left: 0; padding-right: 0; max-width: 760px; margin: 0 auto; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <a class="icon-btn" href="{{ route('user.benefit.blood.dashboard') }}" aria-label="Back">
                <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
            </a>
            <h1 class="page-title">My Blood Details</h1>
            <div style="width: 44px;"></div>
        </div>
    </div>

    @if(($donations ?? collect())->count() === 0)
        <div class="page-shell">
            <div class="empty-wrap">
                <svg class="empty-illustration" viewBox="0 0 900 520" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="No data">
                    <rect x="0" y="0" width="900" height="520" fill="none"/>
                    <ellipse cx="330" cy="360" rx="250" ry="130" fill="#e9efff"/>
                    <path d="M120 360c0-70 60-130 140-130h250c55 0 100 45 100 100v40c0 55-45 100-100 100H280c-90 0-160-70-160-110z" fill="#2f3b73"/>
                    <path d="M140 360c0-60 50-110 120-110h240c45 0 82 37 82 82v28c0 45-37 82-82 82H280c-78 0-140-58-140-82z" fill="#3a4685"/>
                    <rect x="250" y="310" width="210" height="42" rx="10" fill="#7581d9" opacity="0.55"/>
                    <circle cx="290" cy="400" r="50" fill="#6a77d6" opacity="0.55"/>
                    <path d="M275 400h30M290 385v30" stroke="#2f3b73" stroke-width="10" stroke-linecap="round"/>
                    <path d="M530 235c0-12 10-22 22-22h70c12 0 22 10 22 22v10c0 12-10 22-22 22h-70c-12 0-22-10-22-22v-10z" fill="#ffffff" opacity="0.9"/>
                    <circle cx="650" cy="286" r="38" fill="#0f172a" opacity="0.08"/>
                    <path d="M620 300c0-34 28-62 62-62h20c34 0 62 28 62 62v110h-56l-38-74-40 74h-110v-54h76l24-56z" fill="#1f2937"/>
                    <path d="M702 230c0-22 18-40 40-40s40 18 40 40-18 40-40 40-40-18-40-40z" fill="#f2c9a0"/>
                    <path d="M690 344c22-16 56-16 78 0" stroke="#2563eb" stroke-width="16" stroke-linecap="round"/>
                    <path d="M640 268c18 10 24 26 24 26" stroke="#0f172a" stroke-width="10" stroke-linecap="round" opacity="0.35"/>
                    <path d="M280 235l-24-34M310 235l-10-38M340 235l8-34" stroke="#94a3b8" stroke-width="10" stroke-linecap="round"/>
                    <path d="M470 250c0 0-30-70-80-70" stroke="#94a3b8" stroke-width="10" stroke-linecap="round"/>
                    <path d="M450 265c0 0-26-40-60-40" stroke="#94a3b8" stroke-width="10" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    @else
        <div class="card-list">
            @foreach(($donations ?? []) as $row)
                <div class="request-card">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <p class="name">{{ $row->name ?? 'Request' }}</p>
                        </div>
                        <div class="status-pill">Approved</div>
                    </div>

                    <div class="divider-gap"></div>

                    <div class="two-col">
                        <div class="kv"><b>Gender:</b> {{ $row->gender ?? '-' }}</div>
                        <div class="kv"><b>Age:</b> {{ $row->age ?? '-' }}</div>
                    </div>
                    <div class="kv"><b>Blood Group:</b> {{ $row->blood_group ?? '-' }}</div>
                    <div class="kv"><b>Hospital:</b> {{ $row->hospital_name ?? '-' }}</div>
                    <div class="kv"><b>Date:</b> {{ optional($row->request_date)->format('d-m-Y') ?? '-' }}</div>
                    <div class="kv"><b>Address:</b> {{ $row->hospital_address ?? '-' }}</div>
                </div>
                <div class="divider-gap"></div>
            @endforeach
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>

