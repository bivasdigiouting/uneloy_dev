@extends('ecard.ecard')

@section('title', 'Stock Report')

@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">Stock Report</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">Product Name</label>
          <input type="text" id="filter_product_name" class="form-control" placeholder="Product Name">
        </div>
      </div>

      <table class="table table-striped table-bordered w-100" id="stockTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Total Quantity</th>
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
  const table = $('#stockTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    order: [[1, 'asc']],
    ajax: {
      url: '{{ route('ecard.product.stock.report.data') }}',
      data: function (d) {
        d.product_name = document.getElementById('filter_product_name').value;
      }
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
      { data: 'product_name', name: 'product_name' },
      { data: 'total_quantity', name: 'total_quantity' },
    ]
  });

  document.getElementById('filter_product_name').addEventListener('change', () => table.ajax.reload());
});
</script>
@endsection