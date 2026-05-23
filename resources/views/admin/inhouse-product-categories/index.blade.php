@extends('layouts.admin')

@section('title', 'Inhouse Product Categories')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Inhouse Product Categories</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Inhouse Product Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Product Categories</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="mb-2">
                <a href="{{ route('admin.inhouse-product-categories.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-2"></i>Add Category
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
            <h5 class="card-title mb-0">Category List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100" id="inhouse-product-categories-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Order</th>
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
    const table = $('#inhouse-product-categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.inhouse-product-categories.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'icon_display', name: 'icon_display', orderable: false, searchable: false },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'display_order', name: 'display_order' },
            {
                data: 'status',
                name: 'status',
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
                    actions += '<a href="' + "{{ route('admin.inhouse-product-categories.show', ':id') }}".replace(':id', row.id) + '" class="me-2"><i class="ti ti-eye"></i></a>';
                    actions += '<a href="' + "{{ route('admin.inhouse-product-categories.edit', ':id') }}".replace(':id', row.id) + '" class="me-2"><i class="ti ti-edit"></i></a>';
                    const statusIcon = row.status == 1 ? 'ti-toggle-right text-success' : 'ti-toggle-left text-danger';
                    const statusTitle = row.status == 1 ? 'Deactivate' : 'Activate';
                    actions += '<a href="javascript:void(0);" class="me-2 toggle-status" data-id="' + row.id + '" title="' + statusTitle + '"><i class="ti ' + statusIcon + '"></i></a>';
                    actions += '<a href="javascript:void(0);" class="delete-btn" data-id="' + row.id + '"><i class="ti ti-trash"></i></a>';
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[5, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search inhouse categories...",
            lengthMenu: "_MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)"
        }
    });

    $(document).on('click', '.toggle-status', function() {
        const id = $(this).data('id');
        const button = $(this);
        $.ajax({
            url: "{{ route('admin.inhouse-product-categories.toggle-status', ':id') }}".replace(':id', id),
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(resp) {
                if (!resp || !resp.success) {
                    return;
                }
                const icon = button.find('i');
                if (resp.status == 1) {
                    icon.removeClass('ti-toggle-left text-danger').addClass('ti-toggle-right text-success');
                    button.attr('title', 'Deactivate');
                } else {
                    icon.removeClass('ti-toggle-right text-success').addClass('ti-toggle-left text-danger');
                    button.attr('title', 'Activate');
                }
                table.ajax.reload(null, false);
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (!confirm('Delete this inhouse category?')) {
            return;
        }
        $.ajax({
            url: "{{ route('admin.inhouse-product-categories.destroy', ':id') }}".replace(':id', id),
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

