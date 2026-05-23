@extends('layouts.admin')

@section('title', 'Salary Structure')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7 d-flex align-items-center">
                    <h1 class="mb-0 me-2"><i class="ti ti-cash me-1"></i> Salary Structures</h1>
                    <span class="badge bg-primary">{{ $structures->count() }} items</span>
                </div>
                <div class="col-sm-5 text-end">
                    <a href="{{ route('admin.payroll.structures.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Create Structure
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
                    <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department</th>
                                <th>Title</th>
                                <th class="text-end">Basic</th>
                                <th class="text-end">HRA</th>
                                <th class="text-end">DA</th>
                                <th class="text-end">TA</th>
                                <th class="text-end">Medical</th>
                                <th class="text-end">Special Allowance</th>
                                <th class="text-end">Bonus</th>
                                <th class="text-end">EIC</th>
                                <th class="text-end">PF</th>
                                <th class="text-end">Loan</th>
                                <th class="text-end">ESIC</th>
                                <th class="text-end">Gross</th>
                                <th class="text-end">Net</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($structures as $structure)
                                <tr>
                                    <td>{{ $structure->id }}</td>
                                    <td>{{ $structure->department->department_name ?? '—' }}</td>
                                    <td>{{ $structure->title }}</td>
                                    <td class="text-end">{{ number_format($structure->basic, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->hra, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->da, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->ta, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->medical, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->special_allowance, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->bonus, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->eic, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->pf, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->loan, 2) }}</td>
                                    <td class="text-end">{{ number_format($structure->esic, 2) }}</td>
                                    @php
                                        $gross = ($structure->basic + $structure->hra + $structure->da + $structure->ta + $structure->medical + $structure->special_allowance + $structure->bonus + $structure->eic);
                                        $ded = ($structure->pf + $structure->loan + $structure->esic);
                                        $net = $gross - $ded;
                                    @endphp
                                    <td class="text-end">{{ number_format($gross, 2) }}</td>
                                    <td class="text-end">{{ number_format($net, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $structure->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $structure->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.payroll.structures.edit', $structure) }}" class="btn btn-sm btn-info">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
