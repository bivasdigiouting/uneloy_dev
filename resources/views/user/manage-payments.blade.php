<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment Manage - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Common Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: #ffffff;
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
            margin-right: 24px;
        }

        /* Content */
        .content-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Illustration */
        .illustration-container {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .illustration-img {
            max-width: 250px;
            width: 100%;
            height: auto;
        }

        /* Payment Manage Card */
        .payment-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 25px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .toggle-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .toggle-item:last-child {
            margin-bottom: 0;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 16px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .toggle-icon {
            width: 24px;
            text-align: center;
        }

        /* Custom Toggle Switch */
        .form-check-input {
            width: 3em;
            height: 1.5em;
            background-color: #e2e8f0;
            border-color: #e2e8f0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #d63384; /* Pink color */
            border-color: #d63384;
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(214, 51, 132, 0.25);
            border-color: #d63384;
        }

        /* Chart Section */
        .chart-container {
            position: relative;
            width: 300px;
            height: 300px;
            margin: 0 auto;
        }

        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: white;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .chart-center-title {
            font-size: 18px;
            color: #333;
            font-weight: 600;
            line-height: 1.2;
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
            <div class="page-title">Payment Manage</div>
        </div>

        <div class="content-body">
            <!-- Illustration -->
            <div class="illustration-container">
                <!-- Using a placeholder that represents the image content: phones exchanging money -->
                <!-- Since I cannot upload the exact image, I will use a FontAwesome composition or a similar looking placeholder if available, but standard SVG/Img is safer -->
                <svg width="200" height="150" viewBox="0 0 200 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Simplified vector representation of two phones -->
                    <rect x="30" y="20" width="60" height="110" rx="8" transform="rotate(-15 30 20)" fill="white" stroke="#2563eb" stroke-width="3"/>
                    <rect x="120" y="30" width="60" height="110" rx="8" transform="rotate(15 120 30)" fill="white" stroke="#2563eb" stroke-width="3"/>
                    <!-- Hands/Money representation abstract -->
                    <circle cx="80" cy="70" r="15" fill="#eab308"/> <!-- Coin -->
                    <path d="M60 80 L100 90 L140 80" stroke="#2563eb" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <!-- Payment Manage Card -->
            <div class="payment-card">
                <div class="card-title">Payment Manage</div>
                
                <!-- e-Card Payment -->
                <div class="toggle-item">
                    <div class="toggle-label">
                        <div class="toggle-icon" style="color: #d63384;">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        e-Card Payment
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" checked>
                    </div>
                </div>

                <!-- e-Wallet Payment -->
                <div class="toggle-item">
                    <div class="toggle-label">
                        <div class="toggle-icon" style="color: #d63384;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        e- Wallet Payment
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" checked>
                    </div>
                </div>

                <!-- e-QR Payment -->
                <div class="toggle-item">
                    <div class="toggle-label">
                        <div class="toggle-icon" style="color: #d63384;">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        e- QR Payment
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" checked>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="chart-container">
                <canvas id="paymentChart"></canvas>
                <div class="chart-center-text">
                    <div class="chart-center-title">Payment<br>Shares</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('paymentChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['e-Wallet', 'e-QR', 'e-Card'],
                    datasets: [{
                        data: [50, 30, 20], 
                        backgroundColor: [
                            '#a855f7', // Purple
                            '#06b6d4', // Cyan
                            '#ec4899'  // Pink
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '65%',
                    rotation: 180, // Start from bottom to position Purple (50%) on the Left
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    layout: {
                        padding: 10
                    }
                },
                plugins: [{
                    id: 'customLabels',
                    afterDraw: function(chart) {
                        const ctx = chart.ctx;
                        chart.data.datasets.forEach((dataset, i) => {
                            chart.getDatasetMeta(i).data.forEach((datapoint, index) => {
                                const {x, y} = datapoint.tooltipPosition();
                                
                                ctx.save();
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillStyle = '#fff';
                                
                                // Icons
                                ctx.font = '900 18px "Font Awesome 6 Free"';
                                let icon = '';
                                if(index === 0) icon = '\uf555'; // Wallet (Purple)
                                if(index === 1) icon = '\uf029'; // QR (Cyan)
                                if(index === 2) icon = '\uf09d'; // Card (Pink)
                                
                                // Position adjustment
                                if (index === 2) {
                                    // For the small pink slice, just center the icon
                                    ctx.fillText(icon, x, y);
                                } else {
                                    // For others, icon top, text bottom
                                    ctx.fillText(icon, x, y - 10);
                                    
                                    ctx.font = '600 14px "Poppins"';
                                    let label = dataset.data[index] + '%';
                                    ctx.fillText(label, x, y + 12);
                                }
                                
                                ctx.restore();
                            });
                        });
                    }
                }]
            });
        });
    </script>
    @include('user.partials.theme-script')
</body>
</html>