<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-card Details - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: var(--bg-light);
        }

        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
        }

        /* Header */
        .header-section {
            background: var(--header-gradient);
            color: white;
            padding: 20px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            position: relative;
            z-index: 1;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .header-title {
            font-size: 24px;
            font-weight: 600;
        }

        .header-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        /* E-Card Visual */
        .ecard-container {
            margin: -50px 15px 20px;
            position: relative;
            z-index: 2;
            perspective: 1000px;
        }

        .ecard-visual {
            background: url('https://i.pinimg.com/originals/2d/e8/82/2de882cd4f435bb306920026e4729649.jpg') center/cover no-repeat;
            border-radius: 15px;
            padding: 20px;
            color: white;
            min-height: 200px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        
        .ecard-visual::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 0;
        }

        .ecard-content {
            position: relative;
            z-index: 1;
        }

        .ecard-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-size: 24px;
        }

        .ecard-logo {
            text-align: right;
        }

        .ecard-logo img {
            height: 30px;
        }
        
        .ecard-logo-text {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .ecard-number-display {
            font-family: 'Courier New', monospace;
            font-size: 22px;
            letter-spacing: 2px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .ecard-validity {
            font-size: 12px;
            opacity: 0.9;
        }

        /* Update Toggle Card */
        .update-card {
            background: white;
            border-radius: 15px;
            padding: 15px 20px;
            margin: 0 15px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .update-text h6 {
            margin: 0;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .update-text p {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Details Section */
        .details-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin: 0 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .detail-group {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            letter-spacing: 1px;
        }

        .form-control-plaintext {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            padding: 0;
        }

        .form-control.edit-field {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .form-control.edit-field[readonly] {
            background-color: transparent;
            border: none;
            padding: 0;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Switch */
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            background-color: #e2e8f0;
            border-color: #e2e8f0;
            cursor: pointer;
        }
        .form-switch .form-check-input:checked {
            background-color: var(--pink-highlight);
            border-color: var(--pink-highlight);
        }

        /* Modal */
        .modal-content {
            border-radius: 20px;
            border: none;
        }
        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }
        .letter-spacing-2 {
            letter-spacing: 5px;
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
            }
        }
    </style>
</head>
<body>

    <div class="mobile-wrapper">
        
        <!-- Header -->
        <div class="header-section">
            <div class="header-top">
                <div class="header-title">E-card Details</div>
                <a href="{{ route('user.profile') }}" style="color: white; font-size: 20px;">
                    <i class="fas fa-sliders-h"></i>
                </a>
            </div>
            <div class="header-subtitle">View and manage your digital card.</div>
            <div style="height: 30px;"></div> <!-- Spacer for overlapping card -->
        </div>

        <!-- E-Card Visual -->
        <div class="ecard-container">
            <div class="ecard-visual">
                <div class="ecard-content">
                    <div class="ecard-top">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div style="font-size: 18px; font-weight: 600;">{{ $user->full_name ?? 'Your Name' }}</div>
                                <div style="font-size: 12px; opacity: 0.8;">D.O.B {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d.m.Y') : '01.01.2000' }}</div>
                            </div>
                        </div>
                        <div class="ecard-logo">
                            <div class="ecard-logo-text">
                                <i class="fas fa-wifi"></i> e-card
                            </div>
                            <div style="font-size: 10px; opacity: 0.8;">the benefits card</div>
                        </div>
                    </div>

                    <div class="ecard-number-display">
                        {{ $user->ecard_number ? chunk_split($user->ecard_number, 4, ' ') : 'XXXX XXXX XXXX XXXX' }}
                    </div>

                    <div class="ecard-validity">
                        VALID THRU 9/2028
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Toggle -->
        <div class="update-card">
            <div class="update-text">
                <h6>Update Details</h6>
                <p>Toggle to edit your card information.</p>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="updateToggle">
            </div>
        </div>

        <!-- Card Details -->
        <div class="details-card">
            <div class="detail-group">
                <div class="detail-label">e-card Number</div>
                <div class="detail-value">{{ $user->ecard_number ?? 'Not Assigned' }}</div>
            </div>

            <div class="detail-group">
                <div class="detail-label">CVV Number</div>
                <input type="password" class="form-control edit-field" id="ecard_cvv" value="{{ $user->ecard_cvv }}" readonly>
            </div>

            <div class="detail-group">
                <div class="detail-label">Security Pin</div>
                <input type="password" class="form-control edit-field" id="ecard_security_pin" value="{{ $user->ecard_security_pin }}" readonly>
            </div>
            
            <button id="saveBtn" class="btn btn-primary w-100 mt-3 d-none" onclick="saveDetails()">
                <i class="fas fa-save me-2"></i>Save Changes
            </button>
        </div>

    </div>

    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">OTP Verification</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetToggle()"></button>
          </div>
          <div class="modal-body text-center">
            <div class="mb-3">
                <div style="width: 80px; height: 80px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                </div>
            </div>
            <h6 class="mb-2">Enter Verification Code</h6>
            <p class="text-muted small mb-4">We have sent a 6-digit code to your registered mobile number and email.</p>
            
            <div class="alert alert-success py-2 small mb-3">
                <i class="fas fa-info-circle me-1"></i> Default OTP: <strong>123456</strong>
            </div>

            <input type="text" id="otpInput" class="form-control text-center fs-4 letter-spacing-2 mb-3" maxlength="6" placeholder="------" autocomplete="off">
            
            <div id="otpError" class="text-danger small mb-3" style="min-height: 20px;"></div>
            
            <button type="button" class="btn btn-primary w-100 rounded-pill" onclick="verifyOtp()">
                Verify & Proceed
            </button>
            
            <div class="mt-3">
                <button class="btn btn-link btn-sm text-decoration-none" onclick="resendOtp()">Resend OTP</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
    <script>
        function toggleDetails(id) {
            const content = document.getElementById(id);
            const icon = content.previousElementSibling.querySelector('.fa-chevron-down');
        }

        const updateToggle = document.getElementById('updateToggle');
        const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
        const otpInput = document.getElementById('otpInput');
        const otpError = document.getElementById('otpError');
        const saveBtn = document.getElementById('saveBtn');
        const inputs = ['ecard_cvv', 'ecard_security_pin'];
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        updateToggle.addEventListener('change', function() {
            if (this.checked) {
                sendOtp();
            } else {
                disableEditing();
            }
        });

        function sendOtp() {
            // Show loading or disable toggle momentarily
            updateToggle.disabled = true;
            
            fetch('{{ route("user.ecard.otp.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                updateToggle.disabled = false;
                if (data.success) {
                    otpInput.value = '';
                    otpError.textContent = '';
                    otpModal.show();
                } else {
                    updateToggle.checked = false;
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                updateToggle.disabled = false;
                updateToggle.checked = false;
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            });
        }

        function verifyOtp() {
            const otp = otpInput.value;
            if (otp.length !== 6) {
                otpError.textContent = 'Please enter a valid 6-digit OTP.';
                return;
            }

            fetch('{{ route("user.ecard.otp.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ otp: otp })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    otpModal.hide();
                    enableEditing();
                    Swal.fire({
                        icon: 'success',
                        title: 'Verified!',
                        text: 'You can now edit your card details.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    otpError.textContent = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                otpError.textContent = 'An error occurred. Please try again.';
            });
        }

        function enableEditing() {
            inputs.forEach(id => {
                const el = document.getElementById(id);
                el.removeAttribute('readonly');
                el.type = 'text'; // Show value
                el.classList.remove('detail-value-hidden');
            });
            saveBtn.classList.remove('d-none');
        }

        function disableEditing() {
            inputs.forEach(id => {
                const el = document.getElementById(id);
                el.setAttribute('readonly', true);
                el.type = 'password'; // Hide value
            });
            saveBtn.classList.add('d-none');
        }

        function resetToggle() {
            updateToggle.checked = false;
        }

        function resendOtp() {
            otpError.textContent = '';
            fetch('{{ route("user.ecard.otp.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'OTP Resent',
                        text: 'A new OTP has been sent to your device.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    otpError.textContent = data.message;
                }
            });
        }

        function saveDetails() {
            const cvv = document.getElementById('ecard_cvv').value;
            const pin = document.getElementById('ecard_security_pin').value;

            fetch('{{ route("user.ecard.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    ecard_cvv: cvv,
                    ecard_security_pin: pin
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', data.message, 'success');
                    // Optionally disable editing after save
                    // updateToggle.checked = false;
                    // disableEditing();
                } else {
                    Swal.fire('Error', 'Failed to update details.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to update details.', 'error');
            });
        }
    </script>
    @include('user.partials.theme-script')
</body>
</html>