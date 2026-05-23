<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Device Sharing - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
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

        /* Content Card */
        .content-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            margin: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            color: var(--text-dark);
        }

        /* Specific Device Page Styles */
        .device-setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .setting-label {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-dark);
        }

        /* Toggle Switch */
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
            border-radius: 34px;
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
            border-radius: 50%;
        }

        input:checked + .slider {
            background: var(--primary-gradient);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px var(--pink-highlight);
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(22px);
            -ms-transform: translateX(22px);
            transform: translateX(22px);
        }

        /* Info Box */
        .info-box {
            background-color: rgba(128, 90, 213, 0.1);
            border-radius: 16px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            align-items: flex-start;
            color: var(--text-dark);
            border-left: 4px solid #805AD5;
        }

        .info-icon-wrapper {
            margin-right: 12px;
            margin-top: 2px;
        }

        .info-icon {
            color: #805AD5;
            font-size: 20px;
        }

        .info-text {
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-dark);
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

    <div class="mobile-wrapper">
        
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.profile') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">Device Sharing</div>
        </div>

        <!-- Device Settings Card -->
        <div class="content-card">
            <div class="device-setting-row">
                <div class="setting-label">Enable Device Sharing</div>
                <label class="switch">
                    <input type="checkbox" id="deviceSharingToggle" {{ $registration->device_sharing_enabled ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <!-- Info Box (Inside card or outside? Image shows it's separate usually, but user asked for "same structure". 
                 In transactions, content is in cards. Let's keep it in the card or a separate card. 
                 The uploaded image showed the toggle in a white card and the info box below it separately.
                 However, strictly following "Transaction Page Structure", we should use content-cards.
                 I will put the toggle in one card, and maybe the info in another or just inside the same one below.)
            -->
             <div class="info-box">
                <div class="info-icon-wrapper">
                    <i class="fas fa-info-circle info-icon"></i>
                </div>
                <div class="info-text">
                    Sharing your device allows other users to access certain features. Adjust these settings to your preference.
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePermission(settingName, isChecked) {
            // Logic to update permission via AJAX would go here
            console.log(settingName + " is now " + (isChecked ? "enabled" : "disabled"));
            // Example: fetch('/user/settings/update', { method: 'POST', body: JSON.stringify({ setting: settingName, value: isChecked }) });
        }
    </script>
    @include('user.partials.theme-script')
</body>
</html>