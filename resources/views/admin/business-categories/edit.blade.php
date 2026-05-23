@extends('layouts.admin')

@section('title', 'Edit Business Category')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Business Category</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.business-categories.index') }}">Business Categories</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.business-categories.show', $category->id) }}" class="btn btn-info d-inline-flex align-items-center">
                    <i class="ti ti-eye me-1"></i>View Details
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.business-categories.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Business Categories
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Edit Category: {{ $category->category_name }}</h4>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.business-categories.show', $category->id) }}" class="btn btn-info btn-sm">
                                    <i class="ti ti-eye me-1"></i> View
                                </a>
                                <a href="{{ route('admin.business-categories.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.business-categories.update', $category->id) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Category Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('category_name') is-invalid @enderror" 
                                           id="category_name" name="category_name" 
                                           value="{{ old('category_name', $category->category_name) }}" 
                                           placeholder="Enter category name" required>
                                    @error('category_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" 
                                           value="{{ old('slug', $category->slug) }}" 
                                           placeholder="Auto-generated from category name">
                                    <div class="form-text">Leave empty to auto-generate from category name</div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Enter category description (optional)">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sort Order -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" 
                                           value="{{ old('sort_order', $category->sort_order) }}" 
                                           min="0" placeholder="0">
                                    <div class="form-text">Lower numbers appear first</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_active" name="is_active" 
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-text">Enable this category for public use</div>
                                </div>
                            </div>
                        </div>

                        <!-- Category Info -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Created:</strong> {{ $category->created_at->format('d M Y, h:i A') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Last Updated:</strong> {{ $category->updated_at->format('d M Y, h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.business-categories.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-1"></i> Update Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Store original values
    const originalCategoryName = $('#category_name').val();
    const originalSlug = $('#slug').val();
    
    // Auto-generate slug from category name only if it's changed
    $('#category_name').on('input', function() {
        const categoryName = $(this).val();
        
        // Only auto-generate if category name changed and slug wasn't manually modified
        if (categoryName !== originalCategoryName) {
            const slug = categoryName
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
                .trim('-'); // Remove leading/trailing hyphens
            
            // Only update if slug field hasn't been manually modified
            if ($('#slug').val() === originalSlug || $('#slug').val() === '') {
                $('#slug').val(slug);
            }
        }
    });

    // Form validation
    $('#categoryForm').on('submit', function(e) {
        const categoryName = $('#category_name').val().trim();
        
        if (!categoryName) {
            e.preventDefault();
            $('#category_name').addClass('is-invalid');
            
            // Show error message
            if (!$('#category_name').next('.invalid-feedback').length) {
                $('#category_name').after('<div class="invalid-feedback">Category name is required.</div>');
            }
            
            $('#category_name').focus();
            return false;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="ti ti-loader ti-spin me-1"></i> Updating...').prop('disabled', true);
        
        // Re-enable button after 10 seconds (fallback)
        setTimeout(function() {
            submitBtn.html(originalText).prop('disabled', false);
        }, 10000);
    });

    // Remove validation error on input
    $('#category_name').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert:not(.alert-info)').fadeOut();
    }, 5000);
});
</script>
@endpush