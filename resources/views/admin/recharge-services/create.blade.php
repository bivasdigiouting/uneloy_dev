@extends('layouts.admin')

@section('title', 'Add Recharge Service')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Recharge Service</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Recharge Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.recharge-services.index') }}">Recharge Service</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.recharge-services.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to list
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Service Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.recharge-services.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="service_name" class="form-label">Service Name</label>
                        <input type="text" name="service_name" id="service_name" value="{{ old('service_name') }}" class="form-control" required>
                        @error('service_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="service_code" class="form-label">Service Code</label>
                        <input type="text" name="service_code" id="service_code" value="{{ old('service_code') }}" class="form-control" required>
                        <div class="form-text">Use alpha-numeric with dashes/underscores (e.g., dth_recharge).</div>
                        @error('service_code')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection