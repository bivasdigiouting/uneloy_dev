@extends('layouts.admin')

@section('title', 'Add City')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create New City</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cities.index') }}">City Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to City Master
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">City Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.cities.store') }}" method="POST" id="cityForm">
                        @csrf
                        <div class="row">
                            <!-- State Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">State Name <span class="text-danger">*</span></label>
                                    <select class="form-select @error('state_id') is-invalid @enderror" 
                                            id="state_id" name="state_id" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                {{ $state->state_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- District Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">District Name <span class="text-danger">*</span></label>
                                    <select class="form-select @error('district_id') is-invalid @enderror" 
                                            id="district_id" name="district_id" required disabled>
                                        <option value="">Select District</option>
                                    </select>
                                    @error('district_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- City Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city_name" class="form-label">City Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('city_name') is-invalid @enderror" 
                                           id="city_name" 
                                           name="city_name" 
                                           value="{{ old('city_name') }}" 
                                           placeholder="Enter city name" 
                                           required>
                                    @error('city_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="status" 
                                                   id="status_active" 
                                                   value="active" 
                                                   {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">
                                                Active
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="status" 
                                                   id="status_inactive" 
                                                   value="inactive" 
                                                   {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_inactive">
                                                Inactive
                                            </label>
                                        </div>
                                    </div>
                                    @error('status')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Save City
                            </button>
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#state_id, #district_id').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Handle state change to load districts
    $('#state_id').on('change select2:select', function() {
        var stateId = $(this).val();
        var districtSelect = $('#district_id');
        
        console.log('State changed to:', stateId);
        
        // Reset district dropdown
        districtSelect.empty().append('<option value="">Select District</option>');
        
        if (stateId) {
            // Enable district dropdown
            districtSelect.prop('disabled', false);
            
            // Show loading
            districtSelect.append('<option value="">Loading...</option>');
            
            var ajaxUrl = "{{ route('admin.districts.by-state', ['state_id' => 'STATE_ID']) }}".replace('STATE_ID', stateId);
            console.log('AJAX URL:', ajaxUrl);
            console.log('Sending request with state_id:', stateId);
            
            // Fetch districts
            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('AJAX Success Response:', response);
                    districtSelect.empty().append('<option value="">Select District</option>');
                    
                    // Support multiple response shapes: {data: [...]}, {districts: [...]} or an array
                    var districts = [];
                    if ($.isArray(response)) {
                        districts = response;
                    } else if (response && (response.data || response.districts)) {
                        districts = response.data || response.districts;
                    }
                    
                    if (districts && districts.length > 0) {
                        $.each(districts, function(index, district) {
                            var id = district.id || district.value;
                            var name = district.district_name || district.name || district.text;
                            if (id && name) {
                                districtSelect.append('<option value="' + id + '">' + name + '</option>');
                            }
                        });
                    } else {
                        console.log('No districts found or invalid response structure');
                        districtSelect.append('<option value="">No districts found</option>');
                    }
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr);
                    console.error('Status:', xhr.status);
                    console.error('Response Text:', xhr.responseText);
                    districtSelect.empty().append('<option value="">Error loading districts</option>');
                }
            });
        } else {
            // Disable district dropdown
            districtSelect.prop('disabled', true);
        }
    });

    // Handle form submission
    $('#cityForm').on('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        var isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Please fill all required fields.');
            return false;
        }
        
        // Submit form
        this.submit();
    });
});
</script>
@endpush