@extends('layouts.admin')

@section('title', 'User Redeem Report')

@section('content')
<div class="container">
  <div class="page-header">
    <div class="row align-items-center">
      <div class="col">
        <h3 class="page-title">User Redeem Report</h3>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Redeem Modules</li>
          <li class="breadcrumb-item active">User Redeem Report</li>
        </ul>
      </div>
    </div>
    <hr>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">Filter</h5>
    </div>
    <div class="card-body">
      <form id="filter-form" class="row g-3">
        <div class="col-md-3">
          <label for="filter_month" class="form-label">Select Month</label>
          <input type="month" id="filter_month" name="filter_month" class="form-control">
        </div>
        <div class="col-md-4">
          <label for="search_text" class="form-label">Search (ID/Name/Email)</label>
          <input type="text" id="search_text" name="search_text" class="form-control" placeholder="Enter ID, name or email">
        </div>
        <div class="col-md-5 d-flex align-items-end">
          <div>
            <button type="button" id="btn-search" class="btn btn-primary">Search</button>
            <button type="button" id="btn-reset" class="btn btn-secondary ms-2">Reset</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Report List</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="report-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th>User Id (Name)</th>
              <th>Month Name</th>
              <th>Total Purchase</th>
              <th>Distribute Value</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endsection

@section('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <script>
    $(function() {
      var table = $('#report-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf'],
        ajax: {
          url: '{{ route('admin.redeem-values.user-redeem-report.data') }}',
          data: function (d) {
            d.filter_month = $('#filter_month').val();
            d.search_text = $('#search_text').val();
          }
        },
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'user_display', name: 'user_display', orderable: false, searchable: false },
          { data: 'month_name', name: 'month_name' },
          { data: 'total_purchase', name: 'total_purchase' },
          { data: 'distribute_value', name: 'distribute_value', orderable: false, searchable: false },
        ],
        order: [[2, 'desc']]
      });

      $('#btn-search').on('click', function () {
        table.ajax.reload();
      });
      $('#btn-reset').on('click', function () {
        $('#filter-form')[0].reset();
        table.ajax.reload();
      });
    });
  </script>
@endsection