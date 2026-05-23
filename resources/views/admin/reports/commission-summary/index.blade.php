@extends('layouts.admin')

@section('title', 'Commission Summary Report')

@section('content')
<div class="page-header mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="mb-1">Commission Summary Report</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Report Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Commission Summary Report</li>
                </ol>
            </nav>
        </div>
    </div>
    <hr>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select id="status" class="form-select form-select-sm">
            <option value="">All</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Product Category</label>
        <input type="text" id="category" class="form-control form-control-sm" placeholder="Search category name">
    </div>
    <div class="col-md-2">
        <label class="form-label">Min Member Comm. (%)</label>
        <input type="number" id="min_commission" class="form-control form-control-sm" step="0.01" min="0" max="100" placeholder="0.00">
    </div>
    <div class="col-md-2">
        <label class="form-label">Max Member Comm. (%)</label>
        <input type="number" id="max_commission" class="form-control form-control-sm" step="0.01" min="0" max="100" placeholder="100.00">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button id="btnFilter" class="btn btn-primary btn-sm me-2">Apply Filters</button>
        <button id="btnReset" class="btn btn-outline-secondary btn-sm">Reset</button>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-2">
        <div class="card card-body py-2">
            <div class="text-muted small">Avg State</div>
            <div id="avg_state" class="fs-6 fw-semibold">0.00%</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-body py-2">
            <div class="text-muted small">Avg District</div>
            <div id="avg_district" class="fs-6 fw-semibold">0.00%</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-body py-2">
            <div class="text-muted small">Avg Block</div>
            <div id="avg_block" class="fs-6 fw-semibold">0.00%</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-body py-2">
            <div class="text-muted small">Avg Panchayat</div>
            <div id="avg_panchayat" class="fs-6 fw-semibold">0.00%</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-body py-2">
            <div class="text-muted small">Avg Village</div>
            <div id="avg_village" class="fs-6 fw-semibold">0.00%</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-body py-2">
            <div class="text-muted small">Avg Member</div>
            <div id="avg_customer" class="fs-6 fw-semibold">0.00%</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card card-body py-2">
            <div class="text-muted small">Active Categories</div>
            <div id="active_count" class="fs-6 fw-semibold">0</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body py-2">
            <div class="text-muted small">Inactive Categories</div>
            <div id="inactive_count" class="fs-6 fw-semibold">0</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body py-2">
            <div class="text-muted small">Total Categories</div>
            <div id="total_categories" class="fs-6 fw-semibold">0</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered" id="commissionTable" style="width:100%">
            <thead>
                <tr>
                    <th>Product Category</th>
                    <th>State Member (%)</th>
                    <th>District Member (%)</th>
                    <th>Block Member (%)</th>
                    <th>Panchayat Member (%)</th>
                    <th>Village Member (%)</th>
                    <th>Member (%)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="card-footer small text-muted">
        Showing Level Wise Product Commission settings across categories.
    </div>
    
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = $('#commissionTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pageLength: 10,
        ajax: {
            url: "{{ route('admin.reports.commission-summary.data') }}",
            data: function(d) {
                d.status = $('#status').val();
                d.category = $('#category').val();
                d.min_commission = $('#min_commission').val();
                d.max_commission = $('#max_commission').val();
            },
            dataSrc: function(json) {
                updateSummary(json.summary || {});
                return json.data || [];
            }
        },
        columns: [
            { data: 'product_category', name: 'product_category' },
            { data: 'state_member_commission', name: 'state_member_commission', className: 'text-end' },
            { data: 'district_member_commission', name: 'district_member_commission', className: 'text-end' },
            { data: 'block_member_commission', name: 'block_member_commission', className: 'text-end' },
            { data: 'panchayat_member_commission', name: 'panchayat_member_commission', className: 'text-end' },
            { data: 'village_member_commission', name: 'village_member_commission', className: 'text-end' },
            { data: 'customer_commission', name: 'customer_commission', className: 'text-end' },
            { data: 'is_active', name: 'is_active' },
        ]
    });

    function updateSummary(s) {
        $('#avg_state').text(s.avg_state || '0.00%');
        $('#avg_district').text(s.avg_district || '0.00%');
        $('#avg_block').text(s.avg_block || '0.00%');
        $('#avg_panchayat').text(s.avg_panchayat || '0.00%');
        $('#avg_village').text(s.avg_village || '0.00%');
        $('#avg_customer').text(s.avg_customer || '0.00%');
        $('#active_count').text(s.active_count ?? 0);
        $('#inactive_count').text(s.inactive_count ?? 0);
        $('#total_categories').text(s.total_categories ?? 0);
    }

    $('#btnFilter').on('click', function() {
        table.ajax.reload();
    });
    $('#btnReset').on('click', function() {
        $('#status').val('');
        $('#category').val('');
        $('#min_commission').val('');
        $('#max_commission').val('');
        table.ajax.reload();
    });
});
</script>
@endsection
