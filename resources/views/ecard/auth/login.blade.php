<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Card Login - UOnly">
    <meta name="keywords" content="ecard, login, uonly">
    <meta name="author" content="UOnly">
    <meta name="robots" content="index, follow">
    <title>E-Card Login - UOnly</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->ecardseva_favicon ? asset('storage/'.$settings->ecardseva_favicon) : ($settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico')) }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/bootstrap.min.css') }}">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/style.css') }}">
    
</head>
<body class="account-page bg-white">

    <div id="global-loader" >
        <div class="whirly-loader"> </div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper login-new">
                <div class="row w-100">
                    <div class="col-lg-5 mx-auto">
                        <div class="login-content user-login">
                            <div class="login-logo">
                                @if($settings && $settings->ecardseva_logo)
                                    <img src="{{ asset('storage/'.$settings->ecardseva_logo) }}" alt="img">
                                @elseif($settings && $settings->logo)
                                    <img src="{{ asset('storage/'.$settings->logo) }}" alt="img">
                                @else
                                    <img src="{{ asset('backend_assets/assets/img/logo.svg') }}" alt="img">
                                @endif
                                <a href="#" class="login-logo logo-white">
                                    @if($settings && $settings->ecardseva_logo)
                                        <img src="{{ asset('storage/'.$settings->ecardseva_logo) }}" alt="Img">
                                    @elseif($settings && $settings->logo)
                                        <img src="{{ asset('storage/'.$settings->logo) }}" alt="Img">
                                    @else
                                        <img src="{{ asset('backend_assets/assets/img/logo-white.svg') }}"  alt="Img">
                                    @endif
                                </a>
                            </div>
                            <form method="POST" action="{{ route('ecard.login') }}">
                                @csrf
                                <div class="card">
                                    <div class="card-body p-5">
                                        <div class="login-userheading">
                                            <h3>Sign In</h3>
                                            <h4>Enter your credentials. A 6-digit OTP will be sent to your registered email for verification.</h4>
                                        </div>

                                        @if(session('success'))
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                            </div>
                                        @endif

                                        @if($errors->any())
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                @foreach($errors->all() as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">User ID / Email / Mobile <span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control border-end-0 @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" placeholder="Enter User ID, email or mobile number" required>
                                                <span class="input-group-text border-start-0">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password <span class="text-danger"> *</span></label>
                                            <div class="pass-group">
                                                <input type="password" class="pass-input form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password" required>
                                                <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                                            </div>
                                        </div>
                                        <div class="form-login authentication-check">
                                            <div class="row">
                                                <div class="col-12 d-flex align-items-center justify-content-between">
                                                    <div class="custom-control custom-checkbox">
                                                        <label class="checkboxs ps-4 mb-0 pb-0 line-height-1 fs-16 text-gray-6">
                                                            <input type="checkbox" class="form-control" name="remember">
                                                            <span class="checkmarks"></span>Remember me
                                                        </label>
                                                    </div>
                                                    <div class="text-end">
                                                        <a class="text-orange fs-16 fw-medium" href="javascript:void(0);">Forgot Password?</a>
                                                    </div>
                                                </div>                                    
                                            </div>
                                        </div>
                                        <div class="form-login">
                                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                                        </div>
                                        
                                        <!-- Optional Social Login / Register (Commented out or kept as UI placeholder if desired, but functionality not guaranteed) -->
                                        <!-- Keeping UI structure but disabling links -->
                                        <!--
                                        <div class="signinform">
                                            <h4>New on our platform?<a href="javascript:void(0);" class="hover-a"> Create an account</a></h4>
                                        </div>
                                        <div class="form-setlogin or-text">
                                            <h4>OR</h4>
                                        </div>
                                        <div class="mt-2">
                                            <div class="d-flex align-items-center justify-content-center flex-wrap">
                                                <div class="text-center me-2 flex-fill">
                                                    <a href="javascript:void(0);"
                                                        class="br-10 p-2 btn btn-info d-flex align-items-center justify-content-center">
                                                        <img class="img-fluid m-1" src="{{ asset('backend_assets/assets/img/icons/facebook-logo.svg') }}" alt="Facebook">
                                                    </a>
                                                </div>
                                                <div class="text-center me-2 flex-fill">
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-white br-10 p-2  border d-flex align-items-center justify-content-center">
                                                        <img class="img-fluid m-1" src="{{ asset('backend_assets/assets/img/icons/google-logo.svg') }}" alt="Facebook">
                                                    </a>
                                                </div>
                                                <div class="text-center flex-fill">
                                                    <a href="javascript:void(0);"
                                                        class="bg-dark br-10 p-2 btn btn-dark d-flex align-items-center justify-content-center">
                                                        <img class="img-fluid m-1" src="{{ asset('backend_assets/assets/img/icons/apple-logo.svg') }}" alt="Apple">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                            <p>Copyright &copy; {{ date('Y') }} UOnly</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('backend_assets/assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('backend_assets/assets/js/feather.min.js') }}"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="{{ asset('backend_assets/assets/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('backend_assets/assets/js/script.js') }}"></script>

</body>
</html>
