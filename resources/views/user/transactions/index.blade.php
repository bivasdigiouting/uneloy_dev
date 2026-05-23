<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Transactions - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Common Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 20px;
        }

        /* Header */
        .profile-header {
            background: var(--bg-light);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .back-btn {
            font-size: 24px;
            color: var(--text-dark);
            text-decoration: none;
            margin-right: 15px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            flex-grow: 1;
            text-align: center;
            margin-right: 24px;
        }

        /* Content Card */
        .content-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            margin: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        /* Filter Section */
        .filter-section {
            background-color: var(--bg-light);
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            border: 1px solid var(--muted-text);
        }

        /* Transaction Item (Mobile Friendly) */
        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--muted-text);
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .t-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 15px;
        }

        .t-icon.credit {
            background-color: rgba(56, 178, 172, 0.1);
            color: #38b2ac;
        }

        .t-icon.debit {
            background-color: rgba(229, 62, 62, 0.1);
            color: #e53e3e;
        }

        .t-details {
            flex: 1;
        }

        .t-title {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 15px;
            margin-bottom: 2px;
        }

        .t-date {
            font-size: 12px;
            color: var(--text-muted);
        }

        .t-amount {
            font-weight: 700;
            font-size: 15px;
            text-align: right;
        }

        .t-balance {
            font-size: 11px;
            color: var(--text-muted);
            text-align: right;
            margin-top: 2px;
        }

        .text-success { color: #38b2ac !important; }
        .text-danger { color: #e53e3e !important; }

        /* Desktop Optimizations */
        @media (min-width: 992px) {
            body {
                background-color: var(--bg-light);
                display: flex;
                justify-content: center;
                min-height: 100vh;
            }

            .mobile-wrapper {
                max-width: 450px;
                box-shadow: 0 0 50px rgba(0,0,0,0.15);
                border-left: 1px solid rgba(0,0,0,0.05);
                border-right: 1px solid rgba(0,0,0,0.05);
                background-color: var(--bg-light);
            }
        }
    </style>
</head>
<body>

    <div class="mobile-wrapper">
        
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">Transactions</div>
        </div>

        <!-- Filter & Export -->
        <div class="content-card">
            <form action="{{ route('user.manage.transactions') }}" method="GET">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="small text-muted mb-1">From</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-6">
                        <label class="small text-muted mb-1">To</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-6 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm w-100" style="background: var(--primary-gradient); border: none;">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                    </div>
                    <div class="col-6 mt-3">
                        <button type="submit" name="export" value="excel" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-file-excel me-1"></i> Download Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Transactions List -->
        <div class="content-card mt-0">
            <h6 class="mb-3 fw-bold">Transactions</h6>
            
            @forelse($transactions as $transaction)
                @php
                    $category = (string) ($transaction['category'] ?? '');
                    $type = (string) ($transaction['type'] ?? '');
                    $iconTone = $type === 'credit' ? 'credit' : 'debit';
                    $amountTone = $type === 'credit' ? 'text-success' : 'text-danger';
                    $sign = $type === 'credit' ? '+' : '-';
                    $icon = 'fa-receipt';
                    if ($category === 'Purchase') {
                        $icon = 'fa-shopping-cart';
                    } elseif ($category === 'Recharge') {
                        $icon = 'fa-bolt';
                    } elseif ($category === 'Wallet') {
                        $icon = $type === 'credit' ? 'fa-arrow-down' : 'fa-arrow-up';
                    }
                    $date = $transaction['date'] ?? null;
                    $dateText = $date instanceof \Illuminate\Support\Carbon ? $date->format('d M, h:i A') : '';
                    $balance = $transaction['balance'] ?? null;
                    $title = (string) ($transaction['title'] ?? 'Transaction');
                @endphp
                <div class="transaction-item">
                    <div class="t-icon {{ $iconTone }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="t-details">
                        <div class="t-title">{{ Str::limit($title, 28) }}</div>
                        <div class="t-date">{{ $dateText }}{{ $category !== '' ? ' • '.$category : '' }}</div>
                    </div>
                    <div>
                        <div class="t-amount {{ $amountTone }}">
                            {{ $sign }} ₹{{ number_format((float) ($transaction['amount'] ?? 0), 2) }}
                        </div>
                        @if($balance !== null)
                            <div class="t-balance">Bal: ₹{{ number_format((float) $balance, 2) }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <div class="mb-3"><i class="fas fa-receipt fa-3x opacity-25"></i></div>
                    <p class="mb-0">No transactions found</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                 {{ $transactions->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
