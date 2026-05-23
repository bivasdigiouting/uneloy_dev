@extends('layouts.admin')

@section('title', 'Help & Support')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Help & Support</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Website Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Help & Support</li>
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

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Page Settings</h4>
        </div>
        <form action="{{ route('admin.website-help-support.update') }}" method="POST" id="helpSupportForm">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Page Title</label>
                        <input type="text" class="form-control @error('page_title') is-invalid @enderror" name="page_title" value="{{ old('page_title', $settings->page_title) }}">
                        @error('page_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Working Hours</label>
                        <input type="text" class="form-control @error('working_hours') is-invalid @enderror" name="working_hours" value="{{ old('working_hours', $settings->working_hours) }}" placeholder="Mon–Sat 10:00 AM – 06:00 PM">
                        @error('working_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Intro Text</label>
                        <textarea class="form-control @error('intro_text') is-invalid @enderror" name="intro_text" rows="2" maxlength="500">{{ old('intro_text', $settings->intro_text) }}</textarea>
                        @error('intro_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Max 500 characters.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Support Email</label>
                        <input type="email" class="form-control @error('support_email') is-invalid @enderror" name="support_email" value="{{ old('support_email', $settings->support_email) }}" placeholder="support@example.com">
                        @error('support_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Support Phone</label>
                        <input type="text" class="form-control @error('support_phone') is-invalid @enderror" name="support_phone" value="{{ old('support_phone', $settings->support_phone) }}" placeholder="+91 90000 00000">
                        @error('support_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control @error('support_whatsapp') is-invalid @enderror" name="support_whatsapp" value="{{ old('support_whatsapp', $settings->support_whatsapp) }}" placeholder="+91 90000 00000">
                        @error('support_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Live Chat URL</label>
                        <input type="text" class="form-control @error('live_chat_url') is-invalid @enderror" name="live_chat_url" value="{{ old('live_chat_url', $settings->live_chat_url) }}" placeholder="https://tawk.to/...">
                        @error('live_chat_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Support Address</label>
                        <input type="text" class="form-control @error('support_address') is-invalid @enderror" name="support_address" value="{{ old('support_address', $settings->support_address) }}">
                        @error('support_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Additional Info</label>
                        <textarea class="form-control @error('additional_info') is-invalid @enderror" id="additional_info" name="additional_info" rows="6">{{ old('additional_info', $settings->additional_info) }}</textarea>
                        @error('additional_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Update Help & Support
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#additional_info').summernote({
        height: 200,
        placeholder: 'Add any additional support information...',
        toolbar: [
            ['fontsize', ['fontsize']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endpush

