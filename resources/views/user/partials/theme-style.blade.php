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
</style>

