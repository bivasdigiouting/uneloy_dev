<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>DTH Recharge - UOnly</title>
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
            background: linear-gradient(135deg, #ff4b8b 0%, #a855f7 100%);
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

        /* Select styling override */
        select.form-control-transparent option {
            background-color: #fff;
            color: #333;
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

        /* Plan Search & Filters */
        .plan-search-container {
            padding: 15px 20px 5px;
            background: white;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .search-box {
            position: relative;
            margin-bottom: 15px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border-radius: 25px;
            border: 1px solid #e0e0e0;
            outline: none;
            font-size: 14px;
            color: #333;
        }

        .search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 16px;
        }

        /* Tabs */
        .plan-tabs-container {
            display: flex;
            overflow-x: auto;
            padding: 10px 15px;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #eee;
            scrollbar-width: none; /* Firefox */
        }
        
        .plan-tabs-container::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        .scrollable-plans {
            max-height: calc(100vh - 300px);
            overflow-y: auto;
            padding-bottom: 20px;
        }

        /* Desktop specific adjustments */
        @media (min-width: 992px) {
            .scrollable-plans {
                max-height: 600px;
            }
        }

        .plan-tab {
            padding: 15px 0;
            margin-right: 25px;
            font-weight: 600;
            font-size: 14px;
            color: #666;
            white-space: nowrap;
            cursor: pointer;
            position: relative;
        }

        .plan-tab.active {
            color: #6a11cb;
        }

        .plan-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #6a11cb;
            border-radius: 3px 3px 0 0;
        }

        /* Plan Cards */
        .plan-list {
            padding: 15px 20px;
            background: #f5f5f5;
            min-height: 400px;
        }

        .plan-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            position: relative;
            cursor: pointer;
        }
        
        .plan-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .plan-price {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .plan-meta {
            display: flex;
            gap: 30px;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 2px;
        }

        .meta-value {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .plan-desc {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .plan-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .btn-details {
            color: #512da8;
            font-weight: 600;
            font-size: 14px;
            background: none;
            border: none;
            padding: 0;
        }
        
        /* Desktop Sidebar overrides for this page if needed */
        .desktop-wrapper .card-header {
            border-radius: 1rem 1rem 0 0;
        }
        
        .desktop-wrapper .nav-pills .nav-link {
            border-radius: 20px;
            padding: 8px 16px;
            color: #666;
            background: #f8f9fa;
        }
        .desktop-wrapper .nav-pills .nav-link.active {
            background-color: #6a11cb;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Desktop Wrapper -->
    <div class="desktop-wrapper d-none d-lg-flex bg-light min-vh-100" style="width: 100%; margin-left: 294px;">
        @include('user.partials.desktop-sidebar')
        <div class="flex-grow-1 d-flex flex-column ms-auto" style="margin-left: 280px;">
             @section('page_title', 'DTH Recharge')
             @include('user.partials.desktop-header')
             <main class="p-4">
                 <div class="container-fluid">
                     <div class="row justify-content-center">
                         <div class="col-lg-8 col-xl-7">
                              <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                  <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #ff4b8b 0%, #a855f7 100%) !important;">
                                      <h5 class="fw-bold mb-1 text-white">DTH Recharge</h5>
                                      <p class="mb-0 opacity-75 text-white">Enter subscriber ID to browse plans</p>
                                  </div>
                                  <div class="card-body p-4">
                                      <div class="mb-4">
                                          <label class="form-label fw-semibold">Dth number</label>
                                          <div class="input-group input-group-lg border rounded-3 overflow-hidden mb-3">
                                              <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-satellite-dish text-muted"></i></span>
                                              <input type="text" class="form-control border-0 shadow-none dth-input" placeholder="Enter Dth number" id="desktop_subscriber_id">
                                          </div>

                                          <label class="form-label fw-semibold">Operator</label>
                                          <div class="input-group input-group-lg border rounded-3 overflow-hidden" id="desktop_operator_trigger" style="cursor: pointer;">
                                              <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-tv text-muted"></i></span>
                                              <div class="form-control border-0 shadow-none d-flex align-items-center bg-white" id="desktop_selected_operator_display">
                                                  Select Operator
                                              </div>
                                              <input type="hidden" class="dth-operator">
                                              <span class="input-group-text bg-white border-0 pe-3"><i class="fas fa-chevron-down text-muted"></i></span>
                                          </div>
                                      </div>
                                      
                                      <div class="plan-tabs-container nav nav-pills mb-3 gap-2 overflow-auto flex-nowrap pb-2" style="scrollbar-width: none;">
                                           <!-- Tabs -->
                                      </div>
                                      
                                      <div class="plans-content-container scrollable-plans">
                                           <div class="text-center text-muted py-5">
                                               Select operator to view plans
                                           </div>
                                      </div>
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
                <div class="page-title">DTH Recharge</div>
            </div>
            
            <div class="page-subtitle">
                Manage your TV subscription.
            </div>

            <!-- Subscriber ID Input -->
            <div class="input-card position-relative">
                <div class="input-icon">
                    <i class="fas fa-satellite-dish"></i>
                </div>
                <input type="text" id="subscriber_id" class="form-control-transparent dth-input" placeholder="Dth number">
            </div>

            <!-- Operator Select -->
            <div class="input-card position-relative" id="operator_trigger" style="cursor: pointer;">
                <div class="input-icon">
                    <i class="fas fa-tv"></i>
                </div>
                <div class="form-control-transparent d-flex align-items-center" id="selected_operator_display">
                    Select Operator
                </div>
                <input type="hidden" id="operator_select" class="dth-operator">
                <div class="text-white">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>

        <!-- Body Content -->
        <div class="content-body" style="flex: 1; background: #f8f9fa;">
            
            <!-- Tabs -->
            <div class="plan-tabs plan-tabs-container" id="plan_tabs">
                <!-- Tabs will be injected here -->
                <div class="plan-tab active">Popular Packs</div>
            </div>

            <!-- Plan List -->
            <div class="plan-list" id="plan_list_container">
                <div class="plans-content-container scrollable-plans">
                     <!-- Initial placeholder content -->
                     <div class="text-center text-muted py-5">
                         Select operator to view plans
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operator Selection Modal -->
    <div class="modal fade" id="operatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Select Operator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 mt-2">
                    <div class="list-group list-group-flush">
                        @if(isset($operators) && count($operators) > 0)
                            @foreach($operators as $op)
                                <button type="button" class="list-group-item list-group-item-action d-flex align-items-center p-3 border-0 operator-item" 
                                    data-code="{{ $op->operator_code }}" 
                                    data-name="{{ $op->operator_name }}"
                                    data-logo="{{ $op->operator_logo ? asset($op->operator_logo) : '' }}">
                                    @if($op->operator_logo)
                                        <img src="{{ asset($op->operator_logo) }}" alt="{{ $op->operator_name }}" class="me-3 rounded-circle shadow-sm" width="45" height="45" style="object-fit: cover;">
                                    @else
                                        <div class="me-3 rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 45px; height: 45px; font-size: 18px;">
                                            {{ substr($op->operator_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="fw-medium text-dark fs-5">{{ $op->operator_name }}</span>
                                </button>
                            @endforeach
                        @else
                            <div class="text-center p-4 text-muted">No operators found</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            let currentPlans = {}; // Grouped plans
            let allPlansFlat = []; // Flat list of all plans
            let currentActiveTab = '';
            
            // Sync Inputs
            $('.dth-input').on('input', function() {
                const val = $(this).val();
                $('.dth-input').val(val);
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
                $('.dth-operator').val(code).trigger('change');

                // Update Display
                $('#selected_operator_display, #desktop_selected_operator_display').text(name);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('operatorModal'));
                modal.hide();
            });

            $('.dth-operator').on('change', function() {
                const val = $(this).val();
                // Ensure sync
                $('.dth-operator').val(val);
                
                // Get subscriber ID
                const subscriberId = $('.dth-input').first().val();

                if (val) {
                    if (!subscriberId) {
                        alert('Please enter DTH number first');
                        $('.dth-operator').val('');
                        $('#selected_operator_display, #desktop_selected_operator_display').text('Select Operator');
                        $('.dth-input').focus();
                        return;
                    }
                    fetchDthPlans(val, subscriberId);
                } else {
                    $('.plans-content-container').html('<div class="text-center text-muted py-5">Select operator to view plans</div>');
                    $('.plan-tabs-container').empty();
                }
            });

            function fetchDthPlans(opcode, subscriberId) {
                // Show loading state
                $('.plans-content-container').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Fetching Plans...</p>
                    </div>
                `);
                $('.plan-tabs-container').empty();

                $.ajax({
                    url: "{{ route('user.service.recharge.fetch-dth-plans') }}",
                    method: 'POST',
                    data: {
                        opcode: opcode,
                        subscriber_id: subscriberId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log('DTH Plans API Response:', response);
                        
                        // Resolve plans data handling various nesting levels
                        // Priority: response.data.data.combo (User specific request)
                        let plansData = response;
                        
                        // Robust data extraction
                        if (response.data) {
                            let target = response.data;
                            if (response.data.data) {
                                target = response.data.data;
                            }
                            
                            // Look for Combo/combo key case-insensitive
                            if (typeof target === 'object') {
                                const comboKey = Object.keys(target).find(k => k.toLowerCase() === 'combo');
                                if (comboKey) {
                                    plansData = target[comboKey];
                                } else {
                                    plansData = target;
                                }
                            } else {
                                plansData = target;
                            }
                        }
                        
                        if (response.plans) plansData = response.plans;

                        allPlansFlat = [];
                        currentPlans = {};

                        // Helper to process a plan object with robust field fallback
                        const processPlan = (plan) => {
                             // Handle wrapped plan object
                             if (plan.plan) plan = plan.plan;
                             if (plan.Plan) plan = plan.Plan;
                             
                             // Debug log to help identify structure
                             console.log('Processing Plan:', plan);

                             // Helper to find key case-insensitive
                             const getValue = (obj, keys) => {
                                 if (!obj) return null;
                                 for (let key of keys) {
                                     if (obj[key] !== undefined && obj[key] !== null && obj[key] !== '') return obj[key];
                                     // Try lowercase
                                     const lowerKey = key.toLowerCase();
                                     const actualKey = Object.keys(obj).find(k => k.toLowerCase() === lowerKey);
                                     if (actualKey && obj[actualKey]) return obj[actualKey];
                                 }
                                 return null;
                             };

                             // Price: try common keys
                             // User reported 0, so we need to be very aggressive
                             let price = getValue(plan, [
                                 'rs', 'Rs', 'RS', 
                                 'price', 'Price', 
                                 'amount', 'Amount', 
                                 'mrp', 'MRP', 
                                 'cost', 'Cost', 
                                 'plan_amount', 'PlanAmount', 
                                 'monthly_price', 
                                 'recharge_amount', 'RechargeAmount', 
                                 'main_price',
                                 'details' // sometimes price is here?
                             ]) || '0';
                             
                             // Clean price string if needed (e.g. "Rs. 200")
                             if (typeof price === 'string') {
                                 const match = price.match(/(\d+(\.\d+)?)/);
                                 if (match) price = match[0];
                             }
                             
                             // Validity: try common keys
                             let validity = getValue(plan, ['validity', 'Validity', 'val', 'period', 'duration', 'validity_period', 'desc']) || '1 Month';
                             if (validity === 'desc' && plan.desc) {
                                 // Try to extract validity from desc if validty key pointed to desc? No, getValue returns value.
                             }
                             
                             // Description: try common keys
                             let desc = getValue(plan, [
                                 'desc', 'Desc', 
                                 'description', 'Description', 
                                 'details', 'Details', 
                                 'package_description', 
                                 'long_desc', 'short_desc',
                                 'plan_name', 'pack_name', 'name'
                             ]) || 'No description available';
                             
                             // If description is very short and plan_name exists, prefer plan_name + description?
                             // For now, trust the priority list.
                             
                             // Type/Name
                             let type = getValue(plan, ['plan_name', 'type', 'pack_type']) || 'General';
                             
                             return {
                                 price: price,
                                 validity: validity,
                                 desc: desc,
                                 type: type,
                                 language: plan.language || 'General',
                                 raw: plan 
                             };
                        };

                        // Smart recursive plan extractor
                        const isPlan = (obj) => {
                            if (!obj || typeof obj !== 'object') return false;
                            const keys = Object.keys(obj).map(k => k.toLowerCase());
                            return keys.some(k => ['rs', 'price', 'amount', 'mrp', 'cost', 'plan_amount', 'recharge_amount'].includes(k));
                        };

                        const extractPlans = (data, contextKey = 'General') => {
                            if (!data) return;

                            if (Array.isArray(data)) {
                                // Check if it's an array of plans
                                if (data.length > 0 && isPlan(data[0])) {
                                    // Found plans!
                                    const processed = data.map(p => processPlan(p));
                                    
                                    // Use contextKey as category (Language)
                                    let category = contextKey;
                                    
                                    // If category is effectively "Combo" or "General", try to be more specific if possible
                                    // But user wants "Language" key. 
                                    // The recursion should have passed the language key as contextKey.
                                    if (category.toLowerCase() === 'combo' || category === 'General') {
                                        category = 'All Plans';
                                    }

                                    if (currentPlans[category]) {
                                        currentPlans[category] = currentPlans[category].concat(processed);
                                    } else {
                                        currentPlans[category] = processed;
                                    }
                                    allPlansFlat = allPlansFlat.concat(processed);
                                } else {
                                    // Array of something else (wrappers?), recurse
                                    data.forEach(item => extractPlans(item, contextKey));
                                }
                            } else if (typeof data === 'object') {
                                // Check if object itself is a plan
                                if (isPlan(data)) {
                                     const p = processPlan(data);
                                     let category = contextKey;
                                     if (category.toLowerCase() === 'combo' || category === 'General') category = 'All Plans';
                                     
                                     if (currentPlans[category]) currentPlans[category].push(p);
                                     else currentPlans[category] = [p];
                                     allPlansFlat.push(p);
                                     return;
                                }

                                // Iterate keys
                                Object.keys(data).forEach(key => {
                                    const value = data[key];
                                    
                                    // Determine new context
                                    // If key is 'Combo' (case insensitive), IGNORE it for context purposes, 
                                    // but continue recursing with OLD context.
                                    // If key is 'data', ignore it too.
                                    // Otherwise, treat key as the Language/Category.
                                    
                                    let newContext = contextKey;
                                    const lowerKey = key.toLowerCase();
                                    
                                    if (lowerKey !== 'combo' && lowerKey !== 'data' && isNaN(key)) {
                                        // It's likely a language key or category
                                        newContext = key;
                                    }
                                    
                                    extractPlans(value, newContext);
                                });
                            }
                        };

                        // Start extraction
                        extractPlans(plansData);

                        // If no plans found
                        if (allPlansFlat.length === 0) {
                             $('.plans-content-container').html('<div class="text-center p-3">No plans available for this operator.</div>');
                             return;
                        }

                        // Render Tabs
                        renderTabs(Object.keys(currentPlans));

                        // Render First Tab
                        const firstType = Object.keys(currentPlans)[0];
                        if (firstType) {
                            currentActiveTab = firstType;
                            renderPlansList(currentPlans[firstType]);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching plans:', xhr);
                        $('.plans-content-container').html('<div class="text-center p-3 text-danger">Failed to load plans. Please try again.</div>');
                    }
                });
            }

            function renderTabs(types) {
                const tabsContainer = $('.plan-tabs-container');
                tabsContainer.empty();
                
                types.forEach((type, index) => {
                    const activeClass = index === 0 ? 'active' : '';
                    // Using nav-link for desktop compatibility if needed, but keeping plan-tab style
                    const tabHtml = `<div class="plan-tab ${activeClass}" data-type="${type}">${type}</div>`;
                    tabsContainer.append(tabHtml);
                });

                $('.plan-tab').click(function() {
                    $('.plan-tab').removeClass('active');
                    const type = $(this).data('type');
                    // Activate all tabs with this type (mobile/desktop sync)
                    $(`.plan-tab[data-type="${type}"]`).addClass('active');
                    
                    currentActiveTab = type;
                    renderPlansList(currentPlans[type] || []);
                });
            }

            function renderPlansList(plans) {
                const container = $('.plans-content-container');
                container.empty();

                if (!plans || plans.length === 0) {
                    container.html('<div class="text-center p-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 text-secondary"></i><p>No plans found in this category.</p></div>');
                    return;
                }

                plans.forEach(plan => {
                    const price = plan.price;
                    const validity = plan.validity;
                    const desc = plan.desc;
                    
                    // Professional Card Design
                    const html = `
                        <div class="plan-card position-relative overflow-hidden bg-white rounded-4 mb-3 border shadow-sm hover-shadow transition-all" style="transition: all 0.3s ease;">
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h3 class="mb-0 fw-bold text-dark display-6" style="font-size: 1.75rem;">₹${price}</h3>
                                        <div class="mt-2">
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium">
                                                <i class="far fa-calendar-alt me-1 text-muted"></i> Validity: ${validity}
                                            </span>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary rounded-pill px-4 py-2 btn-details shadow-sm fw-semibold" 
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border:none; letter-spacing: 0.5px;"
                                        data-plan='${JSON.stringify({
                                            price: plan.price,
                                            validity: plan.validity,
                                            desc: plan.desc
                                        }).replace(/'/g, "&#39;")}'>
                                        SELECT
                                    </button>
                                </div>
                                
                                <div class="plan-desc text-secondary mb-3" style="font-size: 0.95rem; line-height: 1.6; max-height: 3.2em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    ${desc}
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light opacity-75">
                                    <span class="text-muted small"><i class="fas fa-check-circle text-success me-1"></i> Verified Plan</span>
                                    <small class="text-primary cursor-pointer btn-details fw-medium" data-plan='${JSON.stringify({
                                            price: plan.price,
                                            validity: plan.validity,
                                            desc: plan.desc
                                        }).replace(/'/g, "&#39;")}' style="cursor:pointer;">
                                        View Details <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i>
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(html);
                });
                
                // Add hover effect via JS since we can't easily edit global CSS
                $('.plan-card').hover(
                    function() { $(this).addClass('shadow-lg').css('transform', 'translateY(-2px)'); },
                    function() { $(this).removeClass('shadow-lg').css('transform', 'translateY(0)'); }
                );
            }

            // Event listener for select button
            $(document).on('click', '.btn-details', function() {
                const planData = $(this).data('plan');
                const subscriberId = $('.dth-input').first().val();
                const operator = $('.dth-operator').first().val();

                if (!subscriberId) {
                    alert('Please enter Subscriber ID');
                    $('.dth-input').focus();
                    return;
                }
                
                if (!operator) {
                    alert('Please select an operator');
                    $('.dth-operator').focus();
                    return;
                }
                
                // Show modal
                $('#modal_price').text(planData.price);
                
                // Handle description formatting
                let descContent = planData.desc;
                // If contains HTML tags, render as HTML, otherwise handle newlines
                if (typeof descContent === 'string' && !/<[a-z][\s\S]*>/i.test(descContent)) {
                     // Convert newlines to breaks if it's plain text
                     descContent = descContent.replace(/\n/g, '<br>');
                }
                
                $('#modal_desc').html(descContent);
                $('#modal_validity').text(planData.validity);
                $('#modal_data').text('N/A'); 
                
                $('#btnNavigateToConfirm').data('details', planData);
                
                const modal = new bootstrap.Modal(document.getElementById('planDetailsModal'));
                modal.show();
            });
            
            $('#btnNavigateToConfirm').click(function() {
                const data = $(this).data('details');
                const subscriberId = $('.dth-input').first().val();
                const operator = $('.dth-operator').first().val();
                
                // Construct query parameters
                const params = new URLSearchParams({
                    mobile: subscriberId, 
                    operator: operator,
                    amount: data.price,
                    service: 'dth'
                });
                
                window.location.href = "{{ route('user.service.recharge.confirm') }}?" + params.toString();
            });
        });
    </script>

    <!-- Plan Details Modal -->
    <div class="modal fade" id="planDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Plan Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary mb-1">₹<span id="modal_price"></span></h2>
                        <div class="text-muted small text-start mt-3 p-2 bg-light rounded" id="modal_desc" style="max-height: 250px; overflow-y: auto; text-align: left;"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Validity</span>
                        <span class="fw-bold" id="modal_validity"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-primary rounded-circle p-3" id="btnNavigateToConfirm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('user.partials.theme-script')
</body>
</html>
