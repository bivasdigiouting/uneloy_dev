@extends('layouts.admin')

@section('title', 'Create Monthly Salary')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Monthly Salary Credit</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.payroll.credits.index') }}" class="btn btn-secondary">Back to Report</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.payroll.credits.store') }}" method="POST" id="salaryCreditForm">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Department</label>
                                <form action="{{ route('admin.payroll.credits.create') }}" method="GET" id="deptReloadForm"></form>
                                <select name="department_id" class="form-control select2" required form="salaryCreditForm" onchange="reloadWithDepartment(this.value)">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ ($selectedDepartment == $dept->id) ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Staff</label>
                                <select name="staff_id" class="form-control select2" required>
                                    <option value="">Select Staff</option>
                                    @foreach($staff as $s)
                                        <option value="{{ $s->id }}">{{ $s->staff_name }}</option>
                                    @endforeach
                                </select>
                                @error('staff_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label>Month</label>
                                <select name="month" class="form-control select2" required>
                                    @for($m=1;$m<=12;$m++)
                                        <option value="{{ $m }}" {{ (int)date('n') === $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                    @endfor
                                </select>
                                @error('month')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label>Year</label>
                                <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                                @error('year')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Earnings</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>Basic</label><input type="number" step="0.01" name="basic" id="earn_basic" class="form-control validate-number" data-required="true" value="{{ $structure->basic ?? 0 }}" required>@error('basic')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                    <div class="form-group col-md-6"><label>HRA</label><input type="number" step="0.01" name="hra" id="earn_hra" class="form-control validate-number" value="{{ $structure->hra ?? 0 }}">@error('hra')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>DA</label><input type="number" step="0.01" name="da" id="earn_da" class="form-control validate-number" value="{{ $structure->da ?? 0 }}">@error('da')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                    <div class="form-group col-md-6"><label>TA</label><input type="number" step="0.01" name="ta" id="earn_ta" class="form-control validate-number" value="{{ $structure->ta ?? 0 }}">@error('ta')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>Medical</label><input type="number" step="0.01" name="medical" id="earn_medical" class="form-control validate-number" value="{{ $structure->medical ?? 0 }}">@error('medical')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                    <div class="form-group col-md-6"><label>Special Allowance</label><input type="number" step="0.01" name="special_allowance" id="earn_sa" class="form-control validate-number" value="{{ $structure->special_allowance ?? 0 }}">@error('special_allowance')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>Bonus</label><input type="number" step="0.01" name="bonus" id="earn_bonus" class="form-control validate-number" value="{{ $structure->bonus ?? 0 }}">@error('bonus')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                    <div class="form-group col-md-6"><label>EIC</label><input type="number" step="0.01" name="eic" id="earn_eic" class="form-control validate-number" value="{{ $structure->eic ?? 0 }}">@error('eic')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Deductions</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>PF</label><input type="number" step="0.01" name="pf" id="ded_pf" class="form-control validate-number" value="{{ $structure->pf ?? 0 }}">@error('pf')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                    <div class="form-group col-md-6"><label>Loan</label><input type="number" step="0.01" name="loan" id="ded_loan" class="form-control validate-number" value="{{ $structure->loan ?? 0 }}">@error('loan')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label>ESIC</label><input type="number" step="0.01" name="esic" id="ded_esic" class="form-control validate-number" value="{{ $structure->esic ?? 0 }}">@error('esic')<small class="text-danger">{{ $message }}</small>@enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Totals:</strong>
                            <span id="grossTotal">Gross: 0.00</span> |
                            <span id="deductionTotal">Deductions: 0.00</span> |
                            <span id="netTotal">Net Pay: 0.00</span>
                        </div>

                        <button type="submit" class="btn btn-success">Credit Salary</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
function reloadWithDepartment(deptId) {
    const url = new URL(window.location.href);
    url.searchParams.set('department_id', deptId);
    window.location.href = url.toString();
}

function calcTotals() {
    const earnIds = ['earn_basic','earn_hra','earn_da','earn_ta','earn_medical','earn_sa','earn_bonus','earn_eic'];
    const dedIds = ['ded_pf','ded_loan','ded_esic'];
    let gross = 0, ded = 0;
    earnIds.forEach(id => { gross += parseFloat(document.getElementById(id).value || 0); });
    dedIds.forEach(id => { ded += parseFloat(document.getElementById(id).value || 0); });
    const net = gross - ded;
    document.getElementById('grossTotal').innerText = 'Gross: ' + gross.toFixed(2);
    document.getElementById('deductionTotal').innerText = 'Deductions: ' + ded.toFixed(2);
    document.getElementById('netTotal').innerText = 'Net Pay: ' + net.toFixed(2);
}

['earn_basic','earn_hra','earn_da','earn_ta','earn_medical','earn_sa','earn_bonus','earn_eic','ded_pf','ded_loan','ded_esic']
    .forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', calcTotals);
    });

window.addEventListener('load', calcTotals);

// inline validation
function validateNumberInput(el) {
    const val = parseFloat(el.value);
    const required = el.getAttribute('data-required') === 'true';
    const invalid = (required && (isNaN(val) || el.value === '')) || (!isNaN(val) && val < 0);
    if (invalid) {
        el.classList.add('is-invalid');
    } else {
        el.classList.remove('is-invalid');
    }
}

document.querySelectorAll('.validate-number').forEach(el => {
    el.addEventListener('input', () => validateNumberInput(el));
    el.addEventListener('blur', () => validateNumberInput(el));
});
</script>
@endsection
