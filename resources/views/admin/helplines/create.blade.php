@extends('layouts.admin')

@section('title', 'Add Helpline')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Helpline</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.helplines.index') }}">Helpline Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Helpline</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.helplines.index') }}" class="btn btn-light"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.helplines.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Helpline Name <span class="text-danger">*</span></label>
                        <input type="text" name="helpline_name" class="form-control" value="{{ old('helpline_name') }}" required>
                        @error('helpline_name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Helpline Number <span class="text-danger">*</span></label>
                        <input type="text" name="helpline_number" class="form-control" value="{{ old('helpline_number') }}" required>
                        @error('helpline_number')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <select name="state_id" id="stateSelect" class="form-select" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" @selected(old('state_id') == $state->id)>{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                        @error('state_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <select name="district_id" id="districtSelect" class="form-select" required disabled>
                            <option value="">Select District</option>
                        </select>
                        @error('district_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <select name="city_id" id="citySelect" class="form-select" required disabled>
                            <option value="">Select City</option>
                        </select>
                        @error('city_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Icon</label>
                        <input type="file" name="icon" class="form-control" accept="image/*" id="iconInput">
                        <div class="mt-2">
                            <img id="iconPreview" src="" alt="Preview" style="display:none;width:48px;height:48px" class="img-thumbnail">
                        </div>
                        @error('icon')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('iconInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (ev) {
        const img = document.getElementById('iconPreview');
        img.src = ev.target.result;
        img.style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
});

const stateSelect = document.getElementById('stateSelect');
const districtSelect = document.getElementById('districtSelect');
const citySelect = document.getElementById('citySelect');

stateSelect.addEventListener('change', function () {
    const stateId = this.value;
    districtSelect.innerHTML = '<option value="">Select District</option>';
    citySelect.innerHTML = '<option value="">Select City</option>';
    citySelect.disabled = true;
    if (!stateId) {
        districtSelect.disabled = true;
        return;
    }
    districtSelect.disabled = false;
    fetch(`{{ route('admin.cities.get-districts-by-state') }}?state_id=${stateId}`)
        .then(resp => resp.json())
        .then(data => {
            districtSelect.innerHTML = '<option value="">Select District</option>';
            (data || []).forEach(function (d) {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.district_name || d.name || d.text || '';
                districtSelect.appendChild(opt);
            });
        });
});

districtSelect.addEventListener('change', function () {
    const districtId = this.value;
    citySelect.innerHTML = '<option value="">Select City</option>';
    if (!districtId) {
        citySelect.disabled = true;
        return;
    }
    citySelect.disabled = false;
    fetch(`{{ route('admin.cities.by-district', ':id') }}`.replace(':id', districtId))
        .then(resp => resp.json())
        .then(data => {
            citySelect.innerHTML = '<option value="">Select City</option>';
            (data || []).forEach(function (c) {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.city_name || c.name || c.text || '';
                citySelect.appendChild(opt);
            });
        });
});
</script>
@endpush