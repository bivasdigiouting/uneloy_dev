@extends('layouts.admin')

@section('title', 'Profile Update')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Profile Update</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Profile Update</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.registrations.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Registrations
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Section -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search User</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.search') }}" method="POST" id="searchForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="search_id" class="form-label">Search by ID/Aadhaar/Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('search_id') is-invalid @enderror" id="search_id" name="search_id" value="{{ old('search_id') }}" placeholder="Enter User ID, Aadhaar Number, or Mobile Number" required>
                                    @error('search_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-search me-1"></i>Search User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($registration))
    <!-- Profile Update Form -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.profile.update.save', $registration->id) }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                @csrf
                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <!-- Section 1: Business Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Section 1: Business Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name', $registration->business_name) }}" placeholder="Enter business name" required>
                                            @error('business_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_mobile" class="form-label">Mobile No.</label>
                                            <input type="text" class="form-control @error('business_mobile') is-invalid @enderror" id="business_mobile" name="business_mobile" value="{{ old('business_mobile', $registration->business_mobile) }}" placeholder="Enter mobile number">
                                            @error('business_mobile')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_whatsapp" class="form-label">WhatsApp No.</label>
                                            <input type="text" class="form-control @error('business_whatsapp') is-invalid @enderror" id="business_whatsapp" name="business_whatsapp" value="{{ old('business_whatsapp', $registration->business_whatsapp) }}" placeholder="Enter WhatsApp number">
                                            @error('business_whatsapp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_gmail" class="form-label">Gmail ID</label>
                                            <input type="email" class="form-control @error('business_gmail') is-invalid @enderror" id="business_gmail" name="business_gmail" value="{{ old('business_gmail', $registration->business_gmail) }}" placeholder="Enter Gmail ID">
                                            @error('business_gmail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="business_address" class="form-label">Business Full Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('business_address') is-invalid @enderror" id="business_address" name="business_address" rows="3" placeholder="Enter business address" required>{{ old('business_address', $registration->business_address) }}</textarea>
                                            @error('business_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_gst" class="form-label">Business GST No.</label>
                                            <input type="text" class="form-control @error('business_gst') is-invalid @enderror" id="business_gst" name="business_gst" value="{{ old('business_gst', $registration->business_gst) }}" placeholder="Enter GST number">
                                            @error('business_gst')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_upi" class="form-label">UPI Address</label>
                                            <input type="text" class="form-control @error('business_upi') is-invalid @enderror" id="business_upi" name="business_upi" value="{{ old('business_upi', $registration->business_upi) }}" placeholder="Enter UPI address">
                                            @error('business_upi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="business_location_map" class="form-label">Business Location (Map) <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('business_location_map') is-invalid @enderror" id="business_location_map" name="business_location_map" rows="2" placeholder="Enter map coordinates or location details" required>{{ old('business_location_map', $registration->business_location_map) }}</textarea>
                                            @error('business_location_map')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Personal Details -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Section 2: Personal Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $registration->first_name) }}" placeholder="Enter first name" required>
                                                    @error('first_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="middle_name" class="form-label">Middle Name</label>
                                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $registration->middle_name) }}" placeholder="Enter middle name">
                                                    @error('middle_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $registration->last_name) }}" placeholder="Enter last name" required>
                                                    @error('last_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="blood_group" class="form-label">Blood Group</label>
                                                    <select class="form-control @error('blood_group') is-invalid @enderror" id="blood_group" name="blood_group">
                                                        <option value="">Select Blood Group</option>
                                                        <option value="A+" {{ old('blood_group', $registration->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                                        <option value="A-" {{ old('blood_group', $registration->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                                        <option value="B+" {{ old('blood_group', $registration->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                                        <option value="B-" {{ old('blood_group', $registration->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                                        <option value="AB+" {{ old('blood_group', $registration->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                        <option value="AB-" {{ old('blood_group', $registration->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                        <option value="O+" {{ old('blood_group', $registration->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                                        <option value="O-" {{ old('blood_group', $registration->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                                    </select>
                                                    @error('blood_group')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $registration->date_of_birth) }}" required>
                                                    @error('date_of_birth')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="male" {{ old('gender', $registration->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ old('gender', $registration->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                        <option value="other" {{ old('gender', $registration->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error('gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="marital_status" class="form-label">Marital Status</label>
                                                    <select class="form-control @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                                        <option value="">Select Marital Status</option>
                                                        <option value="single" {{ old('marital_status', $registration->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                                        <option value="married" {{ old('marital_status', $registration->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                                        <option value="divorced" {{ old('marital_status', $registration->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                                        <option value="widowed" {{ old('marital_status', $registration->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                    </select>
                                                    @error('marital_status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="father_name" class="form-label">Father's Name</label>
                                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name', $registration->father_name) }}" placeholder="Enter father's name">
                                                    @error('father_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Contact Details -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Section 3: Contact Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="current_address" class="form-label">Current Address <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('current_address') is-invalid @enderror" id="current_address" name="current_address" rows="3" placeholder="Enter current address" required>{{ old('current_address', $registration->current_address) }}</textarea>
                                                    @error('current_address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', $registration->nationality) }}" placeholder="Enter nationality" required>
                                                    @error('nationality')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $registration->state) }}" placeholder="Enter state" required>
                                                    @error('state')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district', $registration->district) }}" placeholder="Enter district" required>
                                                    @error('district')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $registration->city) }}" placeholder="Enter city" required>
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="pin_code" class="form-label">Pin Code <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('pin_code') is-invalid @enderror" id="pin_code" name="pin_code" value="{{ old('pin_code', $registration->pin_code) }}" placeholder="Enter pin code" required>
                                                    @error('pin_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="mobile_no" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $registration->mobile_no) }}" placeholder="Enter mobile number" required>
                                                    @error('mobile_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="live_location_map" class="form-label">Live Location (Map) <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('live_location_map') is-invalid @enderror" id="live_location_map" name="live_location_map" rows="2" placeholder="Enter live location map coordinates" required>{{ old('live_location_map', $registration->live_location_map) }}</textarea>
                                                    @error('live_location_map')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="aadhaar_no" class="form-label">Aadhaar Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('aadhaar_no') is-invalid @enderror" id="aadhaar_no" name="aadhaar_no" value="{{ old('aadhaar_no', $registration->aadhaar_no) }}" placeholder="Enter Aadhaar number" required>
                                                    @error('aadhaar_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                    <a href="{{ route('admin.profile.update') }}" class="btn btn-secondary">
                                        <i class="fas fa-search me-2"></i>Search Another User
                                    </a>
                                </div>
                                
                                <hr class="my-3">
                                
                                <div class="text-muted small">
                                    <h6 class="fw-bold">User Information</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-user me-2"></i>User ID: {{ $registration->id }}</li>
                                        <li><i class="fas fa-id-card me-2"></i>Aadhaar: {{ $registration->aadhaar_no }}</li>
                                        <li><i class="fas fa-phone me-2"></i>Mobile: {{ $registration->mobile_no }}</li>
                                        <li><i class="fas fa-calendar me-2"></i>Registered: {{ $registration->created_at->format('d M Y') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation for profile update
        $('#profileUpdateForm').on('submit', function(e) {
            const businessName = $('#business_name').val().trim();
            const businessAddress = $('#business_address').val().trim();
            const firstName = $('#first_name').val().trim();
            const currentAddress = $('#current_address').val().trim();
            const nationality = $('#nationality').val();
            const state = $('#state').val();
            const district = $('#district').val();
            const city = $('#city').val();
            const pinCode = $('#pin_code').val().trim();
            const mobileNo = $('#mobile_no').val().trim();
            const dateOfBirth = $('#date_of_birth').val();
            const businessLocationMap = $('#business_location_map').val().trim();
            const liveLocationMap = $('#live_location_map').val().trim();
            const aadhaarNo = $('#aadhaar_no').val().trim();
            
            let hasError = false;
            
            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            
            // Validate required fields
            if (!businessName) {
                $('#business_name').addClass('is-invalid');
                hasError = true;
            }
            
            if (!businessAddress) {
                $('#business_address').addClass('is-invalid');
                hasError = true;
            }
            
            if (!firstName) {
                $('#first_name').addClass('is-invalid');
                hasError = true;
            }
            
            if (!currentAddress) {
                $('#current_address').addClass('is-invalid');
                hasError = true;
            }
            
            if (!nationality) {
                $('#nationality').addClass('is-invalid');
                hasError = true;
            }
            
            if (!state) {
                $('#state').addClass('is-invalid');
                hasError = true;
            }
            
            if (!district) {
                $('#district').addClass('is-invalid');
                hasError = true;
            }
            
            if (!city) {
                $('#city').addClass('is-invalid');
                hasError = true;
            }
            
            if (!pinCode) {
                $('#pin_code').addClass('is-invalid');
                hasError = true;
            }
            
            if (!mobileNo) {
                $('#mobile_no').addClass('is-invalid');
                hasError = true;
            }
            
            if (!dateOfBirth) {
                $('#date_of_birth').addClass('is-invalid');
                hasError = true;
            }
            
            if (!businessLocationMap) {
                $('#business_location_map').addClass('is-invalid');
                hasError = true;
            }
            
            if (!liveLocationMap) {
                $('#live_location_map').addClass('is-invalid');
                hasError = true;
            }
            
            if (!aadhaarNo) {
                $('#aadhaar_no').addClass('is-invalid');
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault();
                // Scroll to first error
                const firstError = $('.is-invalid').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                    firstError.focus();
                }
                return false;
            }
        });
    });
</script>
@endpush