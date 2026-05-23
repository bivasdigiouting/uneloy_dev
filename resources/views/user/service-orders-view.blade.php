<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .page-shell { max-width: 560px; margin: 0 auto; min-height: 100vh; padding-bottom: 74px; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #eef1f6; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 900; font-size: 1.65rem; margin: 0; text-align: center; flex: 1; }

        .list-wrap { padding: 12px 14px 22px; }
        .order-card {
            background: #f6eef8;
            border-radius: 20px;
            padding: 18px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            border: 1px solid rgba(15, 23, 42, 0.08);
        }
        .order-head { display: flex; align-items: flex-start; gap: 14px; }
        .order-icon {
            width: 54px; height: 54px;
            border-radius: 18px;
            background: rgba(168, 85, 247, 0.10);
            display: flex; align-items: center; justify-content: center;
            color: #a855f7;
            font-size: 22px;
            flex: 0 0 auto;
        }
        .order-title { font-weight: 900; font-size: 1.2rem; margin: 0; color: #111827; }
        .order-date { color: rgba(15, 23, 42, 0.55); font-weight: 600; margin-top: 3px; }

        .kv-row { display: flex; justify-content: space-between; gap: 16px; font-size: 1.05rem; color: #111827; }
        .kv-row .label { color: rgba(15, 23, 42, 0.75); font-weight: 700; }
        .kv-row .value { font-weight: 900; }
        .net { font-size: 1.25rem; font-weight: 900; margin-top: 8px; }
        .hr { height: 1px; background: rgba(15, 23, 42, 0.14); margin: 14px 0; }
        .status { display: flex; align-items: center; gap: 10px; font-weight: 900; font-size: 1.15rem; color: #16a34a; }
        .status i { font-size: 18px; }
        .card-gap { height: 14px; }

        .bottom-bar {
            position: fixed;
            left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.98);
            border-top: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 -10px 24px rgba(15, 23, 42, 0.08);
            padding: 12px 14px;
        }
        .bottom-inner { max-width: 560px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .bottom-label { color: rgba(15, 23, 42, 0.75); font-weight: 800; }
        .bottom-value { font-weight: 900; font-size: 1.25rem; }

        @media (min-width: 992px) {
            .topbar-inner, .list-wrap { padding-left: 0; padding-right: 0; }
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
                <h1 class="page-title">My Orders</h1>
                <div style="width: 44px;"></div>
            </div>
        </div>

        <div class="list-wrap">
            @forelse(($orders ?? []) as $item)
                @php
                    $orderNo = (string) ($item['order_no'] ?? '-');
                    $userName = trim((string) ($item['user_name'] ?? ''));
                    $title = $userName !== '' ? ("Order #{$orderNo} ({$userName})") : ("Order #{$orderNo}");
                    $items = (int) ($item['items'] ?? 0);
                    $total = (float) ($item['total'] ?? 0);
                    $discount = (float) ($item['discount'] ?? 0);
                    $coupon = (float) ($item['coupon'] ?? 0);
                    $net = (float) ($item['net'] ?? 0);
                    $status = trim((string) ($item['status'] ?? 'Confirmed'));
                @endphp

                <div class="order-card">
                    <div class="order-head">
                        <div class="order-icon"><i class="fas fa-cart-shopping"></i></div>
                        <div class="flex-grow-1">
                            <p class="order-title">{{ $title }}</p>
                            <div class="order-date">{{ (string) ($item['date'] ?? '-') }}</div>
                        </div>
                    </div>

                    <div class="card-gap"></div>

                    <div class="kv-row">
                        <div><span class="label">Items:</span> <span class="value">{{ $items }}</span></div>
                        <div><span class="label">Total:</span> <span class="value">₹{{ number_format($total, 2) }}</span></div>
                    </div>
                    <div class="kv-row mt-1">
                        <div><span class="label">Discount:</span> <span class="value">{{ number_format($discount, 1) }}</span></div>
                        <div><span class="label">Coupon:</span> <span class="value">{{ number_format($coupon, 1) }}</span></div>
                    </div>
                    <div class="net">Net Amount: ₹{{ number_format($net, 1) }}</div>

                    <div class="hr"></div>

                    <div class="status">
                        <i class="fas fa-square-check"></i>
                        <span>Status : {{ $status }}</span>
                    </div>
                </div>

                <div class="card-gap"></div>
            @empty
                <div class="order-card text-center">
                    <div class="text-muted fw-semibold">No orders found</div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bottom-bar">
        <div class="bottom-inner">
            <div class="bottom-label">Total Purchase Amount:</div>
            <div class="bottom-value">₹{{ number_format((float) ($totalPurchaseAmount ?? 0), 2) }}</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
