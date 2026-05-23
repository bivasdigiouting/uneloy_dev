@extends('layouts.admin')

@section('title', 'Recharge User Commission')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Recharge User Commission</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Recharge Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">User Commission</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.recharge-commissions.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add Commission Rule
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.recharge-commissions.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-select">
                            <option value="">All</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_name }} ({{ $service->service_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Operator</label>
                        <select name="operator_id" class="form-select">
                            <option value="">All</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}" {{ request('operator_id') == $operator->id ? 'selected' : '' }}>
                                    {{ $operator->operator_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Department Level</label>
                        <select name="department_level" class="form-select">
                            <option value="">All</option>
                            @foreach($departmentLevels as $key => $label)
                                <option value="{{ $key }}" {{ request('department_level') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-12 d-flex gap-2">
                        <button class="btn btn-primary" type="submit"><i class="ti ti-filter me-1"></i>Apply</button>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.recharge-commissions.index') }}"><i class="ti ti-refresh me-1"></i>Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Commission Rules</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Operator</th>
                            <th>Department Level</th>
                            <th>Commission</th>
                            <th>Amount Range</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 260px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $rule)
                            <tr>
                                <td>
                                    {{ optional($rule->service)->service_name ?? '—' }}
                                    @if(optional($rule->service)->service_code)
                                        <div class="small text-muted"><code>{{ $rule->service->service_code }}</code></div>
                                    @endif
                                </td>
                                <td>{{ optional($rule->operator)->operator_name ?? 'All Operators' }}</td>
                                <td>{{ $departmentLevels[$rule->department_level] ?? 'All Levels' }}</td>
                                <td>
                                    @if($rule->commission_type === 'percentage')
                                        {{ number_format((float)$rule->commission_value, 2) }}%
                                    @else
                                        ₹{{ number_format((float)$rule->commission_value, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $min = $rule->min_amount;
                                        $max = $rule->max_amount;
                                    @endphp
                                    @if($min === null && $max === null)
                                        Any
                                    @elseif($min !== null && $max === null)
                                        ₹{{ number_format((float)$min, 2) }}+
                                    @elseif($min === null && $max !== null)
                                        Up to ₹{{ number_format((float)$max, 2) }}
                                    @else
                                        ₹{{ number_format((float)$min, 2) }} - ₹{{ number_format((float)$max, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if($rule->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.recharge-commissions.toggle-status', $rule->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $rule->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                            {{ $rule->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.recharge-commissions.edit', $rule->id) }}" class="btn btn-sm btn-info ms-1">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.recharge-commissions.destroy', $rule->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Delete this rule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No commission rules found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $rules->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

