<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .page-shell { max-width: 560px; margin: 0 auto; min-height: 100vh; padding-bottom: 20px; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #eef1f6; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 900; font-size: 1.5rem; margin: 0; text-align: center; flex: 1; }

        .content { padding: 8px 14px 22px; }
        .subtitle { font-weight: 800; color: rgba(15, 23, 42, 0.7); margin-bottom: 12px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .cat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 14px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .cat-icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            background: rgba(236, 72, 153, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            overflow: hidden;
        }
        .cat-icon img { width: 100%; height: 100%; object-fit: cover; }
        .cat-fallback { font-weight: 900; font-size: 22px; color: #ec4899; }
        .cat-name { font-weight: 900; font-size: 1.02rem; margin: 0; line-height: 1.1; }
        .cat-hint { font-weight: 700; color: rgba(15, 23, 42, 0.55); font-size: 0.9rem; margin: 3px 0 0; }

        @media (min-width: 992px) {
            .topbar-inner, .content { padding-left: 0; padding-right: 0; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="topbar">
            <div class="topbar-inner">
                <a class="icon-btn" href="{{ route('user.dashboard') }}" aria-label="Back">
                    <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
                </a>
                <h1 class="page-title">e Store</h1>
                <div style="width: 44px;"></div>
            </div>
        </div>

        <div class="content">
            <div class="subtitle">Select a product category</div>

            @if(($categories ?? collect())->isEmpty())
                <div class="alert alert-light border text-center mb-0">
                    <div class="fw-semibold text-muted">No categories available</div>
                </div>
            @else
                <div class="grid">
                    @foreach($categories as $category)
                        @php
                            $iconUrl = $category->icon ? asset('storage/'.$category->icon) : null;
                            $initial = strtoupper(substr((string) $category->name, 0, 1));
                        @endphp
                        <a class="cat-card" href="{{ route('user.estore.category.vendors', ['category' => $category->id]) }}">
                            <div class="cat-icon">
                                @if($iconUrl)
                                    <img src="{{ $iconUrl }}" alt="{{ $category->name }}">
                                @else
                                    <div class="cat-fallback">{{ $initial }}</div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <p class="cat-name">{{ $category->name }}</p>
                                <p class="cat-hint">View vendors</p>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>

