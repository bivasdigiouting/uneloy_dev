@extends('ecard.ecard')
@section('title', 'E-Card Seva Request')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">E-Card Seva Request</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('ecard.benefit.ecardseva.request.store') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Request Type</label>
          <select name="request_type" class="form-select" required>
            <option value="card-print">Card Print</option>
            <option value="address-update">Address Update</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Description (optional)</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
    </div>
  </div>
</div>
@endsection