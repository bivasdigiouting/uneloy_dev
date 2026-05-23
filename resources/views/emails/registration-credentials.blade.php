<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Login Credentials</title>
</head>
<body style="margin:0;padding:24px;font-family:Arial,Helvetica,sans-serif;line-height:1.6;background:#f8f9fa;color:#212529;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e9ecef;border-radius:8px;overflow:hidden;">
        <div style="padding:20px;border-bottom:1px solid #e9ecef;background:#f1f3f5;">
            <h2 style="margin:0;font-size:20px;">Welcome to Uonly</h2>
        </div>
        <div style="padding:20px;">
            <p style="margin:0 0 12px;">Dear {{ $registration->first_name }}{{ isset($registration->last_name) ? ' ' . $registration->last_name : '' }},</p>
            <p style="margin:0 0 16px;">Your registration has been completed. Here are your login credentials:</p>
            <ul style="margin:0 0 16px; padding-left:20px;">
                <li>User ID: <strong>{{ $registration->user_id }}</strong></li>
                <li>Email: <strong>{{ $registration->email_id ?? '—' }}</strong></li>
                <li>Password: <strong>{{ $password }}</strong></li>
            </ul>
            <p style="margin:0 0 8px;">Sign in here:</p>
            <p style="margin:0 0 16px;"><a href="{{ route('user.login') }}" style="color:#0d6efd;text-decoration:none;" target="_blank">{{ route('user.login') }}</a></p>
            <p style="margin:0 0 16px;font-size:13px;color:#6c757d;">If you did not request this account, you can safely ignore this email.</p>
            <p style="margin:0;">Thanks,<br/>Uonly Team</p>
        </div>
    </div>
</body>
</html>