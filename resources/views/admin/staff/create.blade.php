@extends('layouts.admin')

@section('title', 'Add Staff')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Staff</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Office Management
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.staff.index') }}">Staff</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Staff</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add New Staff</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data" id="staffForm">
                        @csrf
                        
                        <!-- Personal Details Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user"></i> Personal Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="staff_name" class="form-label">Staff Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('staff_name') is-invalid @enderror" 
                                               id="staff_name" name="staff_name" value="{{ old('staff_name') }}" required>
                                        @error('staff_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                               id="profile_image" name="profile_image" accept="image/*">
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_joining" class="form-label">Date of Joining <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('date_of_joining') is-invalid @enderror" 
                                               id="date_of_joining" name="date_of_joining" value="{{ old('date_of_joining') }}" required>
                                        @error('date_of_joining')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="designation_id" class="form-label">Designation <span class="text-danger">*</span></label>
                                        <select class="form-select @error('designation_id') is-invalid @enderror" 
                                                id="designation_id" name="designation_id" required>
                                            <option value="">Select Designation</option>
                                            @foreach($designations as $designation)
                                                <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                                    {{ $designation->designation_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('designation_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="male" value="Male" 
                                                       {{ old('gender') == 'Male' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="male">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="female" value="Female" 
                                                       {{ old('gender') == 'Female' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="female">Female</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="other" value="Other" 
                                                       {{ old('gender') == 'Other' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="other">Other</label>
                                            </div>
                                        </div>
                                        @error('gender')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Details Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-address-book"></i> Contact Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="address_1" class="form-label">Address 1 <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('address_1') is-invalid @enderror" 
                                                  id="address_1" name="address_1" rows="3" required>{{ old('address_1') }}</textarea>
                                        @error('address_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address_2" class="form-label">Address 2</label>
                                        <textarea class="form-control @error('address_2') is-invalid @enderror" 
                                                  id="address_2" name="address_2" rows="3">{{ old('address_2') }}</textarea>
                                        @error('address_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                        <select class="form-select @error('state') is-invalid @enderror" 
                                                id="state" name="state" required>
                                            <option value="">Select State</option>
                                            @foreach($indianStates as $state)
                                                <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>
                                                    {{ $state }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('district') is-invalid @enderror" 
                                               id="district" name="district" value="{{ old('district') }}" required>
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                               id="city" name="city" value="{{ old('city') }}" required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('pincode') is-invalid @enderror" 
                                               id="pincode" name="pincode" value="{{ old('pincode') }}" maxlength="6" required>
                                        @error('pincode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="mobile_no" class="form-label">Mobile No <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('mobile_no') is-invalid @enderror" 
                                               id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" required>
                                        @error('mobile_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email_id" class="form-label">Email ID <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email_id') is-invalid @enderror" 
                                               id="email_id" name="email_id" value="{{ old('email_id') }}" required>
                                        @error('email_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="location" class="form-label">Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                               id="location" name="location" value="{{ old('location') }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Details Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-university"></i> Bank Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="ifsc_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" 
                                               id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" maxlength="11" required>
                                        @error('ifsc_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                        <select class="form-select @error('bank_name') is-invalid @enderror" 
                                                id="bank_name" name="bank_name" required>
                                            <option value="">Select Bank</option>
                                            @foreach($indianBanks as $bank)
                                                <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>
                                                    {{ $bank }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('branch_name') is-invalid @enderror" 
                                               id="branch_name" name="branch_name" value="{{ old('branch_name') }}" required>
                                        @error('branch_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="account_no" class="form-label">Account No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('account_no') is-invalid @enderror" 
                                               id="account_no" name="account_no" value="{{ old('account_no') }}" required>
                                        @error('account_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="pan_no" class="form-label">PAN No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('pan_no') is-invalid @enderror" 
                                               id="pan_no" name="pan_no" value="{{ old('pan_no') }}" maxlength="10" required>
                                        @error('pan_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="aadhar_no" class="form-label">Aadhar No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('aadhar_no') is-invalid @enderror" 
                                               id="aadhar_no" name="aadhar_no" value="{{ old('aadhar_no') }}" maxlength="12" required>
                                        @error('aadhar_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('salary') is-invalid @enderror" 
                                               id="salary" name="salary" value="{{ old('salary') }}" min="0" step="0.01" required>
                                        @error('salary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Login Details Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-key"></i> Login Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label">User ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('user_id') is-invalid @enderror" 
                                               id="user_id" name="user_id" value="{{ old('user_id') }}" required>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimum 8 characters required</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_active" id="active" value="1" 
                                                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="active">Active</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_active" id="inactive" value="0" 
                                                       {{ old('is_active') == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="inactive">Inactive</label>
                                            </div>
                                        </div>
                                        @error('is_active')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Staff
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Format PAN number
    $('#pan_no').on('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Format IFSC code
    $('#ifsc_code').on('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Validate pincode
    $('#pincode').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Validate Aadhar number
    $('#aadhar_no').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Validate mobile number
    $('#mobile_no').on('input', function() {
        this.value = this.value.replace(/[^0-9+\-\s]/g, '');
    });
});
</script>
@endpush