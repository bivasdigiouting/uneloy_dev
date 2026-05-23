<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload KYC Document - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: var(--bg-light); color: var(--text-dark); }
        /* .navbar is handled by partials/theme-style */
        .card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: none; background-color: var(--card-bg); color: var(--text-dark); }
        .upload-box { border: 2px dashed var(--muted-text); padding: 20px; border-radius: 12px; background: var(--bg-light); color: var(--text-dark); }
        .btn-primary-custom { background: var(--primary-gradient); border: none; color: white; }
        .btn-primary-custom:hover { opacity: 0.9; color: white; }
    </style>
</head>
<body>
    @include('user.partials.navbar')

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-3"><i class="fas fa-id-card me-2"></i>Upload KYC Document</h4>
                        @if($errors->any())
                            <div class="alert alert-danger">Please correct the errors below.</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('user.kyc.upload') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Document Type</label>
                                    <select name="document_type" class="form-select" required>
                                        <option value="">Select</option>
                                        <option value="aadhaar">Aadhaar</option>
                                        <option value="pan">PAN</option>
                                        <option value="passport">Passport</option>
                                        <option value="driving_license">Driving License</option>
                                    </select>
                                    @error('document_type')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Document Number</label>
                                    <input type="text" name="document_number" class="form-control" placeholder="Enter number" required>
                                    @error('document_number')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Upload Document (PDF/JPG/PNG)</label>
                                    <div class="upload-box text-center">
                                        <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <small class="text-muted">Max size 2MB</small>
                                    </div>
                                    @error('document_file')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary-custom">Submit KYC</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('user.partials.theme-script')
</body>
</html>