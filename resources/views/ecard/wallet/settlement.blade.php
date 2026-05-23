@extends('ecard.ecard')

@section('title', 'Bank Settlement Request')

@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">Bank Settlement Request</h4>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('ecard.wallet.settlement.store') }}" novalidate>
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Member ID</label>
            <input type="text" name="member_id" class="form-control" value="{{ old('member_id') }}" placeholder="Enter Member ID" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" min="1" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="Enter amount" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Settlement Mode</label>
            <input type="text" name="settlement_mode" class="form-control" value="{{ old('settlement_mode') }}" placeholder="NEFT / UPI / Cash (optional)">
          </div>
          <div class="col-md-6">
            <label class="form-label">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" placeholder="Bank Name (optional)">
          </div>
          <div class="col-md-6">
            <label class="form-label">Account Number</label>
            <input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}" placeholder="Account Number (optional)">
          </div>
          <div class="col-12">
            <label class="form-label">Remark</label>
            <input type="text" name="remark" class="form-control" value="{{ old('remark') }}" placeholder="Remark (optional)">
          </div>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-danger">Deduct from Wallet</button>
        </div>
      </form>
    </div>
  </div>

  <div class="text-muted mt-3">
    <small>Note: This settlement immediately debits the member's wallet and logs a transaction. Ensure sufficient balance.</small>
  </div>
</div>
@endsection