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
                    <li class="breadcrumb-item">Membership E.P.S Modules</li>
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
                    <form id="filterForm" class="row g-3">
                        <div class="col-12 col-md-3">
                            <label for="fund_type" class="form-label">Select Fund Type</label>
                            <select id="fund_type" name="fund_type" class="form-select">
                                @foreach($fundTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="user_type" class="form-label">Select User Type</label>
                            <select id="user_type" name="user_type" class="form-select">
                                <option value="">-- Select --</option>
                                @foreach($userTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control" />
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control" />
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="search_text" class="form-label">Search (ID / Email / Mobile)</label>
                            <input type="text" id="search_text" name="search_text" class="form-control" placeholder="Enter ID, Email, or Mobile" />
                        </div>
                        <div class="col-12 col-md-6 d-flex align-items-end justify-content-end">
                            <button type="button" id="applyFilters" class="btn btn-primary me-2">Apply</button>
                            <button type="button" id="resetFilters" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end g-3">
                        <div class="col-12 col-md-6">
                            <p class="mb-0">Report shows global distributed fund for selected user type and date range.</p>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <div id="exportButtons" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="epsGlobalDisbursReportTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>User Id</th>
                                    <th>User Name</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Distributed Fund</th>
                                    <th>Total Distributed Fund</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style>
    #exportButtons .dt-buttons { display: flex; flex-wrap: wrap; gap: .5rem; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#epsGlobalDisbursReportTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: "{{ route('admin.membership.eps-global-disburs-report.data') }}",
            type: 'GET',
            data: function(d) {
                d.fund_type = $('#fund_type').val();
                d.user_type = $('#user_type').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.search_text = $('#search_text').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_id', name: 'user_id' },
            { data: 'user_name', name: 'user_name' },
            { data: 'status', name: 'status' },
            { data: 'date', name: 'date', searchable: false },
            { data: 'distributed_fund', name: 'distributed_fund', searchable: false },
            { data: 'total_distributed_fund', name: 'total_distributed_fund', searchable: false },
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        buttons: [
            { extend: 'copyHtml5', className: 'btn btn-primary', text: 'Copy' },
            { extend: 'excelHtml5', className: 'btn btn-primary', text: 'Excel' },
            { extend: 'csvHtml5', className: 'btn btn-primary', text: 'CSV' },
            { extend: 'pdfHtml5', className: 'btn btn-primary', text: 'PDF', orientation: 'landscape', pageSize: 'A4' }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching users found",
            emptyTable: "Select filters to load report"
        }
    });

    table.buttons().container().appendTo('#exportButtons');

    $('#applyFilters').on('click', function(){
        table.ajax.reload();
    });

    $('#resetFilters').on('click', function(){
        $('#filterForm')[0].reset();
        table.ajax.reload();
    });

    // Also reload when user type selection changes
    $('#user_type').on('change', function(){ table.ajax.reload(); });
});
</script>
@endpush