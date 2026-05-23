<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Set/Change Password - UOnly</title>
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

        /* Section Card */
        .section-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px 20px;
            margin: 20px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid var(--border-color);
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--pink-highlight);
        }

        .btn-save {
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
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
                background-color: #f8f9fa;
            }
        }
    </style>
</head>
<body>
    <div class="mobile-wrapper">
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.security.settings') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
            <div class="page-title">Set/Change Password</div>
            <div style="width: 24px;"></div>
        </div>

        <div class="section-card">
            @if($errors->any())
                <div class="alert alert-danger mb-4" style="border-radius: 10px; font-size: 14px;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Toast Notification -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
                <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('user.password.change') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent ps-0 pe-2" style="color: var(--pink-highlight);">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                    </div>
                    @error('new_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent ps-0 pe-2" style="color: var(--pink-highlight);">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Re-enter new password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-save">Update Password</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                var toastEl = document.getElementById('successToast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            @endif
        });
    </script>
</body>
</html>