<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .credentials {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to {{ config('app.name') }}</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $staff->staff_name }},</h2>
        
        <p>Welcome to our team! Your staff account has been successfully created. Below are your account details:</p>
        
        <div class="credentials">
            <h3>Your Account Information:</h3>
            <p><strong>Name:</strong> {{ $staff->staff_name }}</p>
            <p><strong>Email:</strong> {{ $staff->email_id }}</p>
            <p><strong>User ID:</strong> {{ $staff->user_id ?? 'Not assigned' }}</p>
            @if($staff->designation)
                <p><strong>Designation:</strong> {{ $staff->designation->designation_name }}</p>
            @endif
            @if($staff->date_of_joining)
                <p><strong>Date of Joining:</strong> {{ date('d M Y', strtotime($staff->date_of_joining)) }}</p>
            @endif
            @if($password)
                <p><strong>Temporary Password:</strong> {{ $password }}</p>
                <p style="color: #dc3545; font-size: 14px;"><em>Please change your password after first login for security purposes.</em></p>
            @endif
        </div>
        
        <p>You can now access your account using the login credentials provided above.</p>
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
        </div>
        
        <h3>Next Steps:</h3>
        <ul>
            <li>Login to your account using the credentials above</li>
            <li>Complete your profile information</li>
            <li>Change your password for security</li>
            <li>Familiarize yourself with the system</li>
        </ul>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact the HR department or your supervisor.</p>
        
        <p>We're excited to have you on board!</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>