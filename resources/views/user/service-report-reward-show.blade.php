@extends('user.layouts.app')

@section('title', 'Reward Details')
@section('hide_mobile_navbar', '1')
@section('hide_desktop_header', '1')

@push('styles')
<style>
    .detail-hero {
        background: var(--primary-gradient);
        border-radius: 18px;
        color: #fff;
        overflow: hidden;
        position: relative;
    }
    .detail-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(900px 320px at 0% 0%, rgba(255,255,255,0.20), transparent 60%),
            radial-gradient(800px 280px at 100% 0%, rgba(255,255,255,0.16), transparent 55%),
            radial-gradient(700px 240px at 50% 100%, rgba(0,0,0,0.12), transparent 60%);
        pointer-events: none;
    }
    .detail-hero-inner {
        position: relative;
        padding: 22px 18px;
    }
    .detail-badge {
        border-radius: 999px;
        padding: 8px 12px;
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.20);
        font-weight: 800;
        color: #fff !important;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .amount-pill {
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 16px;
        padding: 12px 14px;
        display: inline-flex;
        align-items: baseline;
        gap: 8px;
    }
    .amount-pill .rs {
        font-weight: 800;
        opacity: 0.9;
    }
    .amount-pill .amt {
        font-weight: 900;
        font-size: 1.8rem;
        line-height: 1;
        color: #fff !important;
    }
    .info-card {
        border: none;
        border-radius: 18px;
        background: var(--card-bg);
        box-shadow: 0 10px 28px rgba(0,0,0,0.06);
    }
    .kv {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .kv:last-child {
        border-bottom: none;
    }
    .kv .k {
        color: var(--muted-text);
        font-weight: 600;
        font-size: 0.9rem;
    }
    .kv .v {
        color: var(--text-dark);
        font-weight: 800;
        text-align: right;
    }
    .terms li {
        margin-bottom: 10px;
        color: var(--muted-text);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
@php
    $status = (string) ($reward['status'] ?? 'available');
    $badgeText = $status === 'redeemed' ? 'Redeemed' : ($status === 'expired' ? 'Expired' : 'Available');
    $badgeIcon = $status === 'redeemed' ? 'fa-check-circle' : ($status === 'expired' ? 'fa-clock' : 'fa-bolt');
@endphp

<div class="detail-hero mb-4">
    <div class="detail-hero-inner">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <a href="{{ route('user.service.report.reward') }}" class="btn btn-light rounded-pill px-3 fw-semibold mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <div class="detail-badge mb-3">
                    <i class="fas {{ $badgeIcon }}"></i> {{ $badgeText }}
                </div>
                <h4 class="mb-1" style="color:#fff !important;">{{ $reward['title'] ?? 'Reward' }}</h4>
                <div class="opacity-90">{{ $reward['subtitle'] ?? 'Reward details' }}</div>
            </div>
            <div class="text-end">
                <div class="amount-pill">
                    <span class="rs">₹</span>
                    <span class="amt">{{ number_format((float) ($reward['amount'] ?? 0), 2) }}</span>
                </div>
                <div class="opacity-90 small mt-2">Cashback Reward</div>
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

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="info-card p-3 p-md-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h5 class="mb-0 fw-bold">Cashback Details</h5>
                @if($status === 'available')
                    <form method="POST" action="{{ route('user.service.report.reward.redeem', ['reward' => (string) ($reward['id'] ?? '')]) }}">
                        @csrf
                        <button class="btn btn-primary rounded-pill px-4 fw-semibold" type="submit">
                            Redeem Now <i class="fas fa-wallet ms-2"></i>
                        </button>
                    </form>
                @elseif($status === 'redeemed')
                    <button class="btn btn-success rounded-pill px-4 fw-semibold" type="button" disabled>
                        Redeemed <i class="fas fa-check ms-2"></i>
                    </button>
                @else
                    <button class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" type="button" disabled>
                        Not Available <i class="fas fa-lock ms-2"></i>
                    </button>
                @endif
            </div>

            <div class="kv">
                <div class="k">Reward Amount</div>
                <div class="v">₹{{ number_format((float) ($reward['amount'] ?? 0), 2) }}</div>
            </div>
            <div class="kv">
                <div class="k">Earned On</div>
                <div class="v">{{ $reward['earned_at'] ?? '-' }}</div>
            </div>
            <div class="kv">
                <div class="k">Expires On</div>
                <div class="v">{{ $reward['expires_at'] ?? '-' }}</div>
            </div>
            <div class="kv">
                <div class="k">Status</div>
                <div class="v">{{ $badgeText }}</div>
            </div>
            @if(!empty($reward['order_no']))
                <div class="kv">
                    <div class="k">Order</div>
                    <div class="v">#{{ $reward['order_no'] }}</div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="info-card p-3 p-md-4">
            <h5 class="mb-3 fw-bold">Terms &amp; Conditions</h5>
            <ul class="terms mb-0">
                <li>Reward is valid until {{ $reward['expires_at'] ?? 'the expiry date' }}.</li>
                <li>Redeemable once per reward and subject to eligibility checks.</li>
                <li>Cashback credit may take up to 24 hours after redemption.</li>
                <li>Rewards may be cancelled if the related transaction is reversed.</li>
                <li>UOnly reserves the right to modify or withdraw rewards at any time.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
