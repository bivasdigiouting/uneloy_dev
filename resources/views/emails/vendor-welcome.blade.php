<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Vendor Portal</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-message {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .credentials-box h3 {
            color: #495057;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .credential-item {
            margin: 15px 0;
            padding: 12px;
            background-color: #ffffff;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .credential-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .credential-value {
            font-size: 16px;
            color: #2c3e50;
            font-weight: 500;
            margin-top: 5px;
        }
        .login-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .login-button:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        .security-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .company-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Welcome to Vendor Portal</h1>
            <p>Your account has been successfully created!</p>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                <strong>Dear {{ $vendor->first_name }} {{ $vendor->last_name }},</strong>
            </div>
            
            <p>Congratulations! Your vendor account has been successfully registered in our system. We're excited to have you as part of our vendor network.</p>
            
            <div class="credentials-box">
                <h3>🔐 Your Login Credentials</h3>
                
                <div class="credential-item">
                    <div class="credential-label">Vendor Number</div>
                    <div class="credential-value">{{ $vendor->vendor_number }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Email Address</div>
                    <div class="credential-value">{{ $vendor->gmail_id }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Password</div>
                    <div class="credential-value">{{ $password }}</div>
                </div>
            </div>
            
            <div class="security-note">
                <strong>🔒 Security Notice:</strong> Please change your password after your first login for security purposes. Keep your login credentials confidential and do not share them with anyone.
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/vendor/login') }}" class="login-button">
                    🚀 Access Vendor Portal
                </a>
            </div>
            
            <div class="company-info">
                <h4>What's Next?</h4>
                <ul style="text-align: left; color: #6c757d;">
                    <li>Log in to your vendor portal using the credentials above</li>
                    <li>Complete your profile information</li>
                    <li>Upload required documents</li>
                    <li>Start managing your products and orders</li>
                </ul>
            </div>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        </div>
        
        <div class="footer">
            <p><strong>Best regards,</strong><br>
            The Vendor Management Team</p>
            
            <div class="company-info">
                <p>This email was sent to {{ $vendor->gmail_id }}<br>
                If you didn't request this account, please contact us immediately.</p>
            </div>
        </div>
    </div>
</body>
</html>