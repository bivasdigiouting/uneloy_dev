@extends('layouts.admin')

@section('title', 'Edit Website Benefit')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Website Benefit</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.website-benefits.index') }}">Website Benefits</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.website-benefits.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
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
                    <h5 class="card-title mb-0">Edit Website Benefit</h5>
                    <a href="{{ route('admin.website-benefits.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Back to Website Benefits
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.website-benefits.update', $benefit->id) }}" method="POST" enctype="multipart/form-data" id="websiteBenefitForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="benefit_name" class="form-label">Benefit Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('benefit_name') is-invalid @enderror" 
                                           id="benefit_name" 
                                           name="benefit_name" 
                                           value="{{ old('benefit_name', $benefit->benefit_name) }}" 
                                           placeholder="Enter benefit name"
                                           required>
                                    @error('benefit_name')
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
                                           value="{{ old('sequence', $benefit->sequence) }}" 
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
                                
                                <!-- Current Icon Display -->
                                @if($benefit->icon_url)
                                    <div class="mb-3" id="currentIcon">
                                        <label class="form-label">Current Icon</label>
                                        <div class="border rounded p-2 text-center" style="max-width: 200px;">
                                            <img src="{{ $benefit->icon_url }}" alt="Current Icon" class="img-fluid" style="max-height: 100px;">
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- New Icon Preview -->
                                <div class="mb-3" id="iconPreview" style="display: none;">
                                    <label class="form-label">New Icon Preview</label>
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
                                               {{ old('is_active', $benefit->is_active) ? 'checked' : '' }}>
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
                                              placeholder="Enter benefit description"
                                              required>{{ old('description', $benefit->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.website-benefits.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check"></i> Update Website Benefit
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
    $('#websiteBenefitForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this website benefit?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
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
            $('#currentIcon').hide(); // Hide current icon when new one is selected
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#iconPreview').hide();
        $('#currentIcon').show(); // Show current icon if no new file selected
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