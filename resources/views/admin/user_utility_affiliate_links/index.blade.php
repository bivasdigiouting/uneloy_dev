@extends('layouts.admin')

@section('title', 'User Utility & Affiliate Link')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">User Utility & Affiliate Link</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Utility & Affiliate Link</li>
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
                    <h4 class="card-title">Add Utility & Affiliate Link</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user-utility-affiliate-links.store') }}" method="POST" id="utilityAffiliateForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Select Type</label>
                                <select name="audience_type" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="User">User</option>
                                    <option value="E-Card Seva">E-Card Seva</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Select State</label>
                                <select name="state_ids[]" id="state_ids" class="form-select select2" multiple required>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Select District</label>
                                <select name="district_id" id="district_id" class="form-select">
                                    <option value="">Select District</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Select City</label>
                                <select name="city_id" id="city_id" class="form-select">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="Utility & Affiliate Link" selected>Utility & Affiliate Link</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Link</label>
                                <textarea name="link" class="form-control" rows="3" placeholder="Paste your link here" required></textarea>
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
                    <h4 class="card-title">Utility & Affiliate Links</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="utilityAffiliateTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Audience Type</th>
                                    <th>States</th>
                                    <th>District</th>
                                    <th>City</th>
                                    <th>Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Link</th>
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
    // Init select2
    if ($.fn.select2) {
        $('.select2').select2({ width: '100%' });
    }

    // Dependent dropdowns
    $('#state_ids').on('change', function(){
        const stateIds = $(this).val() || [];
        $('#district_id').empty().append('<option value="">Select District</option>');
        $('#city_id').empty().append('<option value="">Select City</option>');
        // For each selected state, fetch districts and merge
        const requests = stateIds.map((sid) => $.getJSON(`{{ route('admin.districts.by-state', ['state_id' => 'STATE_ID']) }}`.replace('STATE_ID', sid)));
        $.when.apply($, requests).done(function(){
            const results = Array.from(arguments);
            const districts = new Map();
            results.forEach(function(res){
                const data = (res[0] && res[0].data) ? res[0].data : [];
                data.forEach(function(d){ districts.set(d.id, d.district_name); });
            });
            districts.forEach(function(name, id){
                $('#district_id').append(`<option value="${id}">${name}</option>`);
            });
        });
    });

    $('#district_id').on('change', function(){
        const districtId = $(this).val();
        $('#city_id').empty().append('<option value="">Select City</option>');
        if (!districtId) return;
        $.getJSON(`{{ route('admin.cities.by-district', ['district_id' => 'DISTRICT_ID']) }}`.replace('DISTRICT_ID', districtId))
        .done(function(res){
            const cities = res.data || [];
            cities.forEach(function(c){
                $('#city_id').append(`<option value="${c.id}">${c.city_name}</option>`);
            });
        });
    });

    // DataTable
    const table = $('#utilityAffiliateTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.user-utility-affiliate-links.data') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'audience_type', name: 'audience_type' },
            { data: 'state_names', name: 'state_names', orderable: false, searchable: false },
            { data: 'district_name', name: 'district_name', orderable: false },
            { data: 'city_name', name: 'city_name', orderable: false },
            { data: 'type', name: 'type' },
            { data: 'from_date', name: 'from_date' },
            { data: 'to_date', name: 'to_date' },
            { data: 'link_short', name: 'link', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // Delete handler
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