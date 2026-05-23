@extends('layouts.admin')

@section('title', 'Create Special Feature')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create Special Feature</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.special-features.index') }}">Special Features</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.special-features.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create Special Feature</h5>
                    <a href="{{ route('admin.special-features.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Back to Special Features
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.special-features.store') }}" method="POST" enctype="multipart/form-data" id="specialFeatureForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="features_name" class="form-label">Features Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('features_name') is-invalid @enderror" 
                                           id="features_name" 
                                           name="features_name" 
                                           value="{{ old('features_name') }}" 
                                           placeholder="Enter feature name"
                                           required>
                                    @error('features_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sequence" class="form-label">Sequence <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('sequence') is-invalid @enderror" 
                                           id="sequence" 
                                           name="sequence" 
                                           value="{{ old('sequence', 1) }}" 
                                           min="1"
                                           placeholder="Enter sequence number"
                                           required>
                                    @error('sequence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Upload Icon (Optional)</label>
                                    <input type="file" 
                                           class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" 
                                           name="icon" 
                                           accept="image/*"
                                           onchange="previewIcon(this)">
                                    <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF, SVG. Max size: 2MB</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Icon Preview -->
                                <div class="mb-3" id="iconPreview" style="display: none;">
                                    <label class="form-label">Icon Preview</label>
                                    <div class="border rounded p-2 text-center" style="max-width: 200px;">
                                        <img id="iconPreviewImg" src="" alt="Icon Preview" class="img-fluid" style="max-height: 100px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control summernote @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="5"
                                              placeholder="Enter feature description"
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.special-features.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check"></i> Create Special Feature
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
    // Initialize Summernote
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
        focus: false
    });

    // Form submission with SweetAlert confirmation
    $('#specialFeatureForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to create this special feature?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, create it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});

// Icon preview function
function previewIcon(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('#iconPreviewImg').attr('src', e.target.result);
            $('#iconPreview').show();
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#iconPreview').hide();
    }
}
</script>

<!-- Custom CSS for Summernote -->
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
</style>
@endpush