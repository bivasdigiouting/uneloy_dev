@extends('layouts.admin')

@section('title', 'Create Benefit')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create Benefit</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.benefits.index') }}">Benefits Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.benefits.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Benefit Details</h4></div>
                <div class="card-body">
                    <form action="{{ route('admin.benefits.store') }}" method="POST" enctype="multipart/form-data" id="benefitForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="benefit_name" class="form-label">Benefit Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="benefit_name" name="benefit_name" value="{{ old('benefit_name') }}" placeholder="Enter benefit name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icon</label>
                                    <input type="file" class="form-control" id="icon" name="icon" accept="image/*">
                                    <div class="mt-2">
                                        <img id="iconPreview" src="#" alt="Icon Preview" class="img-thumbnail d-none" style="width:80px;height:80px;object-fit:cover;" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="schema_type" class="form-label">Schema Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="schema_type" name="schema_type" required>
                                        <option value="">Select Type</option>
                                        <option value="years" {{ old('schema_type') == 'years' ? 'selected' : '' }}>Years</option>
                                        <option value="purchase" {{ old('schema_type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="schema_type_name" class="form-label">Schema Type Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="schema_type_name" name="schema_type_name" value="{{ old('schema_type_name') }}" placeholder="e.g., 5 Years or Purchase Gold" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Optional remarks">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="activeYes" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activeYes">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="activeNo" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activeNo">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save</button>
                            <a href="{{ route('admin.benefits.index') }}" class="btn btn-secondary">Cancel</a>
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
document.getElementById('icon').addEventListener('change', function(event) {
    const [file] = this.files;
    const preview = document.getElementById('iconPreview');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    } else {
        preview.src = '#';
        preview.classList.add('d-none');
    }
});
</script>
@endpush