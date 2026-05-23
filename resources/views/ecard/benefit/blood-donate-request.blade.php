@extends('ecard.ecard')
@section('title', 'Blood Donate Request')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Blood Donate Request</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('ecard.benefit.blooddonate.request.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Blood Group</label>
            <select name="blood_group" class="form-select" required>
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Units</label>
            <input type="number" name="units" min="1" class="form-control" required>
          </div>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection