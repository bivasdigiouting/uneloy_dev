@extends('user.layouts.app')

@section('title', 'Upgrade ID - UOnly')
@section('page_title', 'Upgrade ID')

@push('styles')
    <style>
        .card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: none; background-color: var(--card-bg); color: var(--text-dark); }
        .badge-tier { background: rgba(213, 63, 140, 0.1); color: var(--pink-highlight); }
        .form-check.border { border-color: var(--muted-text) !important; }
        .btn-primary-custom { background: var(--primary-gradient); border: none; color: white; }
        .btn-primary-custom:hover { opacity: 0.9; color: white; }
        
        /* Theme Overrides */
        .bg-primary { background-color: var(--pink-highlight) !important; }
        .form-check-input:checked {
            background-color: var(--pink-highlight);
            border-color: var(--pink-highlight);
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fas fa-arrow-up me-2"></i>Upgrade Your ID</h4>
                    <p class="text-muted">Select a higher tier to unlock more benefits.</p>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('user.upgrade.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="tier" id="tier_basic" value="basic">
                                    <label class="form-check-label" for="tier_basic">
                                        <strong>Basic</strong><br>
                                        <span class="text-muted">Standard access, ideal for starters</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="tier" id="tier_pro" value="pro">
                                    <label class="form-check-label" for="tier_pro">
                                        <strong>Pro</strong> <span class="badge badge-tier ms-2">Popular</span><br>
                                        <span class="text-muted">Enhanced features and support</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="tier" id="tier_elite" value="elite">
                                    <label class="form-check-label" for="tier_elite">
                                        <strong>Elite</strong><br>
                                        <span class="text-muted">All features unlocked, priority support</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary-custom">Request Upgrade</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Current Tier</h5>
                    <p class="mb-1"><strong>Tier:</strong> <span class="badge bg-primary">{{ $user['tier'] ?? 'Basic' }}</span></p>
                    <p class="mb-1"><strong>Member Since:</strong> {{ $user['created_at'] ?? now()->subYear()->format('Y-m-d') }}</p>
                    <hr>
                    <h6>Benefits</h6>
                    <ul class="small text-muted mb-0">
                        <li>Access to dashboard insights</li>
                        <li>Priority support for Pro/Elite users</li>
                        <li>Higher withdrawal limits</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection