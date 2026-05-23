@extends('layouts.admin')

@section('title', 'Edit CMS Page')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit CMS Page</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Website Modules</li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms-pages.index') }}">CMS Pages</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Page</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.cms-pages.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Page: {{ $page->title }}</h5>
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

            <form action="{{ route('admin.cms-pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" class="form-control" required placeholder="Enter page title">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="slug" class="form-label">Page Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" class="form-control" placeholder="Leave blank to use current slug">
                        <small class="text-muted">Unique URL identifier (e.g., terms-and-conditions)</small>
                    </div>

                    <div class="col-12">
                        <label for="content" class="form-label">Page Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control summernote" rows="10">{{ old('content', $page->content) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $page->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active Status</label>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">Update Page</button>
                        <a href="{{ route('admin.cms-pages.index') }}" class="btn btn-secondary">Cancel</a>
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
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush
