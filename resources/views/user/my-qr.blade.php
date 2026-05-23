<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My QR Code - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* :root {
            --primary-gradient: linear-gradient(135deg, #D53F8C 0%, #805AD5 100%);
            --bg-light: #f3f4f6;
            --text-dark: #333333;
        } */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            margin: 0;
        }
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
        }
        /* Header */
        .profile-header {
            background: #fff;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #eee;
        }
        .back-btn {
            font-size: 20px;
            color: var(--text-dark);
            text-decoration: none;
            margin-right: 15px;
        }
        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            flex-grow: 1;
        }
        
        /* QR Section */
        .qr-card {
            margin: 20px;
            padding: 30px;
            background: #fff;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }
        .qr-frame {
            display: inline-block;
            padding: 15px;
            border: 2px dashed var(--pink-highlight);
            border-radius: 15px;
            margin-bottom: 20px;
            background: #fff;
        }
        .qr-image {
            width: 100%;
            max-width: 250px;
            height: auto;
            display: block;
        }
        .user-name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-dark);
        }
        .user-id {
            color: #718096;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .download-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(213, 63, 140, 0.3);
            text-decoration: none;
        }
        .download-btn:hover {
            color: white;
            opacity: 0.95;
        }
        
        /* Desktop specific adjustments */
        @media (min-width: 768px) {
            body {
                background-color: #e2e8f0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .mobile-wrapper {
                border-radius: 20px;
                overflow: hidden;
                height: 90vh;
                max-height: 800px;
                overflow-y: auto;
                width: 100%;
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
            <div class="page-title">My QR Code</div>
            <!-- Optional right icon -->
            <div style="width: 24px;"></div> 
        </div>

        <!-- QR Content -->
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: calc(100% - 70px); padding: 20px;">
            
            <div class="qr-card">
                @if($qrCodeUrl)
                    <div class="qr-frame">
                        <img src="{{ $qrCodeUrl }}" alt="My QR Code" class="qr-image">
                    </div>
                    
                    <h2 class="user-name">{{ $user->full_name ?? 'User' }}</h2>
                    <p class="user-id">ID: {{ $user->user_id ?? 'N/A' }}</p>
                    
                    <p class="text-muted small mb-4">Scan this QR code to pay me directly.</p>
                    
                    <a href="{{ $qrCodeUrl }}" download="my-qr-{{ $user->user_id }}.svg" class="download-btn">
                        <i class="fas fa-download"></i> Download QR
                    </a>
                @else
                    <div class="py-5">
                        <i class="fas fa-exclamation-circle text-muted fa-3x mb-3"></i>
                        <p class="text-muted">QR Code not available.</p>
                        @if(strtolower($user->department_level ?? '') !== 'customer')
                            <p class="small text-danger">Only customers can generate QR codes.</p>
                        @endif
                        <button class="btn btn-outline-primary mt-3" onclick="window.location.reload()">Try Again</button>
                    </div>
                @endif
            </div>
            
            <!-- Bottom Info -->
            <div class="text-center mt-3 px-4">
                <p class="small text-muted">
                    <i class="fas fa-shield-alt me-1"></i> Secured by UOnly Payments
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
    <script>
        function downloadQR() {
            const link = document.createElement('a');
            link.href = '{{ $qrCodeUrl }}';
            link.download = 'my-qr-{{ $user->user_id }}.svg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>



