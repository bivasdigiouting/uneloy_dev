@extends('ecard.ecard')
@section('title', 'BD Other Request Details')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">BD Other Request Details</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Blood Group</th>
              <th>Units</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($others as $row)
              <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['blood_group'] }}</td>
                <td>{{ $row['units'] }}</td>
                <td><span class="badge bg-{{ $row['status'] === 'Approved' ? 'success' : 'warning' }}">{{ $row['status'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">No other requests.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection