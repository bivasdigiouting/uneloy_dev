@extends('layouts.admin')

@section('title', 'Edit Village/Town')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Village/Town</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.villages.index') }}">Village/Town Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.villages.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Village/Town Master
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col"><h4 class="card-title mb-0">Village/Town Information</h4></div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.villages.update', $village->id) }}" method="POST" id="villageForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">State Name <span class="text-danger">*</span></label>
                                    <select class="form-select" id="state_id" name="state_id" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ (old('state_id', $village->state_id) == $state->id) ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">District Name <span class="text-danger">*</span></label>
                                    <select class="form-select" id="district_id" name="district_id" required>
                                        <option value="">Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ (old('district_id', $village->district_id) == $district->id) ? 'selected' : '' }}>{{ $district->district_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">City Name <span class="text-danger">*</span></label>
                                    <select class="form-select" id="city_id" name="city_id" required>
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ (old('city_id', $village->city_id) == $city->id) ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="village_name" class="form-label">Village/Town Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="village_name" name="village_name" value="{{ old('village_name', $village->village_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status_active" value="active" {{ old('status', $village->status) == 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" {{ old('status', $village->status) == 'inactive' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_inactive">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Update Village/Town</button>
                            <a href="{{ route('admin.villages.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.select2-container{width:100% !important}
.select2-container .select2-selection--single{height:calc(2.5rem + 2px);padding:.375rem .75rem}
.select2-container .select2-selection__rendered{line-height:2.5rem}
.select2-container .select2-selection__arrow{height:2.5rem}
</style>
@endpush

@push('scripts')
<script>
$(function(){
    $('#state_id, #district_id, #city_id').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#villageForm') });

    $('#state_id').on('change select2:select', function(){
        var stateId = $(this).val();
        var districtSelect = $('#district_id');
        var citySelect = $('#city_id');
        districtSelect.empty().append('<option value="">Select District</option>').prop('disabled', true).val('').trigger('change');
        citySelect.empty().append('<option value="">Select City</option>').prop('disabled', true).val('').trigger('change');
        if(stateId){
            districtSelect.prop('disabled', false).append('<option value="">Loading...</option>').trigger('change');
            $.get("{{ route('admin.districts.by-state', ':id') }}".replace(':id', stateId), function(resp){
                districtSelect.empty().append('<option value="">Select District</option>');
                var list = Array.isArray(resp) ? resp : (resp.data || resp.districts || []);
                list.forEach(function(d){ districtSelect.append('<option value="'+(d.id||d.value)+'">'+(d.district_name||d.name||d.text)+'</option>'); });
                districtSelect.val('').trigger('change');
            });
        }
    });

    $('#district_id').on('change select2:select', function(){
        var districtId = $(this).val();
        var citySelect = $('#city_id');
        citySelect.empty().append('<option value="">Select City</option>').prop('disabled', true).val('').trigger('change');
        if(districtId){
            citySelect.prop('disabled', false).append('<option value="">Loading...</option>').trigger('change');
            $.get("{{ route('admin.cities.by-district', ':id') }}".replace(':id', districtId), function(resp){
                citySelect.empty().append('<option value="">Select City</option>');
                var list = Array.isArray(resp) ? resp : (resp.data || []);
                list.forEach(function(c){ citySelect.append('<option value="'+(c.id||c.value)+'">'+(c.city_name||c.name||c.text)+'</option>'); });
                citySelect.val('').trigger('change');
            });
        }
    });
});
</script>
@endpush
