<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ ucfirst($category) }} Bill Payment - UOnly</title>
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
            @if($category == 'electricity')
                background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
            @elseif($category == 'water')
                background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            @elseif($category == 'gas')
                background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%);
            @elseif($category == 'broadband')
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            @else
                background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            @endif
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
            text-transform: capitalize;
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
        
        /* BBPS Specific */
        .bbps-card {
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
            @if($category == 'electricity')
                background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
            @elseif($category == 'water')
                background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            @elseif($category == 'gas')
                background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%);
            @elseif($category == 'broadband')
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            @else
                background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            @endif
            border: none;
            border-radius: 50px;
            padding: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0, 0.1);
        }
    </style>
</head>
<body>

    <!-- Desktop Wrapper -->
    <div class="desktop-wrapper d-none d-lg-flex bg-light min-vh-100" style="width: 100%; margin-left: 294px;">
        @include('user.partials.desktop-sidebar')
        <div class="flex-grow-1 d-flex flex-column ms-auto" style="margin-left: 280px;">
             @section('page_title', ucfirst($category) . ' Payment')
             @include('user.partials.desktop-header')
             <main class="p-4">
                 <div class="container-fluid">
                     <div class="row justify-content-center">
                         <div class="col-lg-6">
                              <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                  <div class="card-header bg-primary text-white p-4 border-0" 
                                       style="@if($category == 'electricity') background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
                                              @elseif($category == 'water') background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
                                              @elseif($category == 'gas') background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%);
                                              @elseif($category == 'broadband') background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                                              @else background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%); @endif">
                                      <h5 class="fw-bold mb-1 text-white">{{ ucfirst($category) }} Payment</h5>
                                      <p class="mb-0 opacity-75 text-white">Pay your {{ $category }} bill instantly</p>
                                  </div>
                                  <div class="card-body p-4">
                                      <form id="desktop_bbps_form">
                                          <div class="mb-4">
                                              <label class="form-label fw-semibold">Select Operator</label>
                                              <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-3" id="desktop_operator_trigger" style="cursor: pointer;">
                                                  <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-university text-muted"></i></span>
                                                  <div class="form-control border-0 shadow-none d-flex align-items-center bg-white" id="desktop_selected_operator_display">
                                                      Select Operator
                                                  </div>
                                                  <input type="hidden" class="bbps-operator" name="operator">
                                                  <span class="input-group-text bg-white border-0 pe-3"><i class="fas fa-chevron-down text-muted"></i></span>
                                              </div>

                                              <label class="form-label fw-semibold">Consumer Number / Account ID</label>
                                              <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-3">
                                                  <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-hashtag text-muted"></i></span>
                                                  <input type="text" class="form-control border-0 shadow-none consumer-input" placeholder="e.g. 1234567890">
                                              </div>
                                              
                                              <label class="form-label fw-semibold">Amount</label>
                                              <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-3">
                                                  <span class="input-group-text bg-white border-0 ps-3 fw-bold">₹</span>
                                                  <input type="number" class="form-control border-0 shadow-none amount-input" placeholder="Enter Amount">
                                              </div>
                                              
                                              <button type="button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm btn-proceed-action" 
                                                      style="border:none; @if($category == 'electricity') background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
                                                             @elseif($category == 'water') background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
                                                             @elseif($category == 'gas') background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%);
                                                             @elseif($category == 'broadband') background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                                                             @else background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%); @endif">
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
                <a href="{{ route('service.recharge.utility.link') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="page-title">{{ ucfirst($category) }} Payment</div>
            </div>
            
            <div class="page-subtitle">
                Pay your {{ $category }} bill instantly.
            </div>

            <!-- Operator Select -->
            <div class="input-card position-relative" id="operator_trigger" style="cursor: pointer;">
                <div class="input-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="form-control-transparent d-flex align-items-center" id="selected_operator_display">
                    Select Operator
                </div>
                <input type="hidden" class="bbps-operator">
                <div class="text-white">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            
            <!-- Consumer Number Input -->
            <div class="input-card position-relative">
                <div class="input-icon">
                    <i class="fas fa-hashtag"></i>
                </div>
                <input type="text" class="form-control-transparent consumer-input" placeholder="Consumer No / Account ID">
            </div>
        </div>

        <!-- Body Content -->
        <div class="content-body" style="flex: 1; background: #f8f9fa; padding-bottom: 80px;">
            
            <div class="bbps-card">
                <label class="form-label fw-semibold text-secondary mb-3">Bill Amount</label>
                <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-4 bg-light">
                    <span class="input-group-text border-0 bg-transparent ps-3 fw-bold fs-4 text-dark">₹</span>
                    <input type="number" class="form-control border-0 bg-transparent shadow-none fs-4 fw-bold amount-input" placeholder="0">
                </div>
                
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <div class="amount-chip" onclick="setAmount(500)">₹500</div>
                    <div class="amount-chip" onclick="setAmount(1000)">₹1000</div>
                    <div class="amount-chip" onclick="setAmount(2000)">₹2000</div>
                </div>
            </div>
            
            <div class="px-4">
                <button type="button" class="btn btn-primary btn-proceed btn-proceed-action text-white">
                    PROCEED TO PAY
                </button>
            </div>
            
            <div class="px-4 mt-4 text-center text-muted small">
                <i class="fas fa-shield-alt me-1"></i> Secure Payments by BBPS
            </div>
        </div>
    </div>

    <!-- Hidden Form for Submission -->
    <form id="bbpsForm" action="{{ route('service.recharge.create-order') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="service" value="bbps">
        <input type="hidden" name="category" value="{{ $category }}">
        <input type="hidden" name="operator" id="form_operator">
        <input type="hidden" name="mobile" id="form_consumer_no"> <!-- Using mobile field for Consumer No -->
        <input type="hidden" name="amount" id="form_amount">
        <input type="hidden" name="plan_desc" id="form_desc" value="{{ ucfirst($category) }} Bill Payment">
    </form>

    <!-- Operator Selection Modal -->
    <div class="modal fade" id="operatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Select Operator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control bg-light border-0" id="operatorSearch" placeholder="Search operator...">
                    </div>
                    
                    <div class="list-group list-group-flush" id="operatorList">
                        @foreach($operators as $op)
                            <button type="button" class="list-group-item list-group-item-action border-0 py-3 operator-item" 
                                    data-code="{{ $op->operator_code }}" 
                                    data-name="{{ $op->operator_name }}">
                                <div class="d-flex align-items-center">
                                    <div class="avatar rounded-circle bg-light me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($op->operator_logo)
                                            <img src="{{ asset('storage/'.$op->operator_logo) }}" alt="" width="24">
                                        @else
                                            <i class="fas fa-university text-secondary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $op->operator_name }}</div>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function setAmount(amount) {
            $('.amount-input').val(amount);
        }

        $(document).ready(function() {
            // Operator Modal
            $('#operator_trigger, #desktop_operator_trigger').on('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('operatorModal'));
                modal.show();
            });

            // Search Operator
            $('#operatorSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#operatorList button").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Select Operator
            $('.operator-item').on('click', function() {
                const code = $(this).data('code');
                const name = $(this).data('name');

                // Update hidden inputs
                $('.bbps-operator').val(code).trigger('change');

                // Update Display
                $('#selected_operator_display, #desktop_selected_operator_display').text(name);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('operatorModal'));
                modal.hide();
            });

            // Proceed Button
            $('.btn-proceed-action').on('click', function() {
                const operator = $('.bbps-operator').val();
                // Determine which input is visible (mobile or desktop)
                let consumerNo = '';
                let amount = '';
                
                if ($('.mobile-wrapper').is(':visible')) {
                    consumerNo = $('.mobile-wrapper .consumer-input').val();
                    amount = $('.mobile-wrapper .amount-input').val();
                } else {
                    consumerNo = $('#desktop_bbps_form .consumer-input').val();
                    amount = $('#desktop_bbps_form .amount-input').val();
                }

                if (!operator) {
                    Swal.fire('Error', 'Please select an operator', 'error');
                    return;
                }
                if (!consumerNo) {
                    Swal.fire('Error', 'Please enter Consumer Number / Account ID', 'error');
                    return;
                }
                if (!amount || amount <= 0) {
                    Swal.fire('Error', 'Please enter a valid amount', 'error');
                    return;
                }

                // Fill hidden form
                $('#form_operator').val(operator);
                $('#form_consumer_no').val(consumerNo);
                $('#form_amount').val(amount);

                // Submit
                $('#bbpsForm').submit();
            });
        });
    </script>
</body>
</html>
