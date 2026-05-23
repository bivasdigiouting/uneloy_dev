@extends('layouts.admin')

@section('title', 'Edit Salary Structure')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Payroll Structure</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.payroll.structures.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.payroll.structures.update', $structure) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Department</label>
                                <select name="department_id" class="form-control select2" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ (int)$structure->department_id === (int)$dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Title (optional)</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $structure->title) }}">
                                @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Earnings</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>Basic</label><input type="number" step="0.01" name="basic" class="form-control" value="{{ old('basic', $structure->basic) }}" required></div>
                                    <div class="form-group col-md-6"><label>HRA</label><input type="number" step="0.01" name="hra" class="form-control" value="{{ old('hra', $structure->hra) }}"></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>DA</label><input type="number" step="0.01" name="da" class="form-control" value="{{ old('da', $structure->da) }}"></div>
                                    <div class="form-group col-md-6"><label>TA</label><input type="number" step="0.01" name="ta" class="form-control" value="{{ old('ta', $structure->ta) }}"></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>Medical</label><input type="number" step="0.01" name="medical" class="form-control" value="{{ old('medical', $structure->medical) }}"></div>
                                    <div class="form-group col-md-6"><label>Special Allowance</label><input type="number" step="0.01" name="special_allowance" class="form-control" value="{{ old('special_allowance', $structure->special_allowance) }}"></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>Bonus</label><input type="number" step="0.01" name="bonus" class="form-control" value="{{ old('bonus', $structure->bonus) }}"></div>
                                    <div class="form-group col-md-6"><label>EIC</label><input type="number" step="0.01" name="eic" class="form-control" value="{{ old('eic', $structure->eic) }}"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Deductions</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>PF</label><input type="number" step="0.01" name="pf" class="form-control" value="{{ old('pf', $structure->pf) }}"></div>
                                    <div class="form-group col-md-6"><label>Loan</label><input type="number" step="0.01" name="loan" class="form-control" value="{{ old('loan', $structure->loan) }}"></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>ESIC</label><input type="number" step="0.01" name="esic" class="form-control" value="{{ old('esic', $structure->esic) }}"></div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Totals:</strong>
                            <span id="grossTotal">Gross: 0.00</span> |
                            <span id="deductionTotal">Deductions: 0.00</span> |
                            <span id="netTotal">Net: 0.00</span>
                        </div>

                        <div class="form-group form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $structure->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <button type="submit" class="btn btn-success">Update Structure</button>
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
