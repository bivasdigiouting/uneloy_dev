@extends('layouts.admin')

@section('title', 'A/R Advertisement Report')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Approve/Reject Advertisement Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Advertisement Module</li>
                    <li class="breadcrumb-item active" aria-current="page">A/R Advertisement Report</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" id="from_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" id="to_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select id="type" class="form-select">
                        <option value="">All</option>
                        <option value="user">User</option>
                        <option value="vendor">Vendor</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select id="department" class="form-select">
                        <option value="">All</option>
                        <option value="Banner">Banner</option>
                        <option value="Poster">Poster</option>
                        <option value="Social Media Link">Social Media Link</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Request Status</label>
                    <select id="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search (Id, Name/Email)</label>
                    <input type="text" id="search" class="form-control" placeholder="Enter Id or Name/Email">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button id="btnSearch" class="btn btn-primary w-100"><i class="ti ti-search"></i> Search</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="arAdvertisementTable" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Action</th>
                            <th>Campaign Name</th>
                            <th>Business Category</th>
                            <th>Lead</th>
                            <th>Location</th>
                            <th>Advertisement Type</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    var table = $('#arAdvertisementTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.reports.advertisements.approve-reject.data') }}',
            data: function (d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.type = $('#type').val();
                d.department = $('#department').val();
                d.status = $('#status').val();
                d.search = $('#search').val();
            }
        },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf'],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'campaign_name', name: 'campaign_name' },
            { data: 'business_category_id', name: 'business_category_id' },
            { data: 'lead_id', name: 'lead_id' },
            { data: 'location', name: 'location' },
            { data: 'advertisement_type', name: 'advertisement_type' },
            { data: 'from_date', name: 'from_date' },
            { data: 'to_date', name: 'to_date' },
            { data: 'status', name: 'status' },
        ]
    });

    $('#btnSearch').on('click', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
});
</script>
@endsection