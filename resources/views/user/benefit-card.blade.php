<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $benefitData['title'] ?? 'Benefit' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        :root {
            --screen-bg: #ffffff;
            --surface: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --purple: #a855f7;
            --purple-soft: rgba(168, 85, 247, 0.12);
            --green: #22c55e;
            --chip-blue: rgba(59, 130, 246, 0.14);
            --chip-blue-text: #3b82f6;
            --radius-lg: 22px;
            --shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        body {
            background: var(--screen-bg);
            color: var(--text);
            font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        }

        .screen {
            max-width: 520px;
            margin: 0 auto;
            min-height: 100vh;
        }

        .appbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            padding: 14px 16px 10px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }

        .appbar-row {
            display: grid;
            grid-template-columns: 42px 1fr 42px;
            align-items: center;
        }

        .back {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            text-decoration: none;
        }

        .title {
            text-align: center;
            font-size: 26px;
            font-weight: 400;
            letter-spacing: 0.4px;
            margin: 0;
        }

        .content {
            padding: 16px;
        }

        .card-shell {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 18px;
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .metric-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .metric-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 999px;
            background: var(--chip-blue);
            color: var(--chip-blue-text);
            font-weight: 700;
            font-size: 16px;
            min-width: 92px;
        }

        .metric-amount {
            font-weight: 800;
            font-size: 22px;
            color: var(--green);
            letter-spacing: 0.2px;
        }

        .top-benefits-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
            margin-bottom: 12px;
        }

        .info-dot {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--purple-soft);
            color: var(--purple);
            font-size: 16px;
        }

        .top-benefits-title {
            color: var(--purple);
            font-weight: 800;
            font-size: 26px;
            margin: 0;
        }

        .benefits-box {
            background: #f3f4f6;
            border-radius: 18px;
            padding: 18px;
            color: #374151;
            font-size: 20px;
            line-height: 1.35;
        }

        .status-row {
            display: flex;
            gap: 14px;
            justify-content: center;
            padding-top: 18px;
        }

        .status-pill {
            background: #ededed;
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 700;
            color: #6b7280;
            min-width: 140px;
            text-align: center;
        }

        @media (max-width: 380px) {
            .title {
                font-size: 24px;
            }

            .benefits-box {
                font-size: 18px;
            }

            .status-pill {
                min-width: 128px;
                padding: 11px 14px;
            }
        }
    </style>
</head>

<body>
    @php
        $backHref = route('user.benefit.eligible.report');
        try {
            $prev = url()->previous();
            if (is_string($prev) && $prev !== url()->current()) {
                $backHref = $prev;
            }
        } catch (\Throwable $e) {
        }
    @endphp

    <div class="screen">
        <div class="appbar">
            <div class="appbar-row">
                <a class="back" href="{{ $backHref }}" aria-label="Back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="title">{{ $benefitData['title'] ?? 'Benefit' }}</h1>
                <div></div>
            </div>
        </div>

        <div class="content">
            <div class="card-shell">
                <div class="metric-row">
                    <div class="metric-chip">{{ $benefitData['metric_label'] ?? 'Years' }}</div>
                    <div class="metric-amount">₹{{ number_format((float) ($benefitData['amount'] ?? 0), 2, '.', '') }}</div>
                </div>

                <div class="top-benefits-row">
                    <div class="info-dot"><i class="fas fa-info"></i></div>
                    <p class="top-benefits-title">Top Benefits</p>
                </div>

                <div class="benefits-box">
                    {{ $benefitData['top_benefits'] ?? '' }}
                </div>
            </div>

            <div class="status-row">
                <div class="status-pill">Active: {{ $benefitData['active'] ?? 'Y' }}</div>
                <div class="status-pill">Deleted: {{ $benefitData['deleted'] ?? 'N' }}</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>

</html>
