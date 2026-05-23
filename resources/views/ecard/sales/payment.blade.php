@extends('ecard.ecard')

@section('title', 'Select Payment Method')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Select Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Sale Summary</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td>Member:</td>
                                    <th class="text-end">{{ $sale->customer_name }}</th>
                                </tr>
                                <tr>
                                    <td>Billing Date:</td>
                                    <th class="text-end">{{ $sale->billing_date->format('d-m-Y') }}</th>
                                </tr>
                                <tr>
                                    <td>Items:</td>
                                    <th class="text-end">{{ $sale->items->count() }}</th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 text-end">
                            <h2 class="text-primary">₹{{ number_format($sale->total_amount, 2) }}</h2>
                            <p class="text-muted">Total Amount to Pay</p>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('ecard.sales.payment.process', $sale->id) }}" method="POST">
                        @csrf
                        @php
                            $selectedMethod = old('payment_method', $sale->payment_method);
                        @endphp
                        <div class="list-group mb-4">
                            <!-- Card Option -->
                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                <input class="form-check-input me-3" type="radio" name="payment_method" value="uonley_card" required {{ $selectedMethod === 'uonley_card' ? 'checked' : '' }}>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><i class="fas fa-id-card me-2 text-primary"></i>Card (Uonley Card)</h6>
                                    <small class="text-muted">Pay using your Uonley smart card</small>
                                </div>
                            </label>

                            <!-- QR Option -->
                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                <input class="form-check-input me-3" type="radio" name="payment_method" value="uonley_qr" {{ $selectedMethod === 'uonley_qr' ? 'checked' : '' }}>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><i class="fas fa-qrcode me-2 text-success"></i>QR (Uonley QR)</h6>
                                    <small class="text-muted">Scan QR code for instant payment</small>
                                </div>
                            </label>

                            <!-- Main Wallet -->
                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3 {{ $user->wallet_balance < $sale->total_amount ? 'disabled opacity-50' : '' }}">
                                <input class="form-check-input me-3" type="radio" name="payment_method" value="main_wallet" {{ $user->wallet_balance < $sale->total_amount ? 'disabled' : '' }} {{ $selectedMethod === 'main_wallet' ? 'checked' : '' }}>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><i class="fas fa-wallet me-2 text-info"></i>Wallet (Main Wallet)</h6>
                                    <small class="text-muted">Available Balance: ₹{{ number_format($user->wallet_balance, 2) }}</small>
                                </div>
                                @if($user->wallet_balance < $sale->total_amount)
                                    <span class="badge bg-danger">Low Balance</span>
                                @endif
                            </label>

                            <!-- Bonus Wallet -->
                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3 {{ $user->bonus_wallet_balance < $sale->total_amount ? 'disabled opacity-50' : '' }}">
                                <input class="form-check-input me-3" type="radio" name="payment_method" value="bonus_wallet" {{ $user->bonus_wallet_balance < $sale->total_amount ? 'disabled' : '' }} {{ $selectedMethod === 'bonus_wallet' ? 'checked' : '' }}>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><i class="fas fa-gift me-2 text-warning"></i>Bonus Wallet</h6>
                                    <small class="text-muted">Available Balance: ₹{{ number_format($user->bonus_wallet_balance, 2) }}</small>
                                </div>
                                @if($user->bonus_wallet_balance < $sale->total_amount)
                                    <span class="badge bg-danger">Low Balance</span>
                                @endif
                            </label>

                            <!-- Online Payment Gateway -->
                            <div class="list-group-item p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <input class="form-check-input me-3" type="radio" name="payment_method" value="gateway" id="gateway-radio" {{ $selectedMethod === 'gateway' ? 'checked' : '' }}>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><i class="fas fa-globe me-2 text-secondary"></i>Original Payment Gateway</h6>
                                        <small class="text-muted">Pay via PhonePe, Cashfree, etc.</small>
                                    </div>
                                </div>
                                
                                <div id="gateway-selection" class="ms-4 ps-3 border-start mt-2" style="display: none;">
                                    <p class="small text-muted mb-2">Select your preferred gateway:</p>
                                    @foreach($gateways as $gateway)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="gateway_id" id="gateway_{{ $gateway->id }}" value="{{ $gateway->id }}">
                                            <label class="form-check-label d-flex align-items-center" for="gateway_{{ $gateway->id }}">
                                                @if($gateway->logo)
                                                    <img src="{{ asset('storage/' . $gateway->logo) }}" alt="{{ $gateway->name }}" class="me-2" style="height: 20px;">
                                                @endif
                                                {{ $gateway->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div id="uonley-card-fields" class="card mb-4" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">Enter Uonley Card Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Card Number (16 digits)</label>
                                        <input type="text" class="form-control" name="card_number" inputmode="numeric" maxlength="16" value="{{ old('card_number') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Expiry Month</label>
                                        <input type="number" class="form-control" name="expiry_month" min="1" max="12" value="{{ old('expiry_month') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Expiry Year</label>
                                        <input type="number" class="form-control" name="expiry_year" min="0" value="{{ old('expiry_year') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">CVV</label>
                                        <input type="password" class="form-control" name="cvv" inputmode="numeric" maxlength="4" value="{{ old('cvv') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uonley-qr-section" class="card mb-4" style="display: none;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Scan QR for Instant Payment</h6>
                                @if(!empty($qrPayUrl))
                                    <a href="{{ $qrPayUrl }}" class="btn btn-sm btn-outline-secondary" target="_blank">Open Pay Link</a>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                @if(!empty($qrSvg))
                                    <div class="d-inline-block p-3 border rounded bg-white">
                                        {!! $qrSvg !!}
                                    </div>
                                    <div class="mt-3 text-muted">
                                        Member scans this QR and pays from wallet balance. If wallet balance is enough, the sale will be completed.
                                    </div>
                                @else
                                    <div class="text-muted">
                                        Select QR (Uonley QR) and click Pay Now to generate the QR for this sale.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Pay Now</button>
                            <a href="{{ route('ecard.sales.index') }}" class="btn btn-link text-muted">Cancel and go back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gatewayRadio = document.getElementById('gateway-radio');
        const gatewaySelection = document.getElementById('gateway-selection');
        const allRadios = document.querySelectorAll('input[name="payment_method"]');
        const gatewayRadios = document.querySelectorAll('input[name="gateway_id"]');
        const cardFields = document.getElementById('uonley-card-fields');
        const qrSection = document.getElementById('uonley-qr-section');
        const cardInputs = document.querySelectorAll('input[name="card_number"], input[name="expiry_month"], input[name="expiry_year"], input[name="cvv"]');

        const applyVisibility = () => {
            const selected = document.querySelector('input[name="payment_method"]:checked');
            const method = selected ? selected.value : null;

            if (gatewayRadio.checked) {
                gatewaySelection.style.display = 'block';
                gatewayRadios.forEach(gr => gr.required = true);
            } else {
                gatewaySelection.style.display = 'none';
                gatewayRadios.forEach(gr => gr.required = false);
            }

            if (cardFields) {
                cardFields.style.display = method === 'uonley_card' ? 'block' : 'none';
                cardInputs.forEach(i => i.required = method === 'uonley_card');
            }

            if (qrSection) {
                qrSection.style.display = method === 'uonley_qr' ? 'block' : 'none';
            }
        };

        allRadios.forEach(radio => radio.addEventListener('change', applyVisibility));
        applyVisibility();
    });
</script>
@endpush
@endsection
