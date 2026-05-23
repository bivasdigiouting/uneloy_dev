@extends('layouts.admin')

@section('title', 'New Registration')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">New Registration</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.registrations.index') }}">Registrations</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New Registration</li>
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

    <!-- Create Registration Form -->
     <div class="row">
         <div class="col-12">
             <form action="{{ route('admin.registrations.store') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                 @csrf
                 <div class="row">
                     <!-- Main Content -->
                     <div class="col-lg-8">
                         <!-- Department and Business Category Selection -->
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="card">
                                     <div class="card-header">
                                         <h4 class="card-title mb-0">Section 1: Official Details</h4>
                                     </div>
                                     <div class="card-body">
                                         <div class="row">
                                             <div class="col-md-6">
                                                 <div class="mb-3">
                                                     <label for="department_level" class="form-label">Department Level <span class="text-danger">*</span></label>
                                                     <select class="form-control @error('department_level') is-invalid @enderror" id="department_level" name="department_level" required>
                                                         <option value="">Select Department Level</option>
                                                         @foreach($departmentLevels as $value => $label)
                                                             <option value="{{ $value }}" {{ old('department_level') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                         @endforeach
                                                     </select>
                                                     @error('department_level')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                 <div class="mb-3" id="security_amount_container" style="display:none;">
                                                     <label for="security_amount_display" class="form-label">Security Amount</label>
                                                     <input type="text" class="form-control" id="security_amount_display" value="{{ old('security_amount_display', '0.00') }}" readonly>
                                                 </div>
                                             </div>
                                             <!-- Replace Business Category with Aadhaar Number -->
                                             <div class="col-md-6">
                                                 <div class="mb-3">
                                                     <label for="aadhaar_no" class="form-label">Aadhaar Number <span class="text-danger">*</span></label>
                                                     <input type="text" class="form-control @error('aadhaar_no') is-invalid @enderror" id="aadhaar_no" name="aadhaar_no" value="{{ old('aadhaar_no') }}" placeholder="Enter 12-digit Aadhaar number" required pattern="\d{12}" inputmode="numeric" maxlength="12">
                                                     @error('aadhaar_no')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                                             </div>
                                             <!-- OTP Yes/No dropdown -->
                                             <div class="col-md-6">
                                                 <div class="mb-3">
                                                     <label for="otp_select" class="form-label">OTP</label>
                                                     <select class="form-control" id="otp_select" name="otp_required">
                                                         <option value="0" {{ old('otp_required') == '0' ? 'selected' : '' }}>No</option>
                                                         <option value="1" {{ old('otp_required') == '1' ? 'selected' : '' }}>Yes</option>
                                                     </select>
                                                 </div>
                                             </div>
                                             <!-- Conditional OTP code input with Verify button -->
                                             <div class="col-md-6" id="otp_code_group" style="display:none;">
                                                 <div class="mb-3">
                                                     <label for="otp_code" class="form-label">OTP Code</label>
                                                     <div class="input-group">
                                                         <input type="text" class="form-control @error('otp_code') is-invalid @enderror" id="otp_code" name="otp_code" value="{{ old('otp_code') }}" placeholder="Enter OTP code" maxlength="6" pattern="\d{4,6}" inputmode="numeric">
                                                         <button type="button" class="btn btn-outline-primary" id="otp_verify_btn">Verify</button>
                                                     </div>
                                                     <div id="otp_verify_status" class="small mt-1 text-muted"></div>
                                                     @error('otp_code')
                                                         <div class="invalid-feedback">{{ $message }}</div>
                                                     @enderror
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

            <!-- Section 2: Personal Details -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Section 2: Personal Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                                        @error('middle_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name" required>
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
                                            <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                                        </select>
                                        @error('blood_group')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
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
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
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
                                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                        @error('marital_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="father_name" class="form-label">Father's Name</label>
                                        <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="Enter father's name">
                                        @error('father_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mother_name" class="form-label">Mother's Name</label>
                                        <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="Enter mother's name">
                                        @error('mother_name')
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Section 3: Contact Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="current_address" class="form-label">Current Address <span class="text-danger">*</span></label>
                                     <textarea class="form-control @error('current_address') is-invalid @enderror" id="current_address" name="current_address" rows="3" placeholder="Enter current address" required>{{ old('current_address') }}</textarea>
                                     @error('current_address')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="mb-3">
                                     <label for="permanent_address" class="form-label">Permanent Address <span class="text-danger">*</span></label>
                                     <textarea class="form-control @error('permanent_address') is-invalid @enderror" id="permanent_address" name="permanent_address" rows="3" placeholder="Enter permanent address" required>{{ old('permanent_address') }}</textarea>
                                     @error('permanent_address')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', 'Indian') }}" placeholder="Enter nationality" required>
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                     <select class="form-control @error('state') is-invalid @enderror" id="state" name="state" required>
                                         <option value="">Select State</option>
                                         @foreach($states as $state)
                                             <option value="{{ $state->state_name }}" data-id="{{ $state->id }}" {{ old('state') == $state->state_name ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                         @endforeach
                                     </select>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                                        <select class="form-control @error('district') is-invalid @enderror" id="district" name="district" required>
                                            <option value="">Select District</option>
                                        </select>
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                        <select class="form-control @error('city') is-invalid @enderror" id="city" name="city" required>
                                            <option value="">Select City</option>
                                        </select>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="city_other_group" style="display:none;">
                                    <div class="mb-3">
                                        <label for="city_other" class="form-label">Other City Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('city_other') is-invalid @enderror" id="city_other" name="city_other" value="{{ old('city_other') }}" placeholder="Enter city name">
                                        @error('city_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="area" class="form-label">Area <span class="text-danger">*</span></label>
                                        <select class="form-control " id="area" name="area" required>
                                            <option value="">Select Area</option>
                                            <option value="Village_area">Village Area</option>
                                            <option value="Municipality_area">Municipality Area</option>
                                        </select>
                                        @error('area')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="panchayat" class="form-label">Panchayat <span class="text-danger">*</span></label>
                                        <select class="form-control " id="panchayat" name="panchayat" required>
                                            <option value="">Select Panchayat</option>
                                            
                                        </select>
                                        @error('panchayat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="panchayat_other_group" style="display:none;">
                                    <div class="mb-3">
                                        <label for="panchayat_other" class="form-label">Other Panchayat Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('panchayat_other') is-invalid @enderror" id="panchayat_other" name="panchayat_other" value="{{ old('panchayat_other') }}" placeholder="Enter panchayat name">
                                        @error('panchayat_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="municipality" class="form-label">Muncipility <span class="text-danger">*</span></label>
                                        <select class="form-control" id="municipality" name="municipality" required>
                                            <option value="">Select Muncipility</option>
                                            
                                        </select>
                                        @error('municipality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="municipality_other_group" style="display:none;">
                                    <div class="mb-3">
                                        <label for="municipality_other" class="form-label">Other Municipality Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('municipality_other') is-invalid @enderror" id="municipality_other" name="municipality_other" value="{{ old('municipality_other') }}" placeholder="Enter municipality name">
                                        @error('municipality_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="village_name" class="form-label">Village Name <span class="text-danger">*</span></label>
                                        <select class="form-control " id="village_name" name="village_name" required>
                                            <option value="">Select Village Name</option>
                                            
                                        </select>
                                        @error('village_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="village_other_group" style="display:none;">
                                    <div class="mb-3">
                                        <label for="village_other" class="form-label">Other Village Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('village_other') is-invalid @enderror" id="village_other" name="village_other" value="{{ old('village_other') }}" placeholder="Enter village name">
                                        @error('village_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ward_no" class="form-label">Ward No <span class="text-danger">*</span></label>
                                        <select class="form-control " id="ward_no" name="ward_no" required>
                                            <option value="">Select Ward No</option>
                                            
                                        </select>
                                        @error('ward_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="ward_other_group" style="display:none;">
                                    <div class="mb-3">
                                        <label for="ward_other" class="form-label">Other Ward No & Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('ward_other') is-invalid @enderror" id="ward_other" name="ward_other" value="{{ old('ward_other') }}" placeholder="Enter ward no & name">
                                        @error('ward_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pin_code" class="form-label">Pincode <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('pin_code') is-invalid @enderror" id="pin_code" name="pin_code" value="{{ old('pin_code') }}" placeholder="Enter pincode" required>
                                        @error('pin_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile_no" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                     <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Enter mobile number" required>
                                     @error('mobile_no')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone_no" class="form-label">Alternate Phone / WhatsApp</label>
                                     <input type="text" class="form-control @error('phone_no') is-invalid @enderror" id="phone_no" name="phone_no" value="{{ old('phone_no') }}" placeholder="Enter alternate phone or WhatsApp">
                                     @error('phone_no')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_id" class="form-label">Email Address</label>
                                     <input type="email" class="form-control @error('email_id') is-invalid @enderror" id="email_id" name="email_id" value="{{ old('email_id') }}" placeholder="Enter email address">
                                     @error('email_id')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gmail_id" class="form-label">Gmail ID</label>
                                     <input type="email" class="form-control @error('gmail_id') is-invalid @enderror" id="gmail_id" name="gmail_id" value="{{ old('gmail_id') }}" placeholder="Enter Gmail ID">
                                     @error('gmail_id')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="mb-3">
                                     <label for="live_location_map" class="form-label">Live Location Map <span class="text-danger">*</span></label>
                                     <input type="text" class="form-control @error('live_location_map') is-invalid @enderror" id="live_location_map" name="live_location_map" value="{{ old('live_location_map') }}" placeholder="Google Maps link" required>
                                     @error('live_location_map')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Section 5: Qualification & Experience Details -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Section 5: Qualification & Experience Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_qualification" class="form-label">Last Qualification</label>
                                        <input type="text" class="form-control @error('last_qualification') is-invalid @enderror" id="last_qualification" name="last_qualification" value="{{ old('last_qualification') }}" placeholder="Enter last qualification">
                                        @error('last_qualification')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="work_type" class="form-label">Work Type</label>
                                        <select class="form-control @error('work_type') is-invalid @enderror" id="work_type" name="work_type">
                                            <option value="">Select Work Type</option>
                                            <option value="full_time" {{ old('work_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                            <option value="part_time" {{ old('work_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                            <option value="freelance" {{ old('work_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                            <option value="contract" {{ old('work_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                        </select>
                                        @error('work_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="work_experience" class="form-label">Work Experience</label>
                                        <textarea class="form-control @error('work_experience') is-invalid @enderror" id="work_experience" name="work_experience" rows="3" placeholder="Enter work experience details">{{ old('work_experience') }}</textarea>
                                        @error('work_experience')
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

                     <!-- Actions Sidebar -->
                     <div class="col-lg-4">
                         <div class="card">
                             <div class="card-header">
                                 <h5 class="card-title mb-0">Actions</h5>
                             </div>
                             <div class="card-body">
                                 <div class="d-grid gap-2">
                                     <button type="submit" class="btn btn-primary">
                                         <i class="fas fa-save me-2"></i>Submit Registration
                                     </button>
                                     <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">
                                         <i class="fas fa-times me-2"></i>Cancel
                                     </a>
                                 </div>
                                 
                                 <hr class="my-3">
                                 
                                 <div class="text-muted small">
                                     <h6 class="fw-bold">Registration Information</h6>
                                     <ul class="list-unstyled mb-0">
                                         <li><i class="fas fa-info-circle me-2"></i>Fill all required fields marked with *</li>
                                         <li><i class="fas fa-user me-2"></i>Personal information is required</li>
                                         <li><i class="fas fa-university me-2"></i>Bank details for transactions</li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </form>
         </div>
     </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation
        $(document).ready(function() {
            // Form validation
            
            // Set DOB min/max: between 60 years ago and 5 years ago
            const today = new Date();
            const minDobDate = new Date(today.getFullYear() - 60, today.getMonth(), today.getDate());
            const maxDobDate = new Date(today.getFullYear() - 5, today.getMonth(), today.getDate());
            $('#date_of_birth')
                .attr('min', minDobDate.toISOString().slice(0,10))
                .attr('max', maxDobDate.toISOString().slice(0,10));
        
            $('#registrationForm').on('submit', function(e) {
                const firstName = $('#first_name').val().trim();
                const currentAddress = $('#current_address').val().trim();
                const nationality = $('#nationality').val();
                const state = $('#state').val();
                const district = $('#district').val();
                const city = $('#city').val();
                const pinCode = $('#pin_code').val().trim();
                const mobileNo = $('#mobile_no').val().trim();
                const dateOfBirth = $('#date_of_birth').val();
                const liveLocationMap = $('#live_location_map').val().trim();
                const aadhaarNo = $('#aadhaar_no').val().trim();
                
                let hasError = false;
                
                // Reset previous errors
                $('.is-invalid').removeClass('is-invalid');
                
                // Validate required fields
                if (!firstName) { $('#first_name').addClass('is-invalid'); hasError = true; }
                if (!currentAddress) { $('#current_address').addClass('is-invalid'); hasError = true; }
                if (!nationality) { $('#nationality').addClass('is-invalid'); hasError = true; }
                if (!state) { $('#state').addClass('is-invalid'); hasError = true; }
                if (!district) { $('#district').addClass('is-invalid'); hasError = true; }
                if (!city) { $('#city').addClass('is-invalid'); hasError = true; }
                if (!pinCode) { $('#pin_code').addClass('is-invalid'); hasError = true; }
                if (!mobileNo) { $('#mobile_no').addClass('is-invalid'); hasError = true; }
                if (!dateOfBirth) { $('#date_of_birth').addClass('is-invalid'); hasError = true; }
                if (!liveLocationMap) { $('#live_location_map').addClass('is-invalid'); hasError = true; }
                // Aadhaar must be 12 digits
                if (!/^\d{12}$/.test(aadhaarNo)) { $('#aadhaar_no').addClass('is-invalid'); hasError = true; }
            
                // DOB must be between 5 and 60 years
                if (dateOfBirth) {
                    const dob = new Date(dateOfBirth);
                    if (dob < minDobDate || dob > maxDobDate) {
                        $('#date_of_birth').addClass('is-invalid');
                        hasError = true;
                    }
                }
                
                if (hasError) {
                    e.preventDefault();
                    const firstError = $('.is-invalid').first();
                    if (firstError.length) {
                        $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                        firstError.focus();
                    }
                    return false;
                }
            });
            
            // Department level change handler for security amount display
            $('#department_level').on('change', function() {
                const selectedLevel = $(this).val();
                const securityAmountContainer = $('#security_amount_container');
                const securityAmountDisplay = $('#security_amount_display');
                
                if (selectedLevel) {
                    const securityAmounts = {
                        'state_level': {{ $securityAmounts->state_level_amount ?? 0 }},
                        'district_level': {{ $securityAmounts->district_level_amount ?? 0 }},
                        'block_level': {{ $securityAmounts->block_level_amount ?? 0 }},
                        'panchayat_level': {{ $securityAmounts->panchayat_level_amount ?? 0 }},
                        'village_level': {{ $securityAmounts->village_level_amount ?? 0 }}
                    };
                    const amount = securityAmounts[selectedLevel] || 0;
                    securityAmountDisplay.val(parseFloat(amount).toFixed(2));
                    securityAmountContainer.show();
                } else {
                    securityAmountContainer.hide();
                    securityAmountDisplay.val('0.00');
                }
            });
            // OTP UI toggle based on Yes/No dropdown
            const otpSelect = $('#otp_select');
            const otpCodeGroup = $('#otp_code_group');
            function updateOtpUI() {
                const val = otpSelect.val();
                if (val === '1') {
                    otpCodeGroup.show();
                    $('#otp_code').attr('required', true);
                } else {
                    otpCodeGroup.hide();
                    $('#otp_code').val('').removeAttr('required').removeClass('is-invalid is-valid');
                    $('#otp_verify_status').removeClass('text-success text-danger').text('');
                }
            }
            otpSelect.on('change', updateOtpUI);
            updateOtpUI();
        
            // Verify OTP button click handler
            $(document).on('click', '#otp_verify_btn', function() {
                const $btn = $(this);
                const $input = $('#otp_code');
                const $status = $('#otp_verify_status');
                const code = ($input.val() || '').trim();
        
                $status.removeClass('text-success text-danger').text('');
                $input.removeClass('is-invalid is-valid');
        
                if (!/^\d{4,6}$/.test(code)) {
                    $input.addClass('is-invalid');
                    $status.addClass('text-danger').text('Enter a valid 4–6 digit OTP.');
                    return;
                }
        
                $btn.prop('disabled', true).text('Verifying...');
                setTimeout(function() {
                    $btn.prop('disabled', false).text('Verify');
                    $input.removeClass('is-invalid').addClass('is-valid');
                    $status.addClass('text-success').text('OTP verified.');
                }, 800);
            });
        
            // Dependent dropdowns: District by State, City by District
            const districtUrlTemplate = "{{ route('admin.districts.by-state', ['state_id' => ':stateId']) }}";
            const cityUrlTemplate = "{{ route('admin.cities.by-district', ['district_id' => ':districtId']) }}";
            const panchayatUrlTemplate = "{{ route('admin.panchayats.by-city', ['city_id' => ':cityId']) }}";
            const municipalityUrlTemplate = "{{ route('admin.municipalities.by-city', ['city_id' => ':cityId']) }}";
            const villageUrlTemplate = "{{ route('admin.villages.by-city', ['city_id' => ':cityId']) }}";
            const wardUrlTemplate = "{{ route('admin.wards.by-municipality', ['municipality_id' => ':municipalityId']) }}";
            const OTHER_OPTION_VALUE = '__other__';

            const $cityOtherGroup = $('#city_other_group');
            const $cityOtherInput = $('#city_other');
            const $panchayatOtherGroup = $('#panchayat_other_group');
            const $panchayatOtherInput = $('#panchayat_other');
            const $villageOtherGroup = $('#village_other_group');
            const $villageOtherInput = $('#village_other');
            const $municipalityOtherGroup = $('#municipality_other_group');
            const $municipalityOtherInput = $('#municipality_other');
            const $wardOtherGroup = $('#ward_other_group');
            const $wardOtherInput = $('#ward_other');

            function ensureOtherOption($select) {
                const has = $select.find('option[value="' + OTHER_OPTION_VALUE + '"]').length > 0;
                if (!has) {
                    $select.append(new Option('Other', OTHER_OPTION_VALUE));
                }
            }

            function syncOtherUi() {
                const showCityOther = $('#city').val() === OTHER_OPTION_VALUE;
                const isVillageArea = $('#area').val() === 'Village_area';
                const isMunicipalityArea = $('#area').val() === 'Municipality_area';
                const showPanchayatOther = isVillageArea && $('#panchayat').val() === OTHER_OPTION_VALUE;
                const showVillageOther = isVillageArea && $('#village_name').val() === OTHER_OPTION_VALUE;
                const showMunicipalityOther = isMunicipalityArea && $('#municipality').val() === OTHER_OPTION_VALUE;
                const showWardOther = isMunicipalityArea && $('#ward_no').val() === OTHER_OPTION_VALUE;

                $cityOtherGroup.toggle(showCityOther);
                $cityOtherInput.prop('required', showCityOther);
                $cityOtherInput.prop('disabled', !showCityOther);

                $panchayatOtherGroup.toggle(showPanchayatOther);
                $panchayatOtherInput.prop('required', showPanchayatOther);
                $panchayatOtherInput.prop('disabled', !showPanchayatOther);

                $villageOtherGroup.toggle(showVillageOther);
                $villageOtherInput.prop('required', showVillageOther);
                $villageOtherInput.prop('disabled', !showVillageOther);

                $municipalityOtherGroup.toggle(showMunicipalityOther);
                $municipalityOtherInput.prop('required', showMunicipalityOther);
                $municipalityOtherInput.prop('disabled', !showMunicipalityOther);

                $wardOtherGroup.toggle(showWardOther);
                $wardOtherInput.prop('required', showWardOther);
                $wardOtherInput.prop('disabled', !showWardOther);
            }

            function populateSelect($select, items, textKey, dataIdKey) {
                $select.empty();
                const label = $select.attr('id') === 'city' ? 'City' : 'District';
                $select.append(new Option('Select ' + label, ''));
                (items || []).forEach(function(item) {
                    const opt = new Option(item[textKey], item[textKey]);
                    $(opt).attr('data-id', item[dataIdKey]);
                    $select.append(opt);
                });
            }

            function populateNamedSelect($select, items, placeholder, textKey) {
                $select.empty();
                $select.append(new Option(placeholder, ''));
                (items || []).forEach(function(item) {
                    const opt = new Option(item[textKey], item[textKey]);
                    $(opt).attr('data-id', item.id);
                    $select.append(opt);
                });
            }

            const oldPanchayat = "{{ old('panchayat') }}";
            const oldVillageName = "{{ old('village_name') }}";
            const oldMunicipality = "{{ old('municipality') }}";
            const oldWardNo = "{{ old('ward_no') }}";
            const oldDistrict = "{{ old('district') }}";
            const oldCity = "{{ old('city') }}";

            function resetAreaSelects() {
                populateNamedSelect($('#panchayat'), [], 'Select Panchayat', 'panchayat_name');
                populateNamedSelect($('#village_name'), [], 'Select Village Name', 'village_name');
                populateNamedSelect($('#municipality'), [], 'Select Muncipility', 'municipality_name');
                populateNamedSelect($('#ward_no'), [], 'Select Ward No', 'ward_no');
                ensureOtherOption($('#panchayat'));
                ensureOtherOption($('#village_name'));
                ensureOtherOption($('#municipality'));
                ensureOtherOption($('#ward_no'));
                syncOtherUi();
            }

            function loadVillageArea(cityId) {
                const pUrl = panchayatUrlTemplate.replace(':cityId', cityId);
                const vUrl = villageUrlTemplate.replace(':cityId', cityId);

                $.get(pUrl).done(function(resp) {
                    const list = (resp && resp.data) ? resp.data : resp;
                    populateNamedSelect($('#panchayat'), list, 'Select Panchayat', 'panchayat_name');
                    ensureOtherOption($('#panchayat'));
                    if (oldPanchayat) $('#panchayat').val(oldPanchayat);
                    syncOtherUi();
                });

                $.get(vUrl).done(function(resp) {
                    const list = (resp && resp.data) ? resp.data : resp;
                    populateNamedSelect($('#village_name'), list, 'Select Village Name', 'village_name');
                    ensureOtherOption($('#village_name'));
                    if (oldVillageName) $('#village_name').val(oldVillageName);
                    syncOtherUi();
                });
            }

            function loadMunicipalityArea(cityId) {
                const mUrl = municipalityUrlTemplate.replace(':cityId', cityId);
                $.get(mUrl).done(function(resp) {
                    const list = (resp && resp.data) ? resp.data : resp;
                    populateNamedSelect($('#municipality'), list, 'Select Muncipility', 'municipality_name');
                    ensureOtherOption($('#municipality'));
                    if (oldMunicipality) $('#municipality').val(oldMunicipality).trigger('change');
                    syncOtherUi();
                });
            }

            function loadWards(municipalityId) {
                const wUrl = wardUrlTemplate.replace(':municipalityId', municipalityId);
                $.get(wUrl).done(function(resp) {
                    const list = (resp && resp.data) ? resp.data : resp;
                    populateNamedSelect($('#ward_no'), list, 'Select Ward No', 'ward_no');
                    ensureOtherOption($('#ward_no'));
                    if (oldWardNo) $('#ward_no').val(oldWardNo);
                    syncOtherUi();
                });
            }

            $('#state').on('change', function() {
                const stateId = $('#state option:selected').data('id');
                populateSelect($('#district'), [], 'district_name', 'id');
                populateSelect($('#city'), [], 'city_name', 'id');
                ensureOtherOption($('#city'));
                resetAreaSelects();
                if (!stateId) return;
                const url = districtUrlTemplate.replace(':stateId', stateId);
                $.get(url)
                    .done(function(resp) {
                        var list = (resp && resp.districts) ? resp.districts : ((resp && resp.data) ? resp.data : resp);
                        populateSelect($('#district'), list, 'district_name', 'id');
                        if (oldDistrict) {
                            $('#district').val(oldDistrict).trigger('change');
                        }
                    })
                    .fail(function() {
                        console.error('Failed to load districts');
                    });
            });

            $('#district').on('change', function() {
                const districtId = $('#district option:selected').data('id');
                populateSelect($('#city'), [], 'city_name', 'id');
                ensureOtherOption($('#city'));
                resetAreaSelects();
                if (!districtId) return;
                const url = cityUrlTemplate.replace(':districtId', districtId);
                $.get(url)
                    .done(function(resp) {
                        var list = (resp && resp.data) ? resp.data : ((resp && resp.cities) ? resp.cities : resp);
                        populateSelect($('#city'), list, 'city_name', 'id');
                        ensureOtherOption($('#city'));
                        if (oldCity) {
                            $('#city').val(oldCity).trigger('change');
                        }
                    })
                    .fail(function() {
                        console.error('Failed to load cities');
                    });
            });

            function setAreaVisibility(val){
                var showVillage = (val === 'Village_area');
                var showMunicipal = (val === 'Municipality_area');
                var $panchayatGroup = $('#panchayat').closest('.col-md-4');
                var $villageGroup = $('#village_name').closest('.col-md-4');
                var $municipalityGroup = $('#municipality').closest('.col-md-4');
                var $wardGroup = $('#ward_no').closest('.col-md-4');

                $panchayatGroup.toggle(showVillage);
                $villageGroup.toggle(showVillage);
                $('#panchayat').prop('required', showVillage).prop('disabled', !showVillage).val('');
                $('#village_name').prop('required', showVillage).prop('disabled', !showVillage).val('');

                $municipalityGroup.toggle(showMunicipal);
                $wardGroup.toggle(showMunicipal);
                $('#municipality').prop('required', showMunicipal).prop('disabled', !showMunicipal).val('');
                $('#ward_no').prop('required', showMunicipal).prop('disabled', !showMunicipal).val('');

                const cityId = $('#city option:selected').data('id');
                resetAreaSelects();
                if (!cityId) return;
                if (showVillage) {
                    loadVillageArea(cityId);
                }
                if (showMunicipal) {
                    loadMunicipalityArea(cityId);
                }
            }

            $('#area').on('change', function(){ setAreaVisibility($(this).val()); });
            setAreaVisibility($('#area').val() || '');
            $('#panchayat').on('change', syncOtherUi);
            $('#village_name').on('change', syncOtherUi);
            $('#municipality').on('change', syncOtherUi);
            $('#ward_no').on('change', syncOtherUi);
            syncOtherUi();

            $('#city').on('change', function() {
                syncOtherUi();
                const cityId = $('#city option:selected').data('id');
                resetAreaSelects();
                if (!cityId) return;
                const areaVal = $('#area').val();
                if (areaVal === 'Village_area') {
                    loadVillageArea(cityId);
                } else if (areaVal === 'Municipality_area') {
                    loadMunicipalityArea(cityId);
                }
            });

            $('#municipality').on('change', function() {
                populateNamedSelect($('#ward_no'), [], 'Select Ward No', 'ward_no');
                ensureOtherOption($('#ward_no'));

                const municipalityVal = $('#municipality').val();
                if (municipalityVal === OTHER_OPTION_VALUE) {
                    $('#ward_no').val(OTHER_OPTION_VALUE);
                    syncOtherUi();

                    return;
                }

                const municipalityId = $('#municipality option:selected').data('id');
                if (!municipalityId) {
                    syncOtherUi();

                    return;
                }
                loadWards(municipalityId);
            });

            if ($('#state option:selected').data('id') && oldDistrict) {
                $('#state').trigger('change');
            }
        });
    });
</script>
@endpush
