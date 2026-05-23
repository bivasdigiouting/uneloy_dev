@extends('user.layouts.app')

@section('title', 'Bank Settlement - UOnly')

@push('styles')
<style>
    /* Match dashboard body background */
    body { background-color: var(--bg-light); color: var(--text-dark); }

    /* Match dashboard navbar gradient - handled by global theme styles */
    /* .navbar.bg-dark {
        background: var(--primary-gradient) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */

    .card { border: none; border-radius: 14px; box-shadow: 0 6px 20px rgba(13,110,253,.12); background-color: var(--card-bg); color: var(--text-dark); }
    .form-control { border-radius: 10px; background-color: var(--bg-light); color: var(--text-dark); border: 1px solid var(--muted-text); }
    .page-header { margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="page-header">
        <h4 class="mb-2"><i class="fa-solid fa-building-columns me-2"></i>Bank Settlement Request</h4>
        <p class="text-muted mb-0">Withdraw funds to your bank account securely.</p>
    </div>

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

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('user.wallet.settlement.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount (₹)</label>
                            <input type="number" step="0.01" min="100" name="amount" class="form-control" placeholder="Minimum ₹100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Account Holder Name</label>
                            <input type="text" name="account_holder" class="form-control" placeholder="As per bank" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="account_number" class="form-control" placeholder="Account number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">IFSC Code</label>
                            <input type="text" name="ifsc" class="form-control" placeholder="e.g., HDFC0001234" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane me-1"></i>Submit Settlement</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Settlement Info</h6>
                    <ul class="text-muted">
                        <li>Minimum amount: ₹{{ $settlement['min_amount'] ?? 100 }}</li>
                        <li>Charges: {{ $settlement['charges_percent'] ?? 1.5 }}%</li>
                        <li>Processing: {{ $settlement['processing_time'] ?? 'T+1' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection