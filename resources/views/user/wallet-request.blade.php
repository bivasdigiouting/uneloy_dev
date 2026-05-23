@extends('user.layouts.app')

@section('title', 'Wallet Request - UOnly')

@push('styles')
<style>
    body { background-color: var(--bg-light); color: var(--text-dark); }
    .card { border: none; border-radius: 14px; box-shadow: 0 6px 20px rgba(13,110,253,.12); background-color: var(--card-bg); color: var(--text-dark); }
    .form-control, .form-select { border-radius: 10px; background-color: var(--bg-light); color: var(--text-dark); border: 1px solid var(--muted-text); }
    /* .btn-primary { border-radius: 10px; } */
    .stat-pill { background: var(--card-bg); border-radius:12px; box-shadow:0 4px 14px rgba(0,0,0,.06); color: var(--text-dark); }
    .page-header { margin-bottom: 1rem; }
    .btn-primary-custom { background: var(--primary-gradient); border: none; color: white; border-radius: 10px; }
    .btn-primary-custom:hover { opacity: 0.9; color: white; }
    /* Match dashboard navbar gradient - handled by global theme styles */
    /* .navbar.bg-dark { 
        background: var(--primary-gradient) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */
    .text-primary { color: var(--pink-highlight) !important; }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="page-header">
        <h4 class="mb-2"><i class="fa-solid fa-wallet me-2"></i>Wallet Request</h4>
        <p class="text-muted mb-0">Top-up your wallet balance securely.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="p-3 stat-pill">
                <div class="d-flex align-items-center">
                    <div class="me-3"><i class="fa-solid fa-coins text-warning fs-3"></i></div>
                    <div>
                        <div class="text-muted small">Current Balance</div>
                        <div class="h5 mb-0">₹ {{ number_format(($wallet['balance'] ?? 0), 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 stat-pill">
                <div class="d-flex align-items-center">
                    <div class="me-3"><i class="fa-solid fa-gauge-high text-primary fs-3"></i></div>
                    <div>
                        <div class="text-muted small">Daily Limit</div>
                        <div class="h5 mb-0">₹ {{ number_format(($wallet['daily_limit'] ?? 0), 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 stat-pill">
                <div class="d-flex align-items-center">
                    <div class="me-3"><i class="fa-solid fa-calendar-days text-success fs-3"></i></div>
                    <div>
                        <div class="text-muted small">Monthly Limit</div>
                        <div class="h5 mb-0">₹ {{ number_format(($wallet['monthly_limit'] ?? 0), 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fa-solid fa-plus-circle text-primary"></i> New Wallet Request</h5>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('user.wallet.request.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount (₹)</label>
                            <input type="number" step="0.01" min="1" name="amount" class="form-control" placeholder="Enter amount" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Method</label>
                            <select class="form-select" name="method" required>
                                <option value="">Select method</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="card">Card</option>
                                <option value="upi">UPI</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reference (optional)</label>
                            <input type="text" name="reference" class="form-control" placeholder="Txn Ref / UTR">
                        </div>
                        <button type="submit" class="btn btn-primary-custom"><i class="fa-solid fa-paper-plane me-1"></i>Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Guidelines</h6>
                    <ul class="text-muted">
                        <li>Minimum amount ₹100.</li>
                        <li>Processing time within 24 hours.</li>
                        <li>Keep your transaction reference ready.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection