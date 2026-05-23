<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .page-shell { max-width: 560px; margin: 0 auto; min-height: 100vh; padding-bottom: 20px; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #eef1f6; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 900; font-size: 1.2rem; margin: 0; text-align: center; flex: 1; }

        .content { padding: 8px 14px 22px; }
        .pill { display: inline-flex; align-items: center; gap: 8px; background: rgba(236, 72, 153, 0.10); color: #9d174d; border: 1px solid rgba(236, 72, 153, 0.25); border-radius: 999px; padding: 8px 12px; font-weight: 900; }
        .search-card { background: #ffffff; border-radius: 18px; padding: 12px; border: 1px solid rgba(15, 23, 42, 0.08); box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
        .vendor-card { background: #ffffff; border-radius: 18px; padding: 14px; border: 1px solid rgba(15, 23, 42, 0.08); box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
        .vendor-title { font-weight: 900; font-size: 1.05rem; margin: 0; }
        .vendor-sub { color: rgba(15, 23, 42, 0.65); font-weight: 700; margin-top: 4px; }
        .meta-row { display: flex; gap: 10px; align-items: flex-start; margin-top: 10px; color: rgba(15, 23, 42, 0.75); font-weight: 700; }
        .meta-row i { margin-top: 2px; width: 18px; text-align: center; color: rgba(15, 23, 42, 0.55); }
        .actions { margin-top: 12px; display: flex; gap: 10px; }
        .btn-soft { background: rgba(59, 130, 246, 0.10); border: 1px solid rgba(59, 130, 246, 0.18); color: #1d4ed8; font-weight: 900; border-radius: 14px; padding: 10px 12px; text-decoration: none; display: inline-flex; gap: 8px; align-items: center; }
        .btn-soft-danger { background: rgba(34, 197, 94, 0.10); border: 1px solid rgba(34, 197, 94, 0.20); color: #16a34a; }

        @media (min-width: 992px) {
            .topbar-inner, .content { padding-left: 0; padding-right: 0; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="topbar">
            <div class="topbar-inner">
                <a class="icon-btn" href="{{ route('user.estore.categories') }}" aria-label="Back">
                    <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
                </a>
                <h1 class="page-title">Vendors</h1>
                <div style="width: 44px;"></div>
            </div>
        </div>

        <div class="content">
            <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                <div class="pill">
                    <i class="fas fa-tags"></i>
                    <span>{{ $category->name ?? 'Category' }}</span>
                </div>
                <div class="text-muted fw-semibold">{{ ($vendors ?? null) ? $vendors->total() : 0 }} vendors</div>
            </div>

            <div class="search-card mb-3">
                <form method="GET" action="{{ route('user.estore.category.vendors', ['category' => $category->id]) }}" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-magnifying-glass text-muted"></i></span>
                        <input type="text" class="form-control" name="q" value="{{ $search ?? '' }}" placeholder="Search vendor name, mobile, address...">
                    </div>
                    <button type="submit" class="btn btn-dark fw-semibold">Search</button>
                </form>
            </div>

            @if(($vendors ?? null) && $vendors->count())
                <div class="d-grid gap-3">
                    @foreach($vendors as $v)
                        @php
                            $title = trim((string) ($v->business_name ?? ''));
                            if ($title === '') {
                                $title = trim((string) ($v->contact_person ?? 'Vendor'));
                            }
                            $contact = trim((string) ($v->contact_person ?? ''));
                            $mobile = trim((string) ($v->mobile_no ?? ''));
                            $email = trim((string) ($v->gmail_id ?? ''));
                            $addr = trim((string) ($v->business_full_address ?? ''));
                        @endphp
                        <div class="vendor-card">
                            <p class="vendor-title">{{ $title }}</p>
                            @if($contact !== '')
                                <div class="vendor-sub">{{ $contact }}</div>
                            @endif

                            @if($addr !== '')
                                <div class="meta-row">
                                    <i class="fas fa-location-dot"></i>
                                    <div class="flex-grow-1">{{ $addr }}</div>
                                </div>
                            @endif
                            @if($mobile !== '')
                                <div class="meta-row">
                                    <i class="fas fa-phone"></i>
                                    <div class="flex-grow-1">{{ $mobile }}</div>
                                </div>
                            @endif
                            @if($email !== '')
                                <div class="meta-row">
                                    <i class="fas fa-envelope"></i>
                                    <div class="flex-grow-1">{{ $email }}</div>
                                </div>
                            @endif

                            <div class="actions">
                                @if($mobile !== '')
                                    <a class="btn-soft btn-soft-danger flex-grow-1 justify-content-center" href="tel:{{ $mobile }}">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                @endif
                                @if($email !== '')
                                    <a class="btn-soft flex-grow-1 justify-content-center" href="mailto:{{ $email }}">
                                        <i class="fas fa-envelope"></i> Email
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $vendors->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-light border text-center mb-0">
                    <div class="fw-semibold text-muted">No vendors found</div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>

