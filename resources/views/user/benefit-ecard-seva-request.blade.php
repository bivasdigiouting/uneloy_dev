<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benefit - E-Card Seva Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: var(--bg-light); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--text-dark); }
        /* .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); } */
        .page-title { font-weight: 700; color: var(--text-dark); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); background-color: var(--card-bg); color: var(--text-dark); }
        .btn-primary-custom { background: var(--primary-gradient); border: none; color: white; }
        .btn-primary-custom:hover { opacity: 0.9; color: white; }
        .form-control { background-color: var(--bg-light); border: 1px solid var(--muted-text); color: var(--text-dark); }
        .form-control:focus { background-color: var(--bg-light); color: var(--text-dark); }
        .form-label { color: var(--text-dark); }
    </style>
</head>
<body>
    @include('user.partials.navbar')

    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="page-title">E-Card Seva Request</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('user.benefit.ecard.seva.request.submit') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Service Type</label>
                            <input type="text" name="service_type" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Beneficiary Name</label>
                            <input type="text" name="beneficiary_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Beneficiary Mobile</label>
                            <input type="text" name="beneficiary_mobile" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary-custom" type="submit"><i class="fas fa-paper-plane me-1"></i>Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>