@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Update Product</h5>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">Product Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ($product->category === $cat->name) ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-control" required placeholder="Enter product name">
                    </div>
                    <div class="col-md-4">
                        <label for="gst_tax_id" class="form-label">Tax Category</label>
                        <select name="gst_tax_id" id="gst_tax_id" class="form-select">
                            <option value="">-- Select GST Tax --</option>
                            @foreach ($taxes as $tax)
                                <option value="{{ $tax->id }}" {{ (int) $product->gst_tax_id === (int) $tax->id ? 'selected' : '' }}>{{ $tax->tax_name }} ({{ $tax->rate_percent }}%)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="mrp" class="form-label">MRP</label>
                        <input type="number" step="0.01" name="mrp" id="mrp" value="{{ old('mrp', $product->mrp) }}" class="form-control" placeholder="Enter MRP">
                    </div>
                    <div class="col-md-4">
                        <label for="distributor_price" class="form-label">Distributor Price</label>
                        <input type="number" step="0.01" name="distributor_price" id="distributor_price" value="{{ old('distributor_price', $product->distributor_price) }}" class="form-control" placeholder="Enter distributor price">
                    </div>
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" class="form-control" placeholder="Enter selling price">
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Product Description</label>
                        <textarea name="description" id="description" class="form-control summernote" rows="6">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="thumbnail" class="form-label">Product Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="form-control">
                        <div class="mt-2">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Current Thumbnail" style="width:120px;height:120px;object-fit:cover;border:1px solid #ddd;border-radius:6px;" />
                            @endif
                            <img id="thumbPreview" src="#" alt="Thumbnail Preview" style="display:none;width:120px;height:120px;object-fit:cover;border:1px solid #ddd;border-radius:6px;" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="images" class="form-label">Product Images (Multiple)</label>
                        <input type="file" name="images[]" id="images" accept="image/*" multiple class="form-control">
                        <div class="mt-2 d-flex flex-wrap gap-2" id="imagesPreview">
                            @php
                                $existingImages = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?: []);
                            @endphp
                            @foreach ($existingImages as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Image" style="width:100px;height:100px;object-fit:cover;border:1px solid #ddd;border-radius:6px;" />
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light">Reset</button>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function(){
    $('.summernote').summernote({
        placeholder: 'Write product description here...',
        tabsize: 2,
        height: 220
    });

    $('#thumbnail').on('change', function(evt){
        const [file] = this.files;
        if (file) {
            const url = URL.createObjectURL(file);
            $('#thumbPreview').attr('src', url).show();
        } else {
            $('#thumbPreview').hide();
        }
    });

    $('#images').on('change', function() {
        const container = $('#imagesPreview');
        Array.from(this.files).forEach(f => {
            const url = URL.createObjectURL(f);
            const img = $('<img>').attr('src', url).css({ width:'100px', height:'100px', objectFit:'cover', border:'1px solid #ddd', borderRadius:'6px' });
            container.append(img);
        });
    });

    $('#productForm').on('submit', function(e){
        const thumbSelected = $('#thumbnail')[0].files && $('#thumbnail')[0].files.length > 0;
        const hasCurrentThumb = {{ $product->image ? 'true' : 'false' }};
        const imagesSelected = $('#images')[0].files && $('#images')[0].files.length > 0;
        const existingImagesCount = $('#imagesPreview img').length; // includes existing rendered images
        const hasAnyImages = imagesSelected || (existingImagesCount > 0);

        if (!hasCurrentThumb && !thumbSelected) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Missing Thumbnail', text: 'Please upload a product thumbnail.' });
            return false;
        }

        if (!hasAnyImages) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Missing Images', text: 'Please upload at least one product image.' });
            return false;
        }
    });
});
</script>
@endpush
