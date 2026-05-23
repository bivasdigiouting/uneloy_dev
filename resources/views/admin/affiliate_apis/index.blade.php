@extends('layouts.admin')

@section('title', 'Affiliate API')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Affiliate API</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Affiliate & Banner Module</li>
                    <li class="breadcrumb-item active">Affiliate API</li>
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
                    <h4 class="card-title">Create Affiliate API</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.affiliate-apis.store') }}" method="POST" id="affiliateApiForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Affiliate Name</label>
                                <select name="affiliate_id" class="form-select" required>
                                    <option value="">Select</option>
                                    @foreach($affiliates as $affiliate)
                                        <option value="{{ $affiliate->id }}">{{ $affiliate->service_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Service</label>
                                <input type="text" name="service" class="form-control" placeholder="Enter service" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Affiliate API Name</label>
                                <input type="text" name="api_name" class="form-control" placeholder="Enter API name" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">URL</label>
                                <input type="url" name="api_url" class="form-control" placeholder="https://..." required>
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
                    <h4 class="card-title">Affiliate APIs</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="affiliateApisTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Affiliate</th>
                                    <th>Service</th>
                                    <th>Name</th>
                                    <th>API Name</th>
                                    <th>URL</th>
                                    <th>Created At</th>
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
    const table = $('#affiliateApisTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.affiliate-apis.data') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'affiliate_name', name: 'affiliate.service_name', orderable: false },
            { data: 'service', name: 'service' },
            { data: 'name', name: 'name' },
            { data: 'api_name', name: 'api_name' },
            { data: 'api_url', name: 'api_url', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
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

