@extends('user.layouts.app')

@section('title', 'User to User Transfer - UOnly')
@section('page_title', 'User to User Transfer')

@push('styles')
<style>
    /* Match dashboard background */
    body { background-color: var(--bg-light); color: var(--text-dark); }

    /* Match dashboard navbar gradient - handled by global theme styles */
    /* .navbar.bg-dark {
        background: var(--primary-gradient) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */

    .card { border: none; border-radius: 14px; box-shadow: 0 6px 20px rgba(13,110,253,.12); background-color: var(--card-bg); color: var(--text-dark); }
    .form-control { border-radius: 10px; background-color: var(--bg-light); color: var(--text-dark); border: 1px solid var(--muted-text); }
    .page-header { margin-bottom: 1rem; }
    .btn-primary-custom { background: var(--primary-gradient); border: none; color: white; }
    .btn-primary-custom:hover { opacity: 0.9; color: white; }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="page-header">
        <h4 class="mb-2"><i class="fa-solid fa-user-arrow-right me-2"></i>User to User Transfer</h4>
        <p class="text-muted mb-0">Send funds securely to another user.</p>
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

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.wallet.transfer.user.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Recipient User ID</label>
                            <input type="text" name="to_user_id" class="form-control" placeholder="Enter recipient user ID" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount (₹)</label>
                            <input type="number" step="0.01" min="1" name="amount" class="form-control" placeholder="Enter amount" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note (optional)</label>
                            <input type="text" name="note" class="form-control" placeholder="Message to recipient">
                        </div>
                        <button type="submit" class="btn btn-primary-custom"><i class="fa-solid fa-paper-plane me-1"></i>Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection