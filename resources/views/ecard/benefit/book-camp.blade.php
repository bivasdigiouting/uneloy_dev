@extends('ecard.ecard')
@section('title', 'Book Camp')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Book Camp</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="row">
    <div class="col-lg-6">
      <div class="card shadow-sm mb-3">
        <div class="card-header">Available Camps</div>
        <div class="card-body">
          <ul class="list-group">
            @forelse($camps as $camp)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $camp['name'] }}</strong>
                  <div class="small text-muted">{{ $camp['date'] }} • {{ $camp['location'] }}</div>
                </div>
                <span class="badge bg-primary">Upcoming</span>
              </li>
            @empty
              <li class="list-group-item text-muted">No upcoming camps.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header">Submit Booking Request</div>
        <div class="card-body">
          <form method="POST" action="{{ route('ecard.benefit.bookcamp.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Camp Name</label>
              <input type="text" name="camp_name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Camp Date</label>
              <input type="date" name="camp_date" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Location</label>
              <input type="text" name="location" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Request</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection