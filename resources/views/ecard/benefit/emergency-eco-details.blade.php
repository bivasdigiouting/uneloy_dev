@extends('ecard.ecard')
@section('title', 'Emergency ECO Request Details')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Emergency ECO Request Details</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <ul class="list-group">
        @forelse($contacts as $contact)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong>{{ $contact['name'] }}</strong>
              <div class="small text-muted">Coordinator</div>
            </div>
            <span>{{ $contact['phone'] }}</span>
          </li>
        @empty
          <li class="list-group-item text-muted">No emergency contacts available.</li>
        @endforelse
      </ul>
    </div>
  </div>
</div>
@endsection