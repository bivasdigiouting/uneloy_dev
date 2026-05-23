@extends('layouts.admin')

@section('content')
<div class="pagetitle">
    <h1>A/R Withdrawal Report</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">E-Card Seva Modules</li>
            <li class="breadcrumb-item active">A/R Withdrawal Report</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Filters</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" id="from_date" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" id="to_date" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Request Status</label>
                    <select id="request_status" class="form-select">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" id="search" class="form-control" placeholder="ID / Name / Email / Mobile" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button id="applyFilters" type="button" class="btn btn-primary">Search</button>
                        <button id="resetFilters" type="button" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="retailerWithdrawalTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Action</th>
                            <th>Retailer Id</th>
                            <th>Retailer Name</th>
                            <th>Req. Date</th>
                            <th>Withdrawal Amount</th>
                            <th>Payment Status</th>
                            <th>Remark</th>
                            <th>Benificary Name</th>
                            <th>Admin Remark</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    const table = $('#retailerWithdrawalTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: '{{ route('admin.ecard-seva-ar-withdrawal-report.data') }}',
            data: function (d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.request_status = $('#request_status').val();
                d.search = $('#search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'retailer_id', name: 'retailer_id' },
            { data: 'retailer_name', name: 'retailer_name' },
            { data: 'req_date', name: 'wfr.created_at' },
            { data: 'withdrawal_amount', name: 'wfr.amount', searchable: false },
            { data: 'payment_status', name: 'wfr.status', orderable: false, searchable: false },
            { data: 'remark', name: 'wfr.remark' },
            { data: 'beneficiary_name', name: 'beneficiary_name', orderable: false },
            { data: 'admin_remark', name: 'wfr.admin_remark' },
        ],
        order: [[4, 'desc']]
    });

    $('#applyFilters').on('click', function(){ table.ajax.reload(); });
    $('#resetFilters').on('click', function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#request_status').val('');
        $('#search').val('');
        table.ajax.reload();
    });
});
</script>
@endpush