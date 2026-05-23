@extends('user.layouts.app')

@section('title', 'Wallet Transactions - UOnly')
@section('page_title', 'Wallet Transactions')

@push('styles')
<style>
    /* Match dashboard navbar gradient and styling - handled by global theme styles */
    /* .navbar.bg-dark {
        background: var(--primary-gradient) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */
    .card { border: none; border-radius: 14px; box-shadow: 0 6px 20px rgba(13,110,253,.12); background-color: var(--card-bg); color: var(--text-dark); }
    .badge { border-radius: 8px; }
    .table { background: var(--card-bg); color: var(--text-dark); }
    .table-hover tbody tr:hover { color: var(--text-dark); background-color: rgba(0,0,0,0.05); }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    .table > :not(caption) > * > * { background-color: transparent; color: inherit; }
    .btn-primary-custom { background: var(--primary-gradient); border: none; color: white; }
    .btn-primary-custom:hover { opacity: 0.9; color: white; }
    
    /* Theme Overrides */
    .text-primary { color: var(--pink-highlight) !important; }
    .bg-primary { background-color: var(--pink-highlight) !important; }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="card-title mb-0"><i class="fa-solid fa-list-ul text-primary"></i> Wallet Transaction Details</h5>
                <div>
                    <a href="{{ route('user.wallet.request.show') }}" class="btn btn-sm btn-primary-custom"><i class="fa-solid fa-plus me-1"></i>New Request</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount (₹)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $txn)
                            <tr>
                                <td>{{ $txn['date'] ?? '' }}</td>
                                <td>
                                    @if(($txn['type'] ?? '') === 'credit')
                                        <span class="badge bg-success">Credit</span>
                                    @else
                                        <span class="badge bg-danger">Debit</span>
                                    @endif
                                </td>
                                <td>₹ {{ number_format(($txn['amount'] ?? 0), 2) }}</td>
                                <td><span class="badge bg-primary">{{ ucfirst(($txn['status'] ?? '')) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No transactions available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection