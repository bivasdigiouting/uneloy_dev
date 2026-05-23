<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency E-Card Seva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .page-shell { max-width: 520px; margin: 0 auto; min-height: 100vh; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #eef1f6; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 900; font-size: 1.55rem; margin: 0; text-align: center; flex: 1; }
        .form-wrap { padding: 8px 14px 28px; }
        .field { height: 58px; border-radius: 14px; border: 1px solid rgba(15, 23, 42, 0.28); background: #f7f9fc; padding: 0 16px; font-size: 1rem; }
        .field::placeholder { color: rgba(15, 23, 42, 0.55); }
        .field:focus { box-shadow: none; border-color: rgba(15, 23, 42, 0.45); background: #fff; }
        .select-label { font-weight: 800; font-size: 0.95rem; color: rgba(15, 23, 42, 0.75); margin: 10px 0 6px 6px; }
        .field-select { padding-right: 40px; }
        .field-area { height: auto; padding: 16px; }
        .field-wrap { position: relative; }
        .field-icon { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 18px; pointer-events: none; }
        .upload-label { font-weight: 900; font-size: 1.1rem; margin: 12px 0 10px; }
        .field-file { height: auto; padding: 14px 16px; background: #fff; }
        .submit-btn { height: 56px; border-radius: 14px; border: 0; width: 100%; font-weight: 900; background: var(--primary-gradient); color: #fff; }
        .submit-btn:hover { opacity: 0.95; color: #fff; }
        .alert-wrap { padding: 0 14px; }
        @media (min-width: 992px) {
            .topbar-inner { padding-left: 0; padding-right: 0; }
            .form-wrap { padding-left: 0; padding-right: 0; }
            .alert-wrap { padding-left: 0; padding-right: 0; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="topbar">
            <div class="topbar-inner">
                <a class="icon-btn" href="{{ route('user.benefit.emergency.dashboard') }}" aria-label="Back">
                    <i class="fas fa-arrow-left" style="font-size: 20px;"></i>
                </a>
                <h1 class="page-title">Emergency E-Card Seva</h1>
                <div style="width: 44px;"></div>
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
            <form method="POST" action="{{ route('user.benefit.emergency.ecard.request.submit') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <input type="text" name="name" class="form-control field" placeholder="Name" value="{{ old('name', optional($user)->full_name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="mobile_no" class="form-control field" placeholder="Mobile Number" value="{{ old('mobile_no', optional($user)->mobile_no ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="emergency_type" class="form-control field" placeholder="Emergency Type" value="{{ old('emergency_type') }}" required>
                </div>

                <div class="mb-3">
                    <input type="number" name="age" class="form-control field" placeholder="Age" value="{{ old('age', optional(optional($user)->date_of_birth)->age ?? '') }}" required min="1" max="120">
                </div>

                <div class="select-label">Gender</div>
                <div class="mb-3">
                    <select name="gender" class="form-select field field-select" required>
                        <option value="" {{ old('gender', optional($user)->gender ?? '') === '' ? 'selected' : '' }}>--Select--</option>
                        <option value="Male" {{ old('gender', optional($user)->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', optional($user)->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', optional($user)->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="mb-3 field-wrap">
                    <input type="text" name="live_location" class="form-control field" placeholder="Live Location" value="{{ old('live_location') }}" required>
                    <span class="field-icon"><i class="fas fa-location-dot"></i></span>
                </div>

                <div class="mb-3">
                    <textarea name="description" class="form-control field field-area" rows="4" placeholder="Description" required>{{ old('description') }}</textarea>
                </div>

                <div class="upload-label">Upload Image</div>
                <div class="mb-4">
                    <input type="file" name="image" class="form-control field field-file" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <button type="submit" class="submit-btn">Submit Request</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
