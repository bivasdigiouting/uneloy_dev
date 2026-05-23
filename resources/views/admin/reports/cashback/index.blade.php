@extends('layouts.admin')
@section('title','Cashback Report')
@section('content')
<div class="content">
  <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
      <h2 class="mb-1">Cashback Report</h2>
      <nav>
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
          <li class="breadcrumb-item">Report Modules</li>
          <li class="breadcrumb-item active">Cashback Report</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted">Total Cashback</div>
            <div class="h4 mb-0" id="total_cashback">0.00</div>
          </div>
          <div class="avatar"><span class="avatar-title bg-success-subtle text-success"><i class="ti ti-currency-rupee"></i></span></div>
        </div>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted">Average Cashback</div>
            <div class="h4 mb-0" id="avg_cashback">0.00</div>
          </div>
          <div class="avatar"><span class="avatar-title bg-primary-subtle text-primary"><i class="ti ti-chart-bar"></i></span></div>
        </div>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted">Orders with Cashback</div>
            <div class="h4 mb-0" id="cashback_count">0</div>
          </div>
          <div class="avatar"><span class="avatar-title bg-info-subtle text-info"><i class="ti ti-basket"></i></span></div>
        </div>
      </div></div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header"><strong>Filters</strong></div>
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-2">
          <label for="from_date" class="form-label">From date</label>
          <input type="date" class="form-control" id="from_date" name="from_date">
        </div>
        <div class="col-md-2">
          <label for="to_date" class="form-label">To date</label>
          <input type="date" class="form-control" id="to_date" name="to_date">
        </div>
        <div class="col-md-3">
          <label for="order_no" class="form-label">Order No.</label>
          <div class="input-group">
            <input type="text" class="form-control" id="order_no" name="order_no" placeholder="Order number">
            <button id="searchBtn" class="btn btn-primary" type="button"><i class="ti ti-search"></i> Search</button>
          </div>
        </div>
        <div class="col-md-2">
          <label for="min_cashback" class="form-label">Min Cashback</label>
          <input type="number" step="0.01" class="form-control" id="min_cashback" name="min_cashback" placeholder="0.00">
        </div>
        <div class="col-md-2">
          <label for="max_cashback" class="form-label">Max Cashback</label>
          <input type="number" step="0.01" class="form-control" id="max_cashback" name="max_cashback" placeholder="0.00">
        </div>
        <div class="col-md-3">
          <label for="search_text" class="form-label">Search (name/email)</label>
          <input type="text" class="form-control" id="search_text" name="search_text" placeholder="Seller/Buyer name or email">
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><strong>Cashbacks</strong></div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="cashbackTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th>Order No.</th>
              <th>Order Date</th>
              <th>Seller ID (Name)</th>
              <th>Purchase ID (Name)</th>
              <th>Cashback Amount</th>
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
  var table = $('#cashbackTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route('admin.reports.cashback.data') }}',
      type: 'GET',
      data: function(d) {
        d.from_date = $('#from_date').val();
        d.to_date = $('#to_date').val();
        d.order_no = $('#order_no').val();
        d.min_cashback = $('#min_cashback').val();
        d.max_cashback = $('#max_cashback').val();
        d.search_text = $('#search_text').val();
      }
    },
    order: [[2, 'desc']],
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'order_no', name: 'order_no' },
      { data: 'order_date', name: 'order_date' },
      { data: 'seller_display', name: 'seller_display', orderable: false, searchable: false },
      { data: 'purchase_display', name: 'purchase_display', orderable: false, searchable: false },
      { data: 'cashback_amount', name: 'cashback_amount' },
    ],
    drawCallback: function(settings) {
      var api = this.api();
      var json = api.ajax.json() || {};
      var summary = json.summary || {};
      function fmt(v) { return (v !== undefined && v !== null) ? v : 0; }
      $('#total_cashback').text(Number(fmt(summary.total_cashback)).toFixed(2));
      $('#avg_cashback').text(Number(fmt(summary.avg_cashback)).toFixed(2));
      $('#cashback_count').text(fmt(summary.cashback_count));

      // Append a totals row at the bottom
      $('#cashbackTable tbody tr.table-summary').remove();
      var totalsRow = $('<tr class="table-summary bg-light fw-semibold"></tr>');
      totalsRow.append('<td>Totals</td>'); // Sr. No.
      totalsRow.append('<td></td>'); // Order No.
      totalsRow.append('<td></td>'); // Order Date
      totalsRow.append('<td></td>'); // Seller
      totalsRow.append('<td></td>'); // Purchase
      totalsRow.append('<td>'+ Number(fmt(summary.total_cashback)).toFixed(2) +'</td>');
      $('#cashbackTable tbody').append(totalsRow);
    }
  });

  $('#searchBtn').on('click', function() { table.ajax.reload(); });
});
</script>
@endpush