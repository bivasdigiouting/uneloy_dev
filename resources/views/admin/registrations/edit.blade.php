@extends('layouts.admin')

@section('title', 'Edit Registration')

@section('content')

    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Registration</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
                        <li class="breadcrumb-item active">Edit Registration</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <form action="{{ route('admin.registrations.update', $registration->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Official Details -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Section 1: Official Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department_level">Department Level <span class="text-danger">*</span></label>
                                        <select class="form-control" id="department_level" name="department_level" required>
                                            <option value="">Select Department Level</option>
                                            @foreach($departmentLevels as $key => $value)
                                                <option value="{{ $key }}" {{ old('department_level', $registration->department_level) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_level')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="security_amount_container" style="display:none;">
                                        <label for="security_amount_display">Security Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="text" class="form-control" id="security_amount_display" value="{{ old('security_amount_display', '0.00') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="aadhaar_no">Aadhaar Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('aadhaar_no') is-invalid @enderror" id="aadhaar_no" name="aadhaar_no" value="{{ old('aadhaar_no', $registration->aadhaar_no) }}" placeholder="Enter 12-digit Aadhaar number" required pattern="\d{12}" inputmode="numeric" maxlength="12">
                                        @error('aadhaar_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="otp_select">OTP</label>
                                        <select class="form-control" id="otp_select" name="otp_required">
                                            @php $otpRequiredOld = old('otp_required', ($registration->otp_required ? '1' : '0')); @endphp
                                            <option value="0" {{ $otpRequiredOld == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ $otpRequiredOld == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="otp_code_group" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="otp_code">OTP Code</label>
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
                            <h4 class="card-title mb-0">2. Personal Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $registration->first_name) }}" required>
                                        @error('first_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $registration->middle_name) }}">
                                        @error('middle_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $registration->last_name) }}">
                                        @error('last_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="father_name">Father's Name</label>
                                        <input type="text" class="form-control" id="father_name" name="father_name" value="{{ old('father_name', $registration->father_name) }}">
                                        @error('father_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mother_name">Mother's Name</label>
                                        <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ old('mother_name', $registration->mother_name) }}">
                                        @error('mother_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="blood_group">Select Blood Group</label>
                                        <select class="form-control" id="blood_group" name="blood_group">
                                            <option value="">--Select--</option>
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
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $registration->date_of_birth ? $registration->date_of_birth->format('Y-m-d') : '') }}" required>
                                        @error('date_of_birth')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <div class="form-check-group">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="male" value="Male" {{ old('gender', $registration->gender) == 'Male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="female" value="Female" {{ old('gender', $registration->gender) == 'Female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="female">Female</label>
                                            </div>
                                        </div>
                                        @error('gender')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="marital_status">Marital Status</label>
                                        <select class="form-control" id="marital_status" name="marital_status">
                                            <option value="">--Select--</option>
                                            <option value="Single" {{ old('marital_status', $registration->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ old('marital_status', $registration->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Divorced" {{ old('marital_status', $registration->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Widowed" {{ old('marital_status', $registration->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                        @error('marital_status')
                                            <div class="text-danger">{{ $message }}</div>
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
                            <h4 class="card-title mb-0">3. Contact Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="current_address">Current Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="current_address" name="current_address" rows="3" required>{{ old('current_address', $registration->current_address) }}</textarea>
                                        @error('current_address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="permanent_address">Permanent Address</label>
                                        <textarea class="form-control" id="permanent_address" name="permanent_address" rows="3">{{ old('permanent_address', $registration->permanent_address) }}</textarea>
                                        @error('permanent_address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nationality">Nationality (Country) <span class="text-danger">*</span></label>
                                        <select class="form-control" id="nationality" name="nationality" required>
                                            <option value="">--Select--</option>
                                            <option value="India" {{ old('nationality', $registration->nationality) == 'India' ? 'selected' : '' }}>India</option>
                                            <option value="USA" {{ old('nationality', $registration->nationality) == 'USA' ? 'selected' : '' }}>USA</option>
                                            <option value="UK" {{ old('nationality', $registration->nationality) == 'UK' ? 'selected' : '' }}>UK</option>
                                            <option value="Canada" {{ old('nationality', $registration->nationality) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                            <option value="Australia" {{ old('nationality', $registration->nationality) == 'Australia' ? 'selected' : '' }}>Australia</option>
                                        </select>
                                        @error('nationality')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <select class="form-control" id="state" name="state" required>
                                            <option value="">--Select--</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->state_name }}" data-id="{{ $state->id }}" {{ old('state', $registration->state) == $state->state_name ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district">District <span class="text-danger">*</span></label>
                                        <select class="form-control" id="district" name="district" required>
                                            <option value="">--Select--</option>
                                        </select>
                                        @error('district')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <select class="form-control" id="city" name="city" required>
                                            <option value="">--Select--</option>
                                        </select>
                                        @error('city')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="area">Area <span class="text-danger">*</span></label>
                                        <select class="form-control" id="area" name="area" required>
                                            <option value="">Select Area</option>
                                            <option value="Village_area" {{ old('area', $registration->area ?? '') == 'Village_area' ? 'selected' : '' }}>Village Area</option>
                                            <option value="Municipality_area" {{ old('area', $registration->area ?? '') == 'Municipality_area' ? 'selected' : '' }}>Municipality Area</option>
                                        </select>
                                        @error('area')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="panchayat">Panchayat <span class="text-danger">*</span></label>
                                        <select class="form-control" id="panchayat" name="panchayat" required>
                                            <option value="">Select Panchayat</option>
                                        </select>
                                        @error('panchayat')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="panchayat_other_group" style="display:none;">
                                    <div class="form-group">
                                        <label for="panchayat_other">Other Panchayat Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="panchayat_other" name="panchayat_other" value="{{ old('panchayat_other') }}" placeholder="Enter panchayat name">
                                        @error('panchayat_other')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="municipality">Muncipility <span class="text-danger">*</span></label>
                                        <select class="form-control" id="municipality" name="municipality" required>
                                            <option value="">Select Muncipility</option>
                                        </select>
                                        @error('municipality')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="municipality_other_group" style="display:none;">
                                    <div class="form-group">
                                        <label for="municipality_other">Other Municipality Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="municipality_other" name="municipality_other" value="{{ old('municipality_other') }}" placeholder="Enter municipality name">
                                        @error('municipality_other')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="village_name">Village Name <span class="text-danger">*</span></label>
                                        <select class="form-control" id="village_name" name="village_name" required>
                                            <option value="">Select Village Name</option>
                                        </select>
                                        @error('village_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="village_other_group" style="display:none;">
                                    <div class="form-group">
                                        <label for="village_other">Other Village Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="village_other" name="village_other" value="{{ old('village_other') }}" placeholder="Enter village name">
                                        @error('village_other')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ward_no">Ward No <span class="text-danger">*</span></label>
                                        <select class="form-control" id="ward_no" name="ward_no" required>
                                            <option value="">Select Ward No</option>
                                        </select>
                                        @error('ward_no')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="ward_other_group" style="display:none;">
                                    <div class="form-group">
                                        <label for="ward_other">Other Ward No & Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ward_other" name="ward_other" value="{{ old('ward_other') }}" placeholder="Enter ward no & name">
                                        @error('ward_other')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pin_code">Pin Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ old('pin_code', $registration->pin_code) }}" required>
                                        @error('pin_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile_no">Mobile No. <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $registration->mobile_no) }}" required>
                                        @error('mobile_no')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_no">Phone No.</label>
                                        <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no', $registration->phone_no) }}">
                                        @error('phone_no')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_id">E-Mail ID</label>
                                        <input type="email" class="form-control" id="email_id" name="email_id" value="{{ old('email_id', $registration->email_id) }}">
                                        @error('email_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gmail_id">Gmail ID</label>
                                        <input type="email" class="form-control" id="gmail_id" name="gmail_id" value="{{ old('gmail_id', $registration->gmail_id) }}">
                                        @error('gmail_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="live_location_map">Live Location (Map) <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="live_location_map" name="live_location_map" rows="2" placeholder="Enter map coordinates or location details" required>{{ old('live_location_map', $registration->live_location_map) }}</textarea>
                                        @error('live_location_map')
                                            <div class="text-danger">{{ $message }}</div>
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
                            <h4 class="card-title mb-0">5. Qualification & Experience Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_qualification">Last Qualification</label>
                                        <input type="text" class="form-control" id="last_qualification" name="last_qualification" value="{{ old('last_qualification', $registration->last_qualification) }}">
                                        @error('last_qualification')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="work_type">Work Type</label>
                                        <input type="text" class="form-control" id="work_type" name="work_type" value="{{ old('work_type', $registration->work_type) }}">
                                        @error('work_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="work_experience">Work Experience</label>
                                        <input type="text" class="form-control" id="work_experience" name="work_experience" value="{{ old('work_experience', $registration->work_experience) }}">
                                        @error('work_experience')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Update Registration</button>
                            <a href="{{ route('admin.registrations.show', $registration->id) }}" class="btn btn-secondary btn-lg ml-2">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


<script>
$(document).ready(function() {
    // DOB min/max
    const today = new Date();
    const minDobDate = new Date(today.getFullYear() - 60, today.getMonth(), today.getDate());
    const maxDobDate = new Date(today.getFullYear() - 5, today.getMonth(), today.getDate());
    $('#date_of_birth')
        .attr('min', minDobDate.toISOString().slice(0,10))
        .attr('max', maxDobDate.toISOString().slice(0,10));

    // Security amounts
    const securityAmounts = {
        'state_level': {{ $securityAmounts->state_level_amount ?? 0 }},
        'district_level': {{ $securityAmounts->district_level_amount ?? 0 }},
        'block_level': {{ $securityAmounts->block_level_amount ?? 0 }},
        'panchayat_level': {{ $securityAmounts->panchayat_level_amount ?? 0 }},
        'village_level': {{ $securityAmounts->village_level_amount ?? 0 }}
    };

    function updateSecurityAmount() {
        const selectedDepartment = $('#department_level').val();
        if (selectedDepartment && securityAmounts[selectedDepartment] !== undefined) {
            const amount = securityAmounts[selectedDepartment] || 0;
            $('#security_amount_display').val(parseFloat(amount).toFixed(2));
            $('#security_amount_container').show();
        } else {
            $('#security_amount_container').hide();
            $('#security_amount_display').val('0.00');
        }
    }

    updateSecurityAmount();
    $('#department_level').on('change', updateSecurityAmount);

    // OTP UI toggle
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

    // Verify OTP button (mock)
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

    // Dependent dropdowns
    const districtUrlTemplate = "{{ route('admin.districts.by-state', ['state_id' => ':stateId']) }}";
    const cityUrlTemplate = "{{ route('admin.cities.by-district', ['district_id' => ':districtId']) }}";
    const panchayatUrlTemplate = "{{ route('admin.panchayats.by-city', ['city_id' => ':cityId']) }}";
    const municipalityUrlTemplate = "{{ route('admin.municipalities.by-city', ['city_id' => ':cityId']) }}";
    const villageUrlTemplate = "{{ route('admin.villages.by-city', ['city_id' => ':cityId']) }}";
    const wardUrlTemplate = "{{ route('admin.wards.by-municipality', ['municipality_id' => ':municipalityId']) }}";
    const OTHER_OPTION_VALUE = '__other__';

    const oldPanchayat = "{{ old('panchayat', $registration->panchayat) }}";
    const oldVillageName = "{{ old('village_name', $registration->village_name) }}";
    const oldMunicipality = "{{ old('municipality', $registration->municipality) }}";
    const oldWardNo = "{{ old('ward_no', $registration->ward_no) }}";

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

    function ensureValueOption($select, value) {
        const v = (value || '').trim();
        if (!v) return;
        const has = Array.from($select[0].options || []).some(function(o) { return o.value === v; });
        if (!has) {
            $select.append(new Option(v, v));
        }
    }

    function ensureOtherOption($select) {
        const has = $select.find('option[value="' + OTHER_OPTION_VALUE + '"]').length > 0;
        if (!has) {
            $select.append(new Option('Other', OTHER_OPTION_VALUE));
        }
    }

    const $panchayatOtherGroup = $('#panchayat_other_group');
    const $panchayatOtherInput = $('#panchayat_other');
    const $villageOtherGroup = $('#village_other_group');
    const $villageOtherInput = $('#village_other');
    const $municipalityOtherGroup = $('#municipality_other_group');
    const $municipalityOtherInput = $('#municipality_other');
    const $wardOtherGroup = $('#ward_other_group');
    const $wardOtherInput = $('#ward_other');

    function syncOtherUi() {
        const isVillageArea = $('#area').val() === 'Village_area';
        const isMunicipalityArea = $('#area').val() === 'Municipality_area';
        const showPanchayatOther = isVillageArea && $('#panchayat').val() === OTHER_OPTION_VALUE;
        const showVillageOther = isVillageArea && $('#village_name').val() === OTHER_OPTION_VALUE;
        const showMunicipalityOther = isMunicipalityArea && $('#municipality').val() === OTHER_OPTION_VALUE;
        const showWardOther = isMunicipalityArea && $('#ward_no').val() === OTHER_OPTION_VALUE;

        $panchayatOtherGroup.toggle(showPanchayatOther);
        $panchayatOtherInput.prop('required', showPanchayatOther).prop('disabled', !showPanchayatOther);

        $villageOtherGroup.toggle(showVillageOther);
        $villageOtherInput.prop('required', showVillageOther).prop('disabled', !showVillageOther);

        $municipalityOtherGroup.toggle(showMunicipalityOther);
        $municipalityOtherInput.prop('required', showMunicipalityOther).prop('disabled', !showMunicipalityOther);

        $wardOtherGroup.toggle(showWardOther);
        $wardOtherInput.prop('required', showWardOther).prop('disabled', !showWardOther);
    }

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
            ensureValueOption($('#panchayat'), oldPanchayat);
            if (oldPanchayat) $('#panchayat').val(oldPanchayat);
            syncOtherUi();
        });

        $.get(vUrl).done(function(resp) {
            const list = (resp && resp.data) ? resp.data : resp;
            populateNamedSelect($('#village_name'), list, 'Select Village Name', 'village_name');
            ensureOtherOption($('#village_name'));
            ensureValueOption($('#village_name'), oldVillageName);
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
            ensureValueOption($('#municipality'), oldMunicipality);
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
            ensureValueOption($('#ward_no'), oldWardNo);
            if (oldWardNo) $('#ward_no').val(oldWardNo);
            syncOtherUi();
        });
    }

    function preloadDistrictAndCity() {
        const oldStateId = $('#state option:selected').data('id');
        const oldDistrict = "{{ old('district', $registration->district) }}";
        const oldCity = "{{ old('city', $registration->city) }}";
        if (!oldStateId) return;

        const url = districtUrlTemplate.replace(':stateId', oldStateId);
        $.get(url).done(function(resp) {
            var list = (resp && resp.districts) ? resp.districts : ((resp && resp.data) ? resp.data : resp);
            populateSelect($('#district'), list, 'district_name', 'id');
            if (oldDistrict) {
                $('#district').val(oldDistrict);
            }
            const districtId = $('#district option:selected').data('id');
            if (districtId) {
                const cityUrl = cityUrlTemplate.replace(':districtId', districtId);
                $.get(cityUrl).done(function(resp2) {
                    var cities = (resp2 && resp2.data) ? resp2.data : ((resp2 && resp2.cities) ? resp2.cities : resp2);
                    populateSelect($('#city'), cities, 'city_name', 'id');
                    if (oldCity) {
                        $('#city').val(oldCity);
                    }
                    const areaVal = $('#area').val() || '';
                    setAreaVisibility(areaVal);
                    handleCityChange();
                });
            }
        });
    }

    $('#state').on('change', function() {
        const stateId = $('#state option:selected').data('id');
        populateSelect($('#district'), [], 'district_name', 'id');
        populateSelect($('#city'), [], 'city_name', 'id');
        resetAreaSelects();
        if (!stateId) return;
        const url = districtUrlTemplate.replace(':stateId', stateId);
        $.get(url)
            .done(function(resp) {
                var list = (resp && resp.districts) ? resp.districts : ((resp && resp.data) ? resp.data : resp);
                populateSelect($('#district'), list, 'district_name', 'id');
            })
            .fail(function() { console.error('Failed to load districts'); });
    });

    $('#district').on('change', function() {
        const districtId = $('#district option:selected').data('id');
        populateSelect($('#city'), [], 'city_name', 'id');
        resetAreaSelects();
        if (!districtId) return;
        const url = cityUrlTemplate.replace(':districtId', districtId);
        $.get(url)
            .done(function(resp) {
                var list = (resp && resp.data) ? resp.data : ((resp && resp.cities) ? resp.cities : resp);
                populateSelect($('#city'), list, 'city_name', 'id');
            })
            .fail(function() { console.error('Failed to load cities'); });
    });

    preloadDistrictAndCity();

    function handleCityChange() {
        const cityId = $('#city option:selected').data('id');
        resetAreaSelects();
        if (!cityId) return;
        const areaVal = $('#area').val();
        if (areaVal === 'Village_area') {
            loadVillageArea(cityId);
        } else if (areaVal === 'Municipality_area') {
            loadMunicipalityArea(cityId);
        }
    }

    function setAreaVisibility(val){
        var showVillage = (val === 'Village_area');
        var showMunicipal = (val === 'Municipality_area');
        var $panchayatGroup = $('#panchayat').closest('.col-md-4');
        var $villageGroup = $('#village_name').closest('.col-md-4');
        var $municipalityGroup = $('#municipality').closest('.col-md-4');
        var $wardGroup = $('#ward_no').closest('.col-md-4');

        $panchayatGroup.toggle(showVillage);
        $villageGroup.toggle(showVillage);
        $('#panchayat').prop('required', showVillage).prop('disabled', !showVillage);
        $('#village_name').prop('required', showVillage).prop('disabled', !showVillage);

        $municipalityGroup.toggle(showMunicipal);
        $wardGroup.toggle(showMunicipal);
        $('#municipality').prop('required', showMunicipal).prop('disabled', !showMunicipal);
        $('#ward_no').prop('required', showMunicipal).prop('disabled', !showMunicipal);
        syncOtherUi();
    }

    $('#area').on('change', function(){ setAreaVisibility($(this).val()); });
    setAreaVisibility($('#area').val() || '');
    $('#city').on('change', handleCityChange);
    $('#panchayat').on('change', syncOtherUi);
    $('#village_name').on('change', syncOtherUi);
    $('#municipality').on('change', syncOtherUi);
    $('#ward_no').on('change', syncOtherUi);

    $('#municipality').on('change', function() {
        populateNamedSelect($('#ward_no'), [], 'Select Ward No', 'ward_no');
        ensureValueOption($('#ward_no'), oldWardNo);
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

    handleCityChange();
});
</script>

@endsection
