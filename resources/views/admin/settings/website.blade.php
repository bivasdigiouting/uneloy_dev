@extends('layouts.admin')

@section('title', 'Website Settings')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Website Settings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Website Settings</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Website Configuration</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.website.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-section">Basic Information</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Site Name <span class="text-danger">*</span></label>
                                        <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" 
                                               value="{{ old('site_name', $settings->site_name) }}" placeholder="Enter site name">
                                        @error('site_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Site Title</label>
                                        <input type="text" name="site_title" class="form-control @error('site_title') is-invalid @enderror" 
                                               value="{{ old('site_title', $settings->site_title) }}" placeholder="Enter site title">
                                        @error('site_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Site Description</label>
                                        <textarea name="site_description" class="form-control @error('site_description') is-invalid @enderror" 
                                                  rows="3" placeholder="Enter site description">{{ old('site_description', $settings->site_description) }}</textarea>
                                        @error('site_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Logo & Favicon -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-section">Logo & Favicon</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Logo</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if($logoUrl)
                                                <img class="inline-block" src="{{ $logoUrl }}" alt="Logo" id="logo-preview" style="max-height: 100px;">
                                                <div class="fileupload btn">
                                                    <span class="btn-text">Change</span>
                                                    <input class="upload" type="file" name="logo" accept="image/*" onchange="previewImage(this, 'logo-preview')">
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeLogo()" style="margin-left: 10px;">
                                                    <i class="fa fa-trash"></i> Remove
                                                </button>
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Logo" id="logo-preview" style="max-height: 100px; display: none;">
                                                <div class="fileupload btn">
                                                    <span class="btn-text">Upload Logo</span>
                                                    <input class="upload" type="file" name="logo" accept="image/*" onchange="previewImage(this, 'logo-preview')">
                                                </div>
                                            @endif
                                        </div>
                                        @error('logo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Recommended size: 200x50px. Max size: 2MB</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Favicon</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if($faviconUrl)
                                                <img class="inline-block" src="{{ $faviconUrl }}" alt="Favicon" id="favicon-preview" style="max-height: 50px;">
                                                <div class="fileupload btn">
                                                    <span class="btn-text">Change</span>
                                                    <input class="upload" type="file" name="favicon" accept="image/*" onchange="previewImage(this, 'favicon-preview')">
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeFavicon()" style="margin-left: 10px;">
                                                    <i class="fa fa-trash"></i> Remove
                                                </button>
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Favicon" id="favicon-preview" style="max-height: 50px; display: none;">
                                                <div class="fileupload btn">
                                                    <span class="btn-text">Upload Favicon</span>
                                                    <input class="upload" type="file" name="favicon" accept="image/*" onchange="previewImage(this, 'favicon-preview')">
                                                </div>
                                            @endif
                                        </div>
                                        @error('favicon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Recommended size: 32x32px. Max size: 1MB</small>
                                    </div>
                                </div>
                            </div>

                            <!-- App Logos & Favicons -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-section">App Logos & Favicons</h5>
                                </div>

                                <!-- Member App -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Member App Logo</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if(isset($memberAppLogoUrl) && $memberAppLogoUrl)
                                                <img class="inline-block" src="{{ $memberAppLogoUrl }}" alt="Member App Logo" id="member-app-logo-preview" style="max-height: 100px;">
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Member App Logo" id="member-app-logo-preview" style="max-height: 100px; display: none;">
                                            @endif
                                            <div class="fileupload btn">
                                                <span class="btn-text">Upload</span>
                                                <input class="upload" type="file" name="member_app_logo" accept="image/*" onchange="previewImage(this, 'member-app-logo-preview')">
                                            </div>
                                        </div>
                                        @error('member_app_logo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Member App Favicon</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if(isset($memberAppFaviconUrl) && $memberAppFaviconUrl)
                                                <img class="inline-block" src="{{ $memberAppFaviconUrl }}" alt="Member App Favicon" id="member-app-favicon-preview" style="max-height: 50px;">
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Member App Favicon" id="member-app-favicon-preview" style="max-height: 50px; display: none;">
                                            @endif
                                            <div class="fileupload btn">
                                                <span class="btn-text">Upload</span>
                                                <input class="upload" type="file" name="member_app_favicon" accept="image/*" onchange="previewImage(this, 'member-app-favicon-preview')">
                                            </div>
                                        </div>
                                        @error('member_app_favicon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Ecardseva App -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ecardseva Logo</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if(isset($ecardsevaLogoUrl) && $ecardsevaLogoUrl)
                                                <img class="inline-block" src="{{ $ecardsevaLogoUrl }}" alt="Ecardseva Logo" id="ecardseva-logo-preview" style="max-height: 100px;">
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Ecardseva Logo" id="ecardseva-logo-preview" style="max-height: 100px; display: none;">
                                            @endif
                                            <div class="fileupload btn">
                                                <span class="btn-text">Upload</span>
                                                <input class="upload" type="file" name="ecardseva_logo" accept="image/*" onchange="previewImage(this, 'ecardseva-logo-preview')">
                                            </div>
                                        </div>
                                        @error('ecardseva_logo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ecardseva Favicon</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if(isset($ecardsevaFaviconUrl) && $ecardsevaFaviconUrl)
                                                <img class="inline-block" src="{{ $ecardsevaFaviconUrl }}" alt="Ecardseva Favicon" id="ecardseva-favicon-preview" style="max-height: 50px;">
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Ecardseva Favicon" id="ecardseva-favicon-preview" style="max-height: 50px; display: none;">
                                            @endif
                                            <div class="fileupload btn">
                                                <span class="btn-text">Upload</span>
                                                <input class="upload" type="file" name="ecardseva_favicon" accept="image/*" onchange="previewImage(this, 'ecardseva-favicon-preview')">
                                            </div>
                                        </div>
                                        @error('ecardseva_favicon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Estore App -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Estore App Logo</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if(isset($estoreAppLogoUrl) && $estoreAppLogoUrl)
                                                <img class="inline-block" src="{{ $estoreAppLogoUrl }}" alt="Estore App Logo" id="estore-app-logo-preview" style="max-height: 100px;">
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Estore App Logo" id="estore-app-logo-preview" style="max-height: 100px; display: none;">
                                            @endif
                                            <div class="fileupload btn">
                                                <span class="btn-text">Upload</span>
                                                <input class="upload" type="file" name="estore_app_logo" accept="image/*" onchange="previewImage(this, 'estore-app-logo-preview')">
                                            </div>
                                        </div>
                                        @error('estore_app_logo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Estore App Favicon</label>
                                        <div class="profile-img-wrap edit-img">
                                            @if(isset($estoreAppFaviconUrl) && $estoreAppFaviconUrl)
                                                <img class="inline-block" src="{{ $estoreAppFaviconUrl }}" alt="Estore App Favicon" id="estore-app-favicon-preview" style="max-height: 50px;">
                                            @else
                                                <img class="inline-block" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Estore App Favicon" id="estore-app-favicon-preview" style="max-height: 50px; display: none;">
                                            @endif
                                            <div class="fileupload btn">
                                                <span class="btn-text">Upload</span>
                                                <input class="upload" type="file" name="estore_app_favicon" accept="image/*" onchange="previewImage(this, 'estore-app-favicon-preview')">
                                            </div>
                                        </div>
                                        @error('estore_app_favicon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-section">Contact Information</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Admin Email</label>
                                        <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror" 
                                               value="{{ old('admin_email', $settings->admin_email) }}" placeholder="Enter admin email">
                                        @error('admin_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Email</label>
                                        <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" 
                                               value="{{ old('contact_email', $settings->contact_email) }}" placeholder="Enter contact email">
                                        @error('contact_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Phone</label>
                                        <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" 
                                               value="{{ old('contact_phone', $settings->contact_phone) }}" placeholder="Enter contact phone">
                                        @error('contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Timezone</label>
                                        <select name="timezone" class="form-control @error('timezone') is-invalid @enderror">
                                            <option value="UTC" {{ old('timezone', $settings->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                            <option value="America/New_York" {{ old('timezone', $settings->timezone) == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                            <option value="America/Chicago" {{ old('timezone', $settings->timezone) == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                            <option value="America/Denver" {{ old('timezone', $settings->timezone) == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                            <option value="America/Los_Angeles" {{ old('timezone', $settings->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                            <option value="Europe/London" {{ old('timezone', $settings->timezone) == 'Europe/London' ? 'selected' : '' }}>London</option>
                                            <option value="Europe/Paris" {{ old('timezone', $settings->timezone) == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                            <option value="Asia/Tokyo" {{ old('timezone', $settings->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                            <option value="Asia/Shanghai" {{ old('timezone', $settings->timezone) == 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai</option>
                                            <option value="Asia/Kolkata" {{ old('timezone', $settings->timezone) == 'Asia/Kolkata' ? 'selected' : '' }}>India</option>
                                        </select>
                                        @error('timezone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Contact Address</label>
                                        <textarea name="contact_address" class="form-control @error('contact_address') is-invalid @enderror" 
                                                  rows="3" placeholder="Enter contact address">{{ old('contact_address', $settings->contact_address) }}</textarea>
                                        @error('contact_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media Links -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-section">Social Media Links</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Facebook URL</label>
                                        <input type="url" name="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror" 
                                               value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://facebook.com/yourpage">
                                        @error('facebook_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Twitter URL</label>
                                        <input type="url" name="twitter_url" class="form-control @error('twitter_url') is-invalid @enderror" 
                                               value="{{ old('twitter_url', $settings->twitter_url) }}" placeholder="https://twitter.com/yourhandle">
                                        @error('twitter_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Instagram URL</label>
                                        <input type="url" name="instagram_url" class="form-control @error('instagram_url') is-invalid @enderror" 
                                               value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://instagram.com/yourhandle">
                                        @error('instagram_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>LinkedIn URL</label>
                                        <input type="url" name="linkedin_url" class="form-control @error('linkedin_url') is-invalid @enderror" 
                                               value="{{ old('linkedin_url', $settings->linkedin_url) }}" placeholder="https://linkedin.com/company/yourcompany">
                                        @error('linkedin_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>YouTube URL</label>
                                        <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                               value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://youtube.com/channel/yourchannel">
                                        @error('youtube_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select name="currency" class="form-control @error('currency') is-invalid @enderror">
                                            <option value="USD" {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                            <option value="EUR" {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                            <option value="GBP" {{ old('currency', $settings->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                            <option value="JPY" {{ old('currency', $settings->currency) == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                                            <option value="INR" {{ old('currency', $settings->currency) == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                                        </select>
                                        @error('currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Footer & Maintenance -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-section">Footer & Maintenance</h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Footer Text</label>
                                        <textarea name="footer_text" class="form-control @error('footer_text') is-invalid @enderror" 
                                                  rows="3" placeholder="Enter footer text">{{ old('footer_text', $settings->footer_text) }}</textarea>
                                        @error('footer_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" name="maintenance_mode" class="form-check-input" id="maintenance_mode" 
                                                   value="1" {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="maintenance_mode">
                                                Enable Maintenance Mode
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">When enabled, only admin users can access the website</small>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update Settings</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    if (confirm('Are you sure you want to remove the logo?')) {
        fetch('{{ route("admin.settings.logo.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the logo.');
        });
    }
}

function removeFavicon() {
    if (confirm('Are you sure you want to remove the favicon?')) {
        fetch('{{ route("admin.settings.favicon.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the favicon.');
        });
    }
}
</script>
@endsection