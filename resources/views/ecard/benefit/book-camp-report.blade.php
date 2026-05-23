@extends('ecard.ecard')
@section('title', 'Book Camp Report')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Book Camp Report</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Camp</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($report as $row)
              <tr>
                <td>{{ $row['camp'] }}</td>
                <td>{{ $row['date'] }}</td>
                <td><span class="badge bg-{{ $row['status'] === 'Approved' ? 'success' : ($row['status'] === 'Pending' ? 'warning' : 'secondary') }}">{{ $row['status'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">No report entries.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection