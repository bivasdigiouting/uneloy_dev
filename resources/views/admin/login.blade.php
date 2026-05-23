@extends('layouts.admin-login')

@section('content')

<!-- Professional Loading Overlay -->
<div id="login-loader" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.7); z-index: 9999;">
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="text-center text-white">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mb-2">Authenticating...</h5>
            <p class="mb-0">Please wait while we verify your credentials</p>
        </div>
    </div>
</div>

<!-- Success Message Overlay -->
<div id="success-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.8); z-index: 9999;">
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="text-center text-white">
            <div class="mb-4">
                <i class="ti ti-check-circle text-success" style="font-size: 4rem;"></i>
            </div>
            <h4 class="mb-3 text-success">Login Successful!</h4>
            <p class="mb-3">Welcome back! Redirecting to dashboard...</p>
            <div class="d-flex align-items-center justify-content-center">
                <span class="me-2">Redirecting in</span>
                <span id="countdown" class="badge bg-primary fs-6">5</span>
                <span class="ms-2">seconds</span>
            </div>
        </div>
    </div>
</div>

<div class="container-fuild">
    <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100">
        <div class="row">
            <div class="col-lg-5">
                <div class="login-background position-relative d-lg-flex align-items-center justify-content-center d-none flex-wrap vh-100">
                    <div class="bg-overlay-img"></div>
                    <div class="authentication-card w-100">
                        <div class="authen-overlay-item border w-100">
                            <h1 class="text-white display-1">Uonly Solutions <br>  A Company that provide, infinite solutions</h1>
                            <div class="my-4 mx-auto authen-overlay-img"></div>
                            <div>
                                <p class="text-white fs-20 fw-semibold text-center">Efficiently manage your workforce, streamline <br> operations effortlessly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-12 col-sm-12">
                <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap">
                    <div class="col-md-7 mx-auto vh-100">
                        <form id="admin-login-form" method="POST" action="{{ route('admin.login') }}" class="vh-100">
                            @csrf
                            <div class="vh-100 d-flex flex-column justify-content-between p-4 pb-0">
                                <div class=" mx-auto mb-5 text-center">
                                    <img src="{{ asset('frontend-assets/design_img/logo.png')}}" class="img-fluid" alt="Logo">
                                </div>
                                <div class="">
                                    <!-- Alert Messages -->
                                    <div id="login-alerts"></div>
                                    
                                    <div class="text-center mb-3">
                                        <h2 class="mb-2">Welcome Administrator</h2>
                                        <p class="mb-0">Please enter your details to sign in</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <input type="email" id="email" placeholder="Enter your email" class="form-control border-end-0" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            <span class="input-group-text border-start-0">
                                                <i class="ti ti-mail"></i>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback" id="email-error"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="pass-group">
                                            <input type="password" id="password" placeholder="************" class="pass-input form-control" name="password" required autocomplete="current-password">
                                            <span class="ti toggle-password ti-eye-off"></span>
                                        </div>
                                        <div class="invalid-feedback" id="password-error"></div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-check-md mb-0">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="remember" class="form-check-label mt-0">Remember Me</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" id="login-btn" class="btn btn-primary w-100">
                                            <span class="btn-text">Sign In</span>
                                            <span class="btn-loader d-none">
                                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                Signing In...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-5 pb-4 text-center">
                                    <p class="mb-0 text-gray-9">Copyright &copy; 2025 - Uonly</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Check if CSRF token exists
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    
    
    // Professional AJAX Login Implementation
    $('#admin-login-form').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        clearErrors();
        
        // Disable form and show loading state
        setLoadingState(true);
        
        // Get form data
        const formData = {
            email: $('#email').val(),
            password: $('#password').val(),
            remember: $('#remember').is(':checked') ? 1 : 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        // AJAX Request
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                setLoadingState(false);
                
                if (response.success) {
                    showSuccessMessage(response.message || 'Login successful!');
                    
                    // Redirect after 5 seconds
                    setTimeout(function() {
                        window.location.href = response.redirect || '/admin/dashboard';
                    }, 5000);
                } else {
                    displayErrors(response.errors || {});
                }
            },
            error: function(xhr, status, error) {
                setLoadingState(false);
                
                if (xhr.status === 422) {
                    // Validation errors
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.errors) {
                            displayValidationErrors(response.errors);
                        } else {
                            showError(response.message || 'Validation failed.');
                        }
                    } catch (e) {
                        showError('Validation failed. Please check your input.');
                    }
                } else if (xhr.status === 403) {
                    // Access denied
                    try {
                        const response = JSON.parse(xhr.responseText);
                        showError(response.message || 'Access denied.');
                    } catch (e) {
                        showError('Access denied.');
                    }
                } else if (xhr.status === 419) {
                    // CSRF token mismatch
                    showError('Session expired. Please refresh the page and try again.');
                } else if (xhr.status === 500) {
                    showError('Server error. Please try again later.');
                } else {
                    showError('An error occurred. Please try again.');
                }
            }
        });
    });
    
    function setLoadingState(loading) {
        const $btn = $('#login-btn');
        const $form = $('#admin-login-form');
        
        if (loading) {
            // Disable form elements
            $form.find('input, button').prop('disabled', true);
            
            // Show button loading state
            $btn.find('.btn-text').addClass('d-none');
            $btn.find('.btn-loader').removeClass('d-none');
            
            // Show loading overlay
            $('#login-loader').removeClass('d-none');
        } else {
            // Enable form elements
            $form.find('input, button').prop('disabled', false);
            
            // Hide button loading state
            $btn.find('.btn-text').removeClass('d-none');
            $btn.find('.btn-loader').addClass('d-none');
            
            // Hide loading overlay
            $('#login-loader').addClass('d-none');
        }
    }
    
    function clearErrors() {
        $('#login-alerts').empty();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').empty().hide();
    }
    
    function displayValidationErrors(errors) {
        console.log('Displaying validation errors:', errors); // Debug log
        
        $.each(errors, function(field, messages) {
            const $field = $('#' + field);
            const $error = $('#' + field + '-error');
            
            $field.addClass('is-invalid');
            $error.text(messages[0]).show();
        });
        
        // Also show general error message
        if (errors.email && errors.email[0]) {
            showError(errors.email[0]);
        }
    }
    
    function showError(message) {
        console.log('Showing error:', message); // Debug log
        
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('#login-alerts').html(alertHtml);
        
        // Scroll to top to show the error
        $('html, body').animate({
            scrollTop: $('#login-alerts').offset().top - 100
        }, 500);
    }
    
    function showSuccessMessage(message) {
        $('#success-overlay').removeClass('d-none');
    }
    
    function startCountdownRedirect(redirectUrl) {
        let countdown = 5;
        const $countdown = $('#countdown');
        
        const timer = setInterval(function() {
            countdown--;
            $countdown.text(countdown);
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = redirectUrl;
            }
        }, 1000);
    }
    
    // Password toggle functionality
    $(document).on('click', '.toggle-password', function() {
        const $this = $(this);
        const $input = $this.siblings('.pass-input');
        
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $this.removeClass('ti-eye-off').addClass('ti-eye');
        } else {
            $input.attr('type', 'password');
            $this.removeClass('ti-eye').addClass('ti-eye-off');
        }
    });
});
</script>
@endsection