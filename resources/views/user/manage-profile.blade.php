<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Personal Details - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @include('user.partials.theme-style')
    <style>
        /* :root {
            --primary-gradient: linear-gradient(135deg, #D53F8C 0%, #805AD5 100%);
            --header-gradient: linear-gradient(to right, #c42086, #b02995, #9b30a2, #8435ad, #6a39b6);
            --card-bg: #ffffff;
            --text-dark: #333333;
            --text-muted: #718096;
            --bg-light: #f3f4f6;
            --pink-highlight: #d53f8c;
            --purple-verify: #6b46c1;
        } */
        
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
            background: white;
            border-radius: 20px;
            padding: 25px 20px;
            margin: 20px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
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

        .form-control {
            border: 1px solid #a0aec0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            color: var(--text-dark);
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--pink-highlight);
            box-shadow: 0 0 0 3px rgba(213, 63, 140, 0.1);
        }

        /* Image Upload (Profile) */
        .image-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #e2e8f0;
            margin-bottom: 15px;
            overflow: hidden;
            position: relative;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: #a0aec0;
        }

        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .btn-upload {
            border: 1px solid var(--pink-highlight);
            color: var(--pink-highlight);
            background-color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-upload:hover {
            background-color: var(--pink-highlight);
            color: white;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        /* KYC Specific Styles */
        .kyc-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 25px 0 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .verify-link {
            position: absolute;
            right: 0;
            top: 38px; /* Adjust based on label height */
            color: var(--purple-verify);
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            padding: 5px 0;
            text-transform: uppercase;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 5;
        }
        
        .form-control-verify {
            padding-right: 70px; /* Space for verify link */
        }

        /* Document Upload Section */
        .doc-section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 15px;
        }

        .doc-upload-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
        }

        .doc-upload-box {
            flex: 1;
            border: 1px solid #cbd5e0;
            border-radius: 15px;
            height: 130px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        .doc-upload-box:hover {
            border-color: var(--pink-highlight);
        }

        .doc-plus {
            font-size: 28px;
            color: var(--text-dark);
            margin-bottom: 10px;
            font-weight: 400;
        }

        .doc-label {
            font-size: 14px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .doc-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            display: none;
        }
        
        /* Toggle Section */
        .toggle-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin: 0 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        
        .toggle-text {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            padding-right: 15px;
            line-height: 1.4;
        }

        /* Custom Switch */
        .switch {
          position: relative;
          display: inline-block;
          width: 50px;
          height: 28px;
        }

        .switch input { 
          opacity: 0;
          width: 0;
          height: 0;
        }

        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          -webkit-transition: .4s;
          transition: .4s;
        }

        .slider:before {
          position: absolute;
          content: "";
          height: 20px;
          width: 20px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }

        input:checked + .slider {
          background-color: #718096; /* Gray-ish like screenshot */
        }

        input:focus + .slider {
          box-shadow: 0 0 1px #718096;
        }

        input:checked + .slider:before {
          -webkit-transform: translateX(22px);
          -ms-transform: translateX(22px);
          transform: translateX(22px);
        }

        /* Rounded sliders */
        .slider.round {
          border-radius: 34px;
        }

        .slider.round:before {
          border-radius: 50%;
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
                background-color: #e2e8f0;
                display: flex;
                justify-content: center;
                min-height: 100vh;
            }

            .mobile-wrapper {
                max-width: 450px;
                box-shadow: 0 0 50px rgba(0,0,0,0.15);
                border-left: 1px solid rgba(0,0,0,0.05);
                border-right: 1px solid rgba(0,0,0,0.05);
                background-color: #f8f9fa;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Wrapper -->
    <div class="mobile-wrapper">
        
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.profile') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">Personal Details</div>
            <div class="header-icons">
                <i class="fas fa-shield-alt"></i>
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            
            <!-- Form Section -->
            <div class="form-card">
                <!-- Hidden Profile Image Input -->
                <input type="file" name="profile_image" id="profile_image_input" style="display: none;" accept="image/*" onchange="previewProfileImage(this)">

                <!-- Profile Image Display -->
                <div class="image-upload-container">
                    <div class="image-preview">
                        @if(isset($user->profile_image) && $user->profile_image)
                            <img src="{{ asset($user->profile_image) }}" id="main_profile_preview">
                        @else
                            <img src="https://via.placeholder.com/150?text=User" id="main_profile_preview">
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Full Name</label>
                        <div>
                            <a href="javascript:void(0)" class="text-decoration-none me-3" style="color: #d53f8c; font-size: 14px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#profileImageModal">
                                <i class="fas fa-camera me-1"></i> Edit Profile Pic
                            </a>
                            <a href="{{ route('user.profile.edit') }}" class="text-decoration-none" style="color: #d53f8c; font-size: 14px; font-weight: 600;">
                                <i class="fas fa-edit me-1"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                    <input type="text" class="form-control" name="full_name" value="{{ $user->full_name ?? '' }}" placeholder="Enter full name" readonly>
                </div>

                <div class="kyc-title">KYC DETAILS</div>

                <div class="form-group">
                    <label class="form-label">Aadhaar Card Number</label>
                    <input type="text" class="form-control form-control-verify" name="aadhaar_no" value="{{ $user->aadhaar_no ?? '' }}" placeholder="XXXX XXXX 4444">
                    <a href="#" class="verify-link">VERIFY</a>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-control form-control-verify" name="mobile_no" value="{{ $user->mobile_no ?? '' }}">
                    <a href="#" class="verify-link">VERIFY</a>
                </div>

                <div class="form-group">
                    <label class="form-label">Email ID</label>
                    <input type="email" class="form-control form-control-verify" name="email_id" value="{{ $user->email_id ?? '' }}">
                    <a href="#" class="verify-link">VERIFY</a>
                </div>
            </div>

            <!-- Document Upload Section -->
            <div class="form-card">
                <div class="doc-section-title">Aadhaar Card Document</div>
                
                <div class="doc-upload-grid">
                    <!-- Front Side -->
                    <div class="doc-upload-box" onclick="document.getElementById('front_upload').click()">
                        <i class="fas fa-plus doc-plus"></i>
                        <div class="doc-label">Front Side</div>
                        @if(isset($user->aadhaar_front_image) && $user->aadhaar_front_image)
                            <img src="{{ asset($user->aadhaar_front_image) }}" class="doc-preview" style="display: block;">
                        @else
                            <img src="" class="doc-preview" id="front_preview">
                        @endif
                        <input type="file" name="aadhaar_front_image" id="front_upload" style="display: none;" accept="image/*" onchange="previewDoc(this, 'front_preview')">
                    </div>

                    <!-- Back Side -->
                    <div class="doc-upload-box" onclick="document.getElementById('back_upload').click()">
                        <i class="fas fa-plus doc-plus"></i>
                        <div class="doc-label">Back Side</div>
                        @if(isset($user->aadhaar_back_image) && $user->aadhaar_back_image)
                            <img src="{{ asset($user->aadhaar_back_image) }}" class="doc-preview" style="display: block;">
                        @else
                            <img src="" class="doc-preview" id="back_preview">
                        @endif
                        <input type="file" name="aadhaar_back_image" id="back_upload" style="display: none;" accept="image/*" onchange="previewDoc(this, 'back_preview')">
                    </div>
                </div>
            </div>

            <!-- Toggle Section -->
            <div class="toggle-card">
                <div class="toggle-text">Allow E-Card Seva to share profile for opportunities</div>
                <label class="switch">
                    <input type="checkbox" name="share_profile_to_ecard_seva" value="1" {{ ($user->share_profile_to_ecard_seva ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>

            <button type="submit" class="save-btn">Save Changes</button>
        </form>

    </div>

    <!-- Profile Image Modal -->
    <div class="modal fade" id="profileImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Update Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-4 pb-4">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="rounded-circle overflow-hidden border border-3 border-white shadow-sm" style="width: 120px; height: 120px;">
                            <img id="modal_profile_preview" src="{{ $user->profile_image ? asset($user->profile_image) : 'https://via.placeholder.com/150?text=User' }}" class="w-100 h-100 object-fit-cover">
                        </div>
                        <button type="button" class="btn btn-light rounded-circle position-absolute bottom-0 end-0 shadow-sm p-2" onclick="document.getElementById('profile_image_input').click()">
                            <i class="fas fa-camera text-primary"></i>
                        </button>
                    </div>
                    <p class="text-muted small mb-0">Click the camera icon to select a new photo</p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" onclick="document.getElementById('profileForm').submit()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewDoc(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    var preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    // Hide the plus icon and label if needed, but overlaying image is usually enough
                    // For better UI, we can hide the content behind
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewProfileImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    var modalPreview = document.getElementById('modal_profile_preview');
                    if(modalPreview) modalPreview.src = e.target.result;
                    
                    var mainPreview = document.getElementById('main_profile_preview');
                    if(mainPreview) mainPreview.src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `
                        <ul style="text-align: left; margin-bottom: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    confirmButtonColor: '#d53f8c'
                });
            @endif
        });
    </script>
    @include('user.partials.theme-script')
</body>
</html>