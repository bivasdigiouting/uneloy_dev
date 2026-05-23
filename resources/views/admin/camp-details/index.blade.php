@extends('layouts.admin')

@section('title', 'Camp Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Camp Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Benefit Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Camp Details</li>
                </ol>
            </nav>
        </div>
        <div class="ms-2">
            <a href="{{ route('admin.camp-details.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> Add Camp Detail
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <table id="campDetailsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Banner</th>
                        <th>Camp</th>
                        <th>Title</th>
                        <th>Capacity</th>
                        <th>State</th>
                        <th>District</th>
                        <th>City</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    const table = $('#campDetailsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.camp-details.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'banner', name: 'banner', orderable: false, searchable: false },
            { data: 'camp_id', name: 'camp.camp_name' },
            { data: 'title', name: 'title' },
            { data: 'capacity', name: 'capacity' },
            { data: 'state_id', name: 'state.state_name' },
            { data: 'district_id', name: 'district.district_name' },
            { data: 'city_id', name: 'city.city_name' },
            { data: 'from_date', name: 'from_date' },
            { data: 'to_date', name: 'to_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
        order: [[8, 'desc']]
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        if (!confirm('Delete this record?')) return;
        const url = $(this).data('url');
        $.ajax({
            url,
            type: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function() { table.ajax.reload(null, false); },
            error: function() { alert('Failed to delete'); }
        });
    });
});
</script>
@endpush