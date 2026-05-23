@extends('ecard.ecard')

@section('title', 'TDS Report')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
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
                <h4 class="fw-bold mb-0 text-dark">TDS Report</h4>
            </div>

            <!-- DataTable Report -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <h5 class="mb-0 fw-bold">TDS Ledger</h5>
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
                                <table class="table table-hover table-custom w-100" id="tdsTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="20%">Date & Time</th>
                                            <th width="30%">Source (Narration)</th>
                                            <th width="15%">Gross Amount</th>
                                            <th width="15%">TDS Deducted (5%)</th>
                                            <th width="15%">Net Amount</th>
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
        const table = $('#tdsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records...",
                lengthMenu: "Show _MENU_ entries"
            },
            ajax: {
                url: "{{ route('ecard.report.tds-report.data') }}",
                data: function (d) {
                    d.operation = $('#filter_operation').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-muted' },
                { data: 'created_at', name: 'created_at' },
                { data: 'source', name: 'narration' },
                { data: 'gross_amount', name: 'amount', orderable: false, searchable: false },
                { data: 'tds_deducted', name: 'amount', orderable: false, searchable: false },
                { data: 'net_amount', name: 'amount' },
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
