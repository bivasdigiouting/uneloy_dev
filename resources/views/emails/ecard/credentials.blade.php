<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your E-Card Portal Credentials</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif; background:#f5f7fb; margin:0; padding:0; }
        .container { max-width:640px; margin:32px auto; background:#ffffff; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.08); overflow:hidden; }
        .header { background: linear-gradient(135deg,#00c6ff 0%,#0072ff 100%); color:#fff; padding:24px; }
        .header h1 { margin:0; font-size:22px; }
        .content { padding:24px; color:#333; }
        .credentials { background:#f0f7ff; border:1px solid #d6e8ff; border-radius:8px; padding:16px; margin:16px 0; }
        .credential-item { margin-bottom:8px; }
        .btn { display:inline-block; background:#0072ff; color:#fff !important; text-decoration:none; padding:10px 16px; border-radius:8px; font-weight:600; }
        .footer { padding:16px 24px; color:#6b7280; font-size:13px; }
    </style>
    </head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to the E-Card Portal</h1>
            <div style="opacity:0.9; font-size:14px; margin-top:6px;">UOnly Services</div>
        </div>
        <div class="content">
            <p>Dear {{ $ecard->full_name ?? ($ecard->first_name.' '.$ecard->last_name) }},</p>
            <p>We’re pleased to share your secure access credentials for the E-Card portal. Use the details below to log in and manage your profile and benefits.</p>

            <div class="credentials">
                <div class="credential-item"><strong>User ID:</strong> {{ $userId }}</div>
                <div class="credential-item"><strong>Temporary Password:</strong> {{ $plainPassword }}</div>
                <div class="credential-item"><strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></div>
            </div>

            <p><a href="{{ $loginUrl }}" class="btn">Login to E-Card Portal</a></p>

            <p style="margin-top:16px;">For your security, please change your password immediately after logging in.</p>

            <p>If you need assistance, reply to this email or contact our support team.</p>
            <p>Warm regards,<br>UOnly Services Team</p>
        </div>
        <div class="footer">
            <div>This message was sent to {{ $ecard->email_id }} because you registered for E-Card services.</div>
            <div style="margin-top:6px;">© {{ date('Y') }} UOnly Services. All rights reserved.</div>
        </div>
    </div>
</body>
</html>