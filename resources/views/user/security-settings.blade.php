<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Security Settings - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Common Styles matched with Profile */
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
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            flex-grow: 1;
            text-align: center;
            margin-right: 24px;
        }

        /* List Items */
        .settings-list {
            background: transparent;
            margin-top: 10px;
        }

        .settings-item {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            background: transparent;
            text-decoration: none;
            color: var(--text-dark);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .settings-icon {
            width: 40px;
            font-size: 24px;
            color: var(--pink-highlight);
            display: flex;
            justify-content: flex-start; /* Align left as per screenshot? No, centered in width usually */
            align-items: center;
            margin-right: 15px;
        }
        
        .settings-text {
            flex: 1;
            font-size: 16px;
            font-weight: 500;
        }

        .settings-arrow {
            color: var(--text-dark); /* Screenshot shows black/dark arrows */
            font-size: 14px;
        }

        /* Custom Icons */
        .icon-123 {
            background-color: var(--pink-highlight);
            color: white;
            font-size: 11px;
            font-weight: bold;
            width: 26px;
            height: 22px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .icon-nfc {
            border: 2px solid var(--pink-highlight);
            color: var(--pink-highlight);
            width: 24px;
            height: 24px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* Toggle Switch */
        .form-switch .form-check-input {
            width: 3.5em;
            height: 1.75em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
            border-color: #cbd5e0;
            background-color: #cbd5e0; /* Grey when off */
        }
        .form-switch .form-check-input:checked {
            background-color: #718096; /* Dark grey when on as per screenshot? Screenshot shows grey. */
            border-color: #718096;
        }
        /* Override checked color if needed, screenshot shows grey switch when off, maybe grey when on too? 
           Screenshot shows "Enable Biometric" is OFF (grey)
           "Enable NFC Payment" is OFF (grey)
           I will stick to standard bootstrap or custom colors. 
           Bootstrap default checked is blue. I'll make it grey or theme color.
        */
        
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
            <a href="{{ route('user.profile') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
            <div class="page-title">Security Settings</div>
            <div style="width: 24px;"></div>
        </div>

        <div class="settings-list">
            <!-- Set/Change Password -->
            <a href="{{ route('user.password.show') }}" class="settings-item">
                <div class="settings-icon">
                    <i class="fas fa-lock-open"></i>
                </div>
                <div class="settings-text">Set/Change Password</div>
                <i class="fas fa-chevron-right settings-arrow"></i>
            </a>

            <!-- Change MPIN -->
            <!-- Assuming there is a route for MPIN or placeholder -->
            <a href="{{ route('user.pin.change.show') }}" class="settings-item">
                <div class="settings-icon">
                    <span class="icon-123">123</span>
                </div>
                <div class="settings-text">Change MPIN</div>
                <i class="fas fa-chevron-right settings-arrow"></i>
            </a>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>