@extends('layouts.admin')

@section('title', 'New E-Card Registration')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">New E-Card Registration</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">E-Card Seva</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.ecard-registrations.index') }}">Registration</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New E-Card Registration</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.ecard-registrations.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to E-Card Registrations
                </a>
            </div>
        </div>
    </div>

    <!-- Create E-Card Registration Form -->
     <div class="row">
         <div class="col-12">
             <form action="{{ route('admin.ecard-registrations.store') }}" method="POST" enctype="multipart/form-data" id="ecardRegistrationForm">
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
                                              <input type="text" class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}" placeholder="Enter business name" required>
                                              @error('business_name')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label for="business_mobile" class="form-label">Mobile No.</label>
                                              <input type="text" class="form-control @error('business_mobile') is-invalid @enderror" id="business_mobile" name="business_mobile" value="{{ old('business_mobile') }}" placeholder="Enter mobile number">
                                              @error('business_mobile')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label for="business_whatsapp" class="form-label">WhatsApp No.</label>
                                              <input type="text" class="form-control @error('business_whatsapp') is-invalid @enderror" id="business_whatsapp" name="business_whatsapp" value="{{ old('business_whatsapp') }}" placeholder="Enter WhatsApp number">
                                              @error('business_whatsapp')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label for="business_gmail" class="form-label">Email ID</label>
                                              <input type="email" class="form-control @error('business_gmail') is-invalid @enderror" id="business_gmail" name="business_gmail" value="{{ old('business_gmail') }}" placeholder="Enter Gmail ID">
                                              @error('business_gmail')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="mb-3">
                                              <label for="business_address" class="form-label">Business Full Address <span class="text-danger">*</span></label>
                                              <textarea class="form-control @error('business_address') is-invalid @enderror" id="business_address" name="business_address" rows="3" placeholder="Enter business address" required>{{ old('business_address') }}</textarea>
                                              @error('business_address')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label for="business_gst" class="form-label">Business GST No.</label>
                                              <input type="text" class="form-control @error('business_gst') is-invalid @enderror" id="business_gst" name="business_gst" value="{{ old('business_gst') }}" placeholder="Enter GST number">
                                              @error('business_gst')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label for="business_upi" class="form-label">Business UPI ID</label>
                                              <input type="text" class="form-control @error('business_upi') is-invalid @enderror" id="business_upi" name="business_upi" value="{{ old('business_upi') }}" placeholder="Enter UPI ID">
                                              @error('business_upi')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="mb-3">
                                              <label for="business_location_map" class="form-label">Business Location Map Link</label>
                                              <textarea class="form-control @error('business_location_map') is-invalid @enderror" id="business_location_map" name="business_location_map" rows="2" placeholder="Enter Google Maps link">{{ old('business_location_map') }}</textarea>
                                              @error('business_location_map')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label for="department_level" class="form-label">Department Level</label>
                                              <select class="form-select @error('department_level') is-invalid @enderror" id="department_level" name="department_level">
                                                  <option value="">Select Department Level</option>
                                                  <option value="state_level" {{ old('department_level') == 'state_level' ? 'selected' : '' }}>State e-Card Seva</option>
                                                  <option value="district_level" {{ old('department_level') == 'district_level' ? 'selected' : '' }}>District e-Card Seva</option>
                                                  <option value="block_level" {{ old('department_level') == 'block_level' ? 'selected' : '' }}>Block - e-Card Seva</option>
                                                  <option value="panchayat_level" {{ old('department_level') == 'panchayat_level' ? 'selected' : '' }}>G P M e-Card Seva</option>
                                                  <option value="village_level" {{ old('department_level') == 'village_level' ? 'selected' : '' }}>e-Card Seva</option>
                                                  <option value="customer" {{ old('department_level') == 'customer' ? 'selected' : '' }}>Member</option>
                                              </select>
                                              @error('department_level')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Section 2: Personal Details -->
                         <div class="card mb-4">
                             <div class="card-header">
                                 <h5 class="card-title mb-0">Section 2: Personal Details</h5>
                             </div>
                             <div class="card-body">
                                 <div class="row">
                                     <div class="col-md-4">
                                         <div class="mb-3">
                                             <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                             <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                             @error('first_name')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-4">
                                         <div class="mb-3">
                                             <label for="middle_name" class="form-label">Middle Name</label>
                                             <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                                             @error('middle_name')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-4">
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
                                     <div class="col-md-4">
                                         <div class="mb-3">
                                             <label for="blood_group" class="form-label">Blood Group</label>
                                             <select class="form-select @error('blood_group') is-invalid @enderror" id="blood_group" name="blood_group">
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
                                     <div class="col-md-4">
                                         <div class="mb-3">
                                             <label for="date_of_birth" class="form-label">Date of Birth</label>
                                             <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                             @error('date_of_birth')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-4">
                                         <div class="mb-3">
                                             <label for="gender" class="form-label">Gender</label>
                                             <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                 <option value="">Select Gender</option>
                                                 <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                 <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                 <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                             </select>
                                             @error('gender')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="marital_status" class="form-label">Marital Status</label>
                                             <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                                 <option value="">Select Marital Status</option>
                                                 <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                                 <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                                 <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                 <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                             </select>
                                             @error('marital_status')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Section 3: Contact Details -->
                         <div class="card mb-4">
                             <div class="card-header">
                                 <h5 class="card-title mb-0">Section 3: Contact Details</h5>
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
                                     <div class="col-md-12">
                                         <div class="mb-3">
                                             <label for="permanent_address" class="form-label">Permanent Address</label>
                                             <textarea class="form-control @error('permanent_address') is-invalid @enderror" id="permanent_address" name="permanent_address" rows="3" placeholder="Enter permanent address">{{ old('permanent_address') }}</textarea>
                                             @error('permanent_address')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="nationality" class="form-label">Nationality</label>
                                             <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', 'Indian') }}" placeholder="Enter nationality">
                                             @error('nationality')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="state" class="form-label">State</label>
                                             <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" placeholder="Enter state">
                                             @error('state')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="district" class="form-label">District</label>
                                             <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" placeholder="Enter district">
                                             @error('district')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="city" class="form-label">City</label>
                                             <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="Enter city">
                                             @error('city')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="pin_code" class="form-label">PIN Code</label>
                                             <input type="text" class="form-control @error('pin_code') is-invalid @enderror" id="pin_code" name="pin_code" value="{{ old('pin_code') }}" placeholder="Enter PIN code">
                                             @error('pin_code')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="mobile_no" class="form-label">Mobile No. <span class="text-danger">*</span></label>
                                             <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Enter mobile number" required>
                                             @error('mobile_no')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="email_id" class="form-label">Email ID <span class="text-danger">*</span></label>
                                             <input type="email" class="form-control @error('email_id') is-invalid @enderror" id="email_id" name="email_id" value="{{ old('email_id') }}" placeholder="Enter email ID" required>
                                             @error('email_id')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="mb-3">
                                             <label for="live_location_map" class="form-label">Live Location Map Link</label>
                                             <textarea class="form-control @error('live_location_map') is-invalid @enderror" id="live_location_map" name="live_location_map" rows="2" placeholder="Enter Google Maps link">{{ old('live_location_map') }}</textarea>
                                             @error('live_location_map')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Section 4: Bank Details -->
                         <div class="card mb-4">
                             <div class="card-header">
                                 <h5 class="card-title mb-0">Section 4: Bank Details</h5>
                             </div>
                             <div class="card-body">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="ifsc_code" class="form-label">IFSC Code</label>
                                             <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" placeholder="Enter IFSC code">
                                             @error('ifsc_code')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="bank_name" class="form-label">Bank Name</label>
                                             <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" placeholder="Enter bank name">
                                             @error('bank_name')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="branch_name" class="form-label">Branch Name</label>
                                             <input type="text" class="form-control @error('branch_name') is-invalid @enderror" id="branch_name" name="branch_name" value="{{ old('branch_name') }}" placeholder="Enter branch name">
                                             @error('branch_name')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="account_no" class="form-label">Account No.</label>
                                             <input type="text" class="form-control @error('account_no') is-invalid @enderror" id="account_no" name="account_no" value="{{ old('account_no') }}" placeholder="Enter account number">
                                             @error('account_no')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="pan_no" class="form-label">PAN No.</label>
                                             <input type="text" class="form-control @error('pan_no') is-invalid @enderror" id="pan_no" name="pan_no" value="{{ old('pan_no') }}" placeholder="Enter PAN number">
                                             @error('pan_no')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="aadhaar_no" class="form-label">Aadhaar No.</label>
                                             <input type="text" class="form-control @error('aadhaar_no') is-invalid @enderror" id="aadhaar_no" name="aadhaar_no" value="{{ old('aadhaar_no') }}" placeholder="Enter Aadhaar number">
                                             @error('aadhaar_no')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Section 5: Qualification & Experience Details -->
                         <div class="card mb-4">
                             <div class="card-header">
                                 <h5 class="card-title mb-0">Section 5: Qualification & Experience Details</h5>
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
                                             <input type="text" class="form-control @error('work_type') is-invalid @enderror" id="work_type" name="work_type" value="{{ old('work_type') }}" placeholder="Enter work type">
                                             @error('work_type')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="work_experience" class="form-label">Work Experience</label>
                                             <input type="text" class="form-control @error('work_experience') is-invalid @enderror" id="work_experience" name="work_experience" value="{{ old('work_experience') }}" placeholder="Enter work experience">
                                             @error('work_experience')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <!-- Sidebar -->
                     <div class="col-lg-4">
                         <!-- Status & Wallet -->
                         <div class="card mb-4">
                             <div class="card-header">
                                 <h5 class="card-title mb-0">Status & Wallet</h5>
                             </div>
                             <div class="card-body">
                                 <div class="mb-3">
                                     <label for="status" class="form-label">Status</label>
                                     <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                         <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                         <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                         <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                         <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                     </select>
                                     @error('status')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                                 <div class="mb-3">
                                     <label for="wallet_balance" class="form-label">Wallet Balance</label>
                                     <div class="input-group">
                                         <span class="input-group-text">₹</span>
                                         <input type="number" class="form-control @error('wallet_balance') is-invalid @enderror" id="wallet_balance" name="wallet_balance" value="{{ old('wallet_balance', '0.00') }}" step="0.01" min="0" placeholder="0.00">
                                     </div>
                                     @error('wallet_balance')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>
                         </div>

                         <!-- Form Actions -->
                         <div class="card">
                             <div class="card-body">
                                 <div class="d-grid gap-2">
                                     <button type="submit" class="btn btn-primary">
                                         <i class="ti ti-device-floppy me-1"></i>Create E-Card Registration
                                     </button>
                                     <a href="{{ route('admin.ecard-registrations.index') }}" class="btn btn-light">
                                         <i class="ti ti-arrow-left me-1"></i>Cancel
                                     </a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </form>
         </div>
     </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation and submission
    document.getElementById('ecardRegistrationForm').addEventListener('submit', function(e) {
        // Add any custom validation here if needed
        console.log('E-Card Registration form submitted');
    });

    // Auto-fill permanent address from current address
    document.getElementById('current_address').addEventListener('blur', function() {
        const permanentAddress = document.getElementById('permanent_address');
        if (!permanentAddress.value.trim()) {
            permanentAddress.value = this.value;
        }
    });

    // Auto-fill Gmail ID from Email ID
    document.getElementById('email_id').addEventListener('blur', function() {
        const gmailId = document.getElementById('gmail_id');
        if (!gmailId.value.trim() && this.value.includes('@gmail.com')) {
            gmailId.value = this.value;
        }
    });
</script>
@endpush
