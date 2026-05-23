<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benefit - E-Card Seva Self Req. Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: var(--bg-light); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--text-dark); }
        /* .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); } */
        .page-title { font-weight: 700; color: var(--text-dark); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); background-color: var(--card-bg); color: var(--text-dark); }
        .table { color: var(--text-dark); }
    </style>
</head>
<body>
    @include('user.partials.navbar')

    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="page-title">E-Card Seva Self Req. Report</h2>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>Service</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach(($requests ?? []) as $row)
                                <tr>
                                    <td>{{ $row['service'] }}</td>
                                    <td>{{ $row['date'] }}</td>
                                    <td><span class="badge {{ $row['status'] === 'Completed' ? 'bg-success' : 'bg-warning' }}">{{ $row['status'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>