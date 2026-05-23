@extends('layouts.admin')

@section('title', 'Recharge Operator')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Recharge Operator</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Recharge Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Recharge Operator</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.recharge-operators.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add Recharge Operator
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

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Operators</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Logo</th>
                            <th>Operator Name</th>
                            <th>Operator Code</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 240px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($operators as $operator)
                            <tr>
                                <td>
                                    @if($operator->service)
                                        {{ $operator->service->service_name }}
                                        <div class="small text-muted"><code>{{ $operator->service->service_code }}</code></div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($operator->operator_logo)
                                        <img src="{{ asset('storage/' . $operator->operator_logo) }}" alt="Logo" class="img-thumbnail" style="height: 40px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $operator->operator_name }}</td>
                                <td><code>{{ $operator->operator_code }}</code></td>
                                <td>
                                    @if($operator->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.recharge-operators.toggle-status', $operator->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $operator->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                            {{ $operator->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.recharge-operators.edit', $operator->id) }}" class="btn btn-sm btn-info ms-1">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.recharge-operators.destroy', $operator->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Delete this operator?');">
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
                                <td colspan="6" class="text-center">No recharge operators found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $operators->links() }}
            </div>
        </div>
    </div>
</div>
@endsection