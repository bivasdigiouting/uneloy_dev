@extends('layouts.admin')

@section('title', 'Edit Vendor')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Edit Vendor</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                            <li class="breadcrumb-item active">Edit Vendor</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.vendors.update', $vendor->id) }}" method="POST" id="vendorForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Main Form Content -->
                    <div class="col-lg-8">
                        <!-- Section 1: Business Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-building"></i> Section 1: Business Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                                   id="business_name" name="business_name" 
                                                   value="{{ old('business_name', $vendor->business_name) }}" 
                                                   placeholder="Enter business name" required>
                                            @error('business_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_registration_category" class="form-label">Business Registration Category <span class="text-danger">*</span></label>
                                            <select class="form-control @error('business_registration_category') is-invalid @enderror" 
                                                    id="business_registration_category" name="business_registration_category" required>
                                                <option value="">Select Category</option>
                                                <option value="Private Limited" {{ old('business_registration_category', $vendor->business_registration_category) == 'Private Limited' ? 'selected' : '' }}>Private Limited</option>
                                                <option value="Proprietorship" {{ old('business_registration_category', $vendor->business_registration_category) == 'Proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                                                <option value="Partnership" {{ old('business_registration_category', $vendor->business_registration_category) == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                                <option value="Limited" {{ old('business_registration_category', $vendor->business_registration_category) == 'Limited' ? 'selected' : '' }}>Limited</option>
                                                <option value="NGO" {{ old('business_registration_category', $vendor->business_registration_category) == 'NGO' ? 'selected' : '' }}>NGO</option>
                                            </select>
                                            @error('business_registration_category')
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
                                                    <option value="+91" {{ old('mobile_country_code', $vendor->mobile_country_code) == '+91' ? 'selected' : '' }}>+91</option>
                                                    <option value="+1" {{ old('mobile_country_code', $vendor->mobile_country_code) == '+1' ? 'selected' : '' }}>+1</option>
                                                    <option value="+44" {{ old('mobile_country_code', $vendor->mobile_country_code) == '+44' ? 'selected' : '' }}>+44</option>
                                                    <option value="+61" {{ old('mobile_country_code', $vendor->mobile_country_code) == '+61' ? 'selected' : '' }}>+61</option>
                                                    <option value="+33" {{ old('mobile_country_code', $vendor->mobile_country_code) == '+33' ? 'selected' : '' }}>+33</option>
                                                    <option value="+49" {{ old('mobile_country_code', $vendor->mobile_country_code) == '+49' ? 'selected' : '' }}>+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('mobile_no') is-invalid @enderror"
                                                       id="mobile_no" name="mobile_no"
                                                       value="{{ old('mobile_no', $vendor->mobile_no) }}"
                                                       placeholder="Enter mobile number" required>
                                            </div>
                                            @error('mobile_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gmail_id" class="form-label">Gmail ID <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('gmail_id') is-invalid @enderror"
                                                   id="gmail_id" name="gmail_id"
                                                   value="{{ old('gmail_id', $vendor->gmail_id) }}"
                                                   placeholder="Enter email address" required>
                                            @error('gmail_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="whatsapp_no" class="form-label">WhatsApp No</label>
                                            <div class="input-group">
                                                <select class="form-select" id="whatsapp_country_code" name="whatsapp_country_code" style="max-width: 120px;">
                                                    <option value="+91" {{ old('whatsapp_country_code', $vendor->whatsapp_country_code) == '+91' ? 'selected' : '' }}>+91</option>
                                                    <option value="+1" {{ old('whatsapp_country_code', $vendor->whatsapp_country_code) == '+1' ? 'selected' : '' }}>+1</option>
                                                    <option value="+44" {{ old('whatsapp_country_code', $vendor->whatsapp_country_code) == '+44' ? 'selected' : '' }}>+44</option>
                                                    <option value="+61" {{ old('whatsapp_country_code', $vendor->whatsapp_country_code) == '+61' ? 'selected' : '' }}>+61</option>
                                                    <option value="+33" {{ old('whatsapp_country_code', $vendor->whatsapp_country_code) == '+33' ? 'selected' : '' }}>+33</option>
                                                    <option value="+49" {{ old('whatsapp_country_code', $vendor->whatsapp_country_code) == '+49' ? 'selected' : '' }}>+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('whatsapp_no') is-invalid @enderror"
                                                       id="whatsapp_no" name="whatsapp_no"
                                                       value="{{ old('whatsapp_no', $vendor->whatsapp_no) }}"
                                                       placeholder="Enter WhatsApp number">
                                            </div>
                                            @error('whatsapp_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_gst_no" class="form-label">Business GST No</label>
                                            <input type="text" class="form-control @error('business_gst_no') is-invalid @enderror"
                                                   id="business_gst_no" name="business_gst_no"
                                                   value="{{ old('business_gst_no', $vendor->business_gst_no ?? ($vendor->gst_no ?? null)) }}"
                                                   placeholder="Enter GST number">
                                            @error('business_gst_no')
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
                                                   id="contact_person" name="contact_person" 
                                                   value="{{ old('contact_person', $vendor->contact_person) }}" 
                                                   placeholder="Enter contact person name" required>
                                            @error('contact_person')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_person_designation" class="form-label">Contact Person Designation <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('contact_person_designation') is-invalid @enderror" 
                                                   id="contact_person_designation" name="contact_person_designation" 
                                                   value="{{ old('contact_person_designation', $vendor->contact_person_designation) }}" 
                                                   placeholder="Enter designation" required>
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
                                                      placeholder="Describe your facility">{{ old('facility', $vendor->facility) }}</textarea>
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
                                                      placeholder="Tell us about your business">{{ old('about_us', $vendor->about_us) }}</textarea>
                                            @error('about_us')
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
                                                      placeholder="Enter complete business address" required>{{ old('business_full_address', $vendor->business_full_address ?? ($vendor->business_address ?? null)) }}</textarea>
                                            @error('business_full_address')
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
                                                   id="business_location" name="business_location" 
                                                   value="{{ old('business_location', $vendor->business_location) }}" 
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
                                                @if(isset($productCategories))
                                                    @foreach($productCategories as $category)
                                                        <option value="{{ $category->name }}" 
                                                                {{ in_array($category->name, old('product_categories', $vendor->product_categories ?? [])) ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                                <option value="State Bank of India" {{ old('bank_name', $vendor->bank_name) == 'State Bank of India' ? 'selected' : '' }}>State Bank of India</option>
                                                <option value="HDFC Bank" {{ old('bank_name', $vendor->bank_name) == 'HDFC Bank' ? 'selected' : '' }}>HDFC Bank</option>
                                                <option value="ICICI Bank" {{ old('bank_name', $vendor->bank_name) == 'ICICI Bank' ? 'selected' : '' }}>ICICI Bank</option>
                                                <option value="Axis Bank" {{ old('bank_name', $vendor->bank_name) == 'Axis Bank' ? 'selected' : '' }}>Axis Bank</option>
                                                <option value="Punjab National Bank" {{ old('bank_name', $vendor->bank_name) == 'Punjab National Bank' ? 'selected' : '' }}>Punjab National Bank</option>
                                                <option value="Bank of Baroda" {{ old('bank_name', $vendor->bank_name) == 'Bank of Baroda' ? 'selected' : '' }}>Bank of Baroda</option>
                                                <option value="Canara Bank" {{ old('bank_name', $vendor->bank_name) == 'Canara Bank' ? 'selected' : '' }}>Canara Bank</option>
                                                <option value="Union Bank of India" {{ old('bank_name', $vendor->bank_name) == 'Union Bank of India' ? 'selected' : '' }}>Union Bank of India</option>
                                                <option value="Bank of India" {{ old('bank_name', $vendor->bank_name) == 'Bank of India' ? 'selected' : '' }}>Bank of India</option>
                                                <option value="Indian Bank" {{ old('bank_name', $vendor->bank_name) == 'Indian Bank' ? 'selected' : '' }}>Indian Bank</option>
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
                                                   id="branch_name" name="branch_name" 
                                                   value="{{ old('branch_name', $vendor->branch_name) }}" 
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
                                                   id="account_holder_name" name="account_holder_name" 
                                                   value="{{ old('account_holder_name', $vendor->account_holder_name) }}" 
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
                                                   id="account_no" name="account_no" 
                                                   value="{{ old('account_no', $vendor->account_no) }}" 
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
                                                   id="ifsc_code" name="ifsc_code" 
                                                   value="{{ old('ifsc_code', $vendor->ifsc_code) }}" 
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
                                                   id="pan_no" name="pan_no" 
                                                   value="{{ old('pan_no', $vendor->pan_no) }}" 
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
                                                   id="aadhar_no" name="aadhar_no" 
                                                   value="{{ old('aadhar_no', $vendor->aadhar_no) }}" 
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
                                                   id="upi_no" name="upi_no" 
                                                   value="{{ old('upi_no', $vendor->upi_no) }}" 
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
                                                @if(isset($vendorTypes))
                                                    @foreach($vendorTypes as $vendorType)
                                                        <option value="{{ $vendorType->vendor_type }}" 
                                                                {{ old('vendor_type', $vendor->vendor_type) == $vendorType->vendor_type ? 'selected' : '' }}>
                                                            {{ $vendorType->vendor_type }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                                   id="first_name" name="first_name" 
                                                   value="{{ old('first_name', $vendor->first_name) }}" 
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
                                                   id="middle_name" name="middle_name" 
                                                   value="{{ old('middle_name', $vendor->middle_name) }}" 
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
                                                   id="last_name" name="last_name" 
                                                   value="{{ old('last_name', $vendor->last_name) }}" 
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
                                                   id="fathers_name" name="fathers_name" 
                                                   value="{{ old('fathers_name', $vendor->fathers_name) }}" 
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
                                                   id="mothers_name" name="mothers_name" 
                                                   value="{{ old('mothers_name', $vendor->mothers_name) }}" 
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
                                                <option value="A+" {{ old('blood_group', $vendor->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_group', $vendor->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_group', $vendor->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_group', $vendor->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('blood_group', $vendor->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_group', $vendor->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('blood_group', $vendor->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_group', $vendor->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
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
                                                   id="date_of_birth" name="date_of_birth" 
                                                   value="{{ old('date_of_birth', $vendor->date_of_birth) }}" required>
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
                                                           {{ old('gender', $vendor->gender) == 'Male' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="gender_male">Male</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender_female" value="Female" 
                                                           {{ old('gender', $vendor->gender) == 'Female' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="gender_female">Female</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender_other" value="Other" 
                                                           {{ old('gender', $vendor->gender) == 'Other' ? 'checked' : '' }} required>
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
                                                           {{ old('marital_status', $vendor->marital_status) == 'Single' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="marital_single">Single</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="marital_status" id="marital_married" value="Married" 
                                                           {{ old('marital_status', $vendor->marital_status) == 'Married' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="marital_married">Married</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="marital_status" id="marital_others" value="Others" 
                                                           {{ old('marital_status', $vendor->marital_status) == 'Others' ? 'checked' : '' }} required>
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
                                                      placeholder="Enter your current address" required>{{ old('current_address', $vendor->current_address) }}</textarea>
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
                                                      placeholder="Enter your permanent address" required>{{ old('permanent_address', $vendor->permanent_address) }}</textarea>
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
                                                <option value="Indian" {{ old('nationality', $vendor->nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
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
                                                   id="pincode" name="pincode" 
                                                   value="{{ old('pincode', $vendor->pincode) }}" 
                                                   placeholder="Enter pincode" required>
                                            @error('pincode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_mobile_no" class="form-label">Mobile No <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select" id="contact_mobile_country_code" name="contact_mobile_country_code" style="max-width: 120px;">
                                                    <option value="+91" {{ old('contact_mobile_country_code', $vendor->contact_mobile_country_code) == '+91' ? 'selected' : '' }}>+91</option>
                                                    <option value="+1" {{ old('contact_mobile_country_code', $vendor->contact_mobile_country_code) == '+1' ? 'selected' : '' }}>+1</option>
                                                    <option value="+44" {{ old('contact_mobile_country_code', $vendor->contact_mobile_country_code) == '+44' ? 'selected' : '' }}>+44</option>
                                                    <option value="+61" {{ old('contact_mobile_country_code', $vendor->contact_mobile_country_code) == '+61' ? 'selected' : '' }}>+61</option>
                                                    <option value="+33" {{ old('contact_mobile_country_code', $vendor->contact_mobile_country_code) == '+33' ? 'selected' : '' }}>+33</option>
                                                    <option value="+49" {{ old('contact_mobile_country_code', $vendor->contact_mobile_country_code) == '+49' ? 'selected' : '' }}>+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('contact_mobile_no') is-invalid @enderror" 
                                                       id="contact_mobile_no" name="contact_mobile_no" 
                                                       value="{{ old('contact_mobile_no', $vendor->contact_mobile_no) }}" 
                                                       placeholder="Enter mobile number" required>
                                            </div>
                                            @error('contact_mobile_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="contact_whatsapp_no" class="form-label">WhatsApp No</label>
                                            <div class="input-group">
                                                <select class="form-select" id="contact_whatsapp_country_code" name="contact_whatsapp_country_code" style="max-width: 120px;">
                                                    <option value="+91" {{ old('contact_whatsapp_country_code', $vendor->contact_whatsapp_country_code) == '+91' ? 'selected' : '' }}>+91</option>
                                                    <option value="+1" {{ old('contact_whatsapp_country_code', $vendor->contact_whatsapp_country_code) == '+1' ? 'selected' : '' }}>+1</option>
                                                    <option value="+44" {{ old('contact_whatsapp_country_code', $vendor->contact_whatsapp_country_code) == '+44' ? 'selected' : '' }}>+44</option>
                                                    <option value="+61" {{ old('contact_whatsapp_country_code', $vendor->contact_whatsapp_country_code) == '+61' ? 'selected' : '' }}>+61</option>
                                                    <option value="+33" {{ old('contact_whatsapp_country_code', $vendor->contact_whatsapp_country_code) == '+33' ? 'selected' : '' }}>+33</option>
                                                    <option value="+49" {{ old('contact_whatsapp_country_code', $vendor->contact_whatsapp_country_code) == '+49' ? 'selected' : '' }}>+49</option>
                                                </select>
                                                <input type="text" class="form-control @error('contact_whatsapp_no') is-invalid @enderror" 
                                                       id="contact_whatsapp_no" name="contact_whatsapp_no" 
                                                       value="{{ old('contact_whatsapp_no', $vendor->contact_whatsapp_no) }}" 
                                                       placeholder="Enter WhatsApp number">
                                            </div>
                                            @error('contact_whatsapp_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="contact_gmail_id" class="form-label">Gmail ID <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('contact_gmail_id') is-invalid @enderror" 
                                                   id="contact_gmail_id" name="contact_gmail_id" 
                                                   value="{{ old('contact_gmail_id', $vendor->contact_gmail_id) }}" 
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
                                                   id="current_live_location" name="current_live_location" 
                                                   value="{{ old('current_live_location', $vendor->current_live_location) }}" 
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
                                                   id="last_qualification" name="last_qualification" 
                                                   value="{{ old('last_qualification', $vendor->last_qualification) }}" 
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
                                                   id="work_type" name="work_type" 
                                                   value="{{ old('work_type', $vendor->work_type) }}" 
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
                                                   id="work_experience" name="work_experience" 
                                                   value="{{ old('work_experience', $vendor->work_experience) }}" 
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
                                               {{ old('terms_accepted', $vendor->terms_accepted) ? 'checked' : '' }}>
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
                    
                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Actions -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-cogs"></i> Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status_active" value="active" 
                                               {{ old('status', $vendor->status) == 'active' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status_active">
                                            <i class="fas fa-check-circle text-success"></i> Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" 
                                               {{ old('status', $vendor->status) == 'inactive' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status_inactive">
                                            <i class="fas fa-times-circle text-danger"></i> Inactive
                                        </label>
                                    </div>
                                    @error('status')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>Update Vendor
                                    </button>
                                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vendor Information -->
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Vendor Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-lightbulb"></i> Guidelines:</h6>
                                    <ul class="mb-0 small">
                                        <li>Fill all required fields marked with <span class="text-danger">*</span></li>
                                        <li>Ensure all business details are accurate</li>
                                        <li>Upload valid documents if required</li>
                                        <li>Double-check bank details for accuracy</li>
                                        <li>Contact information should be current</li>
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
    // Initialize Select2 for product categories
    $('.select2').select2({
        placeholder: 'Select product categories...',
        allowClear: true,
        width: '100%'
    });
    
    // Load states on page load
    loadStates();
    
    // Load districts and cities if vendor has existing data
    @if($vendor->state_id)
        loadDistricts({{ $vendor->state_id }}, {{ $vendor->district_id ?? 'null' }});
    @endif
    
    @if($vendor->district_id)
        loadCities({{ $vendor->district_id }}, {{ $vendor->city_id ?? 'null' }});
    @endif
    
    // State change event
    $('#state_id').change(function() {
        var stateId = $(this).val();
        if (stateId) {
            loadDistricts(stateId);
        } else {
            $('#district_id').empty().append('<option value="">Select District</option>');
            $('#city_id').empty().append('<option value="">Select City</option>');
        }
    });
    
    // District change event
    $('#district_id').change(function() {
        var districtId = $(this).val();
        if (districtId) {
            loadCities(districtId);
        } else {
            $('#city_id').empty().append('<option value="">Select City</option>');
        }
    });
    
    // Form validation
    $('#vendorForm').on('submit', function(e) {
        var isValid = true;
        var errorMessage = '';
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
                errorMessage += 'Please fill all required fields.\n';
                return false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Check terms acceptance
        if (!$('#terms_accepted').is(':checked')) {
            isValid = false;
            errorMessage += 'Please accept the terms and conditions.\n';
        }
        
        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }
        
        // Show loading state
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
    });
});

function loadStates() {
    $.ajax({
        url: '/api/states',
        type: 'GET',
        success: function(data) {
            var stateSelect = $('#state_id');
            stateSelect.empty().append('<option value="">Select State</option>');
            
            $.each(data, function(index, state) {
                var selected = {{ $vendor->state_id ?? 'null' }} == state.id ? 'selected' : '';
                stateSelect.append('<option value="' + state.id + '" ' + selected + '>' + state.name + '</option>');
            });
        },
        error: function() {
            console.log('Error loading states');
        }
    });
}

function loadDistricts(stateId, selectedDistrictId = null) {
    $.ajax({
        url: '/api/districts/' + stateId,
        type: 'GET',
        success: function(data) {
            var districtSelect = $('#district_id');
            districtSelect.empty().append('<option value="">Select District</option>');
            
            $.each(data, function(index, district) {
                var selected = selectedDistrictId == district.id ? 'selected' : '';
                districtSelect.append('<option value="' + district.id + '" ' + selected + '>' + district.name + '</option>');
            });
            
            // Clear cities
            $('#city_id').empty().append('<option value="">Select City</option>');
        },
        error: function() {
            console.log('Error loading districts');
        }
    });
}

function loadCities(districtId, selectedCityId = null) {
    $.ajax({
        url: '/api/cities/' + districtId,
        type: 'GET',
        success: function(data) {
            var citySelect = $('#city_id');
            citySelect.empty().append('<option value="">Select City</option>');
            
            $.each(data, function(index, city) {
                var selected = selectedCityId == city.id ? 'selected' : '';
                citySelect.append('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>');
            });
        },
        error: function() {
            console.log('Error loading cities');
        }
    });
}
</script>
@endpush
@endsection
