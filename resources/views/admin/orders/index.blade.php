@extends('layouts.admin')
@section('title','View Order')
@section('content')
<div class="content">
  <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
      <h2 class="mb-1">View Order</h2>
      <nav>
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
          <li class="breadcrumb-item">User Management</li>
          <li class="breadcrumb-item active">View Order</li>
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
          <label for="give_option" class="form-label">Give Option</label>
          <select class="form-select" id="give_option" name="give_option">
            <option>All</option>
            <option>Yes</option>
            <option>No</option>
          </select>
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
          <label for="give_points" class="form-label">Give Points</label>
          <select class="form-select" id="give_points" name="give_points">
            <option>All</option>
            <option>Yes</option>
            <option>No</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="search_text" class="form-label">Search (id/name/email)</label>
          <div class="input-group">
            <input type="text" class="form-control" id="search_text" name="search_text" placeholder="id / name / email">
            <button id="searchBtn" class="btn btn-primary" type="button"><i class="ti ti-search"></i> Search</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><strong>Orders</strong></div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="viewOrderTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th>Order No.</th>
              <th>Order Date</th>
              <th>Seller ID (Name)</th>
              <th>Purchase ID (Name)</th>
              <th>No. of Items</th>
              <th>Total Qty</th>
              <th>Billing Amount</th>
              <th>Discount Amt.</th>
              <th>Cashback Amt.</th>
              <th>Give Coupon Status</th>
              <th>Give Coupon No.</th>
              <th>Apply Coupon Status</th>
              <th>Apply Coupon No.</th>
              <th>Apply Coupon Amt.</th>
              <th>Give Voucher Status</th>
              <th>Give Voucher No.</th>
              <th>Give Voucher Amt.</th>
              <th>Give Voucher Ex.Date</th>
              <th>Apply Voucher Status</th>
              <th>Apply Voucher No.</th>
              <th>Apply Voucher Amt.</th>
              <th>Give Points Status</th>
              <th>Give Points No.</th>
              <th>Apply Points Status</th>
              <th>Apply Points No.</th>
              <th>Total Points</th>
              <th>Action</th>
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
  var table = $('#viewOrderTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route('admin.view-orders.data') }}',
      type: 'GET',
      data: function(d) {
        d.from_date = $('#from_date').val();
        d.to_date = $('#to_date').val();
        d.give_option = $('#give_option').val();
        d.give_voucher = $('#give_voucher').val();
        d.give_points = $('#give_points').val();
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
      { data: 'item_count', name: 'item_count' },
      { data: 'total_qty', name: 'total_qty' },
      { data: 'billing_amount', name: 'billing_amount' },
      { data: 'discount_amount', name: 'discount_amount' },
      { data: 'cashback_amount', name: 'cashback_amount' },
      { data: 'give_coupon_status', name: 'give_coupon_status' },
      { data: 'give_coupon_no', name: 'give_coupon_no' },
      { data: 'apply_coupon_status', name: 'apply_coupon_status' },
      { data: 'apply_coupon_no', name: 'apply_coupon_no' },
      { data: 'apply_coupon_amount', name: 'apply_coupon_amount' },
      { data: 'give_voucher_status', name: 'give_voucher_status' },
      { data: 'give_voucher_no', name: 'give_voucher_no' },
      { data: 'give_voucher_amount', name: 'give_voucher_amount' },
      { data: 'give_voucher_expiry_date', name: 'give_voucher_expiry_date' },
      { data: 'apply_voucher_status', name: 'apply_voucher_status' },
      { data: 'apply_voucher_no', name: 'apply_voucher_no' },
      { data: 'apply_voucher_amount', name: 'apply_voucher_amount' },
      { data: 'give_points_status', name: 'give_points_status' },
      { data: 'give_points_no', name: 'give_points_no' },
      { data: 'apply_points_status', name: 'apply_points_status' },
      { data: 'apply_points_no', name: 'apply_points_no' },
      { data: 'total_points', name: 'total_points' },
      { data: 'action', name: 'action', orderable: false, searchable: false },
    ],
    drawCallback: function(settings) {
      // Append totals row at the end of the table body
      var api = this.api();
      var json = api.ajax.json() || {};
      var summary = json.summary || {};
      $('#viewOrderTable tbody tr.table-summary').remove();
      var totalsRow = $('<tr class="table-summary bg-light fw-semibold"></tr>');
      function fmt(v) { return (v !== undefined && v !== null) ? v : ''; }
      totalsRow.append('<td>Totals</td>'); // Sr. No.
      totalsRow.append('<td></td>'); // Order No.
      totalsRow.append('<td></td>'); // Order Date
      totalsRow.append('<td></td>'); // Seller
      totalsRow.append('<td></td>'); // Purchase
      totalsRow.append('<td></td>'); // No. of Items
      totalsRow.append('<td></td>'); // Total Qty
      totalsRow.append('<td>'+ fmt(summary.billing_amount) +'</td>');
      totalsRow.append('<td>'+ fmt(summary.discount_amount) +'</td>');
      totalsRow.append('<td>'+ fmt(summary.cashback_amount) +'</td>');
      totalsRow.append('<td></td>'); // Give Coupon Status
      totalsRow.append('<td></td>'); // Give Coupon No.
      totalsRow.append('<td></td>'); // Apply Coupon Status
      totalsRow.append('<td></td>'); // Apply Coupon No.
      totalsRow.append('<td>'+ fmt(summary.apply_coupon_amount) +'</td>');
      totalsRow.append('<td></td>'); // Give Voucher Status
      totalsRow.append('<td></td>'); // Give Voucher No.
      totalsRow.append('<td>'+ fmt(summary.give_voucher_amount) +'</td>');
      totalsRow.append('<td></td>'); // Give Voucher Ex.Date
      totalsRow.append('<td></td>'); // Apply Voucher Status
      totalsRow.append('<td></td>'); // Apply Voucher No.
      totalsRow.append('<td>'+ fmt(summary.apply_voucher_amount) +'</td>');
      totalsRow.append('<td></td>'); // Give Points Status
      totalsRow.append('<td></td>'); // Give Points No.
      totalsRow.append('<td></td>'); // Apply Points Status
      totalsRow.append('<td></td>'); // Apply Points No.
      totalsRow.append('<td>'+ fmt(summary.total_points) +'</td>');
      totalsRow.append('<td></td>'); // Action
      $('#viewOrderTable tbody').append(totalsRow);
    }
  });

  $('#searchBtn').on('click', function() { table.ajax.reload(); });
});
</script>
@endpush
