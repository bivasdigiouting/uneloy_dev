<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E Card - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        body { background: var(--bg-light); color: var(--text-dark); }
        /* .navbar is handled by partials/theme-style */
        .card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: none; background-color: var(--card-bg); color: var(--text-dark); }
        .ecard { background: linear-gradient(135deg, #374151 0%, #111827 100%); color: #fff; border-radius: 16px; padding: 24px; }
        .ecard .logo { font-size: 22px; font-weight: 600; }
        .ecard .number { letter-spacing: 2px; font-size: 20px; margin-top: 12px; }
        .ecard .name { font-weight: 500; margin-top: 10px; }
        .ecard .meta { font-size: 12px; color: #e5e7eb; }
    </style>
</head>
<body>
    <!-- Desktop Wrapper -->
    <div class="desktop-wrapper d-none d-lg-flex bg-light min-vh-100" style="width: 100%; margin-left: 294px;">
        @include('user.partials.desktop-sidebar')
        <div class="flex-grow-1 d-flex flex-column ms-auto" style="margin-left: 280px;">
             @section('page_title', 'E-Card')
             @include('user.partials.desktop-header')
             <main class="p-4">
                 <div class="container-fluid">
                     <div class="row justify-content-center">
                         <div class="col-lg-6 col-xl-5">
                             <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                                 <h4 class="mb-4 fw-bold">Your E-Card</h4>
                                 
                                 <div class="ecard text-start mx-auto w-100 shadow-lg" style="max-width: 400px; aspect-ratio: 1.586; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); position: relative; overflow: hidden;">
                                    <!-- Decorative circles -->
                                    <div class="position-absolute rounded-circle" style="width: 200px; height: 200px; background: rgba(255,255,255,0.05); top: -50px; right: -50px;"></div>
                                    <div class="position-absolute rounded-circle" style="width: 150px; height: 150px; background: rgba(255,255,255,0.05); bottom: -30px; left: -30px;"></div>
                                    
                                     <div class="d-flex justify-content-between align-items-center position-relative z-1 mb-4">
                                         <div class="logo fw-bold fs-4 text-white"><i class="fas fa-cube me-2"></i>UOnly</div>
                                         <div class="meta text-white-50 small text-uppercase tracking-wider">Virtual Card</div>
                                     </div>
                                     
                                     <div class="mt-4 mb-4 position-relative z-1">
                                         <div class="number text-white fs-3 font-monospace tracking-widest" style="letter-spacing: 3px;">
                                             {{ $user['ecard_number'] ?? 'XXXX XXXX XXXX 1234' }}
                                         </div>
                                     </div>
                                     
                                     <div class="d-flex justify-content-between align-items-end position-relative z-1 mt-auto">
                                         <div>
                                             <div class="meta text-white-50" style="font-size: 10px;">CARD HOLDER</div>
                                             <div class="name text-white fw-medium">{{ $user['full_name'] ?? 'User Name' }}</div>
                                         </div>
                                         <div class="text-end">
                                             <div class="meta text-white-50" style="font-size: 10px;">EXPIRES</div>
                                             <div class="meta text-white fw-medium">{{ $user['ecard_valid'] ?? '12/25' }}</div>
                                         </div>
                                     </div>
                                 </div>
        
                                 <div class="mt-5 d-flex gap-3 justify-content-center">
                                     <a href="{{ route('user.ecard.details') }}" class="btn btn-primary rounded-pill px-4 py-2">
                                         <i class="fas fa-eye me-2"></i> View Details
                                     </a>
                                     <button class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                         <i class="fas fa-download me-2"></i> Download
                                     </button>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </main>
        </div>
    </div>

    <!-- Mobile Wrapper -->
    <div class="mobile-wrapper d-lg-none">
    @include('user.partials.navbar')
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <h4 class="mb-4">Your E-Card</h4>
                        
                        <div class="ecard text-start mx-auto" style="max-width: 380px;">
                            <div class="d-flex justify-content-between">
                                <div class="logo">UOnly</div>
                                <div class="meta">Virtual Card</div>
                            </div>
                            <div class="number">{{ $user['ecard_number'] ?? 'XXXX-XXXX-XXXX-1234' }}</div>
                            <div class="name">{{ $user['full_name'] ?? 'User Name' }}</div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="meta">Valid: {{ $user['ecard_valid'] ?? now()->addYear()->format('m/Y') }}</div>
                                <div class="meta">CVV: {{ $user['ecard_cvv'] ?? '***' }}</div>
                            </div>
                        </div>

                        <div class="mt-4 d-grid gap-2">
                            <a href="{{ route('user.ecard.details') }}" class="btn btn-outline-primary">View Card Details</a>
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