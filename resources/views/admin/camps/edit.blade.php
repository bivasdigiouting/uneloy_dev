@extends('layouts.admin')

@section('title', 'Edit Camp')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Camp</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.camps.index') }}">Camp Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Camp</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.camps.index') }}" class="btn btn-light"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.camps.update', $camp->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Camp Name <span class="text-danger">*</span></label>
                        <input type="text" name="camp_name" class="form-control" value="{{ old('camp_name', $camp->camp_name) }}" required>
                        @error('camp_name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $camp->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $camp->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Icon</label>
                        <input type="file" name="icon" class="form-control" accept="image/*" id="iconInput">
                        <div class="mt-2">
                            <img id="iconPreview" src="{{ $camp->icon_url }}" alt="Preview" style="{{ $camp->icon_url ? '' : 'display:none;' }}width:48px;height:48px" class="img-thumbnail">
                        </div>
                        @error('icon')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i> Update</button>
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
</script>
@endpush