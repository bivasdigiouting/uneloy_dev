@extends('ecard.ecard')

@section('title', 'A & R Product Stock Report')

@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">A &amp; R Product Stock Report</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label">Member ID</label>
          <input type="text" id="filter_member_id" class="form-control" placeholder="Member ID">
        </div>
        <div class="col-md-4">
          <label class="form-label">Product Name</label>
          <input type="text" id="filter_product_name" class="form-control" placeholder="Product Name">
        </div>
        <div class="col-md-2">
          <label class="form-label">From Date</label>
          <input type="date" id="filter_from" class="form-control">
        </div>
        <div class="col-md-2">
          <label class="form-label">To Date</label>
          <input type="date" id="filter_to" class="form-control">
        </div>
      </div>

      <table class="table table-striped table-bordered w-100" id="arStockTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Member ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const table = $('#arStockTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    order: [[5, 'desc']],
    ajax: {
      url: '{{ route('ecard.product.ar.stock.report.data') }}',
      data: function (d) {
        d.member_id = document.getElementById('filter_member_id').value;
        d.product_name = document.getElementById('filter_product_name').value;
        d.from_date = document.getElementById('filter_from').value;
        d.to_date = document.getElementById('filter_to').value;
      }
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
      { data: 'member_id', name: 'member_id' },
      { data: 'product_name', name: 'product_name' },
      { data: 'quantity', name: 'quantity' },
      { data: 'status', name: 'status' },
      { data: 'created_at', name: 'created_at' },
    ]
  });

  ['filter_member_id','filter_product_name','filter_from','filter_to'].forEach(id => {
    document.getElementById(id).addEventListener('change', () => table.ajax.reload());
  });
});
</script>
@endsection