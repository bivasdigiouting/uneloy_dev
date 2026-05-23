@extends('user.layouts.app')

@section('title', 'Global Disbur. Fund Report')

@push('styles')
<style>
    .report-hero {
        background: var(--primary-gradient);
        color: #fff;
        padding: 16px 0 18px;
    }

    .report-hero-inner {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 12px;
    }

    .report-back {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.22);
        flex: 0 0 auto;
    }

    .report-hero-text {
        min-width: 0;
        flex: 1 1 auto;
    }

    .report-title {
        font-weight: 800;
        font-size: 20px;
        line-height: 1.15;
        margin: 0;
        letter-spacing: 0.2px;
    }

    .report-subtitle {
        margin: 4px 0 0;
        opacity: 0.88;
        font-size: 13px;
        line-height: 1.25;
    }

    .report-hero-icon {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.22);
        font-size: 18px;
        flex: 0 0 auto;
    }

    .report-shell {
        max-width: 560px;
        margin: 0 auto;
        padding: 14px 12px 26px;
    }

    .report-card {
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 18px;
        background: var(--card-bg);
        color: var(--text-dark);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.06);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    .stat {
        padding: 14px 14px;
    }

    .stat-label {
        color: var(--muted-text);
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.25px;
        text-transform: uppercase;
    }

    .stat-value {
        margin-top: 6px;
        font-weight: 900;
        font-size: 18px;
        letter-spacing: 0.2px;
        line-height: 1.1;
    }

    .filters {
        padding: 12px;
        display: grid;
        gap: 10px;
        margin-bottom: 12px;
    }

    .field {
        background: rgba(148, 163, 184, 0.10);
        border-radius: 16px;
        padding: 10px 12px;
        display: flex;
        gap: 10px;
        align-items: center;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .field i {
        color: rgba(100, 116, 139, 0.9);
        font-size: 16px;
        width: 18px;
        text-align: center;
        flex: 0 0 auto;
    }

    .field select,
    .field input {
        background: transparent;
        border: 0;
        outline: none;
        width: 100%;
        color: var(--text-dark);
        font-weight: 700;
        font-size: 14px;
        padding: 0;
    }

    .field select:focus,
    .field input:focus {
        box-shadow: none;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
    }

    .btn-clear {
        width: 100%;
        border-radius: 16px;
        padding: 12px 14px;
        font-weight: 800;
        border: 1px solid rgba(148, 163, 184, 0.22);
        background: transparent;
        color: var(--text-dark);
    }

    .fund-cards {
        display: grid;
        gap: 12px;
    }

    .fund-card {
        padding: 14px 14px;
        display: grid;
        gap: 10px;
    }

    .fund-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .fund-region {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 900;
        font-size: 14px;
        letter-spacing: 0.2px;
        min-width: 0;
    }

    .fund-region-badge {
        width: 36px;
        height: 36px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        background: rgba(99, 102, 241, 0.12);
        color: rgba(99, 102, 241, 1);
        border: 1px solid rgba(99, 102, 241, 0.18);
        flex: 0 0 auto;
        font-size: 14px;
    }

    .fund-date {
        color: var(--muted-text);
        font-weight: 800;
        font-size: 12px;
        white-space: nowrap;
    }

    .fund-amount {
        font-weight: 900;
        font-size: 20px;
        letter-spacing: 0.2px;
        line-height: 1.1;
    }

    .empty {
        padding: 18px 10px;
        text-align: center;
        color: var(--muted-text);
        font-weight: 700;
    }

    @media (max-width: 380px) {
        .report-title {
            font-size: 18px;
        }

        .stat-value {
            font-size: 16px;
        }

        .fund-amount {
            font-size: 18px;
        }
    }

    @media (max-width: 991.98px) {
        body > .d-lg-none {
            display: none !important;
        }

        .desktop-layout-wrapper main.py-4 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .desktop-layout-wrapper main.py-4 > .container-fluid.px-4 {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $backHref = route('user.dashboard');
    try {
        $prev = url()->previous();
        if (is_string($prev) && $prev !== url()->current()) {
            $backHref = $prev;
        }
    } catch (\Throwable $e) {
    }

    $totalAmount = 0;
    $rowCount = 0;
    $regions = [];
    foreach ($funds ?? [] as $row) {
        $rowCount++;
        $totalAmount += (float) ($row['amount'] ?? 0);
        $region = (string) ($row['region'] ?? '');
        if ($region !== '') {
            $regions[$region] = true;
        }
    }
    $regionOptions = array_keys($regions);
    sort($regionOptions);
@endphp

<div class="container-fluid px-0">
    <div class="report-hero">
        <div class="container">
            <div class="report-hero-inner">
                <a class="report-back" href="{{ $backHref }}" aria-label="Back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="report-hero-text">
                    <p class="report-title">Global Disbur. Fund</p>
                    <p class="report-subtitle">Track your distributed funds and amounts.</p>
                </div>
                <div class="report-hero-icon" aria-hidden="true">
                    <i class="fas fa-globe"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="report-shell">
        <div class="stats-grid">
            <div class="report-card stat">
                <div class="stat-label">Total Amount</div>
                <div id="totalAmount" class="stat-value">₹ {{ number_format($totalAmount, 2) }}</div>
            </div>
            <div class="report-card stat">
                <div class="stat-label">Records</div>
                <div id="totalCount" class="stat-value">{{ $rowCount }}</div>
            </div>
        </div>

        <div class="report-card filters">
            <div class="field">
                <i class="fas fa-location-dot"></i>
                <select id="regionFilter" aria-label="Region filter">
                    <option value="">All Regions</option>
                    @foreach($regionOptions as $region)
                        <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <i class="fas fa-calendar"></i>
                <input id="monthFilter" type="month" inputmode="numeric" aria-label="Month filter">
            </div>
            <div class="field">
                <i class="fas fa-magnifying-glass"></i>
                <input id="searchFilter" type="text" inputmode="search" autocomplete="off" placeholder="Search region or date" aria-label="Search">
            </div>
            <div class="filter-actions">
                <button id="clearFilters" type="button" class="btn-clear">Clear Filters</button>
            </div>
        </div>

        <div class="d-md-none">
            <div id="fundCards" class="fund-cards">
                @forelse($funds as $row)
                    @php
                        $region = (string) ($row['region'] ?? '');
                        $date = (string) ($row['date'] ?? '');
                        $amount = (float) ($row['amount'] ?? 0);
                        $month = '';
                        if (strlen($date) >= 7) {
                            $month = substr($date, 0, 7);
                        }
                        $search = strtolower(trim($region.' '.$date));
                    @endphp
                    <div class="report-card fund-card" data-region="{{ $region }}" data-month="{{ $month }}" data-search="{{ $search }}" data-amount="{{ $amount }}">
                        <div class="fund-top">
                            <div class="fund-region">
                                <span class="fund-region-badge"><i class="fas fa-globe"></i></span>
                                <span>{{ $region }}</span>
                            </div>
                            <div class="fund-date">{{ $date }}</div>
                        </div>
                        <div class="fund-amount">₹ {{ number_format($amount, 2) }}</div>
                    </div>
                @empty
                    <div class="report-card empty">No data available.</div>
                @endforelse
            </div>
        </div>

        <div class="d-none d-md-block report-card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Region</th>
                            <th>Date</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="fundTableBody">
                        @forelse($funds as $row)
                            @php
                                $region = (string) ($row['region'] ?? '');
                                $date = (string) ($row['date'] ?? '');
                                $amount = (float) ($row['amount'] ?? 0);
                                $month = '';
                                if (strlen($date) >= 7) {
                                    $month = substr($date, 0, 7);
                                }
                                $search = strtolower(trim($region.' '.$date));
                            @endphp
                            <tr data-region="{{ $region }}" data-month="{{ $month }}" data-search="{{ $search }}" data-amount="{{ $amount }}">
                                <td>{{ $region }}</td>
                                <td>{{ $date }}</td>
                                <td class="text-end">₹ {{ number_format($amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const region = document.getElementById('regionFilter');
        const month = document.getElementById('monthFilter');
        const search = document.getElementById('searchFilter');
        const clear = document.getElementById('clearFilters');
        const totalAmount = document.getElementById('totalAmount');
        const totalCount = document.getElementById('totalCount');

        const cardWrap = document.getElementById('fundCards');
        const tableBody = document.getElementById('fundTableBody');

        const cardItems = cardWrap ? Array.from(cardWrap.querySelectorAll('[data-region][data-month][data-search]')) : [];
        const tableRows = tableBody ? Array.from(tableBody.querySelectorAll('tr[data-region][data-month][data-search]')) : [];

        const toMoney = (n) => {
            const v = Number(n || 0);
            return '₹ ' + v.toFixed(2);
        };

        const match = (el, regionValue, monthValue, searchValue) => {
            const elRegion = (el.getAttribute('data-region') || '').toLowerCase();
            const elMonth = (el.getAttribute('data-month') || '').toLowerCase();
            const elSearch = (el.getAttribute('data-search') || '').toLowerCase();
            if (regionValue && elRegion !== regionValue) return false;
            if (monthValue && elMonth !== monthValue) return false;
            if (searchValue && !elSearch.includes(searchValue)) return false;
            return true;
        };

        const apply = () => {
            const regionValue = ((region && region.value) || '').trim().toLowerCase();
            const monthValue = ((month && month.value) || '').trim().toLowerCase();
            const searchValue = ((search && search.value) || '').trim().toLowerCase();

            let visibleCount = 0;
            let sum = 0;

            cardItems.forEach((el) => {
                const ok = match(el, regionValue, monthValue, searchValue);
                el.style.display = ok ? '' : 'none';
                if (ok) {
                    visibleCount++;
                    sum += Number(el.getAttribute('data-amount') || 0);
                }
            });

            tableRows.forEach((el) => {
                const ok = match(el, regionValue, monthValue, searchValue);
                el.style.display = ok ? '' : 'none';
                if (ok && cardItems.length === 0) {
                    visibleCount++;
                    sum += Number(el.getAttribute('data-amount') || 0);
                }
            });

            if (totalCount) totalCount.textContent = String(visibleCount);
            if (totalAmount) totalAmount.textContent = toMoney(sum);
        };

        const clearAll = () => {
            if (region) region.value = '';
            if (month) month.value = '';
            if (search) search.value = '';
            apply();
        };

        if (region) region.addEventListener('change', apply);
        if (month) month.addEventListener('change', apply);
        if (search) search.addEventListener('input', apply);
        if (clear) clear.addEventListener('click', clearAll);

        apply();
    })();
</script>
@endpush
