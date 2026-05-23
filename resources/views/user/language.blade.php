<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Change Language - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Common Styles */
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
            margin-right: 24px; /* Balance the back button spacing */
        }

        /* Language List */
        .language-list {
            padding: 20px;
        }

        .language-item {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            cursor: pointer;
            transition: all 0.2s;
        }

        .language-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .language-name {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--pink-highlight);
            border-color: var(--pink-highlight);
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
                background-color: #f8f9fa; /* Keep light bg inside wrapper */
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Wrapper -->
    <div class="mobile-wrapper">
        
        <!-- Header -->
        <div class="profile-header">
            <a href="{{ route('user.profile') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-title">{{ __('messages.language') }}</div>
        </div>

        <!-- Language List Form -->
        <form action="{{ route('user.language.update') }}" method="POST" id="languageForm">
            @csrf
            <div class="language-list">
                @php
                    $currentLocale = Session::get('locale', 'en');
                @endphp
                
                @foreach($languages as $code => $name)
                <label class="language-item" for="lang_{{ $code }}">
                    <span class="language-name">{{ $name }}</span>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="locale" id="lang_{{ $code }}" value="{{ $code }}" {{ $currentLocale == $code ? 'checked' : '' }} onchange="this.form.submit()">
                    </div>
                </label>
                @endforeach
            </div>
        </form>

    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                var toast = new bootstrap.Toast(document.getElementById('successToast'));
                toast.show();
            @endif
        });
    </script>
    @include('user.partials.theme-script')
</body>
</html>