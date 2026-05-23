<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Card OTP Verification - UOnly">
    <title>E-Card OTP Verification - UOnly</title>
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->ecardseva_favicon ? asset('storage/'.$settings->ecardseva_favicon) : ($settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico')) }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/assets/css/style.css') }}">
</head>
<body class="account-page bg-white">
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

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
                                        <img src="{{ asset('backend_assets/assets/img/logo-white.svg') }}" alt="Img">
                                    @endif
                                </a>
                            </div>

                            <div class="card">
                                <div class="card-body p-5">
                                    <div class="login-userheading">
                                        <h3>Verify OTP</h3>
                                        <h4>Enter the 6-digit OTP sent to {{ $maskedEmail }}.</h4>
                                    </div>

                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                        </div>
                                    @endif
                                    @if(!empty($devOtp))
                                        <div class="alert alert-warning">
                                            <strong>DEV OTP:</strong> {{ $devOtp }}
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

                                    <form method="POST" action="{{ route('ecard.login.otp.verify') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">OTP <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" inputmode="numeric" maxlength="6" class="form-control border-end-0 @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" placeholder="Enter 6-digit OTP" required>
                                                <span class="input-group-text border-start-0">
                                                    <i class="ti ti-lock"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-login">
                                            <button type="submit" class="btn btn-primary w-100">Verify & Login</button>
                                        </div>
                                    </form>

                                    <div class="mt-3 d-flex gap-2">
                                        <form method="POST" action="{{ route('ecard.login.otp.resend') }}" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary w-100">Resend OTP</button>
                                        </form>
                                        <a href="{{ route('ecard.login') }}" class="btn btn-outline-dark w-100">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                            <p>Copyright &copy; {{ date('Y') }} UOnly</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend_assets/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend_assets/assets/js/script.js') }}"></script>
</body>
</html>
