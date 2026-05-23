@extends('ecard.ecard')
@section('title', 'Advertisement Report')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Advertisement Report</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Title</th>
              <th>Impressions</th>
              <th>Clicks</th>
              <th>CTR</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($report as $row)
              <tr>
                <td>{{ $row['title'] }}</td>
                <td>{{ $row['impressions'] }}</td>
                <td>{{ $row['clicks'] }}</td>
                <td>{{ $row['ctr'] }}</td>
                <td><span class="badge bg-{{ $row['status'] === 'Published' ? 'success' : 'secondary' }}">{{ $row['status'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted">No report data available.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection