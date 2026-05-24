/./@extends('ecard.ecard')
@section('title', 'New Registration')
@section('content')
<section class="content">
    <div class="content-inner">
        <div class="container-fluid py-3">
            <div class="card p-4">
                <h5 class="card-title mb-3"><i class="fas fa-user-plus me-2"></i>Create New Registration</h5>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('ecard.registration.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Section 1: Official Details -->
                        <div class="col-12">
                            <div class="card p-3">
                                <h6 class="fw-bold mb-3"><i class="fas fa-briefcase me-2"></i>Official Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Select Department *</label>
                                        <input type="hidden" name="department_level" value="{{ $allowedDepartmentSlug }}">
                                        <select class="form-select" disabled>
                                            <option value="{{ $allowedDepartmentSlug }}" selected>{{ $allowedDepartmentLabel }}</option>
                                        </select>
                                    </div>
                                    @if(($allowedDepartmentSlug ?? '') !== 'customer')
                                        <div class="col-md-6">
                                            <label class="form-label">Select Business Category</label>
                                            <select name="business_category" class="form-select">
                                                <option value="">-- Select Business Category --</option>
                                                @foreach($businessCategories as $bc)
                                                    <option value="{{ $bc }}" {{ old('business_category')===$bc?'selected':'' }}>{{ $bc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(($allowedDepartmentSlug ?? '') === 'customer')
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-user-check me-2"></i>Member Details</h6>
                                    <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Aadhaar No. *</label>
                                        <div class="input-group">
                                            <input type="text" name="aadhaar_no" id="aadhaarNoInput" class="form-control" value="{{ $aadhaarPrefill ?? old('aadhaar_no') }}" placeholder="Enter Aadhaar number" required>
                                            <button type="button" class="btn btn-outline-primary" id="aadhaarVerifyBtn">Verify Aadhaar</button>
                                            <button type="button" class="btn btn-outline-secondary ms-2" id="aadhaarEditBtn" style="display:none;">Edit Aadhaar No</button>
                                        </div>
                                        <div class="small mt-1" id="aadhaarError"></div>
                                    </div>
                                        <div class="col-md-4">
                                            <label class="form-label">User Type *</label>
                                            <select id="customerUserTypeSelect" name="customer_user_type" class="form-select" required>
                                                <option value="">-- Select --</option>
                                                <option value="free" {{ old('customer_user_type') === 'free' ? 'selected' : '' }}>Free User</option>
                                                <option value="paid" {{ old('customer_user_type') === 'paid' ? 'selected' : '' }}>Paid User</option>
                                            </select>
                                        </div>

                                        <div class="col-md-8" id="firstRechargePlanGroup" style="display:none;">
                                            <label class="form-label">First Recharge Plan *</label>
                                            <select id="firstRechargePlanSelect" name="first_recharge_plan_id" class="form-select">
                                                <option value="">-- Select Plan --</option>
                                                @foreach(($firstRechargePlans ?? collect()) as $plan)
                                                    <option
                                                        value="{{ $plan->id }}"
                                                        data-plan-name="{{ $plan->plan_name }}"
                                                        data-plan-value="{{ (float) $plan->plan_value }}"
                                                        data-bonus-value="{{ (float) $plan->bonus_value }}"
                                                        data-total-value="{{ (float) $plan->total_value }}"
                                                        {{ old('first_recharge_plan_id') == $plan->id ? 'selected' : '' }}
                                                    >
                                                        {{ $plan->plan_name }} (₹{{ number_format((float) $plan->plan_value, 2) }} + Bonus ₹{{ number_format((float) $plan->bonus_value, 2) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="small mt-1 text-muted" id="firstRechargePlanSummary"></div>
                                            <div class="small mt-1 text-muted">Your Wallet Balance: ₹{{ number_format((float) ($user->wallet_balance ?? 0), 2) }}</div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Section 2: Business Details -->
                        @if(($allowedDepartmentSlug ?? '') !== 'customer')
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-building me-2"></i>Business Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Business Name *</label>
                                            <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}" placeholder="Enter business name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Mobile No. *</label>
                                            <input type="text" name="business_mobile" class="form-control" value="{{ old('business_mobile') }}" placeholder="Enter mobile number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Whatsapp No.</label>
                                            <input type="text" name="business_whatsapp" class="form-control" value="{{ old('business_whatsapp') }}" placeholder="Enter WhatsApp number">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Gmail ID</label>
                                            <input type="email" name="business_gmail" class="form-control" value="{{ old('business_gmail') }}" placeholder="name@gmail.com">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Business Full Address *</label>
                                            <textarea name="business_address" class="form-control" rows="2" placeholder="Enter full business address" required>{{ old('business_address') }}</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Business GST No.</label>
                                            <input type="text" name="business_gst" class="form-control" value="{{ old('business_gst') }}" placeholder="Enter GST number">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">UPI Address</label>
                                            <input type="text" name="business_upi" class="form-control" value="{{ old('business_upi') }}" placeholder="Enter UPI ID">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Business Location (Map) *</label>
                                            <input type="text" name="business_location_map" class="form-control" value="{{ old('business_location_map') }}" placeholder="Paste Google Map link" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Section 3: Personal Details -->
                        <div class="col-12">
                            <div class="card p-3">
                                <h6 class="fw-bold mb-3"><i class="fas fa-id-card me-2"></i>Personal Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">First Name *</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Last Name *</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Father's Name</label>
                                        <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" placeholder="Enter father's name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mother's Name</label>
                                        <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}" placeholder="Enter mother's name">
                                    </div>
<div class="col-md-4">
                                        <label class="form-label">Select Blood Group</label>
                                        <select name="blood_group" class="form-select">

                                            <option value="">-- Select Blood Group --</option>
                                            @foreach($bloodGroups as $bg)
                                                <option value="{{ $bg }}" {{ old('blood_group')===$bg?'selected':'' }}>{{ $bg }}</option>
                                            @endforeach
</select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Cadr Number</label>
                                        <input type="text" name="cadr_number" class="form-control" value="{{ old('cadr_number') }}" placeholder="Enter 16-digit card number" maxlength="16" pattern="\\d{16}" inputmode="numeric">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Gender</label>
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" value="Male" {{ old('gender')==='Male'?'checked':'' }}>
                                                <label class="form-check-label">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" value="Fe-Male" {{ old('gender')==='Fe-Male'?'checked':'' }}>
                                                <label class="form-check-label">Fe-Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" value="Others" {{ old('gender')==='Others'?'checked':'' }}>
                                                <label class="form-check-label">Others</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Marital Status</label>
                                        <select name="marital_status" class="form-select">
                                            <option value="">-- Select --</option>
                                            <option value="Single" {{ old('marital_status')==='Single'?'selected':'' }}>Single</option>
                                            <option value="Married" {{ old('marital_status')==='Married'?'selected':'' }}>Married</option>
                                            <option value="Other" {{ old('marital_status')==='Other'?'selected':'' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Profile Image (optional)</label>
                                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Contact Details -->
                        <div class="col-12">
                            <div class="card p-3">
                                <h6 class="fw-bold mb-3"><i class="fas fa-address-card me-2"></i>Contact Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Current Address</label>
                                        <textarea name="current_address" class="form-control" rows="2" placeholder="Enter current address">{{ old('current_address') }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control" rows="2" placeholder="Enter permanent address">{{ old('permanent_address') }}</textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Nationality</label>
                                        <input type="text" name="nationality" class="form-control" value="{{ old('nationality','INDIA') }}" placeholder="INDIA">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">State *</label>
                                        <select id="stateSelect" name="state_id" class="form-select" required>
                                            <option value="">-- Select State --</option>
                                            @foreach($states as $s)
                                                <option value="{{ $s->id }}" {{ old('state_id')==$s->id?'selected':'' }}>{{ $s->state_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">District *</label>
                                        <select id="districtSelect" name="district_id" class="form-select" required>
                                            <option value="">-- Select District --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">City *</label>
                                        <select id="citySelect" name="city_id" class="form-select" required>
                                            <option value="">-- Select City --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" id="cityOtherGroup" style="display:none;">
                                        <label class="form-label">Other City Name *</label>
                                        <input type="text" id="cityOtherInput" name="city_other" class="form-control" value="{{ old('city_other') }}" placeholder="Enter city name">
                                    </div>
                                    @if(($allowedDepartmentSlug ?? '') === 'customer')
                                        <div class="col-md-3">
                                            <label class="form-label">Area *</label>
                                            <select id="areaSelect" name="area" class="form-select" required>
                                                <option value="">-- Select Area --</option>
                                                <option value="Village_area" {{ old('area')==='Village_area'?'selected':'' }}>Village Area</option>
                                                <option value="Municipality_area" {{ old('area')==='Municipality_area'?'selected':'' }}>Municipality Area</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="panchayatGroup" style="display:none;">
                                            <label class="form-label">Panchayat *</label>
                                            <select id="panchayatSelect" name="panchayat" class="form-select">
                                                <option value="">-- Select Panchayat --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="panchayatOtherGroup" style="display:none;">
                                            <label class="form-label">Other Panchayat Name *</label>
                                            <input type="text" id="panchayatOtherInput" name="panchayat_other" class="form-control" value="{{ old('panchayat_other') }}" placeholder="Enter panchayat name">
                                        </div>
                                        <div class="col-md-3" id="villageGroup" style="display:none;">
                                            <label class="form-label">Village Name *</label>
                                            <select id="villageSelect" name="village_name" class="form-select">
                                                <option value="">-- Select Village Name --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="villageOtherGroup" style="display:none;">
                                            <label class="form-label">Other Village Name *</label>
                                            <input type="text" id="villageOtherInput" name="village_other" class="form-control" value="{{ old('village_other') }}" placeholder="Enter village name">
                                        </div>
                                        <div class="col-md-3" id="municipalityGroup" style="display:none;">
                                            <label class="form-label">Municipality *</label>
                                            <select id="municipalitySelect" name="municipality" class="form-select">
                                                <option value="">-- Select Municipality --</option>
                                            </select>
                                            <input type="hidden" id="municipalityIdInput" name="municipality_id" value="{{ old('municipality_id') }}">
                                        </div>
                                        <div class="col-md-3" id="municipalityOtherGroup" style="display:none;">
                                            <label class="form-label">Other Municipality Name *</label>
                                            <input type="text" id="municipalityOtherInput" name="municipality_other" class="form-control" value="{{ old('municipality_other') }}" placeholder="Enter municipality name">
                                        </div>
                                        <div class="col-md-3" id="wardGroup" style="display:none;">
                                            <label class="form-label">Ward No & Name *</label>
                                            <select id="wardSelect" name="ward_no" class="form-select">
                                                <option value="">-- Select Ward No --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="wardOtherGroup" style="display:none;">
                                            <label class="form-label">Other Ward No & Name *</label>
                                            <input type="text" id="wardOtherInput" name="ward_other" class="form-control" value="{{ old('ward_other') }}" placeholder="Enter ward no & name">
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <label class="form-label">Pin Code *</label>
                                        <input type="text" name="pin_code" class="form-control" value="{{ old('pin_code') }}" placeholder="Enter pin code" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Mobile No. *</label>
                                        <input type="text" name="mobile_no" class="form-control" value="{{ old('mobile_no') }}" placeholder="Enter mobile number" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">E-Mail ID *</label>
                                        <input type="email" name="email_id" class="form-control" value="{{ old('email_id') }}" placeholder="name@example.com" required>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Bank Details -->
                        @if(($allowedDepartmentSlug ?? '') !== 'customer')
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-university me-2"></i>Bank Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">IFSC Code</label>
                                            <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code') }}" placeholder="Enter IFSC code">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Bank Name</label>
                                            <select name="bank_name" class="form-select">
                                                <option value="">-- Select Bank --</option>
                                                @foreach($banks as $b)
                                                    <option value="{{ $b->bank_name }}" {{ old('bank_name')===$b->bank_name?'selected':'' }}>{{ $b->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Branch Name</label>
                                            <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name') }}" placeholder="Enter branch name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Account No.</label>
                                            <input type="text" name="account_no" class="form-control" value="{{ old('account_no') }}" placeholder="Enter account number">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">PAN No.</label>
                                            <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no') }}" placeholder="Enter PAN number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Section 6: Qualification & Experience -->
                        <div class="col-12">
                            <div class="card p-3">
                                <h6 class="fw-bold mb-3"><i class="fas fa-graduation-cap me-2"></i>Qualification & Experience</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Last Qualification</label>
                                        <input type="text" name="last_qualification" class="form-control" value="{{ old('last_qualification') }}" placeholder="Enter last qualification">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Work Type</label>
                                        <input type="text" name="work_type" class="form-control" value="{{ old('work_type') }}" placeholder="Enter work type">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Work Experience</label>
                                        <input type="text" name="work_experience" class="form-control" value="{{ old('work_experience') }}" placeholder="Enter work experience">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 7: KYC Details -->
                        @if(($allowedDepartmentSlug ?? '') !== 'customer')
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-file-alt me-2"></i>KYC Details</h6>

                                    <div class="row g-3">
                                        <!-- Aadhaar Front -->
                                        <div class="col-md-4">
                                            <label class="form-label">Aadhaar Front (Image/PDF) *</label>
                                            <input type="file" name="aadhaar_front" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- Aadhaar Back -->
                                        <div class="col-md-4">
                                            <label class="form-label">Aadhaar Back (Image/PDF) *</label>
                                            <input type="file" name="aadhaar_back" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- PAN Card -->
                                        <div class="col-md-4">
                                            <label class="form-label">PAN Card (Image/PDF) *</label>
                                            <input type="file" name="pan_card" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- Cheque Book -->
                                        <div class="col-md-4">
                                            <label class="form-label">Cheque Book (Image/PDF) *</label>
                                            <input type="file" name="cheque_book" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- Business Document -->
                                        <div class="col-md-4">
                                            <label class="form-label">Business Document (Image/PDF) *</label>
                                            <input type="file" name="business_document" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- GST Document -->
                                        <div class="col-md-4">
                                            <label class="form-label">GST Document (Optional)</label>
                                            <input type="file" name="gst_document" class="form-control kyc-file-input" accept="image/*,.pdf">
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- Business Photo -->
                                        <div class="col-md-4">
                                            <label class="form-label">Business Photo (Image/PDF) *</label>
                                            <input type="file" name="business_photo" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- Signature -->
                                        <div class="col-md-4">
                                            <label class="form-label">Signature (Image/PDF) *</label>
                                            <input type="file" name="signature" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                        <!-- User Photo -->
                                        <div class="col-md-4">
                                            <label class="form-label">User Photo (Image/PDF) *</label>
                                            <input type="file" name="user_photo" class="form-control kyc-file-input" accept="image/*,.pdf" required>
                                            <div class="small mt-1 text-muted">Max size: 2MB</div>
                                            <div class="kyc-preview mt-2 border rounded p-2 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                <span class="text-muted">No file selected</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Agreement and Submit -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="agree_terms" id="agreeTerms" value="1" required>
                                    <label class="form-check-label" for="agreeTerms">I Agree With Terms And Conditions</label>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Submit Registration</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const stateSelect = document.getElementById('stateSelect');
    const districtSelect = document.getElementById('districtSelect');
    const citySelect = document.getElementById('citySelect');
    const cityOtherGroup = document.getElementById('cityOtherGroup');
    const cityOtherInput = document.getElementById('cityOtherInput');
    const isCustomer = @json(($allowedDepartmentSlug ?? '') === 'customer');

    const customerUserTypeSelect = isCustomer ? document.getElementById('customerUserTypeSelect') : null;
    const firstRechargePlanGroup = isCustomer ? document.getElementById('firstRechargePlanGroup') : null;
    const firstRechargePlanSelect = isCustomer ? document.getElementById('firstRechargePlanSelect') : null;
    const firstRechargePlanSummary = isCustomer ? document.getElementById('firstRechargePlanSummary') : null;

    const areaSelect = isCustomer ? document.getElementById('areaSelect') : null;
    const panchayatSelect = isCustomer ? document.getElementById('panchayatSelect') : null;
    const villageSelect = isCustomer ? document.getElementById('villageSelect') : null;
    const municipalitySelect = isCustomer ? document.getElementById('municipalitySelect') : null;
    const wardSelect = isCustomer ? document.getElementById('wardSelect') : null;

    const panchayatGroup = isCustomer ? document.getElementById('panchayatGroup') : null;
    const panchayatOtherGroup = isCustomer ? document.getElementById('panchayatOtherGroup') : null;
    const panchayatOtherInput = isCustomer ? document.getElementById('panchayatOtherInput') : null;
    const villageGroup = isCustomer ? document.getElementById('villageGroup') : null;
    const villageOtherGroup = isCustomer ? document.getElementById('villageOtherGroup') : null;
    const villageOtherInput = isCustomer ? document.getElementById('villageOtherInput') : null;
    const municipalityGroup = isCustomer ? document.getElementById('municipalityGroup') : null;
    const municipalityIdInput = isCustomer ? document.getElementById('municipalityIdInput') : null;
    const municipalityOtherGroup = isCustomer ? document.getElementById('municipalityOtherGroup') : null;
    const municipalityOtherInput = isCustomer ? document.getElementById('municipalityOtherInput') : null;
    const wardGroup = isCustomer ? document.getElementById('wardGroup') : null;
    const wardOtherGroup = isCustomer ? document.getElementById('wardOtherGroup') : null;
    const wardOtherInput = isCustomer ? document.getElementById('wardOtherInput') : null;
    const OTHER_OPTION_VALUE = '__other__';
    const aadhaarInput = document.getElementById('aadhaarNoInput');
    const aadhaarVerifyBtn = document.getElementById('aadhaarVerifyBtn');
    const aadhaarEditBtn = document.getElementById('aadhaarEditBtn');
    const aadhaarError = document.getElementById('aadhaarError');

    function syncCityOtherUi() {
        const show = citySelect.value === OTHER_OPTION_VALUE;
        cityOtherGroup.style.display = show ? '' : 'none';
        cityOtherInput.required = show;
        if (!show) cityOtherInput.value = '';
    }

    function syncPanchayatOtherUi() {
        if (!isCustomer) return;
        const show = areaSelect.value === 'Village_area' && panchayatSelect.value === OTHER_OPTION_VALUE;
        panchayatOtherGroup.style.display = show ? '' : 'none';
        panchayatOtherInput.required = show;
        if (!show) panchayatOtherInput.value = '';
    }

    function syncVillageOtherUi() {
        if (!isCustomer) return;
        const show = areaSelect.value === 'Village_area' && villageSelect.value === OTHER_OPTION_VALUE;
        villageOtherGroup.style.display = show ? '' : 'none';
        villageOtherInput.required = show;
        if (!show) villageOtherInput.value = '';
    }

    function ensureOtherOption(selectEl) {
        if (!selectEl) return;
        const has = Array.from(selectEl.options).some(o => o.value === OTHER_OPTION_VALUE);
        if (has) return;
        const opt = document.createElement('option');
        opt.value = OTHER_OPTION_VALUE;
        opt.textContent = 'Other';
        selectEl.appendChild(opt);
    }

    function syncMunicipalityOtherUi() {
        if (!isCustomer) return;
        const show = areaSelect.value === 'Municipality_area' && municipalitySelect.value === OTHER_OPTION_VALUE;
        municipalityOtherGroup.style.display = show ? '' : 'none';
        municipalityOtherInput.required = show;
        if (!show) municipalityOtherInput.value = '';
    }

    function syncWardOtherUi() {
        if (!isCustomer) return;
        const show = areaSelect.value === 'Municipality_area' && wardSelect.value === OTHER_OPTION_VALUE;
        wardOtherGroup.style.display = show ? '' : 'none';
        wardOtherInput.required = show;
        if (!show) wardOtherInput.value = '';
    }

    function setFirstRechargePlanUi() {
        if (!isCustomer || !customerUserTypeSelect || !firstRechargePlanGroup || !firstRechargePlanSelect) return;
        const paid = customerUserTypeSelect.value === 'paid';
        firstRechargePlanGroup.style.display = paid ? '' : 'none';
        firstRechargePlanSelect.required = paid;
        if (!paid) {
            firstRechargePlanSelect.value = '';
            if (firstRechargePlanSummary) firstRechargePlanSummary.textContent = '';
        } else {
            updateFirstRechargePlanSummary();
        }
    }

    function updateFirstRechargePlanSummary() {
        if (!isCustomer || !firstRechargePlanSelect || !firstRechargePlanSummary) return;
        const opt = firstRechargePlanSelect.options[firstRechargePlanSelect.selectedIndex];
        if (!opt || !opt.value) {
            firstRechargePlanSummary.textContent = '';
            return;
        }
        const planName = opt.dataset.planName || '';
        const planValue = opt.dataset.planValue || '0';
        const bonusValue = opt.dataset.bonusValue || '0';
        const totalValue = opt.dataset.totalValue || '0';
        firstRechargePlanSummary.textContent = `${planName}: Pay ₹${Number(planValue).toFixed(2)} | Bonus ₹${Number(bonusValue).toFixed(2)} | Credit ₹${Number(totalValue).toFixed(2)}`;
    }

    function setAadhaarError(message, isSuccess = false) {
        if (!aadhaarError) return;
        aadhaarError.textContent = message || '';
        if (!message) {
            aadhaarError.style.color = '';
            return;
        }
        aadhaarError.style.color = isSuccess ? '#198754' : '#dc3545';
    }

    function isValidAadhaar(value) {
        const v = (value || '').trim();
        return /^\d{12}$/.test(v);
    }

    async function verifyAadhaar() {
        if (!aadhaarInput) return;
        const value = aadhaarInput.value;
        setAadhaarError('');

        if (!isValidAadhaar(value)) {
            setAadhaarError('Please enter a valid 12-digit Aadhaar number.');
            return;
        }

        aadhaarVerifyBtn.disabled = true;
        aadhaarVerifyBtn.textContent = 'Verifying...';

        try {
            const res = await fetch('{{ route('ecard.registration.verify-aadhaar') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    aadhaar_no: value,
                }),
            });

            const data = await res.json();

            if (!res.ok || (data.status && data.status !== 'Success')) {
                setAadhaarError(data.message || 'Aadhaar verification failed.');
            } else {
                setAadhaarError('');
                if (data.url) {
                    window.location.href = data.url;
                }
            }
        } catch (e) {
            setAadhaarError('Unable to verify Aadhaar at the moment. Please try again.');
        } finally {
            aadhaarVerifyBtn.disabled = false;
            aadhaarVerifyBtn.textContent = 'Verify Aadhaar';
        }
    }

    async function fetchDistricts(stateId) {
        districtSelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.innerHTML = '<option value="">-- Select City --</option>';
        syncCityOtherUi();
        const res = await fetch(`/api/location/districts?state_id=${stateId}`);
        const json = await res.json();
        districtSelect.innerHTML = '<option value="">-- Select District --</option>';
        if (json.success) {
            json.data.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id; opt.textContent = d.district_name;
                districtSelect.appendChild(opt);
            });
        }
    }

    async function fetchCities(districtId) {
        citySelect.innerHTML = '<option value="">Loading...</option>';
        const res = await fetch(`/api/location/cities?district_id=${districtId}`);
        const json = await res.json();
        citySelect.innerHTML = '<option value="">-- Select City --</option>';
        if (json.success) {
            json.data.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id; opt.textContent = c.city_name;
                citySelect.appendChild(opt);
            });
        }
        ensureOtherOption(citySelect);
        syncCityOtherUi();
    }

    function resetCustomerAreaSelects() {
        if (!isCustomer) return;
        panchayatSelect.innerHTML = '<option value="">-- Select Panchayat --</option>';
        villageSelect.innerHTML = '<option value="">-- Select Village Name --</option>';
        municipalitySelect.innerHTML = '<option value="">-- Select Municipality --</option>';
        wardSelect.innerHTML = '<option value="">-- Select Ward No --</option>';
        if (panchayatOtherGroup) panchayatOtherGroup.style.display = 'none';
        if (panchayatOtherInput) {
            panchayatOtherInput.required = false;
            panchayatOtherInput.value = '';
        }
        if (villageOtherGroup) villageOtherGroup.style.display = 'none';
        if (villageOtherInput) {
            villageOtherInput.required = false;
            villageOtherInput.value = '';
        }
        if (municipalityIdInput) municipalityIdInput.value = '';
        if (municipalityOtherGroup) municipalityOtherGroup.style.display = 'none';
        if (municipalityOtherInput) {
            municipalityOtherInput.required = false;
            municipalityOtherInput.value = '';
        }
        if (wardOtherGroup) wardOtherGroup.style.display = 'none';
        if (wardOtherInput) {
            wardOtherInput.required = false;
            wardOtherInput.value = '';
        }
    }

    function setCustomerAreaVisibility(val) {
        if (!isCustomer) return;
        const showVillage = (val === 'Village_area');
        const showMunicipality = (val === 'Municipality_area');

        panchayatGroup.style.display = showVillage ? '' : 'none';
        villageGroup.style.display = showVillage ? '' : 'none';
        municipalityGroup.style.display = showMunicipality ? '' : 'none';
        wardGroup.style.display = showMunicipality ? '' : 'none';

        panchayatSelect.required = showVillage;
        villageSelect.required = showVillage;
        municipalitySelect.required = showMunicipality;
        wardSelect.required = showMunicipality;
    }

    async function fetchPanchayats(cityId) {
        if (!isCustomer) return;
        panchayatSelect.innerHTML = '<option value="">Loading...</option>';
        const res = await fetch(`/api/location/panchayats?city_id=${cityId}`);
        const json = await res.json();
        panchayatSelect.innerHTML = '<option value="">-- Select Panchayat --</option>';
        if (json.success) {
            json.data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.panchayat_name; opt.textContent = p.panchayat_name;
                panchayatSelect.appendChild(opt);
            });
        }
        ensureOtherOption(panchayatSelect);
        syncPanchayatOtherUi();
    }

    async function fetchVillages(cityId) {
        if (!isCustomer) return;
        villageSelect.innerHTML = '<option value="">Loading...</option>';
        const res = await fetch(`/api/location/villages?city_id=${cityId}`);
        const json = await res.json();
        villageSelect.innerHTML = '<option value="">-- Select Village Name --</option>';
        if (json.success) {
            json.data.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.village_name; opt.textContent = v.village_name;
                villageSelect.appendChild(opt);
            });
        }
        ensureOtherOption(villageSelect);
        syncVillageOtherUi();
    }

    async function fetchMunicipalities(cityId) {
        if (!isCustomer) return;
        municipalitySelect.innerHTML = '<option value="">Loading...</option>';
        const res = await fetch(`/api/location/municipalities?city_id=${cityId}`);
        const json = await res.json();
        municipalitySelect.innerHTML = '<option value="">-- Select Municipality --</option>';
        if (json.success) {
            json.data.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.municipality_name; opt.textContent = m.municipality_name;
                opt.dataset.id = m.id;
                municipalitySelect.appendChild(opt);
            });
        }
        ensureOtherOption(municipalitySelect);
        syncMunicipalityOtherUi();
    }

    async function fetchWards(municipalityId) {
        if (!isCustomer) return;
        wardSelect.innerHTML = '<option value="">Loading...</option>';
        const res = await fetch(`/api/location/wards?municipality_id=${municipalityId}`);
        const json = await res.json();
        wardSelect.innerHTML = '<option value="">-- Select Ward No --</option>';
        if (json.success) {
            json.data.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.ward_no; opt.textContent = w.ward_no;
                wardSelect.appendChild(opt);
            });
        }
        ensureOtherOption(wardSelect);
        syncWardOtherUi();
    }

    function getVerificationIdFromQuery() {
        try {
            const params = new URLSearchParams(window.location.search || '');
            const v = params.get('verification_id');
            return v && v.trim() ? v.trim() : null;
        } catch (e) {
            return null;
        }
    }

    async function completeAadhaarVerificationIfNeeded() {
        if (!aadhaarInput) return;
        const verificationId = getVerificationIdFromQuery();
        if (!verificationId) return;
        setAadhaarError('');
        try {
            const res = await fetch('{{ route('ecard.registration.verify-aadhaar-document') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    verification_id: verificationId,
                }),
            });
            const data = await res.json();
            if (!res.ok || (data.status && data.status !== 'Success')) {
                setAadhaarError(data.message || 'Aadhaar verification failed.');
            } else {
                if (data.aadhaar_no) {
                    aadhaarInput.value = data.aadhaar_no;
                }
                aadhaarInput.readOnly = true;
                if (aadhaarVerifyBtn) aadhaarVerifyBtn.style.display = 'none';
                if (aadhaarEditBtn) aadhaarEditBtn.style.display = '';
                setAadhaarError('Aadhaar no verified successfully.', true);
            }
        } catch (e) {
            setAadhaarError('Unable to complete Aadhaar verification at the moment. Please try again.');
        }
    }

    stateSelect.addEventListener('change', (e) => {
        const id = e.target.value;
        syncCityOtherUi();
        if (isCustomer) {
            resetCustomerAreaSelects();
            if (areaSelect) areaSelect.value = '';
            setCustomerAreaVisibility('');
        }
        if (id) fetchDistricts(id);
    });
    districtSelect.addEventListener('change', (e) => {
        const id = e.target.value;
        syncCityOtherUi();
        if (isCustomer) {
            resetCustomerAreaSelects();
            setCustomerAreaVisibility(areaSelect.value || '');
        }
        if (id) fetchCities(id);
    });
    citySelect.addEventListener('change', syncCityOtherUi);
    if (aadhaarVerifyBtn) {
        aadhaarVerifyBtn.addEventListener('click', verifyAadhaar);
    }
    if (aadhaarEditBtn && aadhaarInput && aadhaarVerifyBtn) {
        aadhaarEditBtn.addEventListener('click', () => {
            aadhaarInput.readOnly = false;
            aadhaarVerifyBtn.style.display = '';
            aadhaarEditBtn.style.display = 'none';
            setAadhaarError('');
        });
    }
    if (isCustomer) {
        setFirstRechargePlanUi();
        if (customerUserTypeSelect) {
            customerUserTypeSelect.addEventListener('change', setFirstRechargePlanUi);
        }
        if (firstRechargePlanSelect) {
            firstRechargePlanSelect.addEventListener('change', updateFirstRechargePlanSummary);
        }

        areaSelect.addEventListener('change', () => {
            resetCustomerAreaSelects();
            setCustomerAreaVisibility(areaSelect.value || '');
            const cityId = citySelect.value;
            if (!cityId || cityId === OTHER_OPTION_VALUE) {
                if (areaSelect.value === 'Village_area') {
                    ensureOtherOption(panchayatSelect);
                    ensureOtherOption(villageSelect);
                    syncPanchayatOtherUi();
                    syncVillageOtherUi();
                }
                if (areaSelect.value === 'Municipality_area') {
                    ensureOtherOption(municipalitySelect);
                    ensureOtherOption(wardSelect);
                    syncMunicipalityOtherUi();
                    syncWardOtherUi();
                }
                return;
            }
            if (areaSelect.value === 'Village_area') {
                fetchPanchayats(cityId);
                fetchVillages(cityId);
            }
            if (areaSelect.value === 'Municipality_area') {
                fetchMunicipalities(cityId);
            }
        });

        citySelect.addEventListener('change', () => {
            resetCustomerAreaSelects();
            setCustomerAreaVisibility(areaSelect.value || '');
            const cityId = citySelect.value;
            if (!cityId || cityId === OTHER_OPTION_VALUE) {
                if (areaSelect.value === 'Village_area') {
                    ensureOtherOption(panchayatSelect);
                    ensureOtherOption(villageSelect);
                    syncPanchayatOtherUi();
                    syncVillageOtherUi();
                }
                if (areaSelect.value === 'Municipality_area') {
                    ensureOtherOption(municipalitySelect);
                    ensureOtherOption(wardSelect);
                    syncMunicipalityOtherUi();
                    syncWardOtherUi();
                }
                return;
            }
            if (areaSelect.value === 'Village_area') {
                fetchPanchayats(cityId);
                fetchVillages(cityId);
            }
            if (areaSelect.value === 'Municipality_area') {
                fetchMunicipalities(cityId);
            }
        });

        panchayatSelect.addEventListener('change', syncPanchayatOtherUi);
        villageSelect.addEventListener('change', syncVillageOtherUi);

        municipalitySelect.addEventListener('change', () => {
            if (municipalityIdInput) municipalityIdInput.value = '';
            wardSelect.innerHTML = '<option value="">-- Select Ward No --</option>';
            if (wardOtherGroup) wardOtherGroup.style.display = 'none';
            if (wardOtherInput) {
                wardOtherInput.required = false;
                wardOtherInput.value = '';
            }

            syncMunicipalityOtherUi();

            if (municipalitySelect.value === OTHER_OPTION_VALUE) {
                ensureOtherOption(wardSelect);
                syncWardOtherUi();
                return;
            }

            const opt = municipalitySelect.options[municipalitySelect.selectedIndex];
            const municipalityId = opt ? opt.dataset.id : null;
            if (municipalityIdInput) municipalityIdInput.value = municipalityId || '';
            if (municipalityId) fetchWards(municipalityId);
        });

        wardSelect.addEventListener('change', syncWardOtherUi);
    }

    const formEl = document.querySelector('form[action="{{ route('ecard.registration.store') }}"]');
    if (formEl) {
        formEl.addEventListener('submit', (e) => {
            if (isCustomer && customerUserTypeSelect && customerUserTypeSelect.value === 'paid' && firstRechargePlanSelect && !firstRechargePlanSelect.value) {
                e.preventDefault();
                firstRechargePlanGroup.style.display = '';
                firstRechargePlanSelect.focus();
            }
        });
    }

    completeAadhaarVerificationIfNeeded();

    // Preload districts/cities for old values (after validation error)
    (function preload() {
        const oldState = '{{ old('state_id') }}';
        const oldDistrict = '{{ old('district_id') }}';
        const oldCity = '{{ old('city_id') }}';
        const oldCityOther = '{{ old('city_other') }}';
        const oldArea = '{{ old('area') }}';
        const oldPanchayat = '{{ old('panchayat') }}';
        const oldVillage = '{{ old('village_name') }}';
        const oldPanchayatOther = '{{ old('panchayat_other') }}';
        const oldVillageOther = '{{ old('village_other') }}';
        const oldMunicipality = '{{ old('municipality') }}';
        const oldMunicipalityId = '{{ old('municipality_id') }}';
        const oldMunicipalityOther = '{{ old('municipality_other') }}';
        const oldWard = '{{ old('ward_no') }}';
        const oldWardOther = '{{ old('ward_other') }}';
        if (oldState) {
            fetchDistricts(oldState).then(() => {
                if (oldDistrict) {
                    districtSelect.value = oldDistrict;
                    fetchCities(oldDistrict).then(() => {
                        if (oldCity) citySelect.value = oldCity;
                        if (oldCity === OTHER_OPTION_VALUE && oldCityOther) {
                            cityOtherInput.value = oldCityOther;
                        }
                        syncCityOtherUi();
                        if (isCustomer) {
                            if (oldArea) areaSelect.value = oldArea;
                            setCustomerAreaVisibility(areaSelect.value || '');
                            if (oldCity && oldArea === 'Village_area' && oldCity !== OTHER_OPTION_VALUE) {
                                Promise.all([fetchPanchayats(oldCity), fetchVillages(oldCity)]).then(() => {
                                    if (oldPanchayat) panchayatSelect.value = oldPanchayat;
                                    if (oldVillage) villageSelect.value = oldVillage;
                                    if (oldPanchayat === OTHER_OPTION_VALUE && oldPanchayatOther) {
                                        panchayatOtherInput.value = oldPanchayatOther;
                                    }
                                    if (oldVillage === OTHER_OPTION_VALUE && oldVillageOther) {
                                        villageOtherInput.value = oldVillageOther;
                                    }
                                    syncPanchayatOtherUi();
                                    syncVillageOtherUi();
                                });
                            }
                            if (oldCity === OTHER_OPTION_VALUE && oldArea === 'Village_area') {
                                ensureOtherOption(panchayatSelect);
                                ensureOtherOption(villageSelect);
                                if (oldPanchayat) panchayatSelect.value = oldPanchayat;
                                if (oldVillage) villageSelect.value = oldVillage;
                                if (oldPanchayat === OTHER_OPTION_VALUE && oldPanchayatOther) {
                                    panchayatOtherInput.value = oldPanchayatOther;
                                }
                                if (oldVillage === OTHER_OPTION_VALUE && oldVillageOther) {
                                    villageOtherInput.value = oldVillageOther;
                                }
                                syncPanchayatOtherUi();
                                syncVillageOtherUi();
                            }
                            if (oldCity && oldArea === 'Municipality_area' && oldCity !== OTHER_OPTION_VALUE) {
                                fetchMunicipalities(oldCity).then(() => {
                                    if (oldMunicipality) {
                                        municipalitySelect.value = oldMunicipality;
                                        if (municipalityIdInput) municipalityIdInput.value = oldMunicipalityId || '';
                                        if (oldMunicipality === OTHER_OPTION_VALUE && oldMunicipalityOther) {
                                            municipalityOtherInput.value = oldMunicipalityOther;
                                        }
                                        syncMunicipalityOtherUi();
                                        const selected = municipalitySelect.options[municipalitySelect.selectedIndex];
                                        const municipalityId = selected ? selected.dataset.id : null;
                                        if (municipalityId) {
                                            fetchWards(municipalityId).then(() => {
                                                if (oldWard) wardSelect.value = oldWard;
                                                if (oldWard === OTHER_OPTION_VALUE && oldWardOther) {
                                                    wardOtherInput.value = oldWardOther;
                                                }
                                                syncWardOtherUi();
                                            });
                                        } else {
                                            ensureOtherOption(wardSelect);
                                            if (oldWard) wardSelect.value = oldWard;
                                            if (oldWard === OTHER_OPTION_VALUE && oldWardOther) {
                                                wardOtherInput.value = oldWardOther;
                                            }
                                            syncWardOtherUi();
                                        }
                                    }
                                });
                            }
                            if (oldCity === OTHER_OPTION_VALUE && oldArea === 'Municipality_area') {
                                ensureOtherOption(municipalitySelect);
                                ensureOtherOption(wardSelect);
                                if (oldMunicipality === OTHER_OPTION_VALUE) {
                                    municipalitySelect.value = oldMunicipality;
                                }
                                if (oldMunicipality === OTHER_OPTION_VALUE && oldMunicipalityOther) {
                                    municipalityOtherInput.value = oldMunicipalityOther;
                                }
                                syncMunicipalityOtherUi();
                                if (oldWard === OTHER_OPTION_VALUE) {
                                    wardSelect.value = oldWard;
                                }
                                if (oldWard === OTHER_OPTION_VALUE && oldWardOther) {
                                    wardOtherInput.value = oldWardOther;
                                }
                                syncWardOtherUi();
                            }
                        }
                    })
                }
            });
        }
    })();

    // Handle KYC Document file previews
    document.querySelectorAll('.kyc-file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            const previewContainer = this.parentElement.querySelector('.kyc-preview');
            if (!previewContainer) return;

            const file = this.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100px; object-fit: contain;">`;
                    }
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    previewContainer.innerHTML = `<div class="text-primary"><i class="fas fa-file-pdf fa-2x mb-2"></i><br><small>${file.name}</small></div>`;
                } else {
                    previewContainer.innerHTML = `<div class="text-secondary"><i class="fas fa-file fa-2x mb-2"></i><br><small>${file.name}</small></div>`;
                }
            } else {
                previewContainer.innerHTML = '<span class="text-muted">No file selected</span>';
            }
        });
    });
</script>
@endsection
