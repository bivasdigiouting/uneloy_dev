@extends('layouts.admin')

@section('title', 'Edit Affiliate')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Affiliate</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.affiliates.index') }}">Affiliate Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.affiliates.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Affiliate</h5>
                    <a href="{{ route('admin.affiliates.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Back to Affiliate Master
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.affiliates.update', $affiliate->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_name" class="form-label">Affiliate Service Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('service_name') is-invalid @enderror" 
                                           id="service_name" 
                                           name="service_name" 
                                           value="{{ old('service_name', $affiliate->service_name) }}" 
                                           placeholder="Enter affiliate service name"
                                           required>
                                    @error('service_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
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
                                                   {{ old('status', $affiliate->status) == 'active' ? 'checked' : '' }}>
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
                                                   {{ old('status', $affiliate->status) == 'inactive' ? 'checked' : '' }}>
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
                                    <label for="icon" class="form-label">Upload Icon</label>
                                    <input type="file" 
                                           class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" 
                                           name="icon" 
                                           accept="image/*"
                                           onchange="previewIcon(this)">
                                    <small class="form-text text-muted">
                                        Supported formats: JPEG, PNG, JPG, GIF, SVG. Max size: 2MB. Leave empty to keep current icon.
                                    </small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Icon Preview</label>
                                    <div class="border rounded p-3 text-center" style="min-height: 120px;">
                                        @if($affiliate->icon)
                                            <img id="iconPreview" 
                                                 src="{{ $affiliate->icon_url }}" 
                                                 alt="Current Icon" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                            <div id="noIconText" class="text-muted d-none">
                                                <i class="ti ti-photo-off" style="font-size: 2rem;"></i>
                                                <br>No icon selected
                                            </div>
                                        @else
                                            <img id="iconPreview" 
                                                 src="#" 
                                                 alt="Icon Preview" 
                                                 class="img-thumbnail d-none" 
                                                 style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                            <div id="noIconText" class="text-muted">
                                                <i class="ti ti-photo-off" style="font-size: 2rem;"></i>
                                                <br>No icon uploaded
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.affiliates.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check"></i> Update Affiliate
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
function previewIcon(input) {
    const preview = document.getElementById('iconPreview');
    const noIconText = document.getElementById('noIconText');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            noIconText.classList.add('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        // If no file selected, show current icon or no icon text
        @if($affiliate->icon)
            preview.src = "{{ $affiliate->getIconUrl() }}";
            preview.classList.remove('d-none');
            noIconText.classList.add('d-none');
        @else
            preview.src = '#';
            preview.classList.add('d-none');
            noIconText.classList.remove('d-none');
        @endif
    }
}
</script>
@endpush