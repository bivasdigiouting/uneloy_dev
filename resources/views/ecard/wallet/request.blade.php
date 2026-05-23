@extends('ecard.ecard')

@section('title', 'Wallet Fund Request')

@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">Wallet Fund Request</h4>

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
      <form method="POST" action="{{ route('ecard.wallet.request.store') }}" novalidate>
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" min="1" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="Enter amount" required>
          </div>
          <div class="col-md-8">
            <label class="form-label">Remark</label>
            <input type="text" name="remark" class="form-control" value="{{ old('remark') }}" placeholder="Remark (optional)">
          </div>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>

  <div class="text-muted mt-3">
    <small>Note: This request will be reviewed by superadmin.</small>
  </div>

  <div class="card shadow-sm mt-3">
    <div class="card-header">
      <h6 class="mb-0">My Requests</h6>
    </div>
    <div class="card-body">
      @if(($requests ?? collect())->isEmpty())
        <div class="text-muted">No wallet fund requests found.</div>
      @else
        <div class="table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Remark</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($requests as $i => $req)
                @php
                  $status = strtolower((string) ($req->status ?? 'pending'));
                  $badge = $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning');
                @endphp
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>₹{{ number_format((float) ($req->amount ?? 0), 2) }}</td>
                  <td><span class="badge bg-{{ $badge }}">{{ ucfirst($status) }}</span></td>
                  <td>{{ $req->remark ?? '—' }}</td>
                  <td>{{ $req->created_at ? $req->created_at->format('d M Y, h:i A') : '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
