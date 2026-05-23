@extends('ecard.ecard')
@section('title', 'Emergency ECS Request')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Emergency ECS Request</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('ecard.benefit.emergency.ecs.request.store') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Subject</label>
          <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Details</label>
          <textarea name="details" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-danger">Submit Emergency Request</button>
      </form>
    </div>
  </div>
</div>
@endsection