@extends('layouts.admin')

@section('title', 'Camp Master')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Camp Master</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Benefit Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Camp Master</li>
                </ol>
            </nav>
        </div>
        <div class="ms-2">
            <a href="{{ route('admin.camps.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Camp</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="camps-table" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Icon</th>
                            <th>Camp Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const table = $('#camps-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.camps.index') }}',
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'icon', name: 'icon', orderable: false, searchable: false },
            { data: 'camp_name', name: 'camp_name' },
            { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
        order: [[4, 'desc']]
    });

    $(document).on('click', '.delete-camp', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this camp?')) return;
        $.ajax({
            url: '{{ url('admin/camps') }}/' + id,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function () { table.ajax.reload(null, false); },
            error: function (xhr) { alert(xhr.responseJSON?.message || 'Delete failed'); }
        });
    });

    $(document).on('click', '.toggle-camp-status', function () {
        const id = $(this).data('id');
        const url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function () { table.ajax.reload(null, false); },
            error: function (xhr) { alert(xhr.responseJSON?.message || 'Toggle failed'); }
        });
    });
});
</script>
@endpush