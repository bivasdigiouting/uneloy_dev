@extends('layouts.admin')

@section('title', 'About Us')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">About Us</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Website Modules
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">About Us</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">About Us Information</h4>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.about-us.update') }}" method="POST" enctype="multipart/form-data" id="aboutUsForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- Image Upload -->
                                <div class="form-group mb-3">
                                    <label for="image">About Us Image</label>
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB. Recommended size: 1200x600px</small>
                                </div>

                                <!-- Image Text Header -->
                                <div class="form-group mb-3">
                                    <label for="text_header">Image Text Header</label>
                                    <input type="text" 
                                           class="form-control @error('text_header') is-invalid @enderror" 
                                           id="text_header" 
                                           name="text_header" 
                                           value="{{ old('text_header', $aboutUs->text_header ?? '') }}" 
                                           placeholder="Enter header text for about us section"
                                           maxlength="255">
                                    @error('text_header')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Image Text Description -->
                                <div class="form-group mb-3">
                                    <label for="text_description">Image Text Description</label>
                                    <textarea class="form-control @error('text_description') is-invalid @enderror" 
                                              id="text_description" 
                                              name="text_description" 
                                              rows="6" 
                                              placeholder="Enter detailed description for about us section">{{ old('text_description', $aboutUs->text_description ?? '') }}</textarea>
                                    @error('text_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Footer Short Description -->
                                <div class="form-group mb-3">
                                    <label for="footer_short_description">Footer Short Description</label>
                                    <textarea class="form-control @error('footer_short_description') is-invalid @enderror" 
                                              id="footer_short_description" 
                                              name="footer_short_description" 
                                              rows="4" 
                                              placeholder="Enter short description for footer section">{{ old('footer_short_description', $aboutUs->footer_short_description ?? '') }}</textarea>
                                    @error('footer_short_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column - Image Preview -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Current Image</label>
                                    <div class="image-preview-container">
                                        @if($aboutUs && $aboutUs->image)
                                            <div id="currentImagePreview">
                                                <img src="{{ $aboutUs->image_url }}" 
                                                     alt="Current About Us Image" 
                                                     class="img-fluid rounded border"
                                                     style="max-height: 200px; width: 100%; object-fit: cover;">
                                            </div>
                                        @else
                                            <div id="currentImagePreview" class="text-center p-4 border rounded bg-light">
                                                <i class="ti ti-photo text-muted" style="font-size: 48px;"></i>
                                                <p class="text-muted mt-2 mb-0">No image uploaded</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label>New Image Preview</label>
                                    <div id="newImagePreview" class="text-center p-4 border rounded bg-light" style="display: none;">
                                        <img id="newImageDisplay" src="" alt="New Image Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>

                                @if($aboutUs)
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <strong>Created:</strong> {{ $aboutUs->created_at ? $aboutUs->created_at->format('M d, Y H:i') : 'N/A' }}<br>
                                            <strong>Updated:</strong> {{ $aboutUs->updated_at ? $aboutUs->updated_at->format('M d, Y H:i') : 'N/A' }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>Update About Us
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .image-preview-container {
        position: relative;
    }
    
    .note-editor {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .note-editor.note-frame .note-editing-area .note-editable {
        min-height: 150px;
    }
    
    /* Fix Summernote toolbar icons */
    .note-toolbar .note-btn {
        border: 1px solid #dee2e6;
        background: #fff;
        color: #495057;
    }
    
    .note-toolbar .note-btn:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }
    
    .note-toolbar .note-btn.active {
        background: #007bff;
        border-color: #007bff;
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Summernote for text editors
    $('#text_description').summernote({
        height: 200,
        placeholder: 'Enter detailed description for about us section...',
        focus: false,
        toolbar: [
            ['fontsize', ['fontsize']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('#footer_short_description').summernote({
        height: 150,
        placeholder: 'Enter short description for footer section...',
        focus: false,
        toolbar: [
            ['fontsize', ['fontsize']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Image preview functionality
    $('#image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please select a valid image file (JPG, PNG, GIF).',
                });
                $(this).val('');
                $('#newImagePreview').hide();
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Please select an image smaller than 2MB.',
                });
                $(this).val('');
                $('#newImagePreview').hide();
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#newImageDisplay').attr('src', e.target.result);
                $('#newImagePreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#newImagePreview').hide();
        }
    });

    // Form submission with confirmation
    $('#aboutUsForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Update About Us?',
            text: 'Are you sure you want to update the About Us information?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove the preventDefault and submit normally
                this.submit();
            }
        });
    });

    // Show success/error messages with SweetAlert (keeping for additional feedback)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
        });
    @endif
});
</script>
@endpush