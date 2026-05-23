<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Other Request Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #f6f7fb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #f6f7fb; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 900; font-size: 1.35rem; margin: 0; text-align: center; flex: 1; }
        .request-card { background: #fff; border-radius: 18px; padding: 18px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
        .name { font-weight: 900; font-size: 1.25rem; margin: 0; color: #0f172a; }
        .status-pill { border-radius: 999px; padding: 7px 14px; font-weight: 800; font-size: 0.9rem; }
        .status-pending { background: #f6ddb5; color: #6b4e16; }
        .status-approved { background: #cfead3; color: #1f7a3d; }
        .kv { font-size: 1.1rem; line-height: 1.8; color: #111827; }
        .kv b { font-weight: 900; }
        .two-col { display: flex; align-items: baseline; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .actions-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 16px; }
        .accept-btn { border: 2px solid #30a46c; background: #fff; color: #30a46c; font-weight: 900; border-radius: 14px; padding: 12px 20px; display: inline-flex; align-items: center; gap: 10px; min-width: 160px; justify-content: center; }
        .icon-actions { display: inline-flex; align-items: center; gap: 16px; }
        .round-action { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; background: transparent; }
        .round-action.phone { color: #16a34a; }
        .round-action.map { color: #ef4444; }
        .round-action.chat { color: #3b82f6; }
        .divider-gap { height: 16px; }
        .alert-wrap { padding: 0 14px; }
        @media (min-width: 992px) {
            body { background: var(--bg-light); }
            .page-shell { max-width: 760px; margin: 0 auto; }
            .topbar-inner { padding-left: 0; padding-right: 0; }
            .alert-wrap { padding-left: 0; padding-right: 0; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="topbar">
            <div class="topbar-inner">
                <a class="icon-btn" href="{{ route('user.benefit.blood.dashboard') }}" aria-label="Back">
                    <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
                </a>
                <h1 class="page-title">Other Request Details</h1>
                <div class="dropdown">
                    <button class="icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Filter">
                        <i class="fas fa-filter" style="font-size: 18px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ ($status ?? '') === '' || strtoupper($status ?? '') === 'ALL' ? 'active' : '' }}" href="{{ route('user.benefit.blood.other.requests', ['status' => 'All']) }}">All</a></li>
                        <li><a class="dropdown-item {{ ($status ?? '') === 'Pending' ? 'active' : '' }}" href="{{ route('user.benefit.blood.other.requests', ['status' => 'Pending']) }}">Pending</a></li>
                        <li><a class="dropdown-item {{ ($status ?? '') === 'Approved' ? 'active' : '' }}" href="{{ route('user.benefit.blood.other.requests', ['status' => 'Approved']) }}">Approved</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="alert-wrap">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <div class="px-3 pb-4">
            @forelse(($requests ?? []) as $req)
                @php
                    $profile = ($profilesByMobile ?? collect())->get($req->mobile_no);
                    $statusText = $req->status ?? 'Pending';
                    $isPending = $statusText === 'Pending';
                    $dateText = optional($req->request_date)->format('d-m-Y') ?? '-';
                    $cityText = $profile->district ?? ($profile->city ?? '-');
                    $addressText = $req->hospital_address ?? ($profile->current_address ?? '-');
                    $mapQuery = trim((string) ($req->hospital_name ?? '').' '.(string) $addressText);
                @endphp

                <div class="request-card">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <p class="name">{{ $req->name ?? 'Request' }}</p>
                        </div>
                        <div class="status-pill {{ $isPending ? 'status-pending' : 'status-approved' }}">{{ $statusText }}</div>
                    </div>

                    <div class="divider-gap"></div>

                    <div class="two-col">
                        <div class="kv"><b>Gender:</b> {{ $req->gender ?? ($profile->gender ?? '-') }}</div>
                        <div class="kv"><b>Age:</b> {{ $req->age ?? (optional($profile->date_of_birth)->age ?? '-') }}</div>
                    </div>
                    <div class="kv"><b>Blood Group:</b> {{ $req->blood_group ?? ($profile->blood_group ?? '-') }}</div>
                    <div class="kv"><b>Hospital:</b> {{ $req->hospital_name ?? '-' }}</div>

                    <div class="two-col">
                        <div class="kv"><b>Date:</b> {{ $dateText }}</div>
                        <div class="kv"><b>City:</b> {{ $cityText }}</div>
                    </div>
                    <div class="kv"><b>Address:</b> {{ $addressText }}</div>

                    <div class="actions-row">
                        <div>
                            @if($isPending)
                                <form method="POST" action="{{ route('user.benefit.blood.other.requests.accept', ['id' => $req->id]) }}">
                                    @csrf
                                    <button type="submit" class="accept-btn">
                                        <i class="fas fa-circle-check"></i>
                                        Accept
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="icon-actions">
                            <a class="round-action phone" href="{{ $req->mobile_no ? 'tel:'.$req->mobile_no : '#' }}" aria-label="Call">
                                <i class="fas fa-phone" style="font-size: 22px;"></i>
                            </a>
                            <a class="round-action map" href="{{ $mapQuery !== '' ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapQuery) : '#' }}" target="_blank" rel="noopener" aria-label="Location">
                                <i class="fas fa-location-dot" style="font-size: 22px;"></i>
                            </a>
                            <a class="round-action chat" href="{{ $req->mobile_no ? 'sms:'.$req->mobile_no : '#' }}" aria-label="Message">
                                <i class="fas fa-comment-dots" style="font-size: 22px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="divider-gap"></div>
            @empty
                <div class="request-card text-center">
                    <div class="text-muted fw-semibold">No requests found</div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>

