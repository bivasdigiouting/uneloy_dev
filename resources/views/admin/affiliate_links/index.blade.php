@extends('layouts.admin')

@section('title', 'Affiliate Link Creation')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Affiliate Link Creation</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Affiliate & Banner Module</li>
                    <li class="breadcrumb-item active">Affiliate Link Creation</li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Affiliate Link</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.affiliate-links.store') }}" method="POST" id="affiliateLinkForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Affiliate Service</label>
                                <select name="affiliate_id" class="form-select" required>
                                    <option value="">Select</option>
                                    @foreach($affiliates as $affiliate)
                                        <option value="{{ $affiliate->id }}">{{ $affiliate->service_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Link Name</label>
                                <input type="text" name="link_name" class="form-control" placeholder="Enter link name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Destination URL</label>
                                <input type="url" name="destination_url" class="form-control" placeholder="https://..." required>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Affiliate Links</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="affiliateLinksTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service</th>
                                    <th>Link Name</th>
                                    <th>Tracking Link</th>
                                    <th>Destination</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Clicks</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    const table = $('#affiliateLinksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.affiliate-links.data') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'service_name', name: 'affiliate.service_name', orderable: false },
            { data: 'link_name', name: 'link_name' },
            { data: 'tracking_link', name: 'code', orderable: false, searchable: false },
            { data: 'destination_link', name: 'destination_url', orderable: false, searchable: false },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'clicks_count', name: 'clicks_count' },
            { data: 'active_status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    $(document).on('click', '[data-delete]', function(){
        const url = $(this).data('delete');
        if (!confirm('Delete this record?')) return;
        $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' }
        }).done(function(){ table.ajax.reload(); });
    });
});
</script>
@endpush
