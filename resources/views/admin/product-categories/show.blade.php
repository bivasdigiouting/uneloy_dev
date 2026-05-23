@extends('layouts.admin')

@section('title', 'Product Category Details')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Product Category Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Product Management</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product-categories.index') }}">Product Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.product-categories.edit', $productCategory->id) }}" class="btn btn-primary">
            <i class="ti ti-edit me-2"></i>Edit Category
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted">Name</label>
                            <div class="fw-semibold">{{ $productCategory->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Sequence</label>
                            <div class="fw-semibold">{{ $productCategory->sequence }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Commission</label>
                            <div class="fw-semibold">{{ number_format($productCategory->commission, 2) }}%</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Commission (Level Avg)</label>
                            <div class="fw-semibold">{{ number_format($productCategory->commission_level, 2) }}%</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Status</label>
                            <div>
                                @if($productCategory->status === 'active')
                                    <span class="badge badge-success-transparent">Active</span>
                                @else
                                    <span class="badge badge-danger-transparent">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Created</label>
                            <div class="fw-semibold">{{ optional($productCategory->created_at)->format('d M Y, h:i A') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted">Description</label>
                            <div class="fw-semibold">{{ $productCategory->description ?: '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Icon</h5>
                </div>
                <div class="card-body text-center">
                    @if($productCategory->icon)
                        <img src="{{ asset('storage/'.$productCategory->icon) }}" alt="Icon" class="rounded" style="width: 160px; height: 160px; object-fit: cover;">
                    @else
                        <div class="text-muted">No Icon</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

