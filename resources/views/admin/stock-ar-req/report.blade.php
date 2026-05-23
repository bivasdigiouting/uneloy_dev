@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">A &amp; R Req. Stock Report</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Stock Management</li>
                <li class="breadcrumb-item active">A &amp; R Req. Stock Report</li>
            </ul>
        </div>
    </div>
}</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter</h5>
            </div>
            <div class="card-body">
                <form id="report-filter-form" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" id="from_date" name="from_date" class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" id="to_date" name="to_date" class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Member ID</label>
                        <input type="text" id="member_id" name="member_id" class="form-control" placeholder="Enter Member ID" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Enter Product Name" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">All</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="button" id="apply-filter" class="btn btn-primary">Apply Filter</button>
                        <button type="reset" id="reset-filter" class="btn btn-light">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">A &amp; R Stock Requests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="ar-req-stock-report-table" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
}</div>
@endsection

@push('scripts')
<script>
    const reportRoutes = {
        data: "{{ route('admin.stock-ar-req.report.data') }}",
    };

    $(document).ready(function() {
        const table = $('#ar-req-stock-report-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: reportRoutes.data,
                data: function(d) {
                    d.from_date = $('#from_date').val() || '';
                    d.to_date = $('#to_date').val() || '';
                    d.member_id = $('#member_id').val() || '';
                    d.product_name = $('#product_name').val() || '';
                    d.status = $('#status').val() || '';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'member_id', name: 'member_id' },
                { data: 'product_name', name: 'product_name' },
                { data: 'quantity', name: 'quantity' },
                { data: 'status', name: 'status' },
                { data: 'remark', name: 'remark' },
                { data: 'created_at', name: 'created_at' },
            ]
        });

        $('#apply-filter').on('click', function() {
            table.ajax.reload();
        });

        $('#reset-filter').on('click', function() {
            setTimeout(function() {
                table.ajax.reload();
            }, 50);
        });
    });
</script>
@endpush