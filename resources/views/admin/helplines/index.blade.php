@extends('layouts.admin')

@section('title', 'Helpline Master')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Helpline Master</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Benefit Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Helpline Master</li>
                </ol>
            </nav>
        </div>
        <div class="ms-2">
            <a href="{{ route('admin.helplines.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Helpline</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="helplines-table" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Icon</th>
                            <th>Helpline Name</th>
                            <th>Helpline Number</th>
                            <th>State</th>
                            <th>District</th>
                            <th>City</th>
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
    const table = $('#helplines-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.helplines.index') }}',
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'icon', name: 'icon', orderable: false, searchable: false },
            { data: 'helpline_name', name: 'helpline_name' },
            { data: 'helpline_number', name: 'helpline_number' },
            { data: 'state_id', name: 'state_id' },
            { data: 'district_id', name: 'district_id' },
            { data: 'city_id', name: 'city_id' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
        order: [[7, 'desc']]
    });

    $(document).on('click', '.delete-helpline', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this helpline?')) return;
        $.ajax({
            url: '{{ url('admin/helplines') }}/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.success) {
                    table.ajax.reload(null, false);
                } else {
                    alert('Failed to delete helpline');
                }
            },
            error: function () {
                alert('Error while deleting');
            }
        });
    });
});
</script>
@endpush