@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Add Product Stock</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Stock Management</li>
                <li class="breadcrumb-item active">Add Product Stock</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Add Product Stock</h5>
            </div>
            <div class="card-body">
                <form id="product-stock-form">
                    @csrf
                    <div class="mb-3">
                        <label for="product_category_id" class="form-label">Product Category</label>
                        <select id="product_category_id" name="product_category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Select Product</label>
                        <select id="product_id" name="product_id" class="form-select" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" step="1" required />
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="in">In</option>
                            <option value="out">Out</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control" rows="3" placeholder="Remarks (optional)"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Stock Transactions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="stock-transactions-table" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const routes = {
        productsByCategory: function(categoryId) {
            return "{{ route('admin.products.by-category', ':id') }}".replace(':id', categoryId);
        },
        list: "{{ route('admin.product-stock-transactions.index') }}",
        store: "{{ route('admin.product-stock-transactions.store') }}"
    };

    $(document).ready(function() {
        const table = $('#stock-transactions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: routes.list,
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'category', name: 'category' },
                { data: 'product', name: 'product' },
                { data: 'type', name: 'type' },
                { data: 'quantity', name: 'quantity' },
                { data: 'created_at', name: 'created_at' }
            ]
        });

        $('#product_category_id').on('change', function() {
            const categoryId = $(this).val();
            const $product = $('#product_id');
            $product.empty().append('<option value="">Loading...</option>').prop('disabled', true);
            if (!categoryId) {
                $product.empty().append('<option value="">Select Product</option>').prop('disabled', false);
                return;
            }
            $.ajax({
                url: routes.productsByCategory(categoryId),
                method: 'GET',
                success: function(res) {
                    $product.empty().append('<option value="">Select Product</option>');
                    const items = Array.isArray(res?.data) ? res.data : [];
                    if (items.length) {
                        items.forEach(function(p) {
                            $product.append('<option value="' + p.id + '">' + p.name + '</option>');
                        });
                    }
                    $product.prop('disabled', false);
                },
                error: function() {
                    $product.empty().append('<option value="">Select Product</option>').prop('disabled', false);
                }
            });
        });

        $('#product-stock-form').on('submit', function(e) {
            e.preventDefault();
            const $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: routes.store,
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res && res.success) {
                        alert('Stock updated successfully. New stock: ' + (res.data?.new_stock ?? ''));
                        $('#product-stock-form')[0].reset();
                        $('#product_id').empty().append('<option value="">Select Product</option>').prop('disabled', false);
                        table.ajax.reload(null, false);
                    } else {
                        alert(res.message || 'Failed to update stock.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        let msg = 'Validation errors:\n';
                        Object.keys(errors).forEach(function(key) {
                            msg += '- ' + key + ': ' + errors[key].join(', ') + '\n';
                        });
                        alert(msg);
                    } else {
                        alert('Server error. Please try again.');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Submit');
                }
            });
        });
    });
</script>
@endpush