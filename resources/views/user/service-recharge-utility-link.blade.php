@extends('user.layouts.app')

@section('title', 'Utility Services')

@push('styles')
<style>
    /* Mobile Wrapper */
    .mobile-wrapper {
        width: 100%;
        background-color: #ffffff;
        min-height: 100vh;
        margin: 0 auto;
        position: relative;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
    }

    .header-gradient {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%); /* Orange Gradient for Utilities */
        padding: 20px 20px 40px 20px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        color: white;
        position: relative;
        z-index: 1;
    }

    .header-nav {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        position: relative;
        z-index: 10;
    }

    .back-btn {
        color: white;
        font-size: 22px;
        margin-right: 15px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: transparent;
        backdrop-filter: none;
    }

    .page-title {
        font-size: 20px;
        font-weight: 600;
        flex-grow: 1;
    }

    .page-subtitle {
        font-size: 14px;
        font-weight: 400;
        margin-bottom: 10px;
        opacity: 0.9;
        padding-left: 5px;
    }

    .utility-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        padding: 25px 20px;
        margin-top: 0;
        z-index: 2;
        position: relative;
        background: #f8f9fa;
        flex: 1;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        margin-top: -25px;
    }

    .utility-item {
        background: white;
        border-radius: 20px;
        padding: 20px 10px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        text-decoration: none;
        color: var(--text-dark);
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        aspect-ratio: 1/1;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .utility-item:active {
        transform: scale(0.95);
    }

    .utility-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
        background: var(--bg-light);
        color: var(--primary-color);
        transition: background 0.3s;
    }

    .utility-name {
        font-size: 13px;
        font-weight: 500;
        line-height: 1.3;
        color: #555;
    }

    /* Desktop Wrapper */
    .desktop-wrapper {
        padding: 30px;
    }
    
    .desktop-card {
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        background: white;
        padding: 20px;
        transition: transform 0.2s;
    }
    .desktop-card:hover { transform: translateY(-5px); }

</style>
@endpush

@section('content')

<!-- Mobile View -->
<div class="mobile-wrapper d-lg-none">
    <div class="header-gradient">
        <div class="header-nav">
            <a href="{{ route('user.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">Utility Services</div>
        </div>
        <div class="page-subtitle">
            Pay all your bills and recharge instantly.
        </div>
    </div>

    <div class="utility-grid">
        @foreach($utilities as $utility)
            <a href="{{ $utility['url'] }}" class="utility-item">
                <div class="utility-icon" style="background: {{ $utility['color'] }}15; color: {{ $utility['color'] }};">
                    <i class="{{ $utility['icon'] ?? 'fas fa-bolt' }}"></i>
                </div>
                <div class="utility-name">{{ $utility['name'] }}</div>
            </a>
        @endforeach
    </div>

    <div class="px-4 mt-4 text-center text-muted small">
        <i class="fas fa-shield-alt me-1"></i> Secure Payments by BBPS
    </div>
</div>

<!-- Desktop View -->
<div class="desktop-wrapper d-none d-lg-block">
    <div class="container">
        <div class="d-flex align-items-center mb-4">
            <h2 class="fw-bold text-dark mb-0">Utility Services</h2>
        </div>

        <div class="row g-4">
            @foreach($utilities as $utility)
                <div class="col-md-4 col-lg-3">
                    <a href="{{ $utility['url'] }}" class="text-decoration-none">
                        <div class="desktop-card h-100 text-center">
                            <div class="mb-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                                      style="width: 60px; height: 60px; background: {{ $utility['color'] }}15; color: {{ $utility['color'] }}; font-size: 24px;">
                                    <i class="{{ $utility['icon'] ?? 'fas fa-bolt' }}"></i>
                                </span>
                            </div>
                            <h5 class="fw-bold text-dark">{{ $utility['name'] }}</h5>
                            <p class="text-muted small mb-0">Pay your {{ strtolower($utility['name']) }} bills instantly</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
