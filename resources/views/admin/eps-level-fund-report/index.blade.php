@extends('layouts.admin')

@section('title', 'Global Disburs. Level Fund Report')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Global Disburs. Level Fund Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">E-Card Seva & E.P.S Module</li>
                    <li class="breadcrumb-item active" aria-current="page">Global Disburs. Level Fund Report</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Filter Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filter</h5>
                </div>
                <div class="card-body">
                    <form id="filter-form" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search (ID / Email / Mobile)</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="Enter User ID, Email, or Mobile" />
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="btn-filter" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Report</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="eps-level-fund-report-table" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Level name</th>
                                    <th>User Id</th>
                                    <th>User Name</th>
                                    <th>Date</th>
                                    <th>Distributed Fund</th>
                                    <th>Total Distributed Fund</th>
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
const routes = {
    data: "{{ route('admin.eps-level-fund-report.data') }}",
};

$(document).ready(function() {
    const table = $('#eps-level-fund-report-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: routes.data,
            data: function(d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.search = $('#search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'level', name: 'level' },
            { data: 'user_id', name: 'user_id' },
            { data: 'user_name', name: 'user_name' },
            { data: 'date', name: 'date' },
            { data: 'distributed_fund', name: 'distributed_fund' },
            { data: 'total_distributed_fund', name: 'total_distributed_fund' },
        ],
        order: [[4, 'desc']]
    });

    $('#btn-filter').on('click', function() {
        table.ajax.reload();
    });
});
</script>
@endpush