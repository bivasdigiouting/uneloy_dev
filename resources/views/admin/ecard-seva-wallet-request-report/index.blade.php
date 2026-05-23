@extends('layouts.admin')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">E-Card Seva Wallet Req. Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">E-Card Seva</li>
                        <li class="breadcrumb-item active">Wallet Req. Report</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
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
                                <label class="form-label">Search (ID / Email / Mobile)</label>
                                <input type="text" id="search" class="form-control" placeholder="e.g. 1001 or user@domain.com" />
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="alert alert-warning mb-0">Total Pending: <strong id="sum_pending">₹0</strong></div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-success mb-0">Total Approved: <strong id="sum_approved">₹0</strong></div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-danger mb-0">Total Rejected: <strong id="sum_rejected">₹0</strong></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="ecardSevaWalletRequestsTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Id No</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Payment Status</th>
                                        <th>Transaction Id</th>
                                        <th>Remark</th>
                                        <th>Req. Date</th>
                                        <th>Admin Remark</th>
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
    $(function () {
        const table = $('#ecardSevaWalletRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "{{ route('admin.ecard-seva-wallet-request-report.data') }}",
                data: function (d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.request_status = $('#request_status').val();
                    d.search = $('#search').val();
                },
                dataSrc: function (json) {
                    if (json && json.totals) {
                        $('#sum_pending').text(json.totals.pending || '₹0');
                        $('#sum_approved').text(json.totals.approved || '₹0');
                        $('#sum_rejected').text(json.totals.rejected || '₹0');
                    }
                    return json.data;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'reg_user_id', name: 'reg_user_id' },
                { data: 'full_name', name: 'full_name' },
                { data: 'amount', name: 'amount' },
                { data: 'status', name: 'status' },
                { data: 'transaction_id', name: 'transaction_id' },
                { data: 'remark', name: 'remark' },
                { data: 'created_at', name: 'created_at' },
                { data: 'admin_remark', name: 'admin_remark' },
            ],
            order: [[7, 'desc']]
        });

        $('#from_date, #to_date, #request_status').on('change', function () {
            table.ajax.reload();
        });
        $('#search').on('keyup', _.debounce(function () { table.ajax.reload(); }, 300));
    });
</script>
@endpush