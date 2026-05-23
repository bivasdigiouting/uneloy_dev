@extends('layouts.admin')
@section('page_title', 'Level Commission Report')

@section('content')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h4 class="page-title">Level Commission Report</h4>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">Report Modules</li>
        <li class="breadcrumb-item active">Level Commission Report</li>
      </ol>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-1 text-muted">Total Level Commission</p>
              <h5 class="mb-0" id="summary-total">0.00</h5>
            </div>
            <i class="ti ti-coin fs-24 text-primary"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-1 text-muted">Average Commission</p>
              <h5 class="mb-0" id="summary-average">0.00</h5>
            </div>
            <i class="ti ti-chart-average fs-24 text-success"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-1 text-muted">Records</p>
              <h5 class="mb-0" id="summary-count">0</h5>
            </div>
            <i class="ti ti-list-details fs-24 text-info"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-3 shadow-sm">
    <div class="card-header">
      <h6 class="mb-0">Filters</h6>
    </div>
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label for="start_date" class="form-label">Start Date</label>
          <input type="date" id="start_date" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
          <label for="end_date" class="form-label">End Date</label>
          <input type="date" id="end_date" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
          <label for="order_number" class="form-label">Order No.</label>
          <input type="text" id="order_number" class="form-control form-control-sm" placeholder="e.g., ORD-1001">
        </div>
        <div class="col-md-3">
          <label for="search" class="form-label">Search</label>
          <input type="text" id="search" class="form-control form-control-sm" placeholder="Name / Mobile / Order">
        </div>
        <div class="col-md-3">
          <label for="min_commission" class="form-label">Min Commission</label>
          <input type="number" step="0.01" min="0" id="min_commission" class="form-control form-control-sm" placeholder="0.00">
        </div>
        <div class="col-md-3">
          <label for="max_commission" class="form-label">Max Commission</label>
          <input type="number" step="0.01" min="0" id="max_commission" class="form-control form-control-sm" placeholder="100.00">
        </div>
        <div class="col-md-6 d-flex gap-2">
          <button id="btnFilter" class="btn btn-primary btn-sm"><i class="ti ti-filter"></i> Apply</button>
          <button id="btnReset" class="btn btn-secondary btn-sm"><i class="ti ti-refresh"></i> Reset</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Level Commission Details</h6>
    </div>
    <div class="card-body">
      <table id="levelCommissionTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th>Order No</th>
            <th>Order Date</th>
            <th>User</th>
            <th>Mobile</th>
            <th class="text-end">Level Commission</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
            <th colspan="4" class="text-end">Total:</th>
            <th class="text-end" id="footer-total">0.00</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const table = $('#levelCommissionTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    lengthChange: true,
    ajax: {
      url: "{{ route('admin.reports.level-commission.data') }}",
      data: function(d) {
        d.start_date = $('#start_date').val();
        d.end_date = $('#end_date').val();
        d.order_number = $('#order_number').val();
        d.search = $('#search').val();
        d.min_commission = $('#min_commission').val();
        d.max_commission = $('#max_commission').val();
      },
      dataSrc: function(json) {
        $('#summary-total').text(json.summary.total || '0.00');
        $('#summary-average').text(json.summary.average || '0.00');
        $('#summary-count').text(json.summary.count || 0);
        $('#footer-total').text(json.summary.total || '0.00');
        return json.data;
      }
    },
    columns: [
      { data: 'order_number', name: 'order_number' },
      { data: 'order_date', name: 'order_date' },
      { data: 'user_name', name: 'user_name' },
      { data: 'user_mobile', name: 'user_mobile' },
      { data: 'level_commission_amount', name: 'level_commission_amount', className: 'text-end' },
    ]
  });

  $('#btnFilter').on('click', function(){ table.ajax.reload(); });
  $('#btnReset').on('click', function(){
    $('#start_date').val('');
    $('#end_date').val('');
    $('#order_number').val('');
    $('#search').val('');
    $('#min_commission').val('');
    $('#max_commission').val('');
    table.ajax.reload();
  });
});
</script>
@endpush