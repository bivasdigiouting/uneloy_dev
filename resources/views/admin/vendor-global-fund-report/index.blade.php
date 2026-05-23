@extends('layouts.admin')

@section('title', 'Vendor Global Disburs. Fund Report')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Vendor Global Disburs. Fund Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Vendor Global Disburs. Fund</li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Global Disburs. Fund Report</li>
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
                    <form id="vendorGlobalFundReportFilterForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control" />
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label for="search_text" class="form-label">Search (ID / Name / Email)</label>
                            <input type="text" id="search_text" name="search_text" class="form-control" placeholder="Enter ID, Name, or Email" />
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="btnApplyFilter" class="btn btn-primary me-2">Apply</button>
                            <button type="button" id="btnResetFilter" class="btn btn-secondary">Reset</button>
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
                        <table id="vendorGlobalFundReportTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Vendor Id</th>
                                    <th>Vendor Name</th>
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
(function(){
    const table = $('#vendorGlobalFundReportTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.vendor-global-fund-report.data') }}',
            data: function(d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.search_text = $('#search_text').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'vendor_id', name: 'vendors.id' },
            { data: 'vendor_name', name: 'vendors.business_name' },
            { data: 'date', name: 'latest_date', orderable: false },
            { data: 'distributed_fund', name: 'latest_amount', orderable: false, searchable: false },
            { data: 'total_distributed_fund', name: 'total_amount', orderable: false, searchable: false },
        ]
    });

    $('#btnApplyFilter').on('click', function(){ table.ajax.reload(); });
    $('#btnResetFilter').on('click', function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#search_text').val('');
        table.ajax.reload();
    });
})();
</script>
@endpush