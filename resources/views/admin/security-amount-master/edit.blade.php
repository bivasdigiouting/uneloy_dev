@extends('layouts.admin')

@section('title', 'Edit Security Amount Setting')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Security Amount Setting</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        User Management
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.security-amount-master.index') }}">Security Amount Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Setting</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.security-amount-master.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-12">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Security Amount Settings</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.security-amount-master.update', $securityAmountMaster->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_level_amount" class="form-label">State e-Card Seva Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control @error('state_level_amount') is-invalid @enderror" 
                                               id="state_level_amount" name="state_level_amount" 
                                               value="{{ old('state_level_amount', $securityAmountMaster->state_level_amount) }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @error('state_level_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="district_level_amount" class="form-label">District e-Card Seva Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control @error('district_level_amount') is-invalid @enderror" 
                                               id="district_level_amount" name="district_level_amount" 
                                               value="{{ old('district_level_amount', $securityAmountMaster->district_level_amount) }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @error('district_level_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="block_level_amount" class="form-label">Block - e-Card Seva Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control @error('block_level_amount') is-invalid @enderror" 
                                               id="block_level_amount" name="block_level_amount" 
                                               value="{{ old('block_level_amount', $securityAmountMaster->block_level_amount) }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @error('block_level_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="panchayat_level_amount" class="form-label">G P M e-Card Seva Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control @error('panchayat_level_amount') is-invalid @enderror" 
                                               id="panchayat_level_amount" name="panchayat_level_amount" 
                                               value="{{ old('panchayat_level_amount', $securityAmountMaster->panchayat_level_amount) }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @error('panchayat_level_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="village_level_amount" class="form-label">e-Card Seva Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control @error('village_level_amount') is-invalid @enderror" 
                                               id="village_level_amount" name="village_level_amount" 
                                               value="{{ old('village_level_amount', $securityAmountMaster->village_level_amount) }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @error('village_level_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $securityAmountMaster->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.security-amount-master.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy"></i> Update Setting
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
