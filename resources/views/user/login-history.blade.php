<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login History - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        :root {
            /* Removed as handled by global theme styles */
            /* --primary-gradient: linear-gradient(135deg, #D53F8C 0%, #805AD5 100%); */
            /* --card-bg: #ffffff; */
            /* --text-dark: #333333; */
            /* --text-muted: #718096; */
            /* --bg-light: #f3f4f6; */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        /* Common Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 20px;
            color: var(--text-dark);
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
            color: var(--text-dark);
        }

        /* History Item */
        .history-item {
            padding: 15px 0;
            border-bottom: 1px solid var(--muted-text);
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .label {
            color: var(--muted-text);
            font-weight: 400;
        }

        .value {
            color: var(--text-dark);
            font-weight: 600;
            text-align: right;
        }

        .ip-badge {
            background-color: var(--bg-light);
            padding: 2px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            color: var(--text-dark);
            border: 1px solid var(--muted-text);
        }
        
        .form-control {
            background-color: var(--bg-light);
            border: 1px solid var(--muted-text);
            color: var(--text-dark);
        }
        
        .form-control:focus {
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

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
            <a href="{{ route('user.profile') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">Login History</div>
        </div>

        <!-- Filter Section -->
        <div class="content-card">
            <form action="{{ route('user.manage.login-history') }}" method="GET">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="small text-muted mb-1">From</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-6">
                        <label class="small text-muted mb-1">To</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm w-100" style="background: var(--primary-gradient); border: none;">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- History List -->
        <div class="content-card mt-0">
            <h6 class="mb-3 fw-bold">Recent Logins</h6>
            
            @forelse($loginHistories as $history)
                <div class="history-item">
                    <div class="history-row">
                        <span class="label"><i class="fas fa-sign-in-alt me-2 text-success"></i>Login</span>
                        <span class="value">{{ $history->logged_in_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <div class="history-row">
                        <span class="label"><i class="fas fa-globe me-2 text-primary"></i>IP Address</span>
                        <span class="value"><span class="ip-badge">{{ $history->ip_address }}</span></span>
                    </div>
                    @if($history->logged_out_at)
                    <div class="history-row">
                        <span class="label"><i class="fas fa-sign-out-alt me-2 text-danger"></i>Logout</span>
                        <span class="value">{{ $history->logged_out_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @else
                    <div class="history-row">
                        <span class="label"><i class="fas fa-clock me-2 text-warning"></i>Status</span>
                        <span class="value text-success">Active Session</span>
                    </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <div class="mb-3"><i class="fas fa-history fa-3x opacity-25"></i></div>
                    <p class="mb-0">No login history found</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                 {{ $loginHistories->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>