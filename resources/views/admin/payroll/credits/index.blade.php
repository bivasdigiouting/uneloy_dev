@extends('layouts.admin')

@section('title', 'Monthly Salary Credit')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7 d-flex align-items-center">
                    <h1 class="mb-0 me-2"><i class="ti ti-receipt-2 me-1"></i> Salary Credit Report</h1>
                    <span class="badge bg-info">{{ $credits->count() }} records</span>
                </div>
                <div class="col-sm-5 text-end">
                    <a href="{{ route('admin.payroll.credits.create') }}" class="btn btn-primary">
                        <i class="ti ti-credit-card"></i> Credit Salary
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.payroll.credits.index') }}" class="mb-3">
                        <div class="d-flex flex-wrap align-items-end gap-2">
                            <div class="me-2">
                                <label class="form-label mb-0">Department</label>
                                <select name="department_id" class="form-control select2" style="min-width:220px">
                                    <option value="">All</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ ($departmentId == $dept->id) ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="me-2">
                                <label class="form-label mb-0">Month</label>
                                <select name="month" class="form-control select2" style="min-width:160px">
                                    <option value="">All</option>
                                    @for($m=1;$m<=12;$m++)
                                        <option value="{{ $m }}" {{ ($month == $m) ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="me-2">
                                <label class="form-label mb-0">Year</label>
                                <input type="number" name="year" class="form-control" value="{{ $year }}" placeholder="e.g. 2025" style="width:120px">
                            </div>
                            <div class="ms-auto">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Staff</th>
                                    <th>Department</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th class="text-end">Gross</th>
                                    <th class="text-end">Deductions</th>
                                    <th class="text-end">Net Pay</th>
                                    <th>Credited At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($credits as $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->staff->staff_name ?? '—' }}</td>
                                        <td>{{ $c->department->department_name ?? '—' }}</td>
                                        <td>{{ date('F', mktime(0,0,0,$c->month,1)) }}</td>
                                        <td>{{ $c->year }}</td>
                                        <td class="text-end">{{ number_format($c->gross_earnings, 2) }}</td>
                                        <td class="text-end">{{ number_format($c->total_deductions, 2) }}</td>
                                        <td class="text-end">{{ number_format($c->net_pay, 2) }}</td>
                                        <td>{{ optional($c->credited_at)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @php
                                $sumGross = 0; $sumDed = 0; $sumNet = 0;
                                foreach($credits as $c){
                                    $sumGross += (float) $c->gross_earnings;
                                    $sumDed   += (float) $c->total_deductions;
                                    $sumNet   += (float) $c->net_pay;
                                }
                            @endphp
                            <tfoot>
                                <tr>
                                    <th>Totals</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end">{{ number_format($sumGross, 2) }}</th>
                                    <th class="text-end">{{ number_format($sumDed, 2) }}</th>
                                    <th class="text-end">{{ number_format($sumNet, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
