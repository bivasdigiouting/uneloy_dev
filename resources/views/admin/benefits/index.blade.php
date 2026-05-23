@extends('layouts.admin')

@section('title', 'Benefit Master')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Benefit Master</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Benefit Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Benefits Master</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Benefits</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.benefits.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add Benefit
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="benefitsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Icon</th>
                                    <th>Benefit Name</th>
                                    <th>Schema Type</th>
                                    <th>Schema Type Name</th>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#benefitsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: { url: "{{ route('admin.benefits.index') }}", type: 'GET' },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'icon_preview', name: 'icon_preview', orderable: false, searchable: false },
            { data: 'benefit_name', name: 'benefit_name' },
            { data: 'schema_type', name: 'schema_type' },
            { data: 'schema_type_name', name: 'schema_type_name' },
            { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[6, 'desc']]
    });

    // Delete handler
    $(document).on('click', '.delete-btn', function() {
        var url = $(this).data('url');
        if (confirm('Are you sure you want to delete this benefit?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                success: function(res) { table.ajax.reload(null, false); },
                error: function() { alert('Delete failed'); }
            });
        }
    });
});
</script>
@endpush