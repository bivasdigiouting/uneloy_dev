@extends('ecard.ecard')

@section('title', 'Edit Sale')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Sale</h4>
        <a href="{{ route('ecard.sales.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ecard.sales.update', $sale->id) }}" method="POST" id="sales-form">
        @csrf
        @method('PUT')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Member Name</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $sale->customer_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Billing Date</label>
                        <input type="date" name="billing_date" class="form-control" value="{{ old('billing_date', $sale->billing_date->format('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Products</h5>
                <button type="button" class="btn btn-sm btn-success" id="add-product-row"><i class="fas fa-plus"></i> Add Product</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="products-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th width="150">Price</th>
                                <th width="100">Quantity</th>
                                <th width="150">Total</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $index => $item)
                            <tr class="product-row">
                                <td>
                                    <select name="products[{{ $index }}][product_id]" class="form-select product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} (₹{{ $product->price }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control product-price" step="0.01" readonly value="{{ $item->price }}">
                                </td>
                                <td>
                                    <input type="number" name="products[{{ $index }}][quantity]" class="form-control product-quantity" min="1" value="{{ $item->quantity }}" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control product-total" readonly value="{{ $item->price * $item->quantity }}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                                <td colspan="2" class="fw-bold" id="grand-total">{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update Sale</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let productIndex = {{ $sale->items->count() }};
    const products = @json($products);

    function addRow() {
        let options = '<option value="">Select Product</option>';
        products.forEach(p => {
            options += `<option value="${p.id}" data-price="${p.price}">${p.name} (₹${p.price})</option>`;
        });

        const row = `
            <tr class="product-row">
                <td>
                    <select name="products[${productIndex}][product_id]" class="form-select product-select" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control product-price" step="0.01" readonly>
                </td>
                <td>
                    <input type="number" name="products[${productIndex}][quantity]" class="form-control product-quantity" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" class="form-control product-total" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
        $('#products-table tbody').append(row);
        productIndex++;
    }

    $('#add-product-row').click(addRow);

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    $(document).on('change', '.product-select', function() {
        const price = $(this).find(':selected').data('price') || 0;
        const row = $(this).closest('tr');
        row.find('.product-price').val(price);
        calculateRowTotal(row);
    });

    $(document).on('input', '.product-quantity', function() {
        calculateRowTotal($(this).closest('tr'));
    });

    function calculateRowTotal(row) {
        const price = parseFloat(row.find('.product-price').val()) || 0;
        const qty = parseInt(row.find('.product-quantity').val()) || 0;
        const total = price * qty;
        row.find('.product-total').val(total.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        let grandTotal = 0;
        $('.product-total').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').text(grandTotal.toFixed(2));
    }
});
</script>
@endpush
@endsection
