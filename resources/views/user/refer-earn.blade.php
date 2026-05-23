<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Refer & Earn - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Common Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: #ffffff; /* White background for this page */
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .profile-header {
            background: transparent;
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
            margin-right: 24px; /* Balance the back button */
        }

        /* Refer & Earn Specific Styles */
        .refer-content {
            flex: 1;
            padding: 20px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            justify-content: flex-start;
            padding-top: 40px;
        }

        .refer-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 30px;
            max-width: 280px;
            line-height: 1.4;
        }

        .refer-card {
            background-color: #fce7f3; /* Light pink background closer to image */
            border-radius: 20px;
            padding: 30px 20px;
            width: 100%;
            max-width: 350px;
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .refer-label {
            font-size: 14px;
            color: var(--text-dark);
            margin-bottom: 10px;
            font-weight: 500;
        }

        .refer-code {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .copy-btn {
            background-color: #f3e8ff; /* Light purple */
            color: #6b21a8; /* Purple text */
            border: none;
            border-radius: 50px;
            padding: 8px 25px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .copy-btn:active {
            transform: scale(0.98);
        }
        
        .copy-btn i {
            font-size: 16px;
        }

        .invite-btn {
            background-color: white;
            color: #6b21a8; /* Purple text */
            border: none;
            border-radius: 50px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .invite-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
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
            <div class="page-title">Refer & Earn</div>
        </div>

        <div class="refer-content">
            <div class="refer-title">Invite your friends and earn rewards!</div>

            <div class="refer-card">
                <div class="refer-label">Your Referral Code</div>
                <div class="refer-code" id="referralCode">{{ $referralCode ?? 'ECARD12345' }}</div>
                
                <button class="copy-btn" onclick="copyToClipboard()">
                    <i class="far fa-copy"></i> Copy Code
                </button>
            </div>

            <button class="invite-btn" onclick="shareInvite()">
                <i class="fas fa-share-alt"></i> Invite Now
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyToClipboard() {
            const code = document.getElementById('referralCode').innerText;
            navigator.clipboard.writeText(code).then(() => {
                // Optional: Visual feedback
                const btn = document.querySelector('.copy-btn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        function shareInvite() {
            if (navigator.share) {
                navigator.share({
                    title: 'Join UOnly',
                    text: 'Use my referral code ' + document.getElementById('referralCode').innerText + ' to join!',
                    url: window.location.origin
                })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing', error));
            } else {
                alert('Sharing is not supported on this browser. Please copy the code manually.');
            }
        }
    </script>
    @include('user.partials.theme-script')
</body>
</html>
