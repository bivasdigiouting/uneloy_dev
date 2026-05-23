<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benefit - Book Camp</title>
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
        .card-header { background-color: transparent; border-bottom: 1px solid var(--muted-text); color: var(--text-dark); }
    </style>
</head>
<body>
    @include('user.partials.navbar')

    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="page-title">Book Camp</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Submit Booking Request</h5></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.benefit.bookcamp.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Camp Name</label>
                                <input type="text" name="camp_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Camp Date</label>
                                <input type="date" name="camp_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" required>
                            </div>
                            <button class="btn btn-primary-custom" type="submit"><i class="fas fa-paper-plane me-1"></i>Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Upcoming Camps</h5></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead><tr><th>Camp</th><th>Date</th><th>Location</th></tr></thead>
                                <tbody>
                                    @foreach(($camps ?? []) as $camp)
                                        <tr>
                                            <td>{{ $camp['name'] }}</td>
                                            <td>{{ $camp['date'] }}</td>
                                            <td>{{ $camp['location'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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