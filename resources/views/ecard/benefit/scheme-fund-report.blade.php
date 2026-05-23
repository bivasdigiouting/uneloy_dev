@extends('ecard.ecard')
@section('title', 'Global Disbursement Fund Report')
@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Global Disbursement Fund Report</h4>
    <span class="text-muted">User: {{ $user->name ?? 'N/A' }}</span>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Date</th>
              <th>Scheme</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($report as $row)
              <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['scheme'] }}</td>
                <td>₹ {{ number_format($row['amount'], 2) }}</td>
                <td><span class="badge bg-{{ $row['status'] === 'Approved' ? 'success' : ($row['status'] === 'Pending' ? 'warning' : 'secondary') }}">{{ $row['status'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection