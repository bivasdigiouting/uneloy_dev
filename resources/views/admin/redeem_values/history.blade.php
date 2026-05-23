@extends('layouts.admin')

@section('content')

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Redeem Value History</h5>
                        <a href="{{ route('admin.redeem-values.index') }}" class="btn btn-sm btn-primary">Back to Add Redeem Value</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Total User Point</th>
                                        <th>Redeem Amount</th>
                                        <th>Redeem Value</th>
                                        <th>Updated By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($histories as $i => $history)
                                        <tr>
                                            <td>{{ $histories->firstItem() + $i }}</td>
                                            <td>{{ $history->created_at ? $history->created_at->format('d M Y, h:i A') : '—' }}</td>
                                            <td>{{ number_format($history->total_user_points, 2) }}</td>
                                            <td>{{ number_format($history->redeem_amount, 2) }}</td>
                                            <td>{{ number_format($history->redeem_value, 2) }}</td>
                                            <td>{{ optional($history->user)->name ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No history found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div>
                            {{ $histories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection