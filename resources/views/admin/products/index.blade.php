@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Product Management</li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Product Master</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Product</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="products-table" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Thumbnail</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>MRP</th>
                        <th>Distributor Price</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    let table;
    try {
        table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: { url: '{{ route('admin.products.index') }}', type: 'GET' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'category', name: 'category' },
                { data: 'mrp', name: 'mrp' },
                { data: 'distributor_price', name: 'distributor_price' },
                { data: 'price', name: 'price' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[8, 'desc']],
            drawCallback: function() {
                window.toggleProductStatus = function(id) {
                    if (!confirm('Toggle this product\'s status?')) return;
                    $.post({
                        url: '{{ url('admin/products') }}/' + id + '/toggle-status',
                        data: { _token: '{{ csrf_token() }}' }
                    }).done(function() { table.ajax.reload(null, false); })
                      .fail(function(xhr) { alert('Failed to toggle status'); console.error(xhr.responseText); });
                };
                window.deleteProduct = function(id) {
                    if (!confirm('Delete this product?')) return;
                    $.ajax({
                        url: '{{ url('admin/products') }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' }
                    }).done(function() { table.ajax.reload(null, false); })
                      .fail(function(xhr) { alert('Failed to delete product'); console.error(xhr.responseText); });
                };
            }
        });

        $('#products-table').on('error.dt', function(e, settings, techNote, message) {
            console.error('DataTables error:', message);
        });
    } catch (e) {
        console.error('Failed to initialize DataTable:', e);
    }
});
</script>
@endpush