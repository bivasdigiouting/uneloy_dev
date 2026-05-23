@extends('layouts.admin')
@section('title','Admin by User Point Report')
@section('content')
<div class="content">
  <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
      <h2 class="mb-1">Admin by User Point Report</h2>
      <nav>
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
          <li class="breadcrumb-item">Points Modules</li>
          <li class="breadcrumb-item active">Admin by User Point Report</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header"><strong>Filters</strong></div>
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label for="from_date" class="form-label">From date</label>
          <input type="date" class="form-control" id="from_date" name="from_date">
        </div>
        <div class="col-md-3">
          <label for="to_date" class="form-label">To date</label>
          <input type="date" class="form-control" id="to_date" name="to_date">
        </div>
        <div class="col-md-3">
          <label for="available_total_points" class="form-label">Available Admin By Total Points</label>
          <input type="text" class="form-control" id="available_total_points" name="available_total_points" placeholder="0" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label d-block">&nbsp;</label>
          <button id="searchBtn" class="btn btn-primary" type="button"><i class="ti ti-search"></i> Search</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><strong>Admin by User Point Report</strong></div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="adminUserPointsReportTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th>Order No.</th>
              <th>Order Date</th>
              <th>Credit</th>
              <th>Debit</th>
              <th>Mode</th>
              <th>Narration</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
  var table = $('#adminUserPointsReportTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route('admin.points.admin-user-report.data') }}',
      type: 'GET',
      data: function(d) {
        d.from_date = $('#from_date').val();
        d.to_date = $('#to_date').val();
      }
    },
    order: [[2, 'desc']],
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'order_no', name: 'order_no' },
      { data: 'order_date', name: 'order_date' },
      { data: 'credit', name: 'credit', orderable: false, searchable: false },
      { data: 'debit', name: 'debit', orderable: false, searchable: false },
      { data: 'mode', name: 'mode', orderable: false, searchable: false },
      { data: 'narration', name: 'narration', orderable: false, searchable: false },
    ],
    drawCallback: function(settings) {
      var api = this.api();
      var json = api.ajax.json() || {};
      var summary = json.summary || {};
      var avail = summary.available_total_points || 0;
      $('#available_total_points').val(avail);
    }
  });

  $('#searchBtn').on('click', function() { table.ajax.reload(); });
});
</script>
@endpush