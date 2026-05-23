<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Help &amp; Support</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/custom_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/design_css/responsive_styles.css') }}">
</head>
<body>
    <header class="py-3 border-bottom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="text-decoration-none">
                @if($settings && $settings->logo)
                    <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" height="30" class="me-2">
                @else
                    <strong>U Only</strong>
                @endif
            </a>
            <nav>
                <a href="{{ url('/') }}" class="me-3">Home</a>
                <span class="fw-bold">Help &amp; Support</span>
            </nav>
        </div>
    </header>

    <main class="container my-4">
        <h1 class="mb-3">Help &amp; Support</h1>
        <p class="text-muted">Find helpline contacts by location or search by name/number.</p>

        <form method="get" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="state_id" class="form-label">State</label>
                <select id="state_id" name="state_id" class="form-select">
                    <option value="">All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="q" class="form-label">Search</label>
                <input type="text" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Name or number">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('help-support.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="row">
            @forelse($helplines as $helpline)
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $helpline->name }}</h5>
                            <p class="mb-2"><strong>Number:</strong> {{ $helpline->number }}</p>
                            <p class="mb-0 text-muted">
                                {{ optional($helpline->city)->name }}
                                @if($helpline->city && $helpline->district) , @endif
                                {{ optional($helpline->district)->name }}
                                @if(($helpline->city || $helpline->district) && $helpline->state) , @endif
                                {{ optional($helpline->state)->name }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No helpline records found.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $helplines->links() }}
        </div>
    </main>

    <script src="{{ asset('frontend-assets/design_js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/design_js/bootstrap.min.js') }}"></script>
</body>
</html>