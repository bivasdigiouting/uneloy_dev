<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #D53F8C 0%, #805AD5 100%);
        --header-gradient: linear-gradient(to right, #c42086, #b02995, #9b30a2, #8435ad, #6a39b6);
        --card-bg: #ffffff;
        --text-dark: #333333;
        --text-muted: #718096;
        --muted-text: var(--text-muted);
        --bg-light: #f3f4f6;
        --pink-highlight: #d53f8c;
        --border-color: #f0f0f0;
    }

    [data-theme="dark"] {
        --card-bg: #2D3748;
        --text-dark: #EDF2F7;
        --text-muted: #A0AEC0;
        --muted-text: var(--text-muted);
        --bg-light: #1A202C;
        --border-color: #4A5568;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        background-color: var(--bg-light);
        color: var(--text-dark);
    }

    .content-card, .section-card, .profile-card, .card {
        background: var(--card-bg) !important;
        color: var(--text-dark);
    }

    .mobile-wrapper, .profile-header {
        background-color: var(--bg-light) !important;
    }

    .page-title, .back-btn, .section-title, h1, h2, h3, h4, h5, h6, .fw-bold {
        color: var(--text-dark) !important;
    }

    .text-muted, .small, small, .label {
        color: var(--text-muted) !important;
    }

    /* Form Elements in Dark Mode */
    [data-theme="dark"] .form-control {
        background-color: #2D3748;
        border-color: #4A5568;
        color: #EDF2F7;
    }
    
    [data-theme="dark"] .form-control:focus {
        background-color: #2D3748;
        color: #EDF2F7;
        border-color: #D53F8C;
    }

    /* Icon Circles */
    [data-theme="dark"] .icon-circle {
        background-color: #1A202C !important;
    }

    /* Links */
    a {
        color: var(--pink-highlight);
    }
    
    a.text-dark {
        color: var(--text-dark) !important;
    }

    /* ==========================================
       PREMIUM COLORFUL DESKTOP ONLY DESIGN (>= 992px)
       ========================================== */
    @media (min-width: 992px) {
        :root {
            --bg-light: #060913;
            --card-bg: rgba(15, 23, 42, 0.65);
            --text-dark: #f1f5f9;
            --text-muted: #94a3b8;
            --muted-text: var(--text-muted);
            --pink-highlight: #ff007a;
            --border-color: rgba(255, 255, 255, 0.08);
            
            --primary-gradient: linear-gradient(135deg, #ff007a 0%, #7928ca 100%);
            --accent-gradient: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            --success-gradient: linear-gradient(135deg, #00ff87 0%, #60efff 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        }

        body {
            display: block !important;
            background: radial-gradient(circle at 15% 15%, rgba(255, 0, 122, 0.08) 0%, rgba(0,0,0,0) 40%), 
                        radial-gradient(circle at 85% 30%, rgba(121, 40, 202, 0.08) 0%, rgba(0,0,0,0) 40%), 
                        linear-gradient(135deg, #060913 0%, #0d1222 100%) !important;
            color: #f1f5f9 !important;
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
        }

        /* Layout Margins & Alignment Normalization */
        .desktop-wrapper {
            margin-left: 0 !important;
            background: transparent !important;
        }

        .desktop-wrapper > div.flex-grow-1,
        .desktop-layout-wrapper {
            margin-left: 280px !important;
            background: transparent !important;
            padding-bottom: 40px;
        }

        /* Modern Sidebar Redesign */
        .desktop-sidebar {
            background: linear-gradient(180deg, #060913 0%, #0d1220 100%) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3) !important;
            width: 280px !important;
        }

        .desktop-sidebar .sidebar-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            background: rgba(255, 255, 255, 0.01) !important;
        }

        .desktop-sidebar .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        .desktop-sidebar .sidebar-footer .bg-light {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .desktop-sidebar .nav-link {
            color: #94a3b8 !important;
            margin: 4px 12px !important;
            padding: 10px 16px !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-left: 3px solid transparent !important;
            border-radius: 8px !important;
        }

        .desktop-sidebar .nav-link i {
            color: #94a3b8 !important;
            transition: color 0.25s !important;
        }

        .desktop-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.04) !important;
            color: #ffffff !important;
            transform: translateX(5px) !important;
        }

        .desktop-sidebar .nav-link:hover i {
            color: #ffffff !important;
        }

        .desktop-sidebar .nav-link.active {
            background: linear-gradient(135deg, rgba(255, 0, 122, 0.15) 0%, rgba(121, 40, 202, 0.15) 100%) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            border-left: 3px solid #ff007a !important;
            box-shadow: 0 4px 15px rgba(255, 0, 122, 0.08) !important;
        }

        .desktop-sidebar .nav-link.active i {
            color: #ff007a !important;
        }

        .desktop-sidebar .text-muted {
            color: rgba(148, 163, 184, 0.5) !important;
        }

        /* Glassmorphic Header Redesign */
        .desktop-header {
            background: rgba(6, 9, 19, 0.75) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .desktop-header .text-dark {
            color: #f8fafc !important;
        }

        .desktop-header .input-group-text,
        .desktop-header .form-control {
            background-color: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: #f8fafc !important;
        }

        .desktop-header .form-control::placeholder {
            color: rgba(148, 163, 184, 0.5) !important;
        }

        .desktop-header .form-control:focus {
            border-color: #ff007a !important;
            box-shadow: none !important;
        }

        .desktop-header .btn-light,
        .desktop-header .btn-white {
            background-color: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: #f8fafc !important;
        }

        .desktop-header .btn-light:hover,
        .desktop-header .btn-white:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #ff007a !important;
        }

        .desktop-header .dropdown-menu {
            background-color: #0b0f19 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
        }

        .desktop-header .dropdown-item {
            color: #f1f5f9 !important;
        }

        .desktop-header .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ff007a !important;
        }

        .desktop-header .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        /* Glassmorphic Cards & Elevate Animations */
        .card {
            background: rgba(15, 23, 42, 0.65) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s !important;
        }

        .card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 20px 40px rgba(255, 0, 122, 0.12) !important;
            border-color: rgba(255, 0, 122, 0.25) !important;
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: #f8fafc !important;
        }

        .card-footer {
            background: transparent !important;
            border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        /* Background & Border helper overrides */
        .bg-light {
            background-color: rgba(255, 255, 255, 0.03) !important;
            color: #f1f5f9 !important;
        }

        .bg-white {
            background-color: rgba(15, 23, 42, 0.8) !important;
        }

        .border, .border-bottom, .border-start, .border-end, .border-top {
            border-color: rgba(255, 255, 255, 0.06) !important;
        }

        /* Text color overrides */
        .text-dark, h1, h2, h3, h4, h5, h6, .fw-bold {
            color: #f8fafc !important;
        }

        .text-muted, .small, small, .label {
            color: #94a3b8 !important;
        }

        a {
            color: #ff007a;
            transition: color 0.2s;
        }

        a:hover {
            color: #7928ca;
        }

        /* Form elements */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: #f8fafc !important;
            border-radius: 8px !important;
        }

        .form-control::placeholder {
            color: rgba(148, 163, 184, 0.4) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #ff007a !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 0, 122, 0.15) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .form-select option {
            background-color: #0c101d !important;
            color: #f1f5f9 !important;
        }

        /* Tables styling */
        .table {
            color: #f1f5f9 !important;
        }

        .table th {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #94a3b8 !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        /* Button Enhancements */
        .btn-primary {
            background: linear-gradient(135deg, #ff007a 0%, #7928ca 100%) !important;
            border: none !important;
            box-shadow: 0 4px 15px rgba(255, 0, 122, 0.2) !important;
            color: #ffffff !important;
            transition: all 0.2s !important;
        }

        .btn-primary:hover {
            opacity: 0.95 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(255, 0, 122, 0.3) !important;
        }

        .btn-outline-primary {
            border-color: #ff007a !important;
            color: #ff007a !important;
        }

        .btn-outline-primary:hover {
            background-color: #ff007a !important;
            color: #ffffff !important;
        }

        .btn-outline-secondary {
            border-color: rgba(255, 255, 255, 0.2) !important;
            color: #f1f5f9 !important;
        }

        .btn-outline-secondary:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Badge and subtle pill overrides */
        .bg-primary-subtle {
            background-color: rgba(255, 0, 122, 0.12) !important;
            color: #ff007a !important;
        }

        .bg-warning-subtle {
            background-color: rgba(245, 158, 11, 0.12) !important;
            color: #f59e0b !important;
        }

        .bg-success-subtle {
            background-color: rgba(16, 185, 129, 0.12) !important;
            color: #10b981 !important;
        }

        .bg-info-subtle {
            background-color: rgba(6, 182, 212, 0.12) !important;
            color: #06b6d4 !important;
        }

        .border-success-subtle {
            border-color: rgba(16, 185, 129, 0.3) !important;
        }

        .border-primary-subtle {
            border-color: rgba(255, 0, 122, 0.3) !important;
        }

        /* Page specific quick overrides */
        .desktop-wrapper .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .desktop-wrapper .nav-pills .nav-link {
            border-radius: 20px !important;
            padding: 8px 16px !important;
            color: #94a3b8 !important;
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.05) !important;
            margin-right: 8px !important;
        }

        .desktop-wrapper .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #ff007a 0%, #7928ca 100%) !important;
            border-color: transparent !important;
            color: white !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #060913;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 0, 122, 0.3);
        }
    }
</style>

