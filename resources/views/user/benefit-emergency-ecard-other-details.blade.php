<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #0f172a; }
        .page-shell { max-width: 560px; margin: 0 auto; min-height: 100vh; }
        .topbar { position: sticky; top: 0; z-index: 10; background: #eef1f6; }
        .topbar-inner { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; }
        .icon-btn { width: 44px; height: 44px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; color: #0f172a; text-decoration: none; border: 0; background: transparent; }
        .page-title { font-weight: 500; font-size: 1.55rem; margin: 0; text-align: center; flex: 1; }

        .content { padding: 10px 14px 26px; }
        .alert-wrap { padding: 0 14px; }
        .section-title { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 1.25rem; color: rgba(15, 23, 42, 0.85); margin: 12px 0; }
        .section-title .chev { color: #0f172a; font-size: 1.35rem; }

        .field-wrap { position: relative; }
        .field-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: rgba(15, 23, 42, 0.6); font-size: 18px; pointer-events: none; }
        .field { height: 62px; border-radius: 14px; border: 1px solid rgba(15, 23, 42, 0.22); background: #f3f5f8; padding: 0 16px 0 52px; font-size: 1.1rem; }
        .field:focus { box-shadow: none; border-color: rgba(15, 23, 42, 0.35); background: #fff; }
        .field-select { padding-right: 44px; }
        .submit-btn { height: 56px; border-radius: 14px; border: 0; width: 100%; font-weight: 900; background: var(--primary-gradient); color: #fff; }
        .submit-btn:hover { opacity: 0.95; color: #fff; }
        @media (min-width: 992px) {
            .topbar-inner { padding-left: 0; padding-right: 0; }
            .content { padding-left: 0; padding-right: 0; }
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
                <h1 class="page-title">Emergency Contact</h1>
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

        <div class="content">
            <form method="POST" action="{{ route('user.benefit.emergency.ecard.other.details.submit') }}">
                @csrf

                <div class="section-title"><span class="chev">&raquo;&raquo;</span> Personal Information</div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-user"></i></span>
                    <input type="text" name="self_name" class="form-control field" placeholder="Self Name" value="{{ old('self_name', $detail->self_name ?? (optional($user)->full_name ?? '')) }}" required>
                </div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-mobile-screen-button"></i></span>
                    <input type="text" name="self_mobile_no" class="form-control field" placeholder="Self Mobile Number" value="{{ old('self_mobile_no', $detail->self_mobile_no ?? (optional($user)->mobile_no ?? '')) }}" required>
                </div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-kit-medical"></i></span>
                    <select name="blood_group" class="form-select field field-select" required>
                        @php
                            $bg = old('blood_group', $detail->blood_group ?? '');
                            $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                        @endphp
                        <option value="" {{ $bg === '' ? 'selected' : '' }}>Blood Group</option>
                        @foreach($groups as $g)
                            <option value="{{ $g }}" {{ $bg === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="section-title"><span class="chev">&raquo;&raquo;</span> Family Contact Numbers</div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-people-group"></i></span>
                    <input type="text" name="family_contact_1" class="form-control field" placeholder="Family Contact 1" value="{{ old('family_contact_1', $detail->family_contact_1 ?? '') }}">
                </div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-people-group"></i></span>
                    <input type="text" name="family_contact_2" class="form-control field" placeholder="Family Contact 2" value="{{ old('family_contact_2', $detail->family_contact_2 ?? '') }}">
                </div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-people-group"></i></span>
                    <input type="text" name="family_contact_3" class="form-control field" placeholder="Family Contact 3" value="{{ old('family_contact_3', $detail->family_contact_3 ?? '') }}">
                </div>

                <div class="section-title"><span class="chev">&raquo;&raquo;</span> Best Friend Contacts</div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-user-group"></i></span>
                    <input type="text" name="best_friend_contact_1" class="form-control field" placeholder="Best Friend Contact 1" value="{{ old('best_friend_contact_1', $detail->best_friend_contact_1 ?? '') }}">
                </div>

                <div class="mb-3 field-wrap">
                    <span class="field-icon"><i class="fas fa-user-group"></i></span>
                    <input type="text" name="best_friend_contact_2" class="form-control field" placeholder="Best Friend Contact 2" value="{{ old('best_friend_contact_2', $detail->best_friend_contact_2 ?? '') }}">
                </div>

                <div class="mb-4 field-wrap">
                    <span class="field-icon"><i class="fas fa-user-group"></i></span>
                    <input type="text" name="best_friend_contact_3" class="form-control field" placeholder="Best Friend Contact 3" value="{{ old('best_friend_contact_3', $detail->best_friend_contact_3 ?? '') }}">
                </div>

                <button type="submit" class="submit-btn">Save</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>
