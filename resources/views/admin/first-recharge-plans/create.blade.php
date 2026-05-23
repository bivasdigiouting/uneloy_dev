@extends('layouts.admin')

@section('title', 'Add First Recharge Plan')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add First Recharge Plan</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">System Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.first-recharge-plans.index') }}">First Recharge Plan Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.first-recharge-plans.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to list
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Plan Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.first-recharge-plans.store') }}" method="POST" id="firstRechargePlanForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="plan_name" class="form-label">Plan Name</label>
                        <input type="text" name="plan_name" id="plan_name" value="{{ old('plan_name') }}" class="form-control" required>
                        @error('plan_name')
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

                    <div class="col-md-4">
                        <label for="plan_value" class="form-label">Plan Value</label>
                        <input type="number" step="0.01" min="0" name="plan_value" id="plan_value" value="{{ old('plan_value', '0') }}" class="form-control" required>
                        @error('plan_value')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="bonus_value" class="form-label">Bonus Value</label>
                        <input type="number" step="0.01" min="0" name="bonus_value" id="bonus_value" value="{{ old('bonus_value', '0') }}" class="form-control" required>
                        @error('bonus_value')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="total_value" class="form-label">Total Value</label>
                        <input type="number" step="0.01" min="0" name="total_value" id="total_value" value="{{ old('total_value', '0') }}" class="form-control" readonly>
                        <div class="form-text">Auto calculated (Plan Value + Bonus Value).</div>
                    </div>

                    <div class="col-md-6">
                        <label for="benefit_amount" class="form-label">Benefit Amount</label>
                        <input type="number" step="0.01" min="0" name="benefit_amount" id="benefit_amount" value="{{ old('benefit_amount', '0') }}" class="form-control" required>
                        @error('benefit_amount')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="benefit_duration_years" class="form-label">Benefit Duration (in years)</label>
                        <input type="number" step="1" min="1" max="100" name="benefit_duration_years" id="benefit_duration_years" value="{{ old('benefit_duration_years', '1') }}" class="form-control" required>
                        @error('benefit_duration_years')
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

@push('scripts')
<script>
    (function () {
        function toNumber(value) {
            var n = parseFloat(value);
            return isNaN(n) ? 0 : n;
        }
        function recalc() {
            var plan = toNumber(document.getElementById('plan_value').value);
            var bonus = toNumber(document.getElementById('bonus_value').value);
            document.getElementById('total_value').value = (plan + bonus).toFixed(2);
        }
        document.getElementById('plan_value').addEventListener('input', recalc);
        document.getElementById('bonus_value').addEventListener('input', recalc);
        recalc();
    })();
</script>
@endpush

