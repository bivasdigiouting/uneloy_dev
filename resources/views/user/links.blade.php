<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Useful Links - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: var(--bg-light); color: var(--text-dark); }
        /* .navbar is handled by partials/theme-style */
        .card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: none; background-color: var(--card-bg); color: var(--text-dark); }
        .link-item { display: flex; align-items: center; justify-content: space-between; padding: 12px; border: 1px solid var(--muted-text); border-radius: 10px; background: var(--bg-light); color: var(--text-dark); }
        
        /* Theme Overrides */
        .text-primary { color: var(--pink-highlight) !important; }
        .btn-outline-primary {
            color: var(--pink-highlight);
            border-color: var(--pink-highlight);
        }
        .btn-outline-primary:hover {
            background-color: var(--pink-highlight);
            border-color: var(--pink-highlight);
            color: #fff;
        }
    </style>
</head>
<body>
    @include('user.partials.navbar')
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-3"><i class="fas fa-link me-2"></i>Useful Links</h4>
                        <p class="text-muted">Quick access to important resources.</p>

                        <div class="d-grid gap-3">
                            <div class="link-item">
                                <div><i class="fas fa-book me-2 text-primary"></i>Documentation</div>
                                <a href="#" class="btn btn-outline-primary btn-sm">Open</a>
                            </div>
                            <div class="link-item">
                                <div><i class="fas fa-life-ring me-2 text-success"></i>Support Center</div>
                                <a href="#" class="btn btn-outline-success btn-sm">Open</a>
                            </div>
                            <div class="link-item">
                                <div><i class="fas fa-comments me-2 text-warning"></i>Community Forum</div>
                                <a href="#" class="btn btn-outline-warning btn-sm">Open</a>
                            </div>
                            <div class="link-item">
                                <div><i class="fas fa-shield-alt me-2 text-danger"></i>Security & Privacy</div>
                                <a href="#" class="btn btn-outline-danger btn-sm">Open</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>