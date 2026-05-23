@extends('ecard.ecard')

@section('title', 'Sale Invoice')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Invoice #{{ $sale->id }}</h4>
        <a href="{{ route('ecard.sales.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h5 class="mb-3">From:</h5>
                    <h3 class="text-dark mb-1">{{ $sale->ecardRegistration->business_name ?? 'ECard Seva' }}</h3>
                    <div>{{ $sale->ecardRegistration->business_address ?? 'Address' }}</div>
                    <div>Email: {{ $sale->ecardRegistration->business_gmail ?? 'email@example.com' }}</div>
                    <div>Phone: {{ $sale->ecardRegistration->business_mobile ?? 'phone' }}</div>
                </div>
                <div class="col-sm-6 text-end">
                    <h5 class="mb-3">To:</h5>
                    <h3 class="text-dark mb-1">{{ $sale->customer_name }}</h3>
                    @if($sale->user)
                    <div>{{ $sale->user->address ?? '' }}</div>
                    <div>{{ $sale->user->city ?? '' }} {{ $sale->user->state ?? '' }}</div>
                    <div>Phone: {{ $sale->user->phone ?? '' }}</div>
                    <div>Email: {{ $sale->user->email ?? '' }}</div>
                    @endif
                    <div>Date: {{ $sale->billing_date->format('d-m-Y') }}</div>
                    <div>Invoice #: {{ $sale->id }}</div>
                </div>
            </div>

            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Item</th>
                            <th class="right">Price</th>
                            <th class="center">Qty</th>
                            <th class="right">Tax</th>
                            <th class="right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td class="left strong">{{ $item->product->name }}</td>
                            <td class="right">₹{{ number_format($item->price, 2) }}</td>
                            <td class="center">{{ $item->quantity }}</td>
                            <td class="right">₹{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="right">₹{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-lg-4 col-sm-5 ms-auto">
                    <table class="table table-clear">
                        <tbody>
                            <tr>
                                <td class="left"><strong>Subtotal</strong></td>
                                <td class="right">₹{{ number_format($sale->purchase_value, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="left"><strong>Tax</strong></td>
                                <td class="right">₹{{ number_format($sale->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="left"><strong>Total</strong></td>
                                <td class="right"><strong>₹{{ number_format($sale->total_amount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <p class="mb-0">Thank you for your business!</p>
        </div>
    </div>
</div>
@endsection
