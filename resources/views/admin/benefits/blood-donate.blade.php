@extends('layouts.admin')

@section('title', 'Blood Donate')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Blood Donate</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Website Modules</li>
                    <li class="breadcrumb-item">Benefit</li>
                    <li class="breadcrumb-item active" aria-current="page">Blood Donate</li>
                </ol>
            </nav>
        </div>
    </div>

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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Edit Blood Donate</h4>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.benefits.blood-donate.update') }}" method="POST" enctype="multipart/form-data" id="bloodDonateForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="image">Blood Donate Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <small class="form-text text-muted">Supported: JPG, PNG, GIF. Max 2MB.</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="text_header">Header</label>
                                    <input type="text" class="form-control @error('text_header') is-invalid @enderror" id="text_header" name="text_header" value="{{ old('text_header', $bloodDonate->text_header ?? '') }}" maxlength="255">
                                    @error('text_header')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="text_description">Description</label>
                                    <textarea class="form-control @error('text_description') is-invalid @enderror" id="text_description" name="text_description" rows="6">{{ old('text_description', $bloodDonate->text_description ?? '') }}</textarea>
                                    @error('text_description')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="footer_short_description">Footer Short Description</label>
                                    <textarea class="form-control @error('footer_short_description') is-invalid @enderror" id="footer_short_description" name="footer_short_description" rows="4">{{ old('footer_short_description', $bloodDonate->footer_short_description ?? '') }}</textarea>
                                    @error('footer_short_description')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Current Image</label>
                                <div class="image-preview-container">
                                    @if($bloodDonate && $bloodDonate->image)
                                        <img src="{{ $bloodDonate->image_url }}" alt="Current Image" class="img-fluid rounded border" style="max-height: 200px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="text-center p-4 border rounded bg-light">
                                            <i class="ti ti-photo text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 mb-0">No image uploaded</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group mt-3">
                                    <label>New Image Preview</label>
                                    <div id="newImagePreview" class="text-center p-4 border rounded bg-light" style="display:none;">
                                        <img id="newImageDisplay" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>
                                @if($bloodDonate)
                                    <div class="mt-3">
                                        <small class="text-muted"><strong>Created:</strong> {{ $bloodDonate->created_at ? $bloodDonate->created_at->format('M d, Y H:i') : 'N/A' }}<br><strong>Updated:</strong> {{ $bloodDonate->updated_at ? $bloodDonate->updated_at->format('M d, Y H:i') : 'N/A' }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Update Blood Donate</button>
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
    .image-preview-container { position: relative; }
    .note-editor { border: 1px solid #dee2e6; border-radius: 0.375rem; }
    .note-editor.note-frame .note-editing-area .note-editable { min-height: 150px; }
    .note-toolbar .note-btn { border: 1px solid #dee2e6; background: #fff; color: #495057; }
    .note-toolbar .note-btn:hover { background: #f8f9fa; border-color: #adb5bd; }
    .note-toolbar .note-btn.active { background: #007bff; border-color: #007bff; color: #fff; }
    .swal2-container { z-index: 2000; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#text_description').summernote({
        height: 200,
        placeholder: 'Enter blood donate description...',
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
        placeholder: 'Enter short footer description...',
        toolbar: [
            ['fontsize', ['fontsize']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('#image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({ icon: 'error', title: 'Invalid File Type', text: 'Please select JPG/PNG/GIF image.' });
                $(this).val('');
                $('#newImagePreview').hide();
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Image must be under 2MB.' });
                $(this).val('');
                $('#newImagePreview').hide();
                return;
            }
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

    $('#bloodDonateForm').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Update Blood Donate?',
            text: 'Confirm to save your changes.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Success!', text: '{!! session('success') !!}', timer: 2500, showConfirmButton: false });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error!', text: '{!! session('error') !!}' });
    @endif
});
</script>
@endpush
