@extends('ecard.ecard')

@section('title', 'Login History')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<section class="content">
    <div class="content-inner">
        <div class="container-fluid py-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="mb-0">Login History</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <input type="date" id="from_date" class="form-control form-control-sm" placeholder="From">
                                <input type="date" id="to_date" class="form-control form-control-sm" placeholder="To">
                                <input type="text" id="ip" class="form-control form-control-sm" placeholder="IP address">
                                <button class="btn btn-sm btn-primary" id="btnFilter" type="button">Apply</button>
                                <button class="btn btn-sm btn-outline-secondary" id="btnReset" type="button">Reset</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="historyTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>IP Address</th>
                                            <th>Platform</th>
                                            <th>User Agent</th>
                                            <th>Logged In</th>
                                            <th>Logged Out</th>
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
<script>
    $(function () {
        const table = $('#historyTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ecard.report.login-history.data') }}",
                data: function (d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.ip = $('#ip').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'ip_address', name: 'ip_address' },
                { data: 'platform', name: 'platform' },
                { data: 'user_agent', name: 'user_agent' },
                { data: 'logged_in_at', name: 'logged_in_at' },
                { data: 'logged_out_at', name: 'logged_out_at' },
            ],
            order: [[4, 'desc']],
            pageLength: 10,
        });

        $('#btnFilter').on('click', function () {
            table.ajax.reload();
        });
        $('#btnReset').on('click', function () {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#ip').val('');
            table.ajax.reload();
        });
    });
</script>
@endsection
