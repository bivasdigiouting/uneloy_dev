<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Confirm Recharge - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Inheriting Profile Page Styles */
        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
        }

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

        /* Profile Card Style for Main Info */
        .profile-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            margin: 10px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            position: relative;
        }

        .profile-info-row {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .profile-avatar {
            width: 60px;
            height: 60px;
            background-color: var(--bg-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #a0aec0;
        }

        .profile-details {
            flex: 1;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .profile-contact {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 3px;
            display: flex;
            align-items: center;
        }

        .manage-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: var(--pink-highlight);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
        }

        /* Section Card for Plan Details */
        .section-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            margin: 15px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .detail-label {
            color: var(--text-muted);
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-dark);
            text-align: right;
        }

        .amount-box {
            background: linear-gradient(135deg, #ffe5ec 0%, #fff0f5 100%);
            border-radius: 12px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .amount-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .amount-value {
            font-size: 20px;
            font-weight: 700;
            color: #ff4757;
        }

        .confirm-btn {
            display: block;
            width: calc(100% - 40px);
            margin: 30px auto 40px;
            padding: 16px;
            background: linear-gradient(90deg, #ff6b81 0%, #ff4757 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 71, 87, 0.4);
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="mobile-wrapper">
        <!-- Header -->
        <div class="profile-header">
            @php
                $serviceType = request('service', 'mobile');
                $backRoute = $serviceType === 'dth' 
                    ? route('user.service.recharge.dth') 
                    : route('user.service.recharge.mobile', ['mobile' => request('mobile')]);
            @endphp
            <a href="{{ $backRoute }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Confirm Recharge</h1>
        </div>

        <!-- Recharge Info (Profile Card Style) -->
        <div class="profile-card">
            <div class="profile-info-row">
                <div class="profile-avatar">
                    @if(request('service') === 'dth')
                <i class="fas fa-satellite-dish" style="color: var(--text-dark);"></i>
            @elseif(request('service') === 'fastag')
                <i class="fas fa-car" style="color: var(--text-dark);"></i>
            @elseif(request('service') === 'bbps')
                @if(request('category') === 'electricity')
                    <i class="fas fa-lightbulb" style="color: var(--text-dark);"></i>
                @elseif(request('category') === 'water')
                    <i class="fas fa-tint" style="color: var(--text-dark);"></i>
                @elseif(request('category') === 'gas')
                    <i class="fas fa-burn" style="color: var(--text-dark);"></i>
                @elseif(request('category') === 'broadband')
                    <i class="fas fa-wifi" style="color: var(--text-dark);"></i>
                @else
                    <i class="fas fa-bolt" style="color: var(--text-dark);"></i>
                @endif
            @else
                <i class="fas fa-mobile-screen-button" style="color: var(--text-dark);"></i>
            @endif
                </div>
                <div class="profile-details">
                    <div class="profile-name">{{ request('mobile') }}</div>
                    <div class="profile-contact">
                        {{ request('operator') }} {{ request('circle') ? '- ' . request('circle') : '' }}
                    </div>
                </div>
                @if(request('service') === 'dth')
                    <a href="{{ route('user.service.recharge.dth') }}" class="manage-link">Edit</a>
                @else
                    <a href="{{ route('user.service.recharge.mobile') }}" class="manage-link">Edit</a>
                @endif
            </div>
        </div>

        <!-- Plan Details (Section Card Style) -->
        <div class="section-card">
            <h2 class="section-title">Plan Details</h2>
            
            <div class="detail-row">
                <span class="detail-label">Validity</span>
                <span class="detail-value">{{ request('validity') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Description</span>
                <span class="detail-value" style="max-width: 60%;">{{ request('plan_desc') ?? request('desc') }}</span>
            </div>

            <div class="amount-box">
                <span class="amount-label">Amount Payable</span>
                <span class="amount-value">₹{{ request('amount') }}</span>
            </div>
        </div>

        <!-- Confirm Button -->
        <form id="rechargeForm">
            <button type="button" id="payButton" class="confirm-btn">
                Pay ₹{{ request('amount') }}
            </button>
        </form>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1050">
        <div id="statusToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.9); z-index:2000; align-items:center; justify-content:center; flex-direction:column;">
        <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem;"></div>
        <div class="mt-3 fw-bold text-dark" id="loadingText">Processing...</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    <script>
        $(document).ready(function() {
            const cashfree = Cashfree({
                mode: "{{ $cashfreeMode ?? 'sandbox' }}"
            });

            function showToast(message, type = 'success') {
                const toast = $('#statusToast');
                toast.removeClass('bg-success bg-danger').addClass(type === 'success' ? 'bg-success' : 'bg-danger');
                $('#toastMessage').text(message);
                const bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();
            }

            function showLoading(text = 'Processing...') {
                $('#loadingText').text(text);
                $('#loadingOverlay').css('display', 'flex');
            }

            function hideLoading() {
                $('#loadingOverlay').hide();
            }

            // Check if returning from payment (order_id in URL)
            const urlParams = new URLSearchParams(window.location.search);
            const orderId = urlParams.get('order_id');
            // Cashfree usually appends order_status or similar, but we rely on order_id to trigger verification
            
            if (orderId) {
                // Verify and Process Recharge
                showLoading('Verifying Payment & Processing Recharge...');
                
                $.ajax({
                    url: "{{ route('user.service.recharge.process') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { order_id: orderId },
                    success: function(response) {
                        hideLoading();
                        if (response.status === 'success') {
                            showToast('Recharge Successful!', 'success');
                            setTimeout(() => {
                                // Redirect to history or dashboard
                                window.location.href = "{{ route('user.dashboard') }}"; // Or history page
                            }, 2000);
                        } else {
                            showToast(response.message || 'Recharge Failed', 'danger');
                            if (response.mobile) {
                                setTimeout(() => {
                                    if ("{{ $serviceType }}" === 'dth') {
                                        window.location.href = "{{ route('user.service.recharge.dth') }}";
                                    } else {
                                        window.location.href = "{{ route('user.service.recharge.mobile') }}?mobile=" + response.mobile;
                                    }
                                }, 2000);
                            }
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        let msg = 'An error occurred';
                        let mobile = null;
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            if (xhr.responseJSON.mobile) {
                                mobile = xhr.responseJSON.mobile;
                            }
                        }
                        showToast(msg, 'danger');
                        if (mobile) {
                            setTimeout(() => {
                                if ("{{ $serviceType }}" === 'dth') {
                                    window.location.href = "{{ route('user.service.recharge.dth') }}";
                                } else {
                                    window.location.href = "{{ route('user.service.recharge.mobile') }}?mobile=" + mobile;
                                }
                            }, 2000);
                        }
                    }
                });
            }

            $('#payButton').on('click', function(e) {
                e.preventDefault();
                
                const amount = "{{ request('amount') }}";
                const mobile = "{{ request('mobile') }}";
                const operator = "{{ request('operator') }}";
                const circle = "{{ request('circle') }}";
                const planDesc = "{{ request('plan_desc') ?? request('desc') }}";
                const service = "{{ request('service', 'mobile') }}";

                if (!amount || !mobile || !operator) {
                    showToast('Missing required details', 'danger');
                    return;
                }

                showLoading('Initiating Payment...');

                $.ajax({
                    url: "{{ route('user.service.recharge.create-order') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        amount: amount,
                        mobile: mobile,
                        operator: operator,
                        circle: circle,
                        plan_desc: planDesc,
                        service: service
                    },
                    success: function(response) {
                        hideLoading();
                        if (response.status === 'success' && response.payment_session_id) {
                            // Initiate Cashfree Checkout
                            cashfree.checkout({
                                paymentSessionId: response.payment_session_id,
                                redirectTarget: "_self" // Redirects to return_url after payment
                            });
                        } else {
                            showToast(response.message || 'Payment initiation failed', 'danger');
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showToast('Failed to initiate payment', 'danger');
                    }
                });
            });
        });
    </script>
</body>
</html>