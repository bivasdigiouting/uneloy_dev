@extends('ecard.ecard')
@section('title', 'Advertisement')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Advertisement</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="row">
    <div class="col-lg-6">
      <div class="card shadow-sm mb-3">
        <div class="card-header">Create Advertisement</div>
        <div class="card-body">
          <form method="POST" action="{{ route('ecard.advertisement.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-control" required maxlength="150">
            </div>
            <div class="mb-3">
              <label class="form-label">Content</label>
              <textarea name="content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">End Date (optional)</label>
                <input type="date" name="end_date" class="form-control">
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="Draft">Draft</option>
                <option value="Published">Published</option>
              </select>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary">Save Advertisement</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header">Recent Advertisements</div>
        <div class="card-body">
          <ul class="list-group">
            @forelse($ads as $ad)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $ad['title'] }}</strong>
                  <div class="small text-muted">{{ $ad['date'] }}</div>
                </div>
                <span class="badge bg-{{ $ad['status'] === 'Published' ? 'success' : 'secondary' }}">{{ $ad['status'] }}</span>
              </li>
            @empty
              <li class="list-group-item text-muted">No advertisements yet.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection