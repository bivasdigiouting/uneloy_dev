@extends('layouts.admin')

@section('title', 'E.P.S User Fund')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">E.P.S User Fund</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Membership E.P.S Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">E.P.S User Fund</li>
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

    <!-- Add Fund Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add Fund</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.membership.eps-user-fund.store') }}" method="POST" id="epsFundForm">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-4">
                                <label for="fund_type" class="form-label">Select Fund Type</label>
                                <select name="fund_type" id="fund_type" class="form-select">
                                    @foreach($fundTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="user_type" class="form-label">Select User Type</label>
                                <select name="user_type" id="user_type" class="form-select">
                                    <option value="">-- Select --</option>
                                    @foreach($userTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="amount" class="form-label">Enter Distributor Fund</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" placeholder="e.g. 100.00">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Fund</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Previously Added Funds (for selected user type) -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Previously Added Funds (Selected User Type)</h5>
                    <span id="historyStatus" class="text-muted"></span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="fundHistoryTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Fund Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="fundHistoryBody">
                                <tr><td colspan="3" class="text-center text-muted">Select a User Type to load history</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end g-3">
                        <div class="col-12 col-md-6">
                            <p class="mb-0">List is generated after selecting a User Type. Fund column shows the latest saved fund for that type.</p>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <div id="exportButtons" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="epsUserFundTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Status</th>
                                    <th>User Id</th>
                                    <th>User Name</th>
                                    <th>Mobile No.</th>
                                    <th>Fund</th>
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
    // Initialize main DataTable
    var table = $('#epsUserFundTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: "{{ route('admin.membership.eps-user-fund.data') }}",
            type: 'GET',
            data: function(d) {
                d.user_type = $('#user_type').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'user_id', name: 'user_id' },
            { data: 'user_name', name: 'user_name' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'fund', name: 'fund', searchable: false },
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
            emptyTable: "Select a user type to load list"
        }
    });

    table.buttons().container().appendTo('#exportButtons');

    function loadHistory() {
        var type = $('#user_type').val();
        if (!type) {
            $('#fundHistoryBody').html('<tr><td colspan="3" class="text-center text-muted">Select a User Type to load history</td></tr>');
            $('#historyStatus').text('');
            return;
        }
        $('#historyStatus').text('Loading...');
        $.get("{{ route('admin.membership.eps-user-fund.history') }}", { user_type: type })
            .done(function(res) {
                var rows = '';
                if (res.items && res.items.length) {
                    res.items.forEach(function(item) {
                        rows += '<tr>'+
                            '<td>'+ item.date +'</td>'+
                            '<td>'+ item.fund_type +'</td>'+
                            '<td>'+ item.amount +'</td>'+
                        '</tr>';
                    });
                } else {
                    rows = '<tr><td colspan="3" class="text-center text-muted">No previous fund found for selected type</td></tr>';
                }
                $('#fundHistoryBody').html(rows);
            })
            .fail(function(){
                $('#fundHistoryBody').html('<tr><td colspan="3" class="text-center text-danger">Failed to load history</td></tr>');
            })
            .always(function(){
                $('#historyStatus').text('');
            });
    }

    // Reload on user type change
    $('#user_type').on('change', function(){
        table.ajax.reload();
        loadHistory();
    });

    // Initial load (no user type selected)
    loadHistory();
});
</script>
@endpush