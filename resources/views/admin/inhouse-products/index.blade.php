@extends('layouts.admin')

@section('title', 'Inhouse Products')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Inhouse Product Master</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Inhouse Product Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Product Master</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="mb-2">
                <a href="{{ route('admin.inhouse-products.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-2"></i>Add Product
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Products</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100" id="inhouse-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thumbnail</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>MRP</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const table = $('#inhouse-products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.inhouse-products.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'thumbnail_display', name: 'thumbnail_display', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'sku', name: 'sku' },
            { data: 'category_name', name: 'category_name' },
            { data: 'mrp', name: 'mrp' },
            { data: 'price', name: 'price' },
            { data: 'stock', name: 'stock' },
            {
                data: 'status_display',
                name: 'status_display',
                render: function(data) {
                    if (data == 1) {
                        return '<span class="badge badge-success-transparent">Active</span>';
                    }
                    return '<span class="badge badge-danger-transparent">Inactive</span>';
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                render: function(data) {
                    return data ? moment(data).format('DD MMM YYYY') : '';
                }
            },
            {
                data: null,
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(_, __, row) {
                    let actions = '<div class="action-icon d-inline-flex">';
                    actions += '<a href="' + "{{ route('admin.inhouse-products.show', ':id') }}".replace(':id', row.id) + '" class="me-2"><i class="ti ti-eye"></i></a>';
                    actions += '<a href="' + "{{ route('admin.inhouse-products.edit', ':id') }}".replace(':id', row.id) + '" class="me-2"><i class="ti ti-edit"></i></a>';
                    const statusIcon = row.status_display == 1 ? 'ti-toggle-right text-success' : 'ti-toggle-left text-danger';
                    const statusTitle = row.status_display == 1 ? 'Deactivate' : 'Activate';
                    actions += '<a href="javascript:void(0);" class="me-2 toggle-status" data-id="' + row.id + '" title="' + statusTitle + '"><i class="ti ' + statusIcon + '"></i></a>';
                    actions += '<a href="javascript:void(0);" class="delete-btn" data-id="' + row.id + '"><i class="ti ti-trash"></i></a>';
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true
    });

    $(document).on('click', '.toggle-status', function() {
        const id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.inhouse-products.toggle-status', ':id') }}".replace(':id', id),
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(resp) {
                if (resp && resp.success) {
                    table.ajax.reload(null, false);
                }
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (!confirm('Delete this inhouse product?')) {
            return;
        }
        $.ajax({
            url: "{{ route('admin.inhouse-products.destroy', ':id') }}".replace(':id', id),
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(resp) {
                if (resp && resp.success) {
                    table.ajax.reload(null, false);
                }
            }
        });
    });
});
</script>
@endpush

