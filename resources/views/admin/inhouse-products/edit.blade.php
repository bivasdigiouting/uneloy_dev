@extends('layouts.admin')

@section('title', 'Edit Inhouse Product')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Inhouse Product</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.inhouse-products.index') }}">Inhouse Product Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.inhouse-products.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Product Details</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.inhouse-products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="inhouse_product_category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (int) old('inhouse_product_category_id', $product->inhouse_product_category_id) === (int) $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" maxlength="64" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">GST Tax</label>
                            <select name="gst_tax_id" class="form-select">
                                <option value="">-- Select GST Tax --</option>
                                @foreach($taxes as $tax)
                                    <option value="{{ $tax->id }}" {{ (int) old('gst_tax_id', $product->gst_tax_id) === (int) $tax->id ? 'selected' : '' }}>
                                        {{ $tax->tax_name }} ({{ $tax->rate_percent }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">MRP</label>
                            <input type="number" step="0.01" name="mrp" class="form-control" value="{{ old('mrp', $product->mrp) }}" min="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" min="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-select" required>
                                <option value="1" {{ old('is_active', $product->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $product->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            @if($product->thumbnail)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="Thumbnail" style="width: 70px; height: 70px; object-fit: cover;" class="rounded border">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Add More Images</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    @php
                        $imgs = is_array($product->images) ? $product->images : [];
                    @endphp
                    @if(!empty($imgs))
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($imgs as $img)
                                <img src="{{ asset('storage/'.$img) }}" alt="Image" style="width: 70px; height: 70px; object-fit: cover;" class="rounded border">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.inhouse-products.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

