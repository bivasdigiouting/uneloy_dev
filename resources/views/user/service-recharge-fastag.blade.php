<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>FASTag Recharge - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* Common Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        
        .mobile-wrapper {
            width: 100%;
            background-color: #ffffff;
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Header Gradient */
        .header-gradient {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            padding: 20px 20px 40px 20px;
            color: white;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            position: relative;
        }

        .header-nav {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 10;
        }

        .back-btn {
            color: white;
            font-size: 22px;
            margin-right: 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            flex-grow: 1;
        }

        .page-subtitle {
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 25px;
            opacity: 0.9;
            padding-left: 5px;
        }

        /* Input Card */
        .input-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            height: 60px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .input-card:focus-within {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .input-icon {
            font-size: 20px;
            margin-right: 15px;
            color: white;
        }

        .form-control-transparent {
            background: transparent;
            border: none;
            color: white;
            flex: 1;
            width: auto;
            font-size: 16px;
            font-weight: 500;
            outline: none;
            padding: 0;
        }

        .form-control-transparent::placeholder {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }
        
        .form-control-transparent:focus {
            background: transparent;
            border: none;
            box-shadow: none;
            color: white;
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
            }
        }
        
        /* Fastag Specific */
        .fastag-card {
            margin: 20px;
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .amount-chip {
            border: 1px solid #eee;
            border-radius: 50px;
            padding: 8px 16px;
            font-weight: 500;
            color: #555;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .amount-chip:hover, .amount-chip.active {
            background: #e8f5e9;
            color: #11998e;
            border-color: #11998e;
        }

        .btn-proceed {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 50px;
            padding: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 10px 20px rgba(17, 153, 142, 0.3);
        }
    </style>
</head>
<body>

    <!-- Desktop Wrapper -->
    <div class="desktop-wrapper d-none d-lg-flex bg-light min-vh-100" style="width: 100%; margin-left: 294px;">
        @include('user.partials.desktop-sidebar')
        <div class="flex-grow-1 d-flex flex-column ms-auto" style="margin-left: 280px;">
             @section('page_title', 'FASTag Recharge')
             @include('user.partials.desktop-header')
             <main class="p-4">
                 <div class="container-fluid">
                     <div class="row justify-content-center">
                         <div class="col-lg-6">
                              <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                  <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;">
                                      <h5 class="fw-bold mb-1 text-white">FASTag Recharge</h5>
                                      <p class="mb-0 opacity-75 text-white">Recharge your FASTag instantly</p>
                                  </div>
                                  <div class="card-body p-4">
                                      <form id="desktop_fastag_form">
                                          <div class="mb-4">
                                              <label class="form-label fw-semibold">Operator (Bank)</label>
                                              <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-3" id="desktop_operator_trigger" style="cursor: pointer;">
                                                  <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-university text-muted"></i></span>
                                                  <div class="form-control border-0 shadow-none d-flex align-items-center bg-white" id="desktop_selected_operator_display">
                                                      Select Bank
                                                  </div>
                                                  <input type="hidden" class="fastag-operator" name="operator">
                                                  <span class="input-group-text bg-white border-0 pe-3"><i class="fas fa-chevron-down text-muted"></i></span>
                                              </div>

                                              <label class="form-label fw-semibold">Vehicle Number</label>
                                              <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-3">
                                                  <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-car text-muted"></i></span>
                                                  <input type="text" class="form-control border-0 shadow-none vehicle-input" placeholder="e.g. MH12AB1234" style="text-transform: uppercase;">
                                              </div>
                                              
                                              <label class="form-label fw-semibold">Amount</label>
                                              <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-3">
                                                  <span class="input-group-text bg-white border-0 ps-3 fw-bold">₹</span>
                                                  <input type="number" class="form-control border-0 shadow-none amount-input" placeholder="Enter Amount">
                                              </div>
                                              
                                              <div class="d-flex gap-2 mb-4">
                                                  <div class="amount-chip" onclick="setAmount(500)">+ ₹500</div>
                                                  <div class="amount-chip" onclick="setAmount(1000)">+ ₹1000</div>
                                                  <div class="amount-chip" onclick="setAmount(2000)">+ ₹2000</div>
                                              </div>
                                              
                                              <button type="button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm btn-proceed-action" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border:none;">
                                                  PROCEED TO PAY
                                              </button>
                                          </div>
                                      </form>
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
        <!-- Header Section -->
        <div class="header-gradient">
            <div class="header-nav">
                <a href="{{ route('user.dashboard') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="page-title">FASTag Recharge</div>
            </div>
            
            <div class="page-subtitle">
                Recharge your FASTag instantly.
            </div>

            <!-- Operator Select -->
            <div class="input-card position-relative" id="operator_trigger" style="cursor: pointer;">
                <div class="input-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="form-control-transparent d-flex align-items-center" id="selected_operator_display">
                    Select Bank
                </div>
                <input type="hidden" class="fastag-operator">
                <div class="text-white">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            
            <!-- Vehicle Number Input -->
            <div class="input-card position-relative">
                <div class="input-icon">
                    <i class="fas fa-car"></i>
                </div>
                <input type="text" class="form-control-transparent vehicle-input" placeholder="Vehicle Number (e.g. MH12AB1234)" style="text-transform: uppercase;">
            </div>
        </div>

        <!-- Body Content -->
        <div class="content-body" style="flex: 1; background: #f8f9fa; padding-bottom: 80px;">
            
            <div class="fastag-card">
                <label class="form-label fw-semibold text-secondary mb-3">Recharge Amount</label>
                <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-4 bg-light">
                    <span class="input-group-text border-0 bg-transparent ps-3 fw-bold fs-4 text-dark">₹</span>
                    <input type="number" class="form-control border-0 bg-transparent shadow-none fs-4 fw-bold amount-input" placeholder="0">
                </div>
                
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <div class="amount-chip" onclick="setAmount(500)">₹500</div>
                    <div class="amount-chip" onclick="setAmount(1000)">₹1000</div>
                    <div class="amount-chip" onclick="setAmount(1500)">₹1500</div>
                    <div class="amount-chip" onclick="setAmount(2000)">₹2000</div>
                    <div class="amount-chip" onclick="setAmount(3000)">₹3000</div>
                    <div class="amount-chip" onclick="setAmount(5000)">₹5000</div>
                </div>
            </div>
            
            <div class="px-4">
                <button type="button" class="btn btn-primary btn-proceed btn-proceed-action text-white">
                    PROCEED TO PAY <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
            
            <div class="text-center mt-4 text-muted small px-4">
                <i class="fas fa-shield-alt me-1"></i> Secure Payment by BBPS
            </div>
        </div>
    </div>

    <!-- Operator Selection Modal -->
    <div class="modal fade" id="operatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Select Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 mt-2">
                    <div class="p-3">
                        <input type="text" class="form-control rounded-pill" id="operatorSearch" placeholder="Search Bank...">
                    </div>
                    <div class="list-group list-group-flush" id="operatorList">
                        @if(isset($operators) && count($operators) > 0)
                            @foreach($operators as $op)
                                <button type="button" class="list-group-item list-group-item-action d-flex align-items-center p-3 border-0 operator-item" 
                                    data-code="{{ $op->operator_code }}" 
                                    data-name="{{ $op->operator_name }}"
                                    data-logo="{{ $op->operator_logo ? asset($op->operator_logo) : '' }}">
                                    @if($op->operator_logo)
                                        <img src="{{ asset($op->operator_logo) }}" alt="{{ $op->operator_name }}" class="me-3 rounded-circle shadow-sm" width="45" height="45" style="object-fit: cover;">
                                    @else
                                        <div class="me-3 rounded-circle bg-light d-flex align-items-center justify-content-center text-success fw-bold" style="width: 45px; height: 45px; font-size: 18px;">
                                            {{ substr($op->operator_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="fw-medium text-dark fs-5">{{ $op->operator_name }}</span>
                                </button>
                            @endforeach
                        @else
                            <div class="text-center p-4 text-muted">No banks found</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Global function for amount chips
        function setAmount(amount) {
            $('.amount-input').val(amount);
            $('.amount-chip').removeClass('active');
            // Highlight the clicked chip (simple approach)
            $(event.target).addClass('active');
        }

        $(document).ready(function() {
            // Sync Inputs
            $('.vehicle-input').on('input', function() {
                const val = $(this).val().toUpperCase();
                $('.vehicle-input').val(val);
            });
            
            $('.amount-input').on('input', function() {
                const val = $(this).val();
                $('.amount-input').val(val);
            });

            // Operator Search
            $('#operatorSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#operatorList button").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Operator Selection Logic
            $('#operator_trigger, #desktop_operator_trigger').on('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('operatorModal'));
                modal.show();
            });

            $('.operator-item').on('click', function() {
                const code = $(this).data('code');
                const name = $(this).data('name');
                const logo = $(this).data('logo');

                // Update hidden inputs
                $('.fastag-operator').val(code).trigger('change');

                // Update Display
                $('#selected_operator_display, #desktop_selected_operator_display').text(name);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('operatorModal'));
                modal.hide();
            });

            // Proceed Button
            $('.btn-proceed-action').click(function() {
                const operator = $('.fastag-operator').first().val();
                const vehicle = $('.vehicle-input').first().val();
                const amount = $('.amount-input').first().val();
                
                if (!operator) {
                    alert('Please select a Bank');
                    return;
                }
                
                if (!vehicle) {
                    alert('Please enter Vehicle Number');
                    return;
                }
                
                if (!amount || amount <= 0) {
                    alert('Please enter a valid amount');
                    return;
                }
                
                // Construct query parameters
                const params = new URLSearchParams({
                    mobile: vehicle, // Using 'mobile' param for identifier consistency in confirm page
                    operator: operator,
                    amount: amount,
                    service: 'fastag'
                });
                
                window.location.href = "{{ route('user.service.recharge.confirm') }}?" + params.toString();
            });
        });
    </script>
    
    @include('user.partials.theme-script')
</body>
</html>