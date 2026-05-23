@extends('layouts.admin')

@section('title', 'Add Recharge Commission Rule')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Recharge Commission Rule</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Recharge Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.recharge-commissions.index') }}">User Commission</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.recharge-commissions.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to list
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Rule Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.recharge-commissions.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Service</label>
                        <select name="recharge_service_id" class="form-select" required>
                            <option value="" disabled {{ old('recharge_service_id') ? '' : 'selected' }}>Select</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('recharge_service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_name }} ({{ $service->service_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('recharge_service_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Operator</label>
                        <select name="recharge_operator_id" class="form-select">
                            <option value="">All Operators</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}" {{ old('recharge_operator_id') == $operator->id ? 'selected' : '' }}>
                                    {{ $operator->operator_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('recharge_operator_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Department Level</label>
                        <select name="department_level" class="form-select">
                            <option value="">All Levels</option>
                            @foreach($departmentLevels as $key => $label)
                                <option value="{{ $key }}" {{ old('department_level') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_level')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Commission Type</label>
                        <select name="commission_type" class="form-select" required>
                            <option value="percentage" {{ old('commission_type', 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="flat" {{ old('commission_type') === 'flat' ? 'selected' : '' }}>Flat</option>
                        </select>
                        @error('commission_type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Commission Value</label>
                        <input type="number" step="0.01" min="0" name="commission_value" value="{{ old('commission_value') }}" class="form-control" required>
                        @error('commission_value')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Min Amount</label>
                        <input type="number" step="0.01" min="0" name="min_amount" value="{{ old('min_amount') }}" class="form-control">
                        @error('min_amount')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Max Amount</label>
                        <input type="number" step="0.01" min="0" name="max_amount" value="{{ old('max_amount') }}" class="form-control">
                        @error('max_amount')
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

