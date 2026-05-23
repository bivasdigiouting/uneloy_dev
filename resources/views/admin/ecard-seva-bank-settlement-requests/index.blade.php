@extends('layouts.admin')

@section('title', 'Bank Settlement Request')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Bank Settlement Request</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">E-Card Seva</li>
                    <li class="breadcrumb-item active" aria-current="page">Bank Settlement Request</li>
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

    <!-- Filters + Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end g-3">
                        <div class="col-6 col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="request_status" class="form-label">Request Status</label>
                            <select id="request_status" class="form-select">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="search" class="form-label">Search (Id / Name / Email)</label>
                            <input type="text" class="form-control" id="search" placeholder="e.g. 123 or user@example.com">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                            <button id="applyFilters" type="button" class="btn btn-primary w-100">Search</button>
                            <button id="resetFilters" type="button" class="btn btn-outline-secondary w-100">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="bankSettlementRequestsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Action</th>
                                    <th>User Id</th>
                                    <th>User Name</th>
                                    <th>Req. Date</th>
                                    <th>Withdrawal Amount</th>
                                    <th>Beneficiary Name</th>
                                    <th>Payment Status</th>
                                    <th>Remark</th>
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
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('#bankSettlementRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [[4, 'desc']], // Order by Req. Date
            ajax: {
                url: '{{ route('ecard-seva-bank-settlement-requests.data') }}',
                data: function (d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.request_status = $('#request_status').val();
                    d.search = $('#search').val();
                }
            },
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'user_id', name: 'user_id' },
                { data: 'user_name', name: 'user_name' },
                { data: 'req_date', name: 'req_date' },
                { data: 'withdrawal_amount', name: 'withdrawal_amount' },
                { data: 'beneficiary_name', name: 'beneficiary_name' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'remark', name: 'remark' },
                { data: 'admin_remark', name: 'admin_remark' }
            ]
        });

        $('#applyFilters').on('click', function () { table.ajax.reload(); });

        $('#resetFilters').on('click', function () {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#request_status').val('');
            $('#search').val('');
            table.ajax.reload();
        });
    });
</script>
@endpush