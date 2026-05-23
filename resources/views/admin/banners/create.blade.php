@extends('layouts.admin')

@section('title', 'Create Banner')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create Banner</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.banners.index') }}">Banner Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create Banner</h5>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Back to Banner Master
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="banner_type" class="form-label">Banner Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('banner_type') is-invalid @enderror" 
                                            id="banner_type" 
                                            name="banner_type" 
                                            required>
                                        <option value="">Select Banner Type</option>
                                        <option value="home_1" {{ old('banner_type') == 'home_1' ? 'selected' : '' }}>Home 1</option>
                                        <option value="home_2" {{ old('banner_type') == 'home_2' ? 'selected' : '' }}>Home 2</option>
                                        <option value="home_3" {{ old('banner_type') == 'home_3' ? 'selected' : '' }}>Home 3</option>
                                        <option value="my_order" {{ old('banner_type') == 'my_order' ? 'selected' : '' }}>My Order</option>
                                        <option value="deposit" {{ old('banner_type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                                        <option value="withdrawal" {{ old('banner_type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                                        <option value="rewards" {{ old('banner_type') == 'rewards' ? 'selected' : '' }}>Rewards</option>
                                    </select>
                                    @error('banner_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="link" class="form-label">Link (Optional)</label>
                                    <input type="url" 
                                           class="form-control @error('link') is-invalid @enderror" 
                                           id="link" 
                                           name="link" 
                                           value="{{ old('link') }}" 
                                           placeholder="https://example.com">
                                    <small class="form-text text-muted">
                                        Enter a valid URL (optional)
                                    </small>
                                    @error('link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('status') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="status" 
                                                   id="status_active" 
                                                   value="active" 
                                                   {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">
                                                Active
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('status') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="status" 
                                                   id="status_inactive" 
                                                   value="inactive" 
                                                   {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_inactive">
                                                Inactive
                                            </label>
                                        </div>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Banner Image</label>
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <small class="form-text text-muted">
                                        Supported formats: JPEG, PNG, JPG, GIF, SVG. Max size: 2MB
                                    </small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image Preview</label>
                                    <div class="border rounded p-3 text-center" style="min-height: 150px;">
                                        <img id="imagePreview" 
                                             src="#" 
                                             alt="Image Preview" 
                                             class="img-thumbnail d-none" 
                                             style="max-width: 200px; max-height: 120px; object-fit: cover;">
                                        <div id="noImageText" class="text-muted">
                                            <i class="ti ti-photo-off" style="font-size: 2rem;"></i>
                                            <br>No image selected
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check"></i> Create Banner
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
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const noImageText = document.getElementById('noImageText');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            noImageText.classList.add('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.classList.add('d-none');
        noImageText.classList.remove('d-none');
    }
}
</script>
@endpush