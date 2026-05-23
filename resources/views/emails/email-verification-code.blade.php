<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification Code</title>
</head>
<body>
    <p>Hello,</p>
    <p>Your email verification code is <strong>{{ $code }}</strong>.</p>
    <p>This code will expire in {{ $expiresMinutes }} minutes.</p>
    <p>If you did not request this code, you can ignore this email.</p>
    <p>Thanks,<br>UOnly Team</p>
</body>
</html>