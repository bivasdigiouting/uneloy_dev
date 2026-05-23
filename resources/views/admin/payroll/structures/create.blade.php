@extends('layouts.admin')

@section('title', 'Create Salary Structure')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-0">Create Payroll Structure</h1>
                    <small class="text-muted">Define earnings and deductions for a department</small>
                </div>
                <div class="col-sm-5 text-end">
                    <a href="{{ route('admin.payroll.structures.index') }}" class="btn btn-secondary">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.payroll.structures.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">Department <span class="text-danger">*</span></label>
                                    <select name="department_id" class="form-control select2" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">Title <small class="text-muted">(optional)</small></label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Standard Structure">
                                    @error('title')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="mb-3">Earnings</h5>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Basic <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="basic" class="form-control" value="{{ old('basic', 0) }}" required>
                                            @error('basic')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">HRA</label>
                                            <input type="number" step="0.01" name="hra" class="form-control" value="{{ old('hra', 0) }}">
                                            @error('hra')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">DA</label>
                                            <input type="number" step="0.01" name="da" class="form-control" value="{{ old('da', 0) }}">
                                            @error('da')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">TA</label>
                                            <input type="number" step="0.01" name="ta" class="form-control" value="{{ old('ta', 0) }}">
                                            @error('ta')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Medical</label>
                                            <input type="number" step="0.01" name="medical" class="form-control" value="{{ old('medical', 0) }}">
                                            @error('medical')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Special Allowance</label>
                                            <input type="number" step="0.01" name="special_allowance" class="form-control" value="{{ old('special_allowance', 0) }}">
                                            @error('special_allowance')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Bonus</label>
                                            <input type="number" step="0.01" name="bonus" class="form-control" value="{{ old('bonus', 0) }}">
                                            @error('bonus')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">EIC</label>
                                            <input type="number" step="0.01" name="eic" class="form-control" value="{{ old('eic', 0) }}">
                                            @error('eic')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="mb-3">Deductions</h5>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">PF</label>
                                            <input type="number" step="0.01" name="pf" class="form-control" value="{{ old('pf', 0) }}">
                                            @error('pf')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Loan</label>
                                            <input type="number" step="0.01" name="loan" class="form-control" value="{{ old('loan', 0) }}">
                                            @error('loan')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">ESIC</label>
                                            <input type="number" step="0.01" name="esic" class="form-control" value="{{ old('esic', 0) }}">
                                            @error('esic')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <strong>Totals:</strong>
                            <span id="grossTotal">Gross: 0.00</span> |
                            <span id="deductionTotal">Deductions: 0.00</span> |
                            <span id="netTotal">Net: 0.00</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div class="form-group form-check m-0">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <button type="submit" class="btn btn-success">
                                Save Structure
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function calcTotals() {
    const earnNames = ['basic','hra','da','ta','medical','special_allowance','bonus','eic'];
    const dedNames = ['pf','loan','esic'];
    let gross = 0, ded = 0;

    earnNames.forEach(n => {
        const el = document.querySelector(`[name="${n}"]`);
        gross += parseFloat(el && el.value ? el.value : 0);
    });

    dedNames.forEach(n => {
        const el = document.querySelector(`[name="${n}"]`);
        ded += parseFloat(el && el.value ? el.value : 0);
    });

    const net = gross - ded;

    document.getElementById('grossTotal').innerText = 'Gross: ' + gross.toFixed(2);
    document.getElementById('deductionTotal').innerText = 'Deductions: ' + ded.toFixed(2);
    document.getElementById('netTotal').innerText = 'Net: ' + net.toFixed(2);
}

['basic','hra','da','ta','medical','special_allowance','bonus','eic','pf','loan','esic']
    .forEach(n => {
        const el = document.querySelector(`[name="${n}"]`);
        if (el) el.addEventListener('input', calcTotals);
    });

window.addEventListener('load', calcTotals);
</script>

@endsection

