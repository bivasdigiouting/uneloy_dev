<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Request Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #eef1f6; }
        .topbar-inner { height: 64px; display: flex; align-items: center; gap: 10px; padding: 10px 14px; }
        .back-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; }
        .page-title { font-weight: 900; font-size: 1.35rem; margin: 0; }
        .card-list { max-width: 760px; margin: 0 auto; padding: 10px 14px 28px; }
        .request-card { background: #fff; border-radius: 18px; padding: 18px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
        .name { font-weight: 900; font-size: 1.35rem; margin: 0; color: #0f172a; }
        .status-pill { background: #fdeac0; color: #b7791f; border-radius: 999px; padding: 7px 14px; font-weight: 800; font-size: 0.9rem; }
        .kv { font-size: 1.05rem; line-height: 1.9; color: #111827; }
        .kv b { font-weight: 900; }
        .two-col { display: flex; align-items: baseline; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .delete-btn { border: 0; border-radius: 14px; background: #fde8e8; color: #ef4444; font-weight: 800; padding: 12px 18px; display: inline-flex; align-items: center; gap: 12px; min-width: 140px; justify-content: center; }
        .delete-btn i { font-size: 18px; }
        .divider-gap { height: 16px; }
        .alert-wrap { padding: 0 14px; max-width: 760px; margin: 0 auto; }
        @media (min-width: 992px) {
            .topbar-inner { padding-left: 0; padding-right: 0; max-width: 760px; margin: 0 auto; }
            .card-list { padding-left: 0; padding-right: 0; }
            .alert-wrap { padding-left: 0; padding-right: 0; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <a class="back-btn" href="{{ route('user.benefit.emergency.dashboard') }}" aria-label="Back">
                <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
            </a>
            <h1 class="page-title">My Request Details</h1>
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

    <div class="card-list">
        @forelse(($requests ?? []) as $req)
            <div class="request-card">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <p class="name">{{ $req->name ?? 'Request' }}</p>
                    </div>
                    <div class="status-pill">{{ $req->status ?? 'Pending' }}</div>
                </div>

                <div class="divider-gap"></div>

                <div class="two-col">
                    <div class="kv"><b>Gender:</b> {{ $req->gender ?? (optional($user)->gender ?? '-') }}</div>
                    <div class="kv"><b>Age:</b> {{ $req->age ?? (optional(optional($user)->date_of_birth)->age ?? '-') }}</div>
                </div>
                <div class="kv"><b>Emergency Type:</b> {{ $req->emergency_type ?? '-' }}</div>
                <div class="kv"><b>Mobile No:</b> {{ $req->mobile_no ?? (optional($user)->mobile_no ?? '-') }}</div>

                <div class="two-col">
                    <div class="kv"><b>Date:</b> {{ optional($req->request_date)->format('Y-m-d') ?? '-' }}</div>
                    <div class="kv"><b>City:</b> {{ (optional($user)->district ?? optional($user)->city ?? '-') }}</div>
                </div>
                <div class="kv"><b>Address:</b> {{ $req->live_location ?? '-' }}</div>

                <div class="d-flex justify-content-end mt-3">
                    <form method="POST" action="{{ route('user.benefit.emergency.my.requests.delete', ['id' => $req->id]) }}">
                        @csrf
                        <button type="submit" class="delete-btn">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="divider-gap"></div>
        @empty
            <div class="request-card text-center">
                <div class="text-muted fw-semibold">No requests found</div>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
