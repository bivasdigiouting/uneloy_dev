@extends('layouts.admin')

@section('title', 'GST Tax Rate')

@section('content')

    <div class="content ">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title">GST Tax Rate</h4>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">GST Tax Rate</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.gst-taxes.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Tax Rate</a>
                    <a href="javascript:void(0);" class="btn btn-outline-secondary" onclick="$('#gstTaxesTable').DataTable().ajax.reload();"><i class="ti ti-refresh"></i> Refresh</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="gstTaxesTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Action</th>
                                <th>Tax Name</th>
                                <th>Rate (%)</th>
                                <th>Status</th>
                                <th>Created At</th>
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
$(function() {
    var table = $('#gstTaxesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.gst-taxes.data') }}',
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'tax_name', name: 'tax_name' },
            { data: 'rate_percent', name: 'rate_percent' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' }
        ],
        order: [[5, 'desc']],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf']
    });

    // Delete handler
    $(document).on('click', '.btn-delete', function() {
        const url = $(this).data('url');
        if (!confirm('Are you sure you want to delete this tax rate?')) return;
        $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(resp) {
                if (resp.success) {
                    $('#gstTaxesTable').DataTable().ajax.reload();
                } else {
                    alert('Delete failed');
                }
            },
            error: function() {
                alert('An error occurred while deleting');
            }
        });
    });

    // Toggle status handler
    $(document).on('click', '.btn-toggle', function() {
        const url = $(this).data('url');
        $.post(url, { _token: '{{ csrf_token() }}' }, function(resp) {
            if (resp.success) {
                $('#gstTaxesTable').DataTable().ajax.reload();
            } else {
                alert('Toggle failed');
            }
        }).fail(function() {
            alert('An error occurred while toggling status');
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.table th { font-weight: 600; }
.btn + .btn { margin-left: 6px; }
</style>
@endpush