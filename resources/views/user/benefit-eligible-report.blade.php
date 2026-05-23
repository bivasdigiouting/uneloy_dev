<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Benefits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        :root {
            --benefit-bg: #f4eff8;
            --benefit-surface: #ffffff;
            --benefit-text: #1f2937;
            --benefit-muted: #6b7280;
            --benefit-purple: #a855f7;
            --benefit-purple-soft: #f2e7ff;
            --benefit-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            --benefit-radius: 18px;
        }

        body {
            background: var(--benefit-bg);
            color: var(--benefit-text);
            font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        }

        .benefit-shell {
            max-width: 520px;
            margin: 0 auto;
            min-height: 100vh;
        }

        .benefit-appbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: linear-gradient(180deg, #efe6f7 0%, #f4eff8 100%);
            padding: 14px 16px 10px;
        }

        .benefit-appbar-row {
            display: grid;
            grid-template-columns: 42px 1fr 42px;
            align-items: center;
        }

        .benefit-back {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--benefit-text);
            text-decoration: none;
        }

        .benefit-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.2px;
            margin: 0;
        }

        .benefit-content {
            padding: 12px 16px 24px;
        }

        .benefit-search {
            background: var(--benefit-surface);
            border-radius: var(--benefit-radius);
            box-shadow: var(--benefit-shadow);
            padding: 12px 14px;
            display: flex;
            gap: 12px;
            align-items: center;
            border: 1px solid rgba(148, 163, 184, 0.22);
        }

        .benefit-search i {
            color: #94a3b8;
            font-size: 18px;
        }

        .benefit-search input {
            border: 0;
            outline: none;
            width: 100%;
            font-size: 18px;
            background: transparent;
            color: var(--benefit-text);
        }

        .benefit-grid {
            margin-top: 14px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .benefit-tile {
            background: var(--benefit-surface);
            border-radius: var(--benefit-radius);
            box-shadow: var(--benefit-shadow);
            padding: 14px 10px 12px;
            text-decoration: none;
            color: var(--benefit-text);
            border: 1px solid rgba(148, 163, 184, 0.18);
            min-height: 112px;
            display: grid;
            align-content: center;
            justify-items: center;
            gap: 10px;
            transition: transform 120ms ease, box-shadow 120ms ease, border-color 120ms ease;
        }

        .benefit-tile:active {
            transform: scale(0.99);
        }

        .benefit-tile.selected {
            border-color: rgba(168, 85, 247, 0.45);
            box-shadow: 0 14px 32px rgba(168, 85, 247, 0.12);
        }

        .benefit-tile .benefit-icon {
            width: 48px;
            height: 48px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: var(--benefit-purple-soft);
            color: var(--benefit-purple);
            font-size: 22px;
        }

        .benefit-tile .benefit-label {
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.2px;
            text-align: center;
            line-height: 1.15;
            word-break: break-word;
        }

        .benefit-section-title {
            margin: 22px 2px 12px;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .benefit-list {
            display: grid;
            gap: 12px;
        }

        .benefit-rowcard {
            background: var(--benefit-surface);
            border-radius: 22px;
            box-shadow: var(--benefit-shadow);
            padding: 14px 14px;
            text-decoration: none;
            color: var(--benefit-text);
            border: 1px solid rgba(148, 163, 184, 0.18);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .benefit-rowcard .rowicon {
            width: 54px;
            height: 54px;
            border-radius: 22px;
            display: grid;
            place-items: center;
            background: var(--benefit-purple-soft);
            color: var(--benefit-purple);
            flex: 0 0 auto;
            font-size: 20px;
        }

        .benefit-rowcard .rowtext {
            min-width: 0;
            flex: 1 1 auto;
        }

        .benefit-rowcard .rowtitle {
            font-size: 22px;
            font-weight: 800;
            margin: 0;
            line-height: 1.1;
        }

        .benefit-rowcard .rowsub {
            margin: 4px 0 0;
            color: var(--benefit-muted);
            font-size: 16px;
            line-height: 1.25;
        }

        .benefit-rowcard .chev {
            color: #94a3b8;
            font-size: 22px;
            padding-left: 4px;
        }

        .benefit-empty {
            padding: 22px 6px 0;
            color: #9ca3af;
            font-size: 20px;
            text-align: center;
        }

        .benefit-eligible-card {
            background: var(--benefit-surface);
            border-radius: 22px;
            box-shadow: var(--benefit-shadow);
            padding: 14px 14px;
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .benefit-eligible-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 6px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }

        .benefit-eligible-item:last-child {
            border-bottom: 0;
        }

        .benefit-eligible-name {
            font-weight: 700;
            font-size: 16px;
            margin: 0;
        }

        .benefit-pill {
            border-radius: 999px;
            padding: 7px 12px;
            font-weight: 800;
            font-size: 13px;
            border: 1px solid transparent;
        }

        .benefit-pill.yes {
            background: rgba(34, 197, 94, 0.10);
            color: #15803d;
            border-color: rgba(34, 197, 94, 0.18);
        }

        .benefit-pill.no {
            background: rgba(239, 68, 68, 0.10);
            color: #b91c1c;
            border-color: rgba(239, 68, 68, 0.18);
        }

        @media (max-width: 380px) {
            .benefit-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .benefit-section-title {
                font-size: 26px;
            }

            .benefit-rowcard .rowtitle {
                font-size: 20px;
            }

            .benefit-rowcard .rowsub {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    @php
        $benefitTiles = [
            ['label' => 'SFD-E-CARD', 'icon' => 'fa-wand-magic-sparkles', 'href' => route('user.benefit.card.show', ['benefit' => 'sfd-e-card'])],
            ['label' => 'Esewa E-CARD', 'icon' => 'fa-trophy', 'href' => route('user.benefit.card.show', ['benefit' => 'esewa-e-card'])],
            ['label' => 'EPF-E-CARD', 'icon' => 'fa-handshake', 'href' => route('user.benefit.card.show', ['benefit' => 'epf-e-card'])],
            ['label' => 'BENEFITS E.P.S', 'icon' => 'fa-map', 'href' => route('user.benefit.card.show', ['benefit' => 'benefits-eps'])],
            ['label' => 'BENEFITS 02', 'icon' => 'fa-circle-dollar-to-slot', 'href' => route('user.benefit.card.show', ['benefit' => 'benefits-02'])],
            ['label' => 'BENEFITS 01', 'icon' => 'fa-wallet', 'href' => route('user.benefit.card.show', ['benefit' => 'benefits-01'])],
        ];

        $financialReports = [
            [
                'title' => 'View Global Fund Report',
                'subtitle' => 'Track your distributed funds and amounts.',
                'icon' => 'fa-chart-column',
                'href' => route('user.service.report.global.disbursement.fund'),
            ],
            [
                'title' => 'Physically Challenged Fund',
                'subtitle' => 'View report for physically challenged funds.',
                'icon' => 'fa-wheelchair',
                'href' => route('user.service.report.physically.challenged.fund'),
            ],
        ];

        $backHref = route('user.dashboard');
        try {
            $prev = url()->previous();
            if (is_string($prev) && $prev !== url()->current()) {
                $backHref = $prev;
            }
        } catch (\Throwable $e) {
        }
    @endphp

    <div class="benefit-shell">
        <div class="benefit-appbar">
            <div class="benefit-appbar-row">
                <a class="benefit-back" href="{{ $backHref }}" aria-label="Back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="benefit-title">Select Benefits</h1>
                <div></div>
            </div>
        </div>

        <div class="benefit-content">
            <div class="benefit-search">
                <i class="fas fa-magnifying-glass"></i>
                <input id="benefitSearch" type="text" inputmode="search" autocomplete="off" placeholder="Search Benefits">
            </div>

            <div id="benefitGrid" class="benefit-grid">
                @foreach($benefitTiles as $tile)
                    <a href="{{ $tile['href'] }}" class="benefit-tile" data-benefit-name="{{ strtolower($tile['label']) }}">
                        <div class="benefit-icon">
                            <i class="fas {{ $tile['icon'] }}"></i>
                        </div>
                        <div class="benefit-label">{{ $tile['label'] }}</div>
                    </a>
                @endforeach
            </div>

            <div class="benefit-section-title">Financial Reports</div>
            <div class="benefit-list">
                @foreach($financialReports as $report)
                    <a class="benefit-rowcard" href="{{ $report['href'] }}">
                        <div class="rowicon"><i class="fas {{ $report['icon'] }}"></i></div>
                        <div class="rowtext">
                            <p class="rowtitle">{{ $report['title'] }}</p>
                            <p class="rowsub">{{ $report['subtitle'] }}</p>
                        </div>
                        <div class="chev"><i class="fas fa-chevron-right"></i></div>
                    </a>
                @endforeach
            </div>

            <div class="benefit-section-title">Related Services</div>
            <div class="benefit-empty">No related services available.</div>

            <div class="benefit-section-title">Eligible Report</div>
            @if(isset($eligibility) && is_iterable($eligibility) && count($eligibility))
                <div class="benefit-eligible-card">
                    @foreach($eligibility as $row)
                        @php
                            $scheme = is_array($row) ? ($row['scheme'] ?? '-') : ($row->scheme ?? '-');
                            $eligible = is_array($row) ? ($row['eligible'] ?? false) : ($row->eligible ?? false);
                            $eligible = filter_var($eligible, FILTER_VALIDATE_BOOL);
                        @endphp
                        <div class="benefit-eligible-item">
                            <p class="benefit-eligible-name">{{ $scheme }}</p>
                            <span class="benefit-pill {{ $eligible ? 'yes' : 'no' }}">{{ $eligible ? 'Eligible' : 'Not eligible' }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="benefit-empty">No eligible reports available.</div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
    <script>
        (function () {
            const search = document.getElementById('benefitSearch');
            const grid = document.getElementById('benefitGrid');
            if (!search || !grid) return;

            const tiles = Array.from(grid.querySelectorAll('.benefit-tile'));

            const filterTiles = () => {
                const q = (search.value || '').trim().toLowerCase();
                tiles.forEach((tile) => {
                    const name = (tile.getAttribute('data-benefit-name') || '').toLowerCase();
                    const visible = !q || name.includes(q);
                    tile.style.display = visible ? '' : 'none';
                });
            };

            search.addEventListener('input', filterTiles);
            filterTiles();
        })();
    </script>
</body>

</html>
