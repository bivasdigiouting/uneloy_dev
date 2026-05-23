@extends('layouts.admin')

@section('title', 'Edit GST Tax Rate')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit GST Tax Rate</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.gst-taxes.index') }}">GST Tax Rate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.gst-taxes.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to GST Tax Rate
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Tax Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.gst-taxes.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.gst-taxes.update', $tax->id) }}" method="POST" id="gstTaxEditForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_name" class="form-label">Tax Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tax_name') is-invalid @enderror" id="tax_name" name="tax_name" value="{{ old('tax_name', $tax->tax_name) }}" placeholder="e.g., GST 18%" required>
                                    @error('tax_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rate_percent" class="form-label">Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control @error('rate_percent') is-invalid @enderror" id="rate_percent" name="rate_percent" value="{{ old('rate_percent', $tax->rate_percent) }}" placeholder="e.g., 18" required>
                                    @error('rate_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $tax->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.gst-taxes.index') }}" class="btn btn-secondary"><i class="ti ti-x me-1"></i> Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Update GST Tax</button>
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
    $('#gstTaxEditForm').on('submit', function(e) {
        const name = $('#tax_name').val().trim();
        const rate = parseFloat($('#rate_percent').val());
        if (!name || isNaN(rate)) {
            e.preventDefault();
            alert('Please enter valid Tax Name and Rate.');
        }
    });
});
</script>
@endpush