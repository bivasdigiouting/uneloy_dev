@extends('layouts.admin')

@section('title', 'Inhouse Product Details')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Inhouse Product Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.inhouse-products.index') }}">Inhouse Product Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.inhouse-products.edit', $product->id) }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit
                </a>
            </div>
            <div class="mb-2">
                <a href="{{ route('admin.inhouse-products.index') }}" class="btn btn-light d-inline-flex align-items-center">
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
                    <div class="text-muted">Thumbnail</div>
                    <div class="mt-2">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="Thumbnail" style="width: 120px; height: 120px; object-fit: cover;" class="rounded border">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted">Name</div>
                            <div class="fw-semibold">{{ $product->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">SKU</div>
                            <div class="fw-semibold">{{ $product->sku }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">Category</div>
                            <div class="fw-semibold">{{ $product->category?->name ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">Status</div>
                            <div class="fw-semibold">{{ $product->is_active ? 'Active' : 'Inactive' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">MRP</div>
                            <div class="fw-semibold">₹{{ number_format((float) ($product->mrp ?? 0), 2) }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Price</div>
                            <div class="fw-semibold">₹{{ number_format((float) ($product->price ?? 0), 2) }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Stock</div>
                            <div class="fw-semibold">{{ $product->stock }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-muted">GST Tax</div>
                            <div class="fw-semibold">{{ $product->gstTax ? $product->gstTax->tax_name.' ('.$product->gstTax->rate_percent.'%)' : '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="text-muted">Description</div>
                    <div class="fw-semibold">{{ $product->description ?: '—' }}</div>
                </div>

                @php
                    $imgs = is_array($product->images) ? $product->images : [];
                @endphp
                @if(!empty($imgs))
                    <div class="col-12">
                        <div class="text-muted">Images</div>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($imgs as $img)
                                <img src="{{ asset('storage/'.$img) }}" alt="Image" style="width: 90px; height: 90px; object-fit: cover;" class="rounded border">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

