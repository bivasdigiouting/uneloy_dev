@extends('ecard.ecard')

@section('title', 'New Sale')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">New Sale</h4>
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

    <form action="{{ route('ecard.sales.store') }}" method="POST" id="sales-form">
        @csrf
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Billing Date</label>
                        <input type="date" name="billing_date" class="form-control" value="{{ old('billing_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Member Type</label>
                        <div class="mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customer_type" id="customer_existing" value="existing" {{ old('customer_type') == 'existing' ? 'checked' : '' }}>
                                <label class="form-check-label" for="customer_existing">Existing User</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customer_type" id="customer_walkin" value="walk_in" {{ old('customer_type', 'walk_in') == 'walk_in' ? 'checked' : '' }}>
                                <label class="form-check-label" for="customer_walkin">Walk-in</label>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Member Select -->
                    <div class="col-md-4 existing-customer-group" style="display:none;">
                        <label class="form-label">Select Member</label>
                        <select name="user_id" class="form-select select2" id="user_id">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->phone ?? $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Walk-in Member Input -->
                    <div class="col-md-4 walkin-customer-group">
                        <label class="form-label">Member Name</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}">
                    </div>
                </div>

                <!-- Create Account Section (Only for Walk-in) -->
                <div class="row g-3 mt-2 walkin-customer-group">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="create_account" value="1" id="create_account" {{ old('create_account') ? 'checked' : '' }}>
                            <label class="form-check-label" for="create_account">
                                Create User Account
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2 account-details-group" style="display:none;">
                     <div class="col-md-4">
                         <label class="form-label">Phone <span class="text-danger">*</span></label>
                         <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Email <span class="text-danger">*</span></label>
                         <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Password <span class="text-danger">*</span></label>
                         <input type="password" name="password" class="form-control">
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
                                <th width="120">Price</th>
                                <th width="100">Quantity</th>
                                <th width="100">Tax (%)</th>
                                <th width="120">Tax Amount</th>
                                <th width="150">Total</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Product rows will be added here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Sub Total:</td>
                                <td colspan="2" class="fw-bold" id="sub-total">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total Tax:</td>
                                <td colspan="2" class="fw-bold" id="total-tax">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Grand Total:</td>
                                <td colspan="2" class="fw-bold" id="grand-total">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Create Sale</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();

    let productIndex = 0;
    const products = @json($products);

    function addRow() {
        let options = '<option value="">Select Product</option>';
        products.forEach(p => {
            const taxRate = p.gst_tax ? p.gst_tax.rate_percent : 0;
            options += `<option value="${p.id}" data-price="${p.price}" data-tax="${taxRate}">${p.name} (₹${p.price})</option>`;
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
                    <input type="number" class="form-control product-tax-rate" readonly>
                </td>
                <td>
                    <input type="number" class="form-control product-tax-amount" readonly>
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
        
        // Initialize Select2 for the new row's select element
        // Since we appended html string, we need to target the last added select
        $('#products-table tbody tr:last .product-select').select2({
             width: '100%' // Ensure it fits the column
        });
    }

    // Add initial row
    addRow();

    $('#add-product-row').click(addRow);

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    $(document).on('change', '.product-select', function() {
        const option = $(this).find(':selected');
        const price = parseFloat(option.data('price')) || 0;
        const tax = parseFloat(option.data('tax')) || 0;
        
        const row = $(this).closest('tr');
        row.find('.product-price').val(price.toFixed(2));
        row.find('.product-tax-rate').val(tax);
        
        calculateRowTotal(row);
    });

    $(document).on('input', '.product-quantity', function() {
        calculateRowTotal($(this).closest('tr'));
    });

    function calculateRowTotal(row) {
        const price = parseFloat(row.find('.product-price').val()) || 0;
        const qty = parseInt(row.find('.product-quantity').val()) || 0;
        const taxRate = parseFloat(row.find('.product-tax-rate').val()) || 0;
        
        const subtotal = price * qty;
        const taxAmount = (subtotal * taxRate) / 100;
        const total = subtotal + taxAmount;
        
        row.find('.product-tax-amount').val(taxAmount.toFixed(2));
        row.find('.product-total').val(total.toFixed(2));
        
        calculateTotal();
    }

    function calculateTotal() {
        let subTotal = 0;
        let totalTax = 0;
        let grandTotal = 0;
        
        $('.product-row').each(function() {
            const row = $(this);
            const price = parseFloat(row.find('.product-price').val()) || 0;
            const qty = parseInt(row.find('.product-quantity').val()) || 0;
            const taxAmount = parseFloat(row.find('.product-tax-amount').val()) || 0;
            const total = parseFloat(row.find('.product-total').val()) || 0;
            
            subTotal += (price * qty);
            totalTax += taxAmount;
            grandTotal += total;
        });
        
        $('#sub-total').text(subTotal.toFixed(2));
        $('#total-tax').text(totalTax.toFixed(2));
        $('#grand-total').text(grandTotal.toFixed(2));
    }

    // Member Type Toggle
    function toggleCustomerType() {
        const type = $('input[name="customer_type"]:checked').val();
        if (type === 'existing') {
            $('.existing-customer-group').show();
            $('.walkin-customer-group').hide();
            
            $('#create_account').prop('checked', false);
            toggleAccountDetails(); // Reset account details visibility and required state
            
            $('#user_id').prop('required', true);
            $('input[name="customer_name"]').prop('required', false);
        } else {
            $('.existing-customer-group').hide();
            $('.walkin-customer-group').show();
            $('#user_id').prop('required', false);
            $('input[name="customer_name"]').prop('required', true);
            toggleAccountDetails();
        }
    }

    function toggleAccountDetails() {
        if ($('#create_account').is(':checked')) {
            $('.account-details-group').show();
            $('input[name="phone"]').prop('required', true);
            $('input[name="email"]').prop('required', true);
            $('input[name="password"]').prop('required', true);
        } else {
            $('.account-details-group').hide();
            $('input[name="phone"]').prop('required', false);
            $('input[name="email"]').prop('required', false);
            $('input[name="password"]').prop('required', false);
        }
    }

    $('input[name="customer_type"]').change(toggleCustomerType);
    $('#create_account').change(toggleAccountDetails);

    // Initial check
    toggleCustomerType();
});
</script>
@endpush
@endsection
