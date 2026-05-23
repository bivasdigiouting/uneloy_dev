@extends('layouts.admin')

@section('title', 'Add Advertisement')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Advertisement</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.advertisements.index') }}">Advertisement Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Advertisement</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-light"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.advertisements.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Advertisement Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (Per day) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="price_per_day" class="form-control" value="{{ old('price_per_day') }}" required>
                        @error('price_per_day')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="statusActive" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusActive">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="statusInactive" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusInactive">Inactive</label>
                        </div>
                        @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
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