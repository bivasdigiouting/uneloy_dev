@extends('layouts.admin')

@section('title', 'Edit Ward')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Ward</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Master Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.wards.index') }}">Ward Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.wards.update', $row->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select id="state_id" name="state_id" class="form-select" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ $row->state_id == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                @error('state_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select id="district_id" name="district_id" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}" {{ $row->district_id == $d->id ? 'selected' : '' }}>{{ $d->district_name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <select id="city_id" name="city_id" class="form-select" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->id }}" {{ $row->city_id == $c->id ? 'selected' : '' }}>{{ $c->city_name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Municipality <span class="text-danger">*</span></label>
                                <select id="municipality_id" name="municipality_id" class="form-select" required>
                                    <option value="">Select Municipality</option>
                                    @foreach($municipalities as $m)
                                        <option value="{{ $m->id }}" {{ $row->municipality_id == $m->id ? 'selected' : '' }}>{{ $m->municipality_name }}</option>
                                    @endforeach
                                </select>
                                @error('municipality_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ward No <span class="text-danger">*</span></label>
                                <input type="text" name="ward_no" class="form-control" value="{{ old('ward_no', $row->ward_no) }}" required>
                                @error('ward_no')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $row->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $row->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit"><i class="ti ti-device-floppy"></i> Update</button>
                            <a href="{{ route('admin.wards.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    const districtUrl = "{{ route('admin.districts.by-state', ['state_id' => ':sid']) }}";
    const cityUrl = "{{ route('admin.cities.by-district', ['district_id' => ':did']) }}";
    const municipalityUrl = "{{ route('admin.municipalities.by-city', ['city_id' => ':cid']) }}";
    $('#state_id').on('change', function(){
        const sid = $(this).val();
        $('#district_id').empty().append('<option value="">Select District</option>');
        $('#city_id').empty().append('<option value="">Select City</option>');
        $('#municipality_id').empty().append('<option value="">Select Municipality</option>');
        if(!sid) return;
        $.getJSON(districtUrl.replace(':sid', sid)).done(function(res){
            const list = res.data || res.districts || [];
            list.forEach(function(d){ $('#district_id').append(`<option value="${d.id}">${d.district_name}</option>`); });
        });
    });
    $('#district_id').on('change', function(){
        const did = $(this).val();
        $('#city_id').empty().append('<option value="">Select City</option>');
        $('#municipality_id').empty().append('<option value="">Select Municipality</option>');
        if(!did) return;
        $.getJSON(cityUrl.replace(':did', did)).done(function(res){
            const list = res.data || res.cities || [];
            list.forEach(function(c){ $('#city_id').append(`<option value="${c.id}">${c.city_name}</option>`); });
        });
    });
    $('#city_id').on('change', function(){
        const cid = $(this).val();
        $('#municipality_id').empty().append('<option value="">Select Municipality</option>');
        if(!cid) return;
        $.getJSON(municipalityUrl.replace(':cid', cid)).done(function(res){
            const list = res.data || [];
            list.forEach(function(m){ $('#municipality_id').append(`<option value="${m.id}">${m.municipality_name}</option>`); });
        });
    });
});
</script>
@endpush
