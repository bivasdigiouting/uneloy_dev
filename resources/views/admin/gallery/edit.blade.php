@extends('layouts.admin')

@section('title', 'Manage Gallery Album')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Manage Gallery Album</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Website Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.galleries.index') }}">Gallery</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Album Settings</h5>
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

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.galleries.update', $album->id) }}" enctype="multipart/form-data" id="albumForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Gallery Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $album->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug (optional)</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $album->slug) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $album->sort_order) }}" min="0">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $album->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cover Image (optional)</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                        <small class="text-muted">If not set, cover stays as current.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Current Cover</label>
                        <div class="border rounded p-2">
                            @if($album->cover_image)
                                <img src="{{ asset('storage/'.$album->cover_image) }}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            @else
                                <div class="text-muted">No cover image</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Upload More Images</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Add more images to this gallery title.</small>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update Album
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Album Images ({{ $album->images->count() }})</h5>
            <a class="btn btn-light btn-sm" href="{{ route('frontend.gallery.show', $album->slug) }}" target="_blank">
                <i class="ti ti-external-link me-1"></i> View on Frontend
            </a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($album->images as $img)
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="border rounded p-2">
                            <img src="{{ asset('storage/'.$img->image) }}" class="img-fluid rounded" style="width:100%;height:120px;object-fit:cover;">
                            <div class="d-flex gap-1 mt-2">
                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteAlbumImage({{ $img->id }})">
                                    <i class="ti ti-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">No images added yet.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteAlbumImage(id) {
    if (!confirm('Delete this image?')) return;
    $.ajax({
        url: "{{ route('admin.galleries.images.destroy', 0) }}".replace('/0', '/' + id),
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            toastr.success(response.success);
            window.location.reload();
        },
        error: function() {
            toastr.error('Error deleting image');
        }
    });
}
</script>
@endpush
