@extends('layouts.admin')
@section('title','Voucher Details Report')
@section('content')
<div class="content">
  <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
      <h2 class="mb-1">Voucher Details Report</h2>
      <nav>
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
          <li class="breadcrumb-item">Report Modules</li>
          <li class="breadcrumb-item active">Voucher Details Report</li>
        </ol>
      </nav>
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
        <div class="col-md-2">
          <label for="give_voucher" class="form-label">Give Voucher</label>
          <select class="form-select" id="give_voucher" name="give_voucher">
            <option>All</option>
            <option>Yes</option>
            <option>No</option>
          </select>
        </div>
        <div class="col-md-2">
          <label for="apply_voucher" class="form-label">Apply Voucher</label>
          <select class="form-select" id="apply_voucher" name="apply_voucher">
            <option>All</option>
            <option>Yes</option>
            <option>No</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="voucher_no" class="form-label">Voucher No.</label>
          <div class="input-group">
            <input type="text" class="form-control" id="voucher_no" name="voucher_no" placeholder="Voucher number">
            <button id="searchBtn" class="btn btn-primary" type="button"><i class="ti ti-search"></i> Search</button>
          </div>
        </div>
        <div class="col-md-3">
          <label for="search_text" class="form-label">Search (name/email)</label>
          <input type="text" class="form-control" id="search_text" name="search_text" placeholder="Seller/Buyer name or email">
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><strong>Vouchers</strong></div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="voucherDetailsTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th>Order No.</th>
              <th>Order Date</th>
              <th>Seller ID (Name)</th>
              <th>Purchase ID (Name)</th>
              <th>Give Voucher Status</th>
              <th>Give Voucher No.</th>
              <th>Give Voucher Amt.</th>
              <th>Give Voucher Ex.Date</th>
              <th>Apply Voucher Status</th>
              <th>Apply Voucher No.</th>
              <th>Apply Voucher Amt.</th>
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
  var table = $('#voucherDetailsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route('admin.reports.voucher-details.data') }}',
      type: 'GET',
      data: function(d) {
        d.from_date = $('#from_date').val();
        d.to_date = $('#to_date').val();
        d.give_voucher = $('#give_voucher').val();
        d.apply_voucher = $('#apply_voucher').val();
        d.voucher_no = $('#voucher_no').val();
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
      { data: 'give_voucher_status', name: 'give_voucher_status' },
      { data: 'give_voucher_no', name: 'give_voucher_no' },
      { data: 'give_voucher_amount', name: 'give_voucher_amount' },
      { data: 'give_voucher_expiry_date', name: 'give_voucher_expiry_date' },
      { data: 'apply_voucher_status', name: 'apply_voucher_status' },
      { data: 'apply_voucher_no', name: 'apply_voucher_no' },
      { data: 'apply_voucher_amount', name: 'apply_voucher_amount' },
    ],
    drawCallback: function(settings) {
      var api = this.api();
      var json = api.ajax.json() || {};
      var summary = json.summary || {};
      $('#voucherDetailsTable tbody tr.table-summary').remove();
      var totalsRow = $('<tr class="table-summary bg-light fw-semibold"></tr>');
      function fmt(v) { return (v !== undefined && v !== null) ? v : ''; }
      totalsRow.append('<td>Totals</td>'); // Sr. No.
      totalsRow.append('<td></td>'); // Order No.
      totalsRow.append('<td></td>'); // Order Date
      totalsRow.append('<td></td>'); // Seller
      totalsRow.append('<td></td>'); // Purchase
      totalsRow.append('<td></td>'); // Give Voucher Status
      totalsRow.append('<td></td>'); // Give Voucher No.
      totalsRow.append('<td>'+ fmt(summary.give_voucher_amount) +'</td>');
      totalsRow.append('<td></td>'); // Give Voucher Ex.Date
      totalsRow.append('<td></td>'); // Apply Voucher Status
      totalsRow.append('<td></td>'); // Apply Voucher No.
      totalsRow.append('<td>'+ fmt(summary.apply_voucher_amount) +'</td>');
      $('#voucherDetailsTable tbody').append(totalsRow);
    }
  });

  $('#searchBtn').on('click', function() { table.ajax.reload(); });
});
</script>
@endpush