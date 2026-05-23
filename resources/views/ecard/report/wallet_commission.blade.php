@extends('ecard.ecard')

@section('title', 'Wallet Commission')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .kpi-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 12px;
        border: none;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .kpi-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .table-custom thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
    }
    .table-custom tbody td {
        vertical-align: middle;
        font-size: 0.95rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        color: white !important;
        border: 1px solid #0d6efd;
        border-radius: 6px;
    }
</style>

<section class="content">
    <div class="content-inner">
        <div class="container-fluid py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-dark">Wallet Commission Earnings</h4>
            </div>

            <!-- KPI Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card kpi-card shadow-sm bg-primary text-white h-100" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="kpi-icon bg-white text-primary flex-shrink-0 shadow-sm">
                                <i class="fas fa-wallet fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Total Lifetime Commission</h6>
                                <h3 class="fw-bold mb-0">â‚¹ {{ number_format($totalCommission ?? 0, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card kpi-card shadow-sm bg-success text-white h-100" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="kpi-icon bg-white text-success flex-shrink-0 shadow-sm">
                                <i class="fas fa-calendar-alt fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">This Month's Earnings</h6>
                                <h3 class="fw-bold mb-0">â‚¹ {{ number_format($thisMonthCommission ?? 0, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card kpi-card shadow-sm bg-info text-white h-100" style="background: linear-gradient(135deg, #0dcaf0 0%, #0bacce 100%);">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="kpi-icon bg-white text-info flex-shrink-0 shadow-sm">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Today's Earnings</h6>
                                <h3 class="fw-bold mb-0">â‚¹ {{ number_format($todayCommission ?? 0, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable Report -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <h5 class="mb-0 fw-bold">Commission Ledger</h5>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" id="filter_operation" class="form-control border-start-0" placeholder="Search operations...">
                                </div>
                                <button class="btn btn-sm btn-primary fw-bold px-3" id="btnFilter" type="button">Apply</button>
                                <button class="btn btn-sm btn-light border fw-bold px-3" id="btnReset" type="button">Reset</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive p-4">
                                <table class="table table-hover table-custom w-100" id="commissionTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Date & Time</th>
                                            <th width="50%">Operation Detail</th>
                                            <th width="20%">Earned Amount</th>
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
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function () {
        const table = $('#commissionTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records...",
                lengthMenu: "Show _MENU_ entries"
            },
            ajax: {
                url: "{{ route('ecard.report.wallet-commission.data') }}",
                data: function (d) {
                    d.operation = $('#filter_operation').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-muted' },
                { data: 'created_at', name: 'created_at' },
                { data: 'operation_name', name: 'narration' },
                { data: 'amount', name: 'amount' },
            ],
            order: [[1, 'desc']],
            dom: '<"row align-items-center"<"col-md-6"l><"col-md-6"f>>rt<"row align-items-center border-top pt-3 mt-3"<"col-md-6"i><"col-md-6"p>>',
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm justify-content-end mb-0');
            }
        });

        $('#btnFilter').on('click', function () {
            table.ajax.reload();
        });
        
        $('#btnReset').on('click', function () {
            $('#filter_operation').val('');
            table.ajax.reload();
        });
        
        // Enter key support for filter
        $('#filter_operation').on('keypress', function(e) {
            if(e.which == 13) {
                table.ajax.reload();
            }
        });
    });
</script>
@endsection

