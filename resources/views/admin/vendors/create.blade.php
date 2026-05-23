@extends('layouts.admin')

@section('title', 'Vendor Registration')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Vendor Registration</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.vendors.index') }}">Vendors</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New Vendor</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Vendors
                </a>
            </div>
        </div>
    </div>

    <!-- Create Vendor Form -->
    <div class="row">
        <div class="col-12">
            <form id="vendorForm" action="{{ route('admin.vendors.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="business_registration_category" class="form-label">Business Registration Category <span class="text-danger">*</span></label>
                                        <select class="form-control @error('business_registration_category') is-invalid @enderror" 
                                                id="business_registration_category" name="business_registration_category" required>
                                            <option value="">Select Category</option>
                                            <option value="Private Limited" {{ old('business_registration_category') == 'Private Limited' ? 'selected' : '' }}>Private Limited</option>
                                            <option value="Proprietorship" {{ old('business_registration_category') == 'Proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                                            <option value="Partnership" {{ old('business_registration_category') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                            <option value="Limited" {{ old('business_registration_category') == 'Limited' ? 'selected' : '' }}>Limited</option>
                                            <option value="NGO" {{ old('business_registration_category') == 'NGO' ? 'selected' : '' }}>NGO</option>
                                        </select>
                                        @error('business_registration_category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                               id="business_name" name="business_name" value="{{ old('business_name') }}" 
                                               placeholder="Enter your business name" required>
                                        @error('business_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mobile_no" class="form-label">Mobile No <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select" id="mobile_country_code" name="mobile_country_code" style="max-width: 120px;">
                                                    <option value="+91" selected>+91</option>
                                                    <option value="+1">+1</option>
                                                    <option value="+44">+44</option>
                                                    <option value="+61">+61</option>
                                                    <option value="+33">+33</option>
                                                    <option value="+49">+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" 
                                                       id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" 
                                                       placeholder="Enter mobile number" required>
                                            </div>
                                            @error('mobile_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="whatsapp_no" class="form-label">WhatsApp No</label>
                                            <div class="input-group">
                                                <select class="form-select" id="whatsapp_country_code" name="whatsapp_country_code" style="max-width: 120px;">
                                                    <option value="+91" selected>+91</option>
                                                    <option value="+1">+1</option>
                                                    <option value="+44">+44</option>
                                                    <option value="+61">+61</option>
                                                    <option value="+33">+33</option>
                                                    <option value="+49">+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('whatsapp_no') is-invalid @enderror" 
                                                       id="whatsapp_no" name="whatsapp_no" 
                                                       placeholder="Enter WhatsApp number" value="{{ old('whatsapp_no') }}">
                                            </div>
                                            @error('whatsapp_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gmail_id" class="form-label">Gmail ID <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('gmail_id') is-invalid @enderror" 
                                                   id="gmail_id" name="gmail_id" value="{{ old('gmail_id') }}" 
                                                   placeholder="Enter email address" required>
                                            @error('gmail_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_gst_no" class="form-label">Business GST No</label>
                                            <input type="text" class="form-control @error('business_gst_no') is-invalid @enderror" 
                                                   id="business_gst_no" name="business_gst_no" 
                                                   placeholder="Enter GST number" value="{{ old('business_gst_no') }}">
                                            @error('business_gst_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="business_full_address" class="form-label">Business Full Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('business_full_address') is-invalid @enderror" 
                                                      id="business_full_address" name="business_full_address" rows="3" 
                                                      placeholder="Enter complete business address" required>{{ old('business_full_address') }}</textarea>
                                            @error('business_full_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_person" class="form-label">Contact Person <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                                   id="contact_person" name="contact_person" value="{{ old('contact_person') }}" 
                                                   placeholder="Enter contact person name" required>
                                            @error('contact_person')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_person_designation" class="form-label">Contact Person Designation <span class="text-danger">*</span></label>
                                            <select class="form-control @error('contact_person_designation') is-invalid @enderror" 
                                                    id="contact_person_designation" name="contact_person_designation" required>
                                                <option value="">Select Designation</option>
                                                <option value="CEO" {{ old('contact_person_designation') == 'CEO' ? 'selected' : '' }}>CEO</option>
                                                <option value="Managing Director" {{ old('contact_person_designation') == 'Managing Director' ? 'selected' : '' }}>Managing Director</option>
                                                <option value="General Manager" {{ old('contact_person_designation') == 'General Manager' ? 'selected' : '' }}>General Manager</option>
                                                <option value="Manager" {{ old('contact_person_designation') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="Assistant Manager" {{ old('contact_person_designation') == 'Assistant Manager' ? 'selected' : '' }}>Assistant Manager</option>
                                                <option value="Executive" {{ old('contact_person_designation') == 'Executive' ? 'selected' : '' }}>Executive</option>
                                                <option value="Director" {{ old('contact_person_designation') == 'Director' ? 'selected' : '' }}>Director</option>
                                                <option value="Owner" {{ old('contact_person_designation') == 'Owner' ? 'selected' : '' }}>Owner</option>
                                            </select>
                                            @error('contact_person_designation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="facility" class="form-label">Facility</label>
                                            <textarea class="form-control @error('facility') is-invalid @enderror" 
                                                      id="facility" name="facility" rows="3" 
                                                      placeholder="Describe your facility">{{ old('facility') }}</textarea>
                                            @error('facility')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="about_us" class="form-label">About Us</label>
                                            <textarea class="form-control @error('about_us') is-invalid @enderror" 
                                                      id="about_us" name="about_us" rows="3" 
                                                      placeholder="Tell us about your business">{{ old('about_us') }}</textarea>
                                            @error('about_us')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="business_location" class="form-label">Business Location (Map) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('business_location') is-invalid @enderror" 
                                                   id="business_location" name="business_location" value="{{ old('business_location') }}" 
                                                   placeholder="Enter coordinates or address" required>
                                            @error('business_location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 2: Business Product -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-box"></i> Section 2: Business Product</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="product_categories" class="form-label">Choose Product Category (Multiple Selection)</label>
                                            <select class="select2 form-control @error('product_categories') is-invalid @enderror" name="product_categories[]" id="product_categories" multiple="multiple" required data-placeholder="Select product categories...">
                                                @foreach($productCategories as $category)
                                                    <option value="{{ $category->name }}" 
                                                            {{ in_array($category->name, old('product_categories', [])) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Search and select multiple product categories</small>
                                            @error('product_categories')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 3: Business Bank Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-university"></i> Section 3: Business Bank Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                            <select class="form-control @error('bank_name') is-invalid @enderror" 
                                                    id="bank_name" name="bank_name" required>
                                                <option value="">Select Bank</option>
                                                <option value="State Bank of India" {{ old('bank_name') == 'State Bank of India' ? 'selected' : '' }}>State Bank of India</option>
                                                <option value="HDFC Bank" {{ old('bank_name') == 'HDFC Bank' ? 'selected' : '' }}>HDFC Bank</option>
                                                <option value="ICICI Bank" {{ old('bank_name') == 'ICICI Bank' ? 'selected' : '' }}>ICICI Bank</option>
                                                <option value="Axis Bank" {{ old('bank_name') == 'Axis Bank' ? 'selected' : '' }}>Axis Bank</option>
                                                <option value="Punjab National Bank" {{ old('bank_name') == 'Punjab National Bank' ? 'selected' : '' }}>Punjab National Bank</option>
                                                <option value="Bank of Baroda" {{ old('bank_name') == 'Bank of Baroda' ? 'selected' : '' }}>Bank of Baroda</option>
                                                <option value="Canara Bank" {{ old('bank_name') == 'Canara Bank' ? 'selected' : '' }}>Canara Bank</option>
                                                <option value="Union Bank of India" {{ old('bank_name') == 'Union Bank of India' ? 'selected' : '' }}>Union Bank of India</option>
                                                <option value="Bank of India" {{ old('bank_name') == 'Bank of India' ? 'selected' : '' }}>Bank of India</option>
                                                <option value="Indian Bank" {{ old('bank_name') == 'Indian Bank' ? 'selected' : '' }}>Indian Bank</option>
                                            </select>
                                            @error('bank_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('branch_name') is-invalid @enderror" 
                                                   id="branch_name" name="branch_name" value="{{ old('branch_name') }}" 
                                                   placeholder="Enter branch name" required>
                                            @error('branch_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="account_holder_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror" 
                                                   id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}" 
                                                   placeholder="Enter account holder name" required>
                                            @error('account_holder_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="account_no" class="form-label">Account No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('account_no') is-invalid @enderror" 
                                                   id="account_no" name="account_no" value="{{ old('account_no') }}" 
                                                   placeholder="Enter account number" required>
                                            @error('account_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="ifsc_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" 
                                                   id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" 
                                                   placeholder="Enter IFSC code" required>
                                            @error('ifsc_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="pan_no" class="form-label">PAN No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('pan_no') is-invalid @enderror" 
                                                   id="pan_no" name="pan_no" value="{{ old('pan_no') }}" 
                                                   placeholder="Enter PAN number" required>
                                            @error('pan_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="aadhar_no" class="form-label">Aadhar No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('aadhar_no') is-invalid @enderror" 
                                                   id="aadhar_no" name="aadhar_no" value="{{ old('aadhar_no') }}" 
                                                   placeholder="Enter Aadhar number" required>
                                            @error('aadhar_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="upi_no" class="form-label">UPI No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('upi_no') is-invalid @enderror" 
                                                   id="upi_no" name="upi_no" value="{{ old('upi_no') }}" 
                                                   placeholder="Enter UPI ID" required>
                                            @error('upi_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 4: Personal Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-user"></i> Section 4: Personal Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="vendor_type" class="form-label">Select Vendor Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('vendor_type') is-invalid @enderror" 
                                                    id="vendor_type" name="vendor_type" required>
                                                <option value="">Select Vendor Type</option>
                                                @foreach($vendorTypes as $vendorType)
                                                    <option value="{{ $vendorType->vendor_type }}" 
                                                            {{ old('vendor_type') == $vendorType->vendor_type ? 'selected' : '' }}>
                                                        {{ $vendorType->vendor_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vendor_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                   id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                                   placeholder="Enter first name" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="middle_name" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                                   id="middle_name" name="middle_name" value="{{ old('middle_name') }}" 
                                                   placeholder="Enter middle name">
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                   id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                                   placeholder="Enter last name">
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fathers_name" class="form-label">Father's Name</label>
                                            <input type="text" class="form-control @error('fathers_name') is-invalid @enderror" 
                                                   id="fathers_name" name="fathers_name" value="{{ old('fathers_name') }}" 
                                                   placeholder="Enter father's name">
                                            @error('fathers_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mothers_name" class="form-label">Mother's Name</label>
                                            <input type="text" class="form-control @error('mothers_name') is-invalid @enderror" 
                                                   id="mothers_name" name="mothers_name" value="{{ old('mothers_name') }}" 
                                                   placeholder="Enter mother's name">
                                            @error('mothers_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="blood_group" class="form-label">Blood Group <span class="text-danger">*</span></label>
                                            <select class="form-control @error('blood_group') is-invalid @enderror" 
                                                    id="blood_group" name="blood_group" required>
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
                                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <div class="form-check-container">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender_male" value="Male" 
                                                           {{ old('gender') == 'Male' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="gender_male">Male</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender_female" value="Female" 
                                                           {{ old('gender') == 'Female' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="gender_female">Female</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender_other" value="Other" 
                                                           {{ old('gender') == 'Other' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="gender_other">Other</label>
                                                </div>
                                            </div>
                                            @error('gender')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                                            <div class="form-check-container">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="marital_status" id="marital_single" value="Single" 
                                                           {{ old('marital_status') == 'Single' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="marital_single">Single</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="marital_status" id="marital_married" value="Married" 
                                                           {{ old('marital_status') == 'Married' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="marital_married">Married</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="marital_status" id="marital_others" value="Others" 
                                                           {{ old('marital_status') == 'Others' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="marital_others">Others</label>
                                                </div>
                                            </div>
                                            @error('marital_status')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 5: Contact Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-address-book"></i> Section 5: Contact Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="current_address" class="form-label">Current Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('current_address') is-invalid @enderror" 
                                                      id="current_address" name="current_address" rows="3" 
                                                      placeholder="Enter your current address" required>{{ old('current_address') }}</textarea>
                                            @error('current_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="permanent_address" class="form-label">Permanent Address</label>
                                            <textarea class="form-control @error('permanent_address') is-invalid @enderror" 
                                                      id="permanent_address" name="permanent_address" rows="3" 
                                                      placeholder="Enter your permanent address" required>{{ old('permanent_address') }}</textarea>
                                            @error('permanent_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                            <select class="form-control @error('nationality') is-invalid @enderror" 
                                                    id="nationality" name="nationality" required>
                                                <option value="Indian" selected>Indian</option>
                                            </select>
                                            @error('nationality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
                                            <select class="form-control @error('state_id') is-invalid @enderror" 
                                                    id="state_id" name="state_id" required>
                                                <option value="">Select State</option>
                                                <!-- States will be loaded dynamically -->
                                            </select>
                                            @error('state_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="district_id" class="form-label">District <span class="text-danger">*</span></label>
                                            <select class="form-control @error('district_id') is-invalid @enderror" 
                                                    id="district_id" name="district_id" required>
                                                <option value="">Select District</option>
                                                <!-- Districts will be loaded based on state selection -->
                                            </select>
                                            @error('district_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="city_id" class="form-label">City <span class="text-danger">*</span></label>
                                            <select class="form-control @error('city_id') is-invalid @enderror" 
                                                    id="city_id" name="city_id" required>
                                                <option value="">Select City</option>
                                                <!-- Cities will be loaded based on district selection -->
                                            </select>
                                            @error('city_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('pincode') is-invalid @enderror" 
                                                   id="pincode" name="pincode" value="{{ old('pincode') }}" 
                                                   placeholder="Enter pincode" required>
                                            @error('pincode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_mobile_no" class="form-label">Mobile No <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select" id="contact_mobile_country_code" name="contact_mobile_country_code" style="max-width: 120px;">
                                                    <option value="+91" selected>+91</option>
                                                    <option value="+1">+1</option>
                                                    <option value="+44">+44</option>
                                                    <option value="+61">+61</option>
                                                    <option value="+33">+33</option>
                                                    <option value="+49">+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('contact_mobile_no') is-invalid @enderror" 
                                                       id="contact_mobile_no" name="contact_mobile_no" value="{{ old('contact_mobile_no') }}" 
                                                       placeholder="Enter mobile number" required>
                                            </div>
                                            @error('contact_mobile_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_whatsapp_no" class="form-label">WhatsApp No</label>
                                            <div class="input-group">
                                                <select class="form-select" id="contact_whatsapp_country_code" name="contact_whatsapp_country_code" style="max-width: 120px;">
                                                    <option value="+91" selected>+91</option>
                                                    <option value="+1">+1</option>
                                                    <option value="+44">+44</option>
                                                    <option value="+61">+61</option>
                                                    <option value="+33">+33</option>
                                                    <option value="+49">+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('contact_whatsapp_no') is-invalid @enderror" 
                                                       id="contact_whatsapp_no" name="contact_whatsapp_no" value="{{ old('contact_whatsapp_no') }}" 
                                                       placeholder="Enter WhatsApp number">
                                            </div>
                                            @error('contact_whatsapp_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="contact_gmail_id" class="form-label">Gmail ID <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('contact_gmail_id') is-invalid @enderror" 
                                                   id="contact_gmail_id" name="contact_gmail_id" value="{{ old('contact_gmail_id') }}" 
                                                   placeholder="Enter email address" required>
                                            @error('contact_gmail_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="current_live_location" class="form-label">Current Live Location</label>
                                            <input type="text" class="form-control @error('current_live_location') is-invalid @enderror" 
                                                   id="current_live_location" name="current_live_location" value="{{ old('current_live_location') }}" 
                                                   placeholder="Enter current live location">
                                            @error('current_live_location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 6: Education & Qualification Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Section 6: Education & Qualification Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="last_qualification" class="form-label">Last Qualification <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_qualification') is-invalid @enderror" 
                                                   id="last_qualification" name="last_qualification" value="{{ old('last_qualification') }}" 
                                                   placeholder="Enter your last qualification" required>
                                            @error('last_qualification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="work_type" class="form-label">Work Type <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('work_type') is-invalid @enderror" 
                                                   id="work_type" name="work_type" value="{{ old('work_type') }}" 
                                                   placeholder="Enter your work type" required>
                                            @error('work_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="work_experience" class="form-label">Work Experience <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('work_experience') is-invalid @enderror" 
                                                   id="work_experience" name="work_experience" value="{{ old('work_experience') }}" 
                                                   placeholder="Enter your work experience" required>
                                            @error('work_experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms & Conditions -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                               type="checkbox" id="terms_accepted" name="terms_accepted" value="1" 
                                               {{ old('terms_accepted') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="terms_accepted">
                                            I agree to the <a href="#" target="_blank">Terms & Conditions</a> <span class="text-danger">*</span>
                                        </label>
                                        @error('terms_accepted')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                 <!-- Status Selection -->
                                 <div class="mb-3">
                                     <label class="form-label">Status <span class="text-danger">*</span></label>
                                     <div class="form-check">
                                         <input class="form-check-input @error('status') is-invalid @enderror" 
                                                type="radio" id="status_active" name="status" value="active" 
                                                {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                         <label class="form-check-label" for="status_active">
                                             <i class="fas fa-check-circle text-success me-1"></i>Active
                                         </label>
                                     </div>
                                     <div class="form-check">
                                         <input class="form-check-input @error('status') is-invalid @enderror" 
                                                type="radio" id="status_inactive" name="status" value="inactive" 
                                                {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                         <label class="form-check-label" for="status_inactive">
                                             <i class="fas fa-times-circle text-danger me-1"></i>Inactive
                                         </label>
                                     </div>
                                     @error('status')
                                         <div class="invalid-feedback d-block">{{ $message }}</div>
                                     @enderror
                                 </div>
                                 
                                 <div class="d-grid gap-2">
                                     <button type="submit" id="submitBtn" class="btn btn-primary" disabled>
                                         <i class="fas fa-save me-2"></i>Register Vendor
                                     </button>
                                     <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                                         <i class="fas fa-times me-2"></i>Cancel
                                     </a>
                                 </div>
                                 
                                 <hr class="my-3">
                                 
                                 <div class="text-muted small">
                                     <h6 class="fw-bold">Vendor Information</h6>
                                     <ul class="list-unstyled mb-0">
                                         <li><i class="fas fa-info-circle me-2"></i>Fill all required fields marked with *</li>
                                         <li><i class="fas fa-building me-2"></i>Business details are mandatory</li>
                                         <li><i class="fas fa-user me-2"></i>Contact information is required</li>
                                         <li><i class="fas fa-handshake me-2"></i>Accept terms & conditions</li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </form>
         </div>
     </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Load states on page load
    loadStates();

    // Enable/disable submit button based on terms checkbox and required fields
    function checkFormValidity() {
        const termsAccepted = $('#terms_accepted').is(':checked');
        
        // Only check terms acceptance for form validity
        if (termsAccepted) {
            $('#submitBtn').prop('disabled', false);
        } else {
            $('#submitBtn').prop('disabled', true);
        }
    }
    
    // Check form validity on input change
    $('input, select, textarea').on('change keyup', checkFormValidity);
    $('#terms_accepted').on('change', checkFormValidity);
    
    // Load states
    function loadStates() {
        $.ajax({
            url: '/api/location/states',
            type: 'GET',
            success: function(data) {
                $('#state_id').empty().append('<option value="">Select State</option>');
                $.each(data, function(key, value) {
                    $('#state_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });
    }
    
    // Load districts based on state selection
    $('#state_id').on('change', function() {
        const stateId = $(this).val();
        $('#district_id').empty().append('<option value="">Select District</option>');
        $('#city_id').empty().append('<option value="">Select City</option>');
        
        if (stateId) {
            loadDistricts(stateId);
        }
    });
    
    // Load cities based on district selection
    $('#district_id').on('change', function() {
        const districtId = $(this).val();
        $('#city_id').empty().append('<option value="">Select City</option>');
        
        if (districtId) {
            loadCities(districtId);
        }
    });
    
    // Load states function
    function loadStates() {
        $.ajax({
            url: '/api/location/states',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#state_id').empty().append('<option value="">Select State</option>');
                    $.each(response.data, function(index, state) {
                        $('#state_id').append('<option value="' + state.id + '">' + state.state_name + '</option>');
                    });
                }
            },
            error: function() {
                console.error('Failed to load states');
            }
        });
    }
    
    // Load districts function
    function loadDistricts(stateId) {
        $.ajax({
            url: '/api/location/districts',
            type: 'GET',
            data: { state_id: stateId },
            success: function(response) {
                if (response.success) {
                    $('#district_id').empty().append('<option value="">Select District</option>');
                    $.each(response.data, function(index, district) {
                        $('#district_id').append('<option value="' + district.id + '">' + district.district_name + '</option>');
                    });
                }
            },
            error: function() {
                console.error('Failed to load districts');
            }
        });
    }
    
    // Product Category Multi-Select Functionality
    $(document).ready(function() {
        // Update selected categories display text
        function updateSelectedCategoriesText() {
            const checkedCategories = $('.category-checkbox:checked');
            const selectAllCheckbox = $('#selectAllCategories');
            
            if (checkedCategories.length === 0) {
                $('#selectedCategoriesText').text('Select Product Categories');
                selectAllCheckbox.prop('indeterminate', false).prop('checked', false);
            } else if (checkedCategories.length === $('.category-checkbox').length) {
                $('#selectedCategoriesText').text('All Categories Selected');
                selectAllCheckbox.prop('indeterminate', false).prop('checked', true);
            } else {
                const selectedNames = [];
                checkedCategories.each(function() {
                    selectedNames.push($(this).next('label').text().trim());
                });
                
                if (selectedNames.length <= 3) {
                    $('#selectedCategoriesText').text(selectedNames.join(', '));
                } else {
                    $('#selectedCategoriesText').text(selectedNames.slice(0, 3).join(', ') + ' and ' + (selectedNames.length - 3) + ' more');
                }
                selectAllCheckbox.prop('indeterminate', true).prop('checked', false);
            }
        }
        
        // Handle Select All checkbox
        $('#selectAllCategories').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.category-checkbox').prop('checked', isChecked);
            updateSelectedCategoriesText();
        });
        
        // Handle individual category checkboxes
        $('.category-checkbox').on('change', function() {
            updateSelectedCategoriesText();
        });
        
        // Prevent dropdown from closing when clicking inside
        $('.dropdown-menu').on('click', function(e) {
            e.stopPropagation();
        });
        
        // Initialize display text on page load
        updateSelectedCategoriesText();
    });
    
    // Load cities function
    function loadCities(districtId) {
        $.ajax({
            url: '/api/location/cities',
            type: 'GET',
            data: { district_id: districtId },
            success: function(response) {
                if (response.success) {
                    $('#city_id').empty().append('<option value="">Select City</option>');
                    $.each(response.data, function(index, city) {
                        $('#city_id').append('<option value="' + city.id + '">' + city.city_name + '</option>');
                    });
                }
            },
            error: function() {
                console.error('Failed to load cities');
            }
        });
    }
    
    // Initialize Select2 for product categories
    $('#product_categories').select2({
        placeholder: 'Select product categories...',
        allowClear: true,
        width: '100%',
        closeOnSelect: false,
        tags: false,
        dropdownParent: $('#product_categories').parent(),
        language: {
            noResults: function() {
                return "No categories found";
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });
    
    // Form validation
    $('#vendorForm').on('submit', function(e) {
        if (!$('#terms_accepted').is(':checked')) {
            e.preventDefault();
            alert('Please accept the Terms & Conditions to proceed.');
            return false;
        }
    });
    
    // Initialize form validation check
    checkFormValidity();
});
</script>
@endpush

@push('styles')
<style>
.form-check-container {
    padding-top: 7px;
}
.card-header {
    font-weight: 600;
}
.text-danger {
    font-weight: 600;
}
#submitBtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Enhanced Select2 styling for product categories */
.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-height: 38px;
    padding: 2px 8px;
    background-color: #fff;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #0d6efd;
    border: 1px solid #0d6efd;
    color: #fff;
    border-radius: 0.25rem;
    padding: 2px 8px;
    margin: 2px;
    font-size: 0.875rem;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 5px;
    font-weight: bold;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #f8f9fa;
}

.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #0d6efd;
    color: #fff;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
}

.is-invalid + .select2-container .select2-selection {
    border-color: #dc3545;
}

.is-invalid + .select2-container.select2-container--focus .select2-selection {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>
@endpush
@endsection