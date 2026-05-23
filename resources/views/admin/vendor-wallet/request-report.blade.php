@extends('layouts.admin')

@section('title', 'Vendor Wallet Request Report')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Vendor Wallet Request Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Vendor Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Wallet Request Report</li>
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

    <!-- Filters + Totals + Table -->
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
                            <label for="search_by" class="form-label">Search (Vendor Id / Name / Email / Mobile)</label>
                            <input type="text" class="form-control" id="search_by" placeholder="e.g. 101 or Acme Corp or vendor@example.com or 9876543210">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                            <button id="applyFilters" type="button" class="btn btn-primary w-100">Search</button>
                            <button id="resetFilters" type="button" class="btn btn-outline-secondary w-100">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Totals -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded border bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Pending Amount</span>
                                    <span id="totalPending" class="fw-bold">₹0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded border bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Approved Amount</span>
                                    <span id="totalApproved" class="fw-bold">₹0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded border bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Rejected Amount</span>
                                    <span id="totalRejected" class="fw-bold">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="vendorWalletRequestsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
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
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('#vendorWalletRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [[8, 'desc']],
            ajax: {
                url: '{{ route('admin.vendor.wallet.request-report.data') }}',
                data: function (d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.request_status = $('#request_status').val();
                    d.search_by = $('#search_by').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'id_no', name: 'id_no' },
                { data: 'name', name: 'name' },
                { data: 'amount', name: 'amount' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'transaction_id', name: 'transaction_id' },
                { data: 'remark', name: 'remark' },
                { data: 'req_date', name: 'req_date' },
                { data: 'admin_remark', name: 'admin_remark' }
            ]
        });

        // Update totals when data loads
        $('#vendorWalletRequestsTable').on('xhr.dt', function (e, settings, json, xhr) {
            if (json && json.totals) {
                $('#totalPending').text(json.totals.pending ?? '₹0.00');
                $('#totalApproved').text(json.totals.approved ?? '₹0.00');
                $('#totalRejected').text(json.totals.rejected ?? '₹0.00');
            }
        });

        // Apply Filters
        $('#applyFilters').on('click', function () { table.ajax.reload(); });

        // Reset Filters
        $('#resetFilters').on('click', function () {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#request_status').val('');
            $('#search_by').val('');
            table.ajax.reload();
        });
    });
</script>
@endpush