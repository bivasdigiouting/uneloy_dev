@extends('layouts.admin')

@section('title', 'Edit Home Slider')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Home Slider</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Website Modules
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.home-sliders.index') }}">Home Slider</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.home-sliders.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Home Sliders
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
                            <h4 class="card-title mb-0">Edit Slider Image</h4>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.home-sliders.update', $homeSlider->id) }}" method="POST" enctype="multipart/form-data" id="sliderForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- Image Upload -->
                                <div class="form-group mb-3">
                                    <label for="image">Upload Slider Image</label>
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
                                    <small class="form-text text-muted">Leave empty to keep current image. Supported formats: JPG, PNG, GIF. Max size: 2MB. Recommended size: 1920x800px</small>
                                </div>

                                <!-- Image Text Header -->
                                <div class="form-group mb-3">
                                    <label for="text_header">Image Text Header <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('text_header') is-invalid @enderror" 
                                           id="text_header" 
                                           name="text_header" 
                                           value="{{ old('text_header', $homeSlider->text_header) }}" 
                                           placeholder="Enter header text for the slider"
                                           maxlength="255"
                                           required>
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
                                              rows="4" 
                                              placeholder="Enter description text for the slider"
                                              maxlength="500">{{ old('text_description', $homeSlider->text_description) }}</textarea>
                                    @error('text_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Maximum 500 characters</small>
                                </div>

                                <!-- Show On Portal -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Show On Portal <span class="text-danger">*</span></label>
                                    <select class="form-select @error('show_on_portal') is-invalid @enderror" 
                                            id="show_on_portal" 
                                            name="show_on_portal" 
                                            required>
                                        <option value="">Select Option</option>
                                        <option value="1" {{ old('show_on_portal', $homeSlider->show_on_portal) == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('show_on_portal', $homeSlider->show_on_portal) == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('show_on_portal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Sequence Number -->
                                <div class="form-group mb-3">
                                    <label for="sequence_no">Sequence Number <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('sequence_no') is-invalid @enderror" 
                                           id="sequence_no" 
                                           name="sequence_no" 
                                           value="{{ old('sequence_no', $homeSlider->sequence_no) }}" 
                                           min="1"
                                           max="999"
                                           required>
                                    @error('sequence_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Order in which the slider will appear (1 = first)</small>
                                </div>

                                <!-- Status -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="form-check-container mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="is_active" 
                                                   id="status_active" 
                                                   value="1" 
                                                   {{ old('is_active', $homeSlider->is_active) == '1' ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="status_active">
                                                <i class="ti ti-check-circle text-success me-1"></i>Active
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="is_active" 
                                                   id="status_inactive" 
                                                   value="0" 
                                                   {{ old('is_active', $homeSlider->is_active) == '0' ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="status_inactive">
                                                <i class="ti ti-x-circle text-danger me-1"></i>Inactive
                                            </label>
                                        </div>
                                    </div>
                                    @error('is_active')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column - Image Preview -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Image Preview</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <!-- Current Image -->
                                        <div id="currentImageContainer" class="mb-3">
                                            <label class="form-label text-muted">Current Image:</label>
                                            @if($homeSlider->image && file_exists(public_path('storage/home-sliders/' . $homeSlider->image)))
                                                <img id="currentImage" 
                                                     src="{{ asset('storage/home-sliders/' . $homeSlider->image) }}" 
                                                     alt="Current Slider" 
                                                     class="img-fluid rounded border"
                                                     style="max-height: 200px; width: 100%; object-fit: cover;">
                                            @else
                                                <div class="text-muted">
                                                    <i class="ti ti-photo-off" style="font-size: 48px;"></i>
                                                    <p class="mt-2 mb-0">No image found</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- New Image Preview -->
                                        <div id="newImageContainer" class="mb-3" style="display: none;">
                                            <label class="form-label text-success">New Image Preview:</label>
                                            <img id="newImagePreview" 
                                                 src="" 
                                                 alt="New Slider Preview" 
                                                 class="img-fluid rounded border"
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeNewImage">
                                                <i class="ti ti-x me-1"></i>Remove New Image
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Info -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Guidelines</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <i class="ti ti-check text-success me-2"></i>
                                                <small>Use high-quality images</small>
                                            </li>
                                            <li class="mb-2">
                                                <i class="ti ti-check text-success me-2"></i>
                                                <small>Recommended: 1920x800px</small>
                                            </li>
                                            <li class="mb-2">
                                                <i class="ti ti-check text-success me-2"></i>
                                                <small>Keep text concise</small>
                                            </li>
                                            <li class="mb-0">
                                                <i class="ti ti-check text-success me-2"></i>
                                                <small>Set proper sequence order</small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Slider Info -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Slider Info</h5>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-muted">
                                            <strong>Created:</strong> {{ $homeSlider->created_at->format('M d, Y H:i') }}<br>
                                            <strong>Updated:</strong> {{ $homeSlider->updated_at->format('M d, Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.home-sliders.index') }}" class="btn btn-secondary">
                                <i class="ti ti-x me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Update Slider
                            </button>
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
.form-check-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.form-check-inline {
    margin-right: 0;
}

#currentImageContainer img,
#newImageContainer img {
    transition: all 0.3s ease;
}

#currentImageContainer img:hover,
#newImageContainer img:hover {
    transform: scale(1.02);
}

.card-body .text-muted i {
    opacity: 0.5;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview functionality
    $('#image').on('change', function() {
        const file = this.files[0];
        
        if (file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                toastr.error('Please select a valid image file (JPG, PNG, GIF)');
                this.value = '';
                hideNewImagePreview();
                return;
            }
            
            // Validate file size (2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                toastr.error('Image size should not exceed 2MB');
                this.value = '';
                hideNewImagePreview();
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#newImagePreview').attr('src', e.target.result);
                $('#newImageContainer').show();
            };
            reader.readAsDataURL(file);
        } else {
            hideNewImagePreview();
        }
    });
    
    // Remove new image
    $('#removeNewImage').on('click', function() {
        $('#image').val('');
        hideNewImagePreview();
    });
    
    function hideNewImagePreview() {
        $('#newImageContainer').hide();
        $('#newImagePreview').attr('src', '');
    }
    
    // Character counter for description
    $('#text_description').on('input', function() {
        const maxLength = 500;
        const currentLength = $(this).val().length;
        const remaining = maxLength - currentLength;
        
        // Update or create counter
        let counter = $(this).siblings('.char-counter');
        if (counter.length === 0) {
            counter = $('<small class="form-text text-muted char-counter"></small>');
            $(this).after(counter);
        }
        
        counter.text(`${currentLength}/${maxLength} characters`);
        
        if (remaining < 50) {
            counter.removeClass('text-muted').addClass('text-warning');
        } else {
            counter.removeClass('text-warning').addClass('text-muted');
        }
    });
    
    // Trigger character counter on page load
    $('#text_description').trigger('input');
    
    // Form validation
    $('#sliderForm').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = ['text_header', 'show_on_portal', 'sequence_no', 'is_active'];
        requiredFields.forEach(function(field) {
            const input = $(`[name="${field}"]`);
            if (!input.val() || (field === 'is_active' && !$(`[name="${field}"]:checked`).length)) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill all required fields');
        }
    });
    
    // Remove validation errors on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush