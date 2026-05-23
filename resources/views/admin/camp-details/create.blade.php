@extends('layouts.admin')

@section('title', 'Add Camp Detail')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Camp Detail</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Benefit Modules</li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.camp-details.index') }}">Camp Details</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.camp-details.index') }}" class="btn btn-light">
                <i class="ti ti-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.camp-details.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Select Camp</label>
                        <select name="camp_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($camps as $camp)
                                <option value="{{ $camp->id }}" {{ old('camp_id') == $camp->id ? 'selected' : '' }}>{{ $camp->camp_name }}</option>
                            @endforeach
                        </select>
                        @error('camp_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Select State</label>
                        <select id="state_id" name="state_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                        @error('state_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Select District</label>
                        <select id="district_id" name="district_id" class="form-select" required>
                            <option value="">-- Select --</option>
                        </select>
                        @error('district_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Select City</label>
                        <select id="city_id" name="city_id" class="form-select" required>
                            <option value="">-- Select --</option>
                        </select>
                        @error('city_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required />
                        @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}" min="0" />
                        @error('capacity')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ old('from_date') }}" required />
                        @error('from_date')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ old('to_date') }}" required />
                        @error('to_date')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Upload Banner</label>
                        <input type="file" name="banner" id="banner" accept="image/*" class="form-control" />
                        <div class="mt-2">
                            <img id="bannerPreview" src="" alt="Banner Preview" style="display:none;height:60px;width:120px;object-fit:cover;border:1px solid #ddd;" />
                        </div>
                        @error('banner')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" rows="4" class="form-control">{{ old('short_description') }}</textarea>
                        @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('admin.camp-details.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('banner').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('bannerPreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            preview.src = evt.target.result;
            preview.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});

// Dependent dropdowns
$('#state_id').on('change', function() {
    const stateId = $(this).val();
    $('#district_id').html('<option value="">-- Select --</option>');
    $('#city_id').html('<option value="">-- Select --</option>');
    if (!stateId) return;
    $.get(`{{ route('admin.cities.get-districts-by-state') }}?state_id=${stateId}`, function(res) {
        const opts = ['<option value="">-- Select --</option>'];
        (res.data || res).forEach(d => opts.push(`<option value="${d.id}">${d.district_name}</option>`));
        $('#district_id').html(opts.join(''));
    });
});

$('#district_id').on('change', function() {
    const districtId = $(this).val();
    $('#city_id').html('<option value="">-- Select --</option>');
    if (!districtId) return;
    $.get(`{{ url('admin/cities/by-district') }}/${districtId}`, function(res) {
        const opts = ['<option value="">-- Select --</option>'];
        (res.data || res).forEach(c => opts.push(`<option value="${c.id}">${c.city_name}</option>`));
        $('#city_id').html(opts.join(''));
    });
});
</script>
@endpush