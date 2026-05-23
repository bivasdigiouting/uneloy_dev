@extends('layouts.admin')

@section('title', 'Edit Product Category')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Product Category</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.product-categories.index') }}">Product Categories</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.product-categories.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Product Categories
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
                            <h4 class="card-title mb-0">Product Category Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
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

                    <form action="{{ route('admin.product-categories.update', $productCategory->id) }}" method="POST" enctype="multipart/form-data" id="productCategoryForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Category Name -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $productCategory->name) }}"
                                               placeholder="Enter category name"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Sequence -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sequence" class="form-label">Sequence <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('sequence') is-invalid @enderror" 
                                               id="sequence" 
                                               name="sequence" 
                                               value="{{ old('sequence', $productCategory->sequence) }}"
                                               placeholder="Enter display sequence"
                                               min="1"
                                               required>
                                        @error('sequence')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Commission(%) -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="commission" class="form-label">Commission(%) <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('commission') is-invalid @enderror" 
                                               id="commission" 
                                               name="commission" 
                                               value="{{ old('commission', $productCategory->commission) }}"
                                               placeholder="Enter commission percentage"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               required>
                                        @error('commission')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Commission(%) for Level -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="commission_level" class="form-label">Commission(%) for Level <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('commission_level') is-invalid @enderror" 
                                               id="commission_level" 
                                               name="commission_level" 
                                               value="{{ old('commission_level', $productCategory->commission_level) }}"
                                               placeholder="Enter commission percentage for level"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               required>
                                        @error('commission_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Icon Upload -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="icon" class="form-label">Category Icon</label>
                                        <input type="file" 
                                               class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" 
                                               name="icon" 
                                               accept="image/*"
                                               onchange="previewIcon(this)">
                                        <small class="form-text text-muted">Recommended size: 64x64 pixels. Supported formats: JPG, PNG, SVG. Leave empty to keep current icon.</small>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Current Icon Display -->
                                        @if($productCategory->icon)
                                            <div id="currentIcon" class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/' . $productCategory->icon) }}" alt="Current Icon" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                                    <div class="ms-3">
                                                        <h6 class="mb-1">Current Icon</h6>
                                                        <small class="text-muted">This is the current category icon</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- New Icon Preview -->
                                        <div id="iconPreview" class="mt-3" style="display: none;">
                                            <div class="d-flex align-items-center">
                                                <img id="iconPreviewImg" src="" alt="Icon Preview" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                                <div class="ms-3">
                                                    <h6 class="mb-1">New Icon Preview</h6>
                                                    <small class="text-muted">This is how your new icon will appear</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control summernote @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="5"
                                                  placeholder="Enter category description">{{ old('description', $productCategory->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <div class="form-check-group">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input @error('status') is-invalid @enderror" 
                                                       type="radio" 
                                                       name="status" 
                                                       id="status_active" 
                                                       value="active" 
                                                       {{ old('status', $productCategory->status) == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_active">
                                                    <span class="badge bg-success">Active</span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input @error('status') is-invalid @enderror" 
                                                       type="radio" 
                                                       name="status" 
                                                       id="status_inactive" 
                                                       value="inactive" 
                                                       {{ old('status', $productCategory->status) == 'inactive' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_inactive">
                                                    <span class="badge bg-danger">Inactive</span>
                                                </label>
                                            </div>
                                        </div>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-1"></i> Update Product Category
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
    const commissionInput = $('#commission');
    const commissionLevelInput = $('#commission_level');

    // Initialize Summernote for description
    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview']]
        ],
        focus: false,
        placeholder: 'Enter detailed description for the product category...'
    });

    // Form submission with SweetAlert confirmation
    $('#productCategoryForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this product category?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                $(this).find('button[type="submit"]').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...'
                );
                this.submit();
            }
        });
    });

    // Real-time validation for category name
    $('#name').on('input', function() {
        let name = $(this).val().trim();
        if (name.length < 2) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Category name must be at least 2 characters long.');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Real-time validation for commission
    $('#commission').on('input', function() {
        let commission = parseFloat($(this).val());
        if (isNaN(commission) || commission < 0 || commission > 100) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Commission must be between 0 and 100.');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    // Real-time validation for sequence
    $('#sequence').on('input', function() {
        let sequence = parseInt($(this).val());
        if (isNaN(sequence) || sequence < 1) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Sequence must be a positive number.');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Status radio button change handler
    $('input[name="is_active"]').on('change', function() {
        $('input[name="is_active"]').removeClass('is-invalid');
        $('.invalid-feedback').hide();
    });
});

// Icon preview function
function previewIcon(input) {
    if (input.files && input.files[0]) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
        if (!allowedTypes.includes(input.files[0].type)) {
            Swal.fire({
                title: 'Invalid File Type',
                text: 'Please select a valid image file (JPG, PNG, or SVG).',
                icon: 'error'
            });
            input.value = '';
            $('#iconPreview').hide();
            $('#currentIcon').show();
            return;
        }

        // Validate file size (max 2MB)
        if (input.files[0].size > 2 * 1024 * 1024) {
            Swal.fire({
                title: 'File Too Large',
                text: 'Please select an image smaller than 2MB.',
                icon: 'error'
            });
            input.value = '';
            $('#iconPreview').hide();
            $('#currentIcon').show();
            return;
        }

        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('#iconPreviewImg').attr('src', e.target.result);
            $('#iconPreview').show();
            $('#currentIcon').hide(); // Hide current icon when new one is selected
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#iconPreview').hide();
        $('#currentIcon').show(); // Show current icon if no new file selected
    }
}

// Display success/error messages
@if(session('success'))
    Swal.fire('Success!', "{{ session('success') }}", 'success');
@endif

@if(session('error'))
    Swal.fire('Error!', "{{ session('error') }}", 'error');
@endif
</script>

<!-- Custom CSS for Summernote and Form Styling -->
<style>
.note-toolbar .note-btn {
    border: 1px solid #ddd !important;
    background: #fff !important;
    color: #333 !important;
}

.note-toolbar .note-btn:hover {
    background: #f8f9fa !important;
    border-color: #adb5bd !important;
}

.note-toolbar .note-btn.active,
.note-toolbar .note-btn:active {
    background: #e9ecef !important;
    border-color: #adb5bd !important;
}

.form-check-group {
    display: flex;
    gap: 15px;
    align-items: center;
}

.form-check-inline {
    margin-right: 0;
}

.form-check-label .badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

#iconPreview, #currentIcon {
    border: 2px dashed #e9ecef;
    border-radius: 8px;
    padding: 15px;
    background-color: #f8f9fa;
}

.img-thumbnail {
    border: 2px solid #dee2e6;
}
</style>
@endpush
