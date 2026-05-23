@extends('user.layouts.app')

@section('title', 'Rewards')
@section('hide_mobile_navbar', '1')
@section('hide_desktop_header', '1')

@push('styles')
<style>
    .reward-hero {
        background: var(--primary-gradient);
        border-radius: 18px;
        color: #fff;
        overflow: hidden;
        position: relative;
    }
    .reward-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(1000px 350px at 0% 0%, rgba(255,255,255,0.20), transparent 60%),
            radial-gradient(900px 300px at 100% 0%, rgba(255,255,255,0.15), transparent 55%),
            radial-gradient(700px 260px at 50% 100%, rgba(0,0,0,0.12), transparent 60%);
        pointer-events: none;
    }
    .reward-hero-inner {
        position: relative;
        padding: 22px 18px;
    }
    .hero-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: rgba(255,255,255,0.16);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .hero-meta {
        opacity: 0.92;
        font-size: 0.95rem;
    }
    .hero-stat {
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 14px;
        padding: 10px 12px;
        min-width: 150px;
    }
    .hero-stat .label {
        font-size: 0.75rem;
        opacity: 0.9;
    }
    .hero-stat .value {
        font-weight: 800;
        font-size: 1.05rem;
        line-height: 1.1;
        color: #fff !important;
    }
    .summary-card {
        border: none;
        border-radius: 18px;
        background: var(--card-bg);
        box-shadow: 0 10px 28px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .summary-strip {
        background: linear-gradient(135deg, rgba(213,63,140,0.10), rgba(128,90,213,0.10));
        border-bottom: 1px solid var(--border-color);
    }
    .metric {
        padding: 14px 14px;
    }
    .metric .k {
        font-size: 0.78rem;
        color: var(--muted-text);
    }
    .metric .v {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-dark);
    }
    .reward-tabs .nav-link {
        border-radius: 999px;
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        background: var(--card-bg);
        font-weight: 600;
        padding: 10px 14px;
    }
    .reward-tabs .nav-link.active {
        background: var(--primary-gradient);
        border-color: transparent;
        color: #fff;
    }
    .reward-card {
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        border-radius: 18px;
        padding: 16px 16px;
        box-shadow: 0 10px 26px rgba(0,0,0,0.05);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .reward-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 34px rgba(0,0,0,0.08);
    }
    .reward-card:active {
        transform: translateY(0);
    }
    .reward-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }
    .reward-badge {
        font-size: 0.72rem;
        border-radius: 999px;
        padding: 6px 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-light);
        color: var(--text-dark);
        font-weight: 700;
    }
    .reward-amount {
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--text-dark);
        line-height: 1.1;
    }
    .reward-amount small {
        font-weight: 700;
        color: var(--muted-text);
        font-size: 0.9rem;
    }
    .reward-title {
        font-weight: 800;
        color: var(--text-dark);
        margin: 0;
    }
    .reward-subtitle {
        margin: 0;
        color: var(--muted-text);
        font-size: 0.92rem;
    }
    .reward-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 12px;
        color: var(--muted-text);
        font-size: 0.85rem;
    }
    .reward-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: var(--pink-highlight);
        text-decoration: none;
        background: transparent;
        border: none;
        padding: 0;
    }
    .scratch-modal .modal-content {
        border-radius: 18px;
        border: none;
        overflow: hidden;
        background: var(--card-bg);
        color: var(--text-dark);
    }
    .scratch-head {
        background: var(--primary-gradient);
        color: #fff;
        padding: 14px 16px;
    }
    .scratch-wrap {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        border: 1px dashed rgba(0,0,0,0.12);
        background: linear-gradient(135deg, rgba(213,63,140,0.12), rgba(128,90,213,0.12));
    }
    .scratch-prize {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 14px;
    }
    .scratch-prize .big {
        font-size: 2rem;
        font-weight: 900;
        line-height: 1;
        color: var(--text-dark);
    }
    .scratch-prize .small {
        margin-top: 6px;
        color: var(--muted-text);
        font-weight: 600;
        font-size: 0.95rem;
    }
    #scratchCanvas {
        position: relative;
        width: 100%;
        height: 170px;
        display: block;
        touch-action: none;
    }
</style>
@endpush

@section('content')
<div class="reward-hero mb-4">
    <div class="reward-hero-inner">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="hero-icon"><i class="fas fa-gift"></i></div>
                <div>
                    <h4 class="mb-1" style="color:#fff !important;">Rewards</h4>
                    <div class="hero-meta">Scratch, unlock, and redeem your cashback rewards</div>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-stretch flex-wrap">
                <div class="hero-stat">
                    <div class="label">Total Rewards</div>
                    <div class="value">₹{{ number_format((float) ($summary['total_earned'] ?? 0), 2) }}</div>
                </div>
                <div class="hero-stat">
                    <div class="label">Available Now</div>
                    <div class="value">₹{{ number_format((float) ($summary['available_amount'] ?? 0), 2) }}</div>
                </div>
                <a href="{{ route('user.dashboard') }}" class="btn btn-light rounded-pill px-3 fw-semibold align-self-center">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="summary-card mb-4">
    <div class="summary-strip">
        <div class="row g-0">
            <div class="col-6 col-md-3">
                <div class="metric">
                    <div class="k">Available</div>
                    <div class="v">{{ (int) ($summary['available_count'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="metric">
                    <div class="k">Redeemed</div>
                    <div class="v">{{ (int) ($summary['redeemed_count'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="metric">
                    <div class="k">Expired</div>
                    <div class="v">{{ (int) ($summary['expired_count'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="metric">
                    <div class="k">Redeemed Value</div>
                    <div class="v">₹{{ number_format((float) ($summary['redeemed_amount'] ?? 0), 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-3 p-md-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <ul class="nav nav-pills reward-tabs gap-2" id="rewardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" type="button" data-filter="all">
                        All <span class="badge bg-light text-dark ms-1">{{ $rewards->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" type="button" data-filter="available">
                        Available <span class="badge bg-success-subtle text-success ms-1">{{ (int) ($summary['available_count'] ?? 0) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" type="button" data-filter="redeemed">
                        Redeemed <span class="badge bg-secondary-subtle text-secondary ms-1">{{ (int) ($summary['redeemed_count'] ?? 0) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" type="button" data-filter="expired">
                        Expired <span class="badge bg-danger-subtle text-danger ms-1">{{ (int) ($summary['expired_count'] ?? 0) }}</span>
                    </button>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="rewardSearch" placeholder="Search rewards">
                </div>
            </div>
        </div>

        <div class="row g-3" id="rewardGrid">
            @forelse($rewards as $reward)
                @php
                    $status = (string) ($reward['status'] ?? 'available');
                    $badgeClass = $status === 'redeemed' ? 'bg-secondary-subtle text-secondary' : ($status === 'expired' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success');
                    $badgeText = $status === 'redeemed' ? 'Redeemed' : ($status === 'expired' ? 'Expired' : 'Available');
                @endphp
                <div class="col-12 col-md-6 col-xl-4 reward-card-col"
                     data-status="{{ $status }}"
                     data-reward-id="{{ (string) ($reward['id'] ?? '') }}"
                     data-title="{{ e((string) ($reward['title'] ?? 'Reward')) }}"
                     data-subtitle="{{ e((string) ($reward['subtitle'] ?? '')) }}"
                     data-amount="{{ (float) ($reward['amount'] ?? 0) }}"
                     data-earned="{{ e((string) ($reward['earned_at'] ?? '')) }}"
                     data-expires="{{ e((string) ($reward['expires_at'] ?? '')) }}">
                    <button type="button" class="reward-card w-100 text-start" style="cursor:pointer;">
                        <div class="reward-card-top">
                            <span class="reward-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            <div class="reward-amount">
                                <small>₹</small>{{ number_format((float) ($reward['amount'] ?? 0), 2) }}
                            </div>
                        </div>
                        <h6 class="reward-title">{{ $reward['title'] ?? 'Reward' }}</h6>
                        <p class="reward-subtitle">{{ $reward['subtitle'] ?? '' }}</p>
                        <div class="reward-meta">
                            <span><i class="fas fa-calendar-alt me-1"></i>{{ $reward['earned_at'] ?? '-' }}</span>
                            <span class="d-inline-flex align-items-center gap-2">
                                <span class="text-muted">Expires:</span> {{ $reward['expires_at'] ?? '-' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <span class="reward-cta">
                                <i class="fas fa-hand-pointer"></i>
                                Scratch to view
                            </span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </button>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-gift mb-3" style="font-size: 40px; opacity: .35;"></i>
                        <div class="fw-semibold">No rewards yet</div>
                        <div class="small">Your earned rewards will appear here once available.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="modal fade scratch-modal" id="scratchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="scratch-head d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-ticket-alt"></i>
                    <span class="fw-semibold" id="scratchTitle">Scratch Reward</span>
                </div>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="mb-3 text-muted small" id="scratchSubtitle">Scratch the card to reveal your cashback</div>

                <div class="scratch-wrap mb-3" id="scratchWrap">
                    <div class="scratch-prize">
                        <div>
                            <div class="big" id="scratchAmount">₹0.00</div>
                            <div class="small" id="scratchHint">Keep scratching to unlock</div>
                        </div>
                    </div>
                    <canvas id="scratchCanvas" width="600" height="170"></canvas>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="small text-muted" id="scratchMeta"></div>
                    <a class="btn btn-primary rounded-pill px-4 fw-semibold disabled" id="scratchViewBtn" href="#" aria-disabled="true">
                        View details <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const grid = document.getElementById('rewardGrid');
        const tabs = document.getElementById('rewardTabs');
        const search = document.getElementById('rewardSearch');
        const modalEl = document.getElementById('scratchModal');
        const canvas = document.getElementById('scratchCanvas');
        const scratchWrap = document.getElementById('scratchWrap');
        const titleEl = document.getElementById('scratchTitle');
        const subtitleEl = document.getElementById('scratchSubtitle');
        const amountEl = document.getElementById('scratchAmount');
        const hintEl = document.getElementById('scratchHint');
        const metaEl = document.getElementById('scratchMeta');
        const viewBtn = document.getElementById('scratchViewBtn');

        if (!grid || !tabs || !modalEl || !canvas) return;

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const ctx = canvas.getContext('2d');
        let drawing = false;
        let revealed = false;
        let activeFilter = 'all';
        let activeQuery = '';
        let checkTimer = null;
        let currentReward = null;

        const formatMoney = (val) => {
            const n = Number(val || 0);
            return '₹' + n.toFixed(2);
        };

        const setViewEnabled = (enabled, href) => {
            if (enabled) {
                viewBtn.classList.remove('disabled');
                viewBtn.removeAttribute('aria-disabled');
                viewBtn.href = href || '#';
            } else {
                viewBtn.classList.add('disabled');
                viewBtn.setAttribute('aria-disabled', 'true');
                viewBtn.href = '#';
            }
        };

        const resizeCanvasToWrap = () => {
            const rect = scratchWrap.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            const w = Math.max(320, Math.floor(rect.width));
            const h = 170;
            canvas.style.height = h + 'px';
            canvas.style.width = w + 'px';
            canvas.width = Math.floor(w * dpr);
            canvas.height = Math.floor(h * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        };

        const drawCover = () => {
            resizeCanvasToWrap();
            ctx.globalCompositeOperation = 'source-over';
            const w = canvas.width / (window.devicePixelRatio || 1);
            const h = canvas.height / (window.devicePixelRatio || 1);
            const grad = ctx.createLinearGradient(0, 0, w, h);
            grad.addColorStop(0, '#cbd5e1');
            grad.addColorStop(1, '#94a3b8');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, w, h);
            ctx.fillStyle = 'rgba(255,255,255,0.35)';
            for (let i = 0; i < 14; i++) {
                ctx.fillRect((w / 14) * i, 0, 1, h);
            }
            ctx.fillStyle = 'rgba(0,0,0,0.15)';
            ctx.font = '700 18px system-ui, -apple-system, Segoe UI, Roboto, Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('SCRATCH HERE', w / 2, h / 2);
        };

        const getCanvasPoint = (e) => {
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX - rect.left);
            const y = (e.clientY - rect.top);
            return { x, y };
        };

        const scratchAt = (x, y) => {
            ctx.globalCompositeOperation = 'destination-out';
            ctx.beginPath();
            ctx.arc(x, y, 18, 0, Math.PI * 2);
            ctx.fill();
        };

        const scratchedPercent = () => {
            const w = canvas.width;
            const h = canvas.height;
            const data = ctx.getImageData(0, 0, w, h).data;
            const step = 48;
            let total = 0;
            let cleared = 0;
            for (let i = 3; i < data.length; i += step) {
                total++;
                if (data[i] === 0) cleared++;
            }
            return total ? (cleared / total) * 100 : 0;
        };

        const maybeReveal = () => {
            if (revealed) return;
            const p = scratchedPercent();
            if (p >= 45) {
                revealed = true;
                hintEl.textContent = 'Unlocked! Tap View details';
                setViewEnabled(true, currentReward?.detailsUrl);
            }
        };

        const resetScratch = () => {
            drawing = false;
            revealed = false;
            if (checkTimer) {
                clearTimeout(checkTimer);
                checkTimer = null;
            }
            drawCover();
            hintEl.textContent = 'Keep scratching to unlock';
            setViewEnabled(false);
        };

        canvas.addEventListener('pointerdown', (e) => {
            if (!currentReward) return;
            if (revealed) return;
            drawing = true;
            canvas.setPointerCapture(e.pointerId);
            const p = getCanvasPoint(e);
            scratchAt(p.x, p.y);
        });

        canvas.addEventListener('pointermove', (e) => {
            if (!drawing || !currentReward || revealed) return;
            const p = getCanvasPoint(e);
            scratchAt(p.x, p.y);
            if (!checkTimer) {
                checkTimer = setTimeout(() => {
                    checkTimer = null;
                    maybeReveal();
                }, 180);
            }
        });

        canvas.addEventListener('pointerup', () => {
            if (!drawing) return;
            drawing = false;
            maybeReveal();
        });

        const applyFilters = () => {
            const tiles = Array.from(grid.querySelectorAll('.reward-card-col'));
            const q = (activeQuery || '').trim().toLowerCase();
            tiles.forEach((tile) => {
                const status = (tile.getAttribute('data-status') || 'available').toLowerCase();
                const title = (tile.getAttribute('data-title') || '').toLowerCase();
                const subtitle = (tile.getAttribute('data-subtitle') || '').toLowerCase();
                const matchesTab = activeFilter === 'all' || status === activeFilter;
                const matchesQ = !q || title.includes(q) || subtitle.includes(q);
                tile.style.display = (matchesTab && matchesQ) ? '' : 'none';
            });
        };

        tabs.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-filter]');
            if (!btn) return;
            activeFilter = btn.getAttribute('data-filter') || 'all';
            Array.from(tabs.querySelectorAll('.nav-link')).forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            applyFilters();
        });

        if (search) {
            search.addEventListener('input', () => {
                activeQuery = search.value || '';
                applyFilters();
            });
        }

        grid.addEventListener('click', (e) => {
            const tile = e.target.closest('.reward-card-col');
            if (!tile) return;

            const rewardId = tile.getAttribute('data-reward-id') || '';
            const title = tile.getAttribute('data-title') || 'Reward';
            const subtitle = tile.getAttribute('data-subtitle') || 'Scratch to unlock your cashback';
            const amount = tile.getAttribute('data-amount') || '0';
            const earned = tile.getAttribute('data-earned') || '';
            const expires = tile.getAttribute('data-expires') || '';
            const detailsUrl = "{{ route('user.service.report.reward.show', ['reward' => '___ID___']) }}".replace('___ID___', encodeURIComponent(rewardId));

            currentReward = { rewardId, title, subtitle, amount, earned, expires, detailsUrl };
            titleEl.textContent = title;
            subtitleEl.textContent = subtitle || 'Scratch the card to reveal your cashback';
            amountEl.textContent = formatMoney(amount);
            metaEl.textContent = (earned ? ('Earned: ' + earned + '  •  ') : '') + (expires ? ('Expires: ' + expires) : '');

            resetScratch();
            modal.show();
        });

        modalEl.addEventListener('hidden.bs.modal', () => {
            currentReward = null;
            resetScratch();
        });

        window.addEventListener('resize', () => {
            if (!modalEl.classList.contains('show')) return;
            drawCover();
        });

        applyFilters();
    })();
</script>
@endpush
