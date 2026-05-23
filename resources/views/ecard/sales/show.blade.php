@extends('ecard.ecard')

@section('title', 'Sale Details')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Sale Details #{{ $sale->id }}</h4>
        <div>
            <a href="{{ route('ecard.sales.invoice', $sale->id) }}" class="btn btn-secondary me-2" target="_blank"><i class="fas fa-file-invoice"></i> Invoice</a>
            <a href="{{ route('ecard.sales.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Member Name:</strong> {{ $sale->customer_name }}</p>
                    @if($sale->user)
                    <p><strong>Registered User:</strong> {{ $sale->user->name }} ({{ $sale->user->phone ?? $sale->user->email }})</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <p><strong>Billing Date:</strong> {{ $sale->billing_date->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Products</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td>
                                {{ $item->product->name }}
                                @if($item->product->gstTax)
                                    <small class="text-muted d-block">Tax: {{ $item->product->gstTax->rate_percent }}%</small>
                                @endif
                            </td>
                            <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">₹{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="text-end">₹{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Sub Total:</td>
                            <td class="text-end">₹{{ number_format($sale->purchase_value, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Tax:</td>
                            <td class="text-end">₹{{ number_format($sale->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                            <td class="text-end fw-bold">₹{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
