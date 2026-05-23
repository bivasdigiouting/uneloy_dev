@extends('layouts.admin')

@section('title', 'View Benefit')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">View Benefit</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.benefits.index') }}">Benefits Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.benefits.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Benefit Details</h4></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Benefit Name</label>
                            <div class="form-control-plaintext">{{ $benefit->benefit_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="form-control-plaintext">
                                @if($benefit->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Schema Type</label>
                            <div class="form-control-plaintext">{{ $benefit->schema_type }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Schema Type Name</label>
                            <div class="form-control-plaintext">{{ $benefit->schema_type_name }}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Remarks</label>
                            <div class="form-control-plaintext">{{ $benefit->remarks }}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label d-block">Icon</label>
                            @if($benefit->icon_url)
                                <img src="{{ $benefit->icon_url }}" alt="Icon" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <span class="text-muted">No Icon</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <small class="text-muted">Created at: {{ optional($benefit->created_at)->format('d M Y, h:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection