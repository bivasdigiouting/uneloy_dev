<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Change MPIN - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Mobile Wrapper */
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
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
            background: none;
            border: none;
            padding: 0;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            flex-grow: 1;
            text-align: center;
            margin-right: 39px; /* Balance the back button spacing */
        }

        /* Content Card */
        .section-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px 20px;
            margin: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 10px;
            font-size: 14px;
        }

        .pin-input-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .pin-digit {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 1px solid var(--border-color, #e2e8f0);
            background-color: var(--input-bg, #ffffff);
            color: var(--text-dark);
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            outline: none;
            transition: all 0.2s;
        }

        .pin-digit:focus {
            border-color: #9f7aea;
            box-shadow: 0 0 0 3px rgba(159, 122, 234, 0.1);
        }

        .btn-save {
            background: linear-gradient(135deg, #9f7aea 0%, #ed64a6 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(159, 122, 234, 0.3);
        }

        /* Desktop Responsiveness */
        @media (min-width: 768px) {
            .mobile-wrapper {
                max-width: 480px;
                border-left: 1px solid #eee;
                border-right: 1px solid #eee;
                box-shadow: 0 0 20px rgba(0,0,0,0.05);
            }
            
            body {
                background-color: #f8f9fa;
                display: flex;
                justify-content: center;
            }
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
        
        .toast {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .toast-header {
            background-color: rgba(0,0,0,0.03);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .toast-success .toast-header {
            color: #28a745;
        }
        
        .toast-error .toast-header {
            color: #dc3545;
        }
    </style>
</head>
<body>

    <div class="mobile-wrapper">
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.security.settings') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">Change MPIN</div>
        </div>

        <!-- Form Section -->
        <div class="section-card">
            <form method="POST" action="{{ route('user.pin.change') }}" id="pinForm">
                @csrf
                
                <!-- New PIN -->
                <div class="mb-4">
                    <label class="form-label">New MPIN</label>
                    <div class="pin-input-container" id="new-pin-container">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="new_pin" id="new_pin">
                    @error('new_pin')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Confirm PIN -->
                <div class="mb-4">
                    <label class="form-label">Confirm MPIN</label>
                    <div class="pin-input-container" id="confirm-pin-container">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                        <input type="number" class="pin-digit" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="new_pin_confirmation" id="new_pin_confirmation">
                </div>

                <button type="submit" class="btn btn-save">
                    Update MPIN
                </button>
            </form>
        </div>
    </div>
    
    <!-- Toast Notification -->
    <div class="toast-container">
        <!-- Success Toast -->
        <div class="toast toast-success" id="successToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="successMessage">
                <!-- Message will be injected here -->
            </div>
        </div>
        
        <!-- Error Toast -->
        <div class="toast toast-error" id="errorToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="errorMessage">
                <!-- Message will be injected here -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup PIN inputs
            setupPinInput('new-pin-container', 'new_pin');
            setupPinInput('confirm-pin-container', 'new_pin_confirmation');

            // Show Toast if session has messages
            @if(session('success'))
                showToast('successToast', "{{ session('success') }}");
            @endif
            
            @if(session('error'))
                showToast('errorToast', "{{ session('error') }}");
            @endif
            
            @if($errors->any())
                showToast('errorToast', "Please check the form for errors.");
            @endif
            
            // Prevent form submission if PINs are not complete
            document.getElementById('pinForm').addEventListener('submit', function(e) {
                const newPin = document.getElementById('new_pin').value;
                const confirmPin = document.getElementById('new_pin_confirmation').value;
                
                if (newPin.length !== 4) {
                    e.preventDefault();
                    showToast('errorToast', "Please enter a 4-digit MPIN.");
                    return;
                }
                
                if (confirmPin.length !== 4) {
                    e.preventDefault();
                    showToast('errorToast', "Please confirm your 4-digit MPIN.");
                    return;
                }
                
                if (newPin !== confirmPin) {
                    e.preventDefault();
                    showToast('errorToast', "MPINs do not match.");
                    return;
                }
            });
        });

        function setupPinInput(containerId, hiddenInputId) {
            const container = document.getElementById(containerId);
            const inputs = container.querySelectorAll('.pin-digit');
            const hiddenInput = document.getElementById(hiddenInputId);

            inputs.forEach((input, index) => {
                // Handle digit input
                input.addEventListener('input', function(e) {
                    if (this.value.length > 1) {
                        this.value = this.value.slice(0, 1);
                    }
                    
                    if (this.value.length === 1) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }
                    
                    updateHiddenInput();
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value) {
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });
                
                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const text = (e.clipboardData || window.clipboardData).getData('text');
                    if (!/^\d+$/.test(text)) return;
                    
                    const digits = text.split('').slice(0, 4);
                    
                    digits.forEach((digit, i) => {
                        if (index + i < inputs.length) {
                            inputs[index + i].value = digit;
                        }
                    });
                    
                    updateHiddenInput();
                    
                    // Focus next empty or last
                    const nextEmpty = Array.from(inputs).findIndex(inp => !inp.value);
                    if (nextEmpty !== -1) {
                        inputs[nextEmpty].focus();
                    } else {
                        inputs[inputs.length - 1].focus();
                    }
                });
            });

            function updateHiddenInput() {
                let val = '';
                inputs.forEach(input => {
                    val += input.value;
                });
                hiddenInput.value = val;
            }
        }
        
        function showToast(toastId, message) {
            const toastEl = document.getElementById(toastId);
            const toastBody = toastEl.querySelector('.toast-body');
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>
</body>
</html>
