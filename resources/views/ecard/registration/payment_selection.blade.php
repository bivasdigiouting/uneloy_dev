@extends('ecard.ecard')
@section('title', 'Select Payment Method')
@section('content')
<section class="content">
    <div class="content-inner">
        <div class="container-fluid py-3">
            <div class="card p-4">
                <h5 class="card-title mb-4"><i class="fas fa-credit-card me-2"></i>Complete Registration Payment</h5>
                
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-header fw-bold">Plan Details</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $plan->plan_name }}</h5>
                                <p class="card-text">
                                    <strong>Amount to Pay:</strong> ₹{{ number_format($plan->plan_value, 2) }}<br>
                                    <strong>Bonus Credit:</strong> ₹{{ number_format($plan->bonus_value, 2) }}<br>
                                    <strong>Total Benefit:</strong> ₹{{ number_format($plan->total_value, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                         <ul class="nav nav-tabs" id="paymentTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet" type="button" role="tab">Pay using Wallet</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="gateway-tab" data-bs-toggle="tab" data-bs-target="#gateway" type="button" role="tab">Pay Online</button>
                            </li>
                        </ul>
                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="paymentTabContent">
                            <!-- Wallet Tab -->
                            <div class="tab-pane fade show active" id="wallet" role="tabpanel">
                                <p class="mb-2">Your Wallet Balance: <strong>₹{{ number_format($user->wallet_balance, 2) }}</strong></p>
                                
                                @if($user->wallet_balance >= $plan->plan_value)
                                    <form action="{{ route('ecard.registration.payment.wallet', ['id' => $registration->id]) }}" method="POST">
                                        @csrf
                                        <div class="alert alert-info">
                                            ₹{{ number_format($plan->plan_value, 2) }} will be deducted from your wallet.
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Pay Now</button>
                                    </form>
                                @else
                                    <div class="alert alert-warning">
                                        Insufficient balance. Please add funds to your wallet or use online payment.
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Gateway Tab -->
                            <div class="tab-pane fade" id="gateway" role="tabpanel">
                                <form action="{{ route('ecard.registration.payment.gateway', ['id' => $registration->id]) }}" method="POST">
                                    @csrf
                                    <p class="mb-2">Select Payment Gateway:</p>
                                    
                                    @if($gateways->count() > 0)
                                        @foreach($gateways as $gateway)
                                            <div class="form-check mb-2 d-flex align-items-center">
                                                <input class="form-check-input me-2" type="radio" name="gateway_id" id="gateway_{{ $gateway->id }}" value="{{ $gateway->id }}" required>
                                                <label class="form-check-label d-flex align-items-center" for="gateway_{{ $gateway->id }}">
                                                    @if($gateway->logo)
                                                        <img src="{{ asset('storage/' . $gateway->logo) }}" alt="{{ $gateway->name }}" class="me-2" style="height: 30px; object-fit: contain;">
                                                    @endif
                                                    {{ $gateway->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                        <button type="submit" class="btn btn-success w-100 mt-3">Pay Online</button>
                                    @else
                                        <div class="alert alert-secondary">No active payment gateways available.</div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection