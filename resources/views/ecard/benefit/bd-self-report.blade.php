@extends('ecard.ecard')
@section('title', 'BD Self Request Report')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">BD Self Request Report</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Date</th>
              <th>Units</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($report as $row)
              <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['units'] }}</td>
                <td><span class="badge bg-{{ $row['status'] === 'Completed' ? 'success' : 'secondary' }}">{{ $row['status'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">No self requests.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection