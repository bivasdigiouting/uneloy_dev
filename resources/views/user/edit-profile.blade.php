<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Profile - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        :root {
            /* Removed as handled by global theme styles */
            /* --primary-gradient: linear-gradient(135deg, #D53F8C 0%, #805AD5 100%); */
            /* --header-gradient: linear-gradient(to right, #c42086, #b02995, #9b30a2, #8435ad, #6a39b6); */
            /* --card-bg: #ffffff; */
            /* --text-dark: #333333; */
            /* --text-muted: #718096; */
            /* --bg-light: #f3f4f6; */
            --pink-highlight: #d53f8c;
            --purple-verify: #6b46c1;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        /* Common Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 20px;
            color: var(--text-dark);
        }

        /* Header */
        .profile-header {
            background: var(--bg-light);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .back-btn {
            font-size: 24px;
            color: var(--text-dark);
            text-decoration: none;
            margin-right: 15px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            flex-grow: 1;
            text-align: center;
            margin-right: 24px;
        }
        
        .header-icons {
            display: flex;
            gap: 15px;
            color: var(--text-dark);
            font-size: 20px;
        }

        /* Form Card */
        .form-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px 20px;
            margin: 20px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            color: var(--text-dark);
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
        }

        .form-control, .form-select {
            border: 1px solid var(--muted-text);
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            color: var(--text-dark);
            background-color: var(--bg-light);
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--pink-highlight);
            box-shadow: 0 0 0 3px rgba(213, 63, 140, 0.1);
            outline: none;
        }

        /* Section Title */
        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 25px 0 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--muted-text);
            padding-bottom: 10px;
        }
        
        .section-title:first-child {
            margin-top: 0;
        }

        /* Save Button */
        .save-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px;
            width: calc(100% - 30px);
            margin: 0 15px 30px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(213, 63, 140, 0.3);
            cursor: pointer;
            display: block;
        }

        /* Desktop Optimizations */
        @media (min-width: 992px) {
            body {
                background-color: var(--bg-light);
                display: flex;
                justify-content: center;
                min-height: 100vh;
            }
            
            .mobile-wrapper {
                max-width: 450px;
                box-shadow: 0 0 50px rgba(0,0,0,0.15);
                border-left: 1px solid rgba(0,0,0,0.05);
                border-right: 1px solid rgba(0,0,0,0.05);
                background-color: var(--bg-light);
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Wrapper -->
    <div class="mobile-wrapper">
        
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.profile.manage') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">Edit Profile</div>
            <div class="header-icons">
                <i class="fas fa-shield-alt"></i>
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>

        <form action="{{ route('user.profile.update') }}" method="POST" id="editProfileForm">
            @csrf
            
            <div class="form-card">
                <!-- Personal Information -->
                <div class="section-title">Personal Information</div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name" value="{{ $user->middle_name }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" name="father_name" value="{{ $user->father_name }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" class="form-control" name="mother_name" value="{{ $user->mother_name }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Blood Group</label>
                            <select class="form-select" name="blood_group">
                                <option value="">Select</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ $user->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth" value="{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="">Select</option>
                                <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Marital Status</label>
                            <select class="form-select" name="marital_status">
                                <option value="">Select</option>
                                <option value="Single" {{ $user->marital_status == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ $user->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ $user->marital_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ $user->marital_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="section-title mt-4">Contact Information</div>
                
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Mobile No</label>
                            <input type="text" class="form-control" name="mobile_no" value="{{ $user->mobile_no }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">WhatsApp No</label>
                            <input type="text" class="form-control" name="business_whatsapp" value="{{ $user->business_whatsapp }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Gmail ID</label>
                            <input type="email" class="form-control" name="gmail_id" value="{{ $user->gmail_id }}">
                        </div>
                    </div>
                </div>

                <!-- Address Details -->
                <div class="section-title mt-4">Address Details</div>
                
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Current Address</label>
                            <textarea class="form-control" name="current_address" rows="2">{{ $user->current_address }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Permanent Address</label>
                            <textarea class="form-control" name="permanent_address" rows="2">{{ $user->permanent_address }}</textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">State</label>
                            <select class="form-select" id="state_select">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" data-name="{{ $state->state_name }}" {{ $user->state == $state->state_name ? 'selected' : '' }}>
                                        {{ $state->state_name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="state" id="state_hidden" value="{{ $user->state }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">District</label>
                            <select class="form-select" id="district_select" disabled>
                                <option value="">Select District</option>
                            </select>
                            <input type="hidden" name="district" id="district_hidden" value="{{ $user->district }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">City</label>
                            <select class="form-select" id="city_select" disabled>
                                <option value="">Select City</option>
                            </select>
                            <input type="hidden" name="city" id="city_hidden" value="{{ $user->city }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Pin Code</label>
                            <input type="text" class="form-control" name="pin_code" value="{{ $user->pin_code }}">
                        </div>
                    </div>
                </div>

                <!-- Qualification & Work -->
                <div class="section-title mt-4">Qualification & Work</div>
                
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Last Qualification</label>
                            <input type="text" class="form-control" name="last_qualification" value="{{ $user->last_qualification }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Work Type</label>
                            <input type="text" class="form-control" name="work_type" value="{{ $user->work_type }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="form-label">Work Experience</label>
                            <input type="text" class="form-control" name="work_experience" value="{{ $user->work_experience }}">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="save-btn">Update Profile</button>
        </form>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        @if(session('success'))
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>
     
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <script>
         $(document).ready(function() {
            // Show toast if success message exists
            @if(session('success'))
                var toastEl = document.getElementById('successToast');
                var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                toast.show();
            @endif

             // Initial load
             var initialStateId = $('#state_select').val();
            var initialDistrictName = "{{ $user->district }}";
            var initialCityName = "{{ $user->city }}";

            if(initialStateId) {
                loadDistricts(initialStateId, initialDistrictName, function(districtId) {
                    if(districtId && initialCityName) {
                        loadCities(districtId, initialCityName);
                    }
                });
            }

            // On State Change
            $('#state_select').change(function() {
                var stateId = $(this).val();
                var stateName = $(this).find('option:selected').data('name');
                $('#state_hidden').val(stateName);
                
                // Reset District and City
                $('#district_select').html('<option value="">Select District</option>').prop('disabled', true);
                $('#city_select').html('<option value="">Select City</option>').prop('disabled', true);
                $('#district_hidden').val('');
                $('#city_hidden').val('');

                if(stateId) {
                    loadDistricts(stateId, null);
                }
            });

            // On District Change
            $('#district_select').change(function() {
                var districtId = $(this).val();
                var districtName = $(this).find('option:selected').data('name');
                $('#district_hidden').val(districtName);

                // Reset City
                $('#city_select').html('<option value="">Select City</option>').prop('disabled', true);
                $('#city_hidden').val('');

                if(districtId) {
                    loadCities(districtId, null);
                }
            });

            // On City Change
            $('#city_select').change(function() {
                var cityName = $(this).find('option:selected').data('name');
                $('#city_hidden').val(cityName);
            });

            function loadDistricts(stateId, selectedName, callback) {
                $('#district_select').prop('disabled', true);
                $.get('/api/location/districts', { state_id: stateId }, function(response) {
                    if(response.success) {
                        var options = '<option value="">Select District</option>';
                        var selectedId = null;
                        $.each(response.data, function(index, item) {
                            var isSelected = item.district_name === selectedName ? 'selected' : '';
                            if(isSelected) selectedId = item.id;
                            options += '<option value="' + item.id + '" data-name="' + item.district_name + '" ' + isSelected + '>' + item.district_name + '</option>';
                        });
                        $('#district_select').html(options).prop('disabled', false);
                        if(callback) callback(selectedId);
                    }
                });
            }

            function loadCities(districtId, selectedName) {
                $('#city_select').prop('disabled', true);
                $.get('/api/location/cities', { district_id: districtId }, function(response) {
                    if(response.success) {
                        var options = '<option value="">Select City</option>';
                        $.each(response.data, function(index, item) {
                            var isSelected = item.city_name === selectedName ? 'selected' : '';
                            options += '<option value="' + item.id + '" data-name="' + item.city_name + '" ' + isSelected + '>' + item.city_name + '</option>';
                        });
                        $('#city_select').html(options).prop('disabled', false);
                    }
                });
            }
        });
    </script>
    @include('user.partials.theme-script')
</body>
</html>