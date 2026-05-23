@extends('layouts.admin')

@section('title', 'Edit Recharge Operator')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Recharge Operator</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Recharge Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.recharge-operators.index') }}">Recharge Operator</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.recharge-operators.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to list
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Operator Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.recharge-operators.update', $operator->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="recharge_service_id" class="form-label">Recharge Service</label>
                        <select name="recharge_service_id" id="recharge_service_id" class="form-select" required>
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('recharge_service_id', $operator->recharge_service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_name }} ({{ $service->service_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('recharge_service_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="operator_name" class="form-label">Operator Name</label>
                        <input type="text" name="operator_name" id="operator_name" value="{{ old('operator_name', $operator->operator_name) }}" class="form-control" required>
                        @error('operator_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="operator_code" class="form-label">Operator Code</label>
                        <input type="text" name="operator_code" id="operator_code" value="{{ old('operator_code', $operator->operator_code) }}" class="form-control" required>
                        <div class="form-text">Use alpha-numeric with dashes/underscores (e.g., airtel_dth).</div>
                        @error('operator_code')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="operator_logo" class="form-label">Operator Logo</label>
                        @if($operator->operator_logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $operator->operator_logo) }}" alt="Operator Logo" class="img-thumbnail" style="height: 50px;">
                            </div>
                        @endif
                        <input type="file" name="operator_logo" id="operator_logo" class="form-control" accept="image/*">
                        <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</div>
                        @error('operator_logo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $operator->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $operator->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection