@extends('layouts.admin')

@section('title', 'Vendor by User Points Report')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Vendor by User Points Report</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Points Modules</li>
                <li class="breadcrumb-item active">Vendor by User Points Report</li>
            </ul>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Filters</h4>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="from_date" class="form-label">From date</label>
                <input type="date" id="from_date" class="form-control" />
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label">To date</label>
                <input type="date" id="to_date" class="form-control" />
            </div>
            <div class="col-md-4">
                <label for="available_total_points" class="form-label">Available Vendor By Total Points</label>
                <input type="text" id="available_total_points" class="form-control" value="0" readonly />
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="btnSearch" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="vendorPointsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Vendor IDNo (Name)</th>
                        <th>Vendor Mobile No.</th>
                        <th>User IDNo (Name)</th>
                        <th>Order No.</th>
                        <th>Order Date</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Mode</th>
                        <th>Narration</th>
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
    (function() {
        let table = null;

        function reloadTable() {
            if (table) {
                table.ajax.reload();
                return;
            }
            table = $('#vendorPointsTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                order: [[5, 'desc']],
                ajax: {
                    url: '{{ route('admin.points.vendor-user-report.data') }}',
                    type: 'GET',
                    data: function (d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' }, // Sr. No.
                    { data: 'vendor_ref', name: 'vendor_ref' },
                    { data: 'vendor_mobile', name: 'vendor_mobile' },
                    { data: 'user_ref', name: 'user_ref' },
                    { data: 'order_no', name: 'order_no' },
                    { data: 'order_date', name: 'order_date' },
                    { data: 'credit', name: 'credit' },
                    { data: 'debit', name: 'debit' },
                    { data: 'mode', name: 'mode' },
                    { data: 'narration', name: 'narration' },
                ],
                drawCallback: function(settings) {
                    try {
                        const json = settings.json || {};
                        const summary = json.summary || {};
                        const available = (summary.available_points || 0);
                        $('#available_total_points').val(available);
                    } catch (e) { /* ignore */ }
                }
            });
        }

        $('#btnSearch').on('click', function() { reloadTable(); });

        // Initialize on load
        reloadTable();
    })();
</script>
@endpush