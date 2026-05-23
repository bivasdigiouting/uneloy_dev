@extends('layouts.admin')

@section('title', 'Inhouse Product Category Details')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Inhouse Product Category Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.inhouse-product-categories.index') }}">Inhouse Product Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.inhouse-product-categories.edit', $category->id) }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit
                </a>
            </div>
            <div class="mb-2">
                <a href="{{ route('admin.inhouse-product-categories.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Overview</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted">Icon</div>
                    <div class="mt-2">
                        @if($category->icon)
                            <img src="{{ asset('storage/'.$category->icon) }}" alt="Icon" style="width: 80px; height: 80px; object-fit: cover;" class="rounded border">
                        @else
                            <span class="text-muted">No Icon</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted">Code</div>
                            <div class="fw-semibold">{{ $category->code }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">Status</div>
                            <div class="fw-semibold">{{ ucfirst($category->status) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">Name</div>
                            <div class="fw-semibold">{{ $category->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">Display Order</div>
                            <div class="fw-semibold">{{ $category->display_order }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-muted">Slug</div>
                            <div class="fw-semibold">{{ $category->slug }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-muted">Description</div>
                            <div class="fw-semibold">{{ $category->description ?: '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

