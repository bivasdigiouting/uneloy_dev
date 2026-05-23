@extends('ecard.ecard')

@section('title', 'QR Payment')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Uonley QR Payment</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">Sale #{{ $sale->id }}</div>
                            <div class="fw-semibold">₹{{ number_format($sale->total_amount, 2) }}</div>
                        </div>
                        <div class="text-muted mt-1">Seller: {{ $seller->full_name ?? ($seller->business_name ?? 'N/A') }}</div>
                        <div class="text-muted">Member: {{ $sale->customer_name }}</div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">Your Wallet Balance</div>
                            <div class="fw-semibold">₹{{ number_format($payer->wallet_balance ?? 0, 2) }}</div>
                        </div>
                    </div>

                    @if($sale->payment_status === 'paid')
                        <div class="alert alert-success mb-0">
                            Payment already completed for this sale.
                        </div>
                    @elseif(! $isValid)
                        <div class="alert alert-danger mb-0">
                            Invalid or expired QR. Please ask the seller to generate a new QR.
                        </div>
                    @else
                        <form action="{{ route('ecard.sales.qr-pay.process', $sale->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Pay Now</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

