@extends('layouts.admin')

@section('title', 'Add News')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add News</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Website Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">News</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.news.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Create News</h5>
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

            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" id="newsForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generated">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Feature Image</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Supported: JPG, PNG, GIF. Max 2MB.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Published At</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Short Excerpt</label>
                        <textarea name="excerpt" class="form-control" rows="3" maxlength="500">{{ old('excerpt') }}</textarea>
                        <small class="form-text text-muted">Max 500 characters.</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publish</label>
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Save News
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#content').summernote({
        height: 250,
        toolbar: [
            ['fontsize', ['fontsize']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('#title').on('input', function() {
        if ($('#slug').val().trim() === '') {
            const slug = $(this).val().toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            $('#slug').val(slug);
        }
    });
});
</script>
@endpush

