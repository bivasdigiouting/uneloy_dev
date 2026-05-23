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
        body { background: #f6f7fb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #f6f7fb; }
        .topbar-inner { height: 64px; display: flex; align-items: center; gap: 10px; padding: 10px 14px; }
        .back-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; }
        .page-title { font-weight: 900; font-size: 1.35rem; margin: 0; }
        .request-card { background: #fff; border-radius: 18px; padding: 18px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
        .name { font-weight: 900; font-size: 1.25rem; margin: 0; color: #0f172a; }
        .status-pill { background: #f6ddb5; color: #6b4e16; border-radius: 999px; padding: 7px 14px; font-weight: 700; font-size: 0.9rem; }
        .kv { font-size: 1.1rem; line-height: 1.8; color: #111827; }
        .kv b { font-weight: 900; }
        .two-col { display: flex; align-items: baseline; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .delete-btn { border: 0; border-radius: 14px; background: #fde8e8; color: #dc2626; font-weight: 800; padding: 10px 18px; display: inline-flex; align-items: center; gap: 10px; }
        .delete-btn i { font-size: 18px; }
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
                <a class="back-btn" href="{{ route('user.benefit.blood.dashboard') }}" aria-label="Back">
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

        <div class="px-3 pb-4">
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
                        <div class="kv"><b>Gender:</b> {{ $req->gender ?? ($user->gender ?? '-') }}</div>
                        <div class="kv"><b>Age:</b> {{ $req->age ?? (optional($user->date_of_birth)->age ?? '-') }}</div>
                    </div>
                    <div class="kv"><b>Blood Group:</b> {{ $req->blood_group ?? ($user->blood_group ?? '-') }}</div>
                    <div class="kv"><b>Mobile No:</b> {{ $req->mobile_no ?? ($user->mobile_no ?? '-') }}</div>
                    <div class="kv"><b>Hospital:</b> {{ $req->hospital_name ?? '-' }}</div>

                    <div class="two-col">
                        <div class="kv"><b>Date:</b> {{ optional($req->request_date)->format('Y-m-d') ?? '-' }}</div>
                        <div class="kv"><b>City:</b> {{ $user->district ?? ($user->city ?? '-') }}</div>
                    </div>
                    <div class="kv"><b>Address:</b> {{ $req->hospital_address ?? ($user->current_address ?? '-') }}</div>

                    <div class="d-flex justify-content-end mt-3">
                        <form method="POST" action="{{ route('user.benefit.blood.my.requests.delete', ['id' => $req->id]) }}">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
