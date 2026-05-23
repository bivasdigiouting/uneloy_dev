<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #f7eaf1; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .page-shell { max-width: 520px; margin: 0 auto; min-height: 100vh; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #f7eaf1; }
        .topbar-inner { height: 64px; display: flex; align-items: center; gap: 10px; padding: 10px 14px; }
        .back-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; }
        .page-title { font-weight: 900; font-size: 1.35rem; margin: 0; flex: 1; text-align: center; padding-right: 44px; }
        .form-wrap { padding: 8px 14px 28px; }
        .field { height: 58px; border-radius: 14px; border: 1px solid rgba(15, 23, 42, 0.35); background: #ffffff; padding: 0 16px; font-size: 1rem; }
        .field::placeholder { color: rgba(15, 23, 42, 0.6); }
        .field:focus { box-shadow: none; border-color: rgba(15, 23, 42, 0.55); }
        .field-select { padding-right: 40px; }
        .submit-btn { height: 56px; border-radius: 14px; border: 0; width: 100%; font-weight: 800; background: var(--primary-gradient); color: #fff; }
        .submit-btn:hover { opacity: 0.95; color: #fff; }
        .alert-wrap { padding: 0 14px; }
        @media (min-width: 992px) {
            .form-wrap { padding-left: 0; padding-right: 0; }
            .topbar-inner { padding-left: 0; padding-right: 0; }
            .alert-wrap { padding-left: 0; padding-right: 0; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="topbar">
            <div class="topbar-inner">
                <a class="back-btn" href="{{ route('user.benefit.blood.dashboard') }}" aria-label="Back">
                    <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
                </a>
                <h1 class="page-title">Blood Donate</h1>
            </div>
        </div>

        <div class="alert-wrap">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-0">
                    <div class="fw-semibold">Please fix the errors and try again.</div>
                </div>
            @endif
        </div>

        <div class="form-wrap">
            <form method="POST" action="{{ route('user.benefit.blood.request.submit') }}">
                @csrf

                <div class="mb-3">
                    <input type="text" name="name" class="form-control field" placeholder="Name" value="{{ old('name', optional($user)->full_name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="mobile_no" class="form-control field" placeholder="Mobile Number" value="{{ old('mobile_no', optional($user)->mobile_no ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <input type="number" name="age" class="form-control field" placeholder="Age" value="{{ old('age', optional(optional($user)->date_of_birth)->age ?? '') }}" required min="1" max="120">
                </div>

                <div class="mb-3">
                    <select name="gender" class="form-select field field-select" required>
                        <option value="" {{ old('gender', optional($user)->gender ?? '') === '' ? 'selected' : '' }}>Gender</option>
                        <option value="Male" {{ old('gender', optional($user)->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', optional($user)->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', optional($user)->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <select name="blood_group" class="form-select field field-select" required>
                        <option value="" {{ old('blood_group', optional($user)->blood_group ?? '') === '' ? 'selected' : '' }}>Blood Group</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', optional($user)->blood_group ?? '') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <input type="text" name="hospital_name" class="form-control field" placeholder="Hospital Name" value="{{ old('hospital_name') }}" required>
                </div>

                <div class="mb-4">
                    <input type="text" name="hospital_address" class="form-control field" placeholder="Hospital Address" value="{{ old('hospital_address') }}" required>
                </div>

                <button type="submit" class="submit-btn">Submit Request</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
