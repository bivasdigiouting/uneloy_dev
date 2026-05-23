@extends('layouts.admin')

@section('title', 'Add Village/Town')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create New Village/Town</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.villages.index') }}">Village/Town Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
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
                        <div class="col-auto"><a href="{{ route('admin.villages.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i> Back to List</a></div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.villages.store') }}" method="POST" id="villageForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                
                                    <label for="state_id" class="form-label">State Name <span class="text-danger">*</span></label>
                                    <select class="form-control @error('state_id') is-invalid @enderror" id="state_id" name="state_id" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">District Name <span class="text-danger">*</span></label>
                                    <select class="form-control @error('district_id') is-invalid @enderror" id="district_id" name="district_id" required disabled>
                                        <option value="">Select District</option>
                                    </select>
                                    @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">City Name <span class="text-danger">*</span></label>
                                    <select class="form-control @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required disabled>
                                        <option value="">Select City</option>
                                    </select>
                                    @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="village_name" class="form-label">Village/Town Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('village_name') is-invalid @enderror" id="village_name" name="village_name" value="{{ old('village_name') }}" placeholder="Enter village/town name" required>
                                    @error('village_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status_active" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_inactive">Inactive</label>
                                        </div>
                                    </div>
                                    @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Save Village/Town</button>
                            <a href="{{ route('admin.villages.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col"><h4 class="card-title mb-0">Bulk Add Villages/Towns</h4></div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.villages.bulk-store') }}" method="POST" id="bulkVillageForm">
                        @csrf
                        <input type="hidden" name="state_id" id="bulk_state_id">
                        <input type="hidden" name="district_id" id="bulk_district_id">
                        <input type="hidden" name="city_id" id="bulk_city_id">
                        <div class="mb-3">
                            <label for="bulk_names" class="form-label">Village/Town Names (one per line) <span class="text-danger">*</span></label>
                            <textarea id="bulk_names" name="names" class="form-control" rows="6" placeholder="Enter one name per line" required></textarea>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary"><i class="ti ti-upload me-1"></i> Add All</button>
                            <span class="text-muted ms-2">Uses selected State, District and City above.</span>
                        </div>
                    </form>
                    <hr class="my-4" />
                    <form action="{{ route('admin.villages.import-csv') }}" method="POST" enctype="multipart/form-data" id="csvVillageForm">
                        @csrf
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Import CSV of Villages/Towns <span class="text-danger">*</span></label>
                            <input type="file" id="csv_file" name="csv_file" class="form-control" accept=".csv" required />
                            <div class="form-text">Columns: state, district, city, village, status(optional). IDs supported via state_id, district_id, city_id.</div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-success"><i class="ti ti-file-import me-1"></i> Import CSV</button>
                            <span class="text-muted ms-2">Automatically maps or creates State, District and City.</span>
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
$(function() {
    $('#state_id, #district_id, #city_id').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#villageForm') });

    $('#state_id').on('change select2:select', function() {
        var stateId = $(this).val();
        var districtSelect = $('#district_id');
        var citySelect = $('#city_id');
        districtSelect.empty().append('<option value="">Select District</option>').prop('disabled', true).val('').trigger('change');
        citySelect.empty().append('<option value="">Select City</option>').prop('disabled', true).val('').trigger('change');
        if (stateId) {
            districtSelect.prop('disabled', false).append('<option value="">Loading...</option>').trigger('change');
            var url = "{{ route('admin.districts.by-state', ':id') }}".replace(':id', stateId);
            $.get(url, function(resp) {
                districtSelect.empty().append('<option value="">Select District</option>');
                var list = Array.isArray(resp) ? resp : (resp.data || resp.districts || []);
                list.forEach(function(d){
                    districtSelect.append('<option value="'+ (d.id || d.value) +'">'+ (d.district_name || d.name || d.text) +'</option>');
                });
                districtSelect.val('').trigger('change');
            }).fail(function(){
                districtSelect.empty().append('<option value="">Error loading districts</option>');
            });
        }
    });

    $('#district_id').on('change select2:select', function() {
        var districtId = $(this).val();
        var citySelect = $('#city_id');
        citySelect.empty().append('<option value="">Select City</option>').prop('disabled', true).val('').trigger('change');
        if (districtId) {
            citySelect.prop('disabled', false).append('<option value="">Loading...</option>').trigger('change');
            var url = "{{ route('admin.cities.by-district', ':id') }}".replace(':id', districtId);
            $.get(url, function(resp) {
                citySelect.empty().append('<option value="">Select City</option>');
                var list = Array.isArray(resp) ? resp : (resp.data || []);
                list.forEach(function(c){
                    citySelect.append('<option value="'+ (c.id || c.value) +'">'+ (c.city_name || c.name || c.text) +'</option>');
                });
                citySelect.val('').trigger('change');
            }).fail(function(){
                citySelect.empty().append('<option value="">Error loading cities</option>');
            });
        }
    });

    $('#villageForm').on('submit', function(e) {
        var ok = true;
        $(this).find('[required]').each(function(){ if(!$(this).val()) { ok = false; $(this).addClass('is-invalid'); } else { $(this).removeClass('is-invalid'); } });
        if (!ok) { e.preventDefault(); alert('Please fill all required fields.'); }
    });

    $('#bulkVillageForm').on('submit', function(e){
        var s = $('#state_id').val();
        var d = $('#district_id').val();
        var c = $('#city_id').val();
        var names = $('#bulk_names').val().trim();
        if(!s || !d || !c){ e.preventDefault(); alert('Select State, District and City first.'); return; }
        if(names === ''){ e.preventDefault(); alert('Enter at least one village/town name.'); return; }
        $('#bulk_state_id').val(s);
        $('#bulk_district_id').val(d);
        $('#bulk_city_id').val(c);
    });

    $('#csvVillageForm').on('submit', function(e){
        var f = $('#csv_file')[0];
        if(!f || !f.files || !f.files.length){ e.preventDefault(); alert('Select a CSV file.'); return; }
        var name = f.files[0].name.toLowerCase();
        if(!name.endsWith('.csv')){ e.preventDefault(); alert('Only .csv files are allowed.'); return; }
    });
});
</script>
@endpush
