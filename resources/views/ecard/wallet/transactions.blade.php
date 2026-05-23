@extends('ecard.ecard')

@section('title', 'Wallet Transaction Details')

@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">Wallet Transaction Details</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label">Member ID</label>
          <input type="text" id="filter_member_id" class="form-control" placeholder="Member ID">
        </div>
        <div class="col-md-3">
          <label class="form-label">Type</label>
          <select id="filter_type" class="form-select">
            <option value="">All</option>
            <option value="add">Add</option>
            <option value="remove">Remove</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">From Date</label>
          <input type="date" id="filter_from" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">To Date</label>
          <input type="date" id="filter_to" class="form-control">
        </div>
      </div>

      <table class="table table-striped table-bordered w-100" id="txnTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Member ID</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Prev Balance</th>
            <th>New Balance</th>
            <th>Narration</th>
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
  const table = $('#txnTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    order: [[7, 'desc']],
    ajax: {
      url: '{{ route('ecard.wallet.transactions.data') }}',
      data: function (d) {
        d.member_id = document.getElementById('filter_member_id').value;
        d.type = document.getElementById('filter_type').value;
        d.from_date = document.getElementById('filter_from').value;
        d.to_date = document.getElementById('filter_to').value;
      }
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
      { data: 'member_id', name: 'member_id' },
      { data: 'transaction_type', name: 'transaction_type' },
      { data: 'amount', name: 'amount' },
      { data: 'previous_balance', name: 'previous_balance' },
      { data: 'new_balance', name: 'new_balance' },
      { data: 'narration', name: 'narration' },
      { data: 'created_at', name: 'created_at' },
    ]
  });

  ['filter_member_id','filter_type','filter_from','filter_to'].forEach(id => {
    document.getElementById(id).addEventListener('change', () => table.ajax.reload());
  });
});
</script>
@endsection