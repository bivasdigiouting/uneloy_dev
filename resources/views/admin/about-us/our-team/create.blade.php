@extends('layouts.admin')

@section('title', 'Add Team Member')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Team Member</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Website Modules</li>
                    <li class="breadcrumb-item">About Us</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.our-team.index') }}">Our Team</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="mb-2">
                <a href="{{ route('admin.our-team.index') }}" class="btn btn-secondary d-flex align-items-center"><i class="ti ti-arrow-left me-1"></i>Back to List</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.our-team.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('designation') is-invalid @enderror" id="designation" name="designation" value="{{ old('designation') }}" required>
                                @error('designation')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_no" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('contact_no') is-invalid @enderror" id="contact_no" name="contact_no" value="{{ old('contact_no') }}">
                                @error('contact_no')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <small class="text-muted">Recommended size: 300x300 pixels (JPG/PNG).</small>
                                @error('image')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3 border-bottom pb-2">Social Media Links</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="facebook_link" class="form-label">Facebook Profile URL</label>
                                <input type="url" class="form-control @error('facebook_link') is-invalid @enderror" id="facebook_link" name="facebook_link" value="{{ old('facebook_link') }}">
                                @error('facebook_link')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="twitter_link" class="form-label">Twitter Profile URL</label>
                                <input type="url" class="form-control @error('twitter_link') is-invalid @enderror" id="twitter_link" name="twitter_link" value="{{ old('twitter_link') }}">
                                @error('twitter_link')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="linkedin_link" class="form-label">LinkedIn Profile URL</label>
                                <input type="url" class="form-control @error('linkedin_link') is-invalid @enderror" id="linkedin_link" name="linkedin_link" value="{{ old('linkedin_link') }}">
                                @error('linkedin_link')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instagram_link" class="form-label">Instagram Profile URL</label>
                                <input type="url" class="form-control @error('instagram_link') is-invalid @enderror" id="instagram_link" name="instagram_link" value="{{ old('instagram_link') }}">
                                @error('instagram_link')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Save Team Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
