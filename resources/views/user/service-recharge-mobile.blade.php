<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mobile Recharge - UOnly</title>
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

        .filter-scroll {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding-bottom: 5px;
            scrollbar-width: none; /* Firefox */
        }
        .filter-scroll::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        .filter-chip {
            white-space: nowrap;
            padding: 6px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            font-size: 13px;
            color: #333;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .filter-chip:hover {
            background-color: #f8f9fa;
        }

        .filter-chip.active {
            background-color: #e8f0fe;
            color: #1967d2;
            border-color: #1967d2;
        }

        /* Tabs */
        .plan-tabs {
            display: flex;
            overflow-x: auto;
            border-bottom: 1px solid #f0f0f0;
            padding: 0 20px;
            scrollbar-width: none;
            background: white;
        }
        .plan-tabs::-webkit-scrollbar {
            display: none;
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
        
        .plan-badge {
            background: #512da8; /* Deep purple like in screenshot */
            color: white;
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 15px 0 15px 0;
            position: absolute;
            top: 0;
            left: 0;
            font-weight: 600;
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

        .plan-icons {
            display: flex;
            gap: 8px;
        }

        .plan-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #eee;
            object-fit: cover;
        }
        
        /* Placeholder colors for icons */
        .icon-red { background-color: #e60000; }
        .icon-black { background-color: #000; }
        .icon-pink { background-color: #ff4081; }

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
             @section('page_title', 'Mobile Recharge')
             @include('user.partials.desktop-header')
             <main class="p-4">
                 <div class="container-fluid">
                     <div class="row justify-content-center">
                         <div class="col-lg-8 col-xl-7">
                              <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                  <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #ff4b8b 0%, #a855f7 100%) !important;">
                                      <h5 class="fw-bold mb-1 text-white">Recharge Mobile</h5>
                                      <p class="mb-0 opacity-75 text-white">Enter your mobile number to browse plans</p>
                                  </div>
                                  <div class="card-body p-4">
                                      <div class="mb-4">
                                          <label class="form-label fw-semibold">Mobile Number</label>
                                          <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                                              <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-mobile-alt text-muted"></i></span>
                                              <input type="tel" class="form-control border-0 shadow-none mobile-input" placeholder="Enter 10-digit number" maxlength="10" pattern="\d{10}">
                                              <span class="input-group-text bg-white border-0 pe-3">
                                                  <div class="loading-spinner" style="display:none;">
                                                      <i class="fas fa-spinner fa-spin text-primary"></i>
                                                  </div>
                                                  <div class="operator-logo-container" style="display:none;">
                                                      <img class="operator-logo" src="" alt="" style="width: 24px; height: 24px;">
                                                  </div>
                                              </span>
                                          </div>
                                          <div class="operator-info mt-2 small text-success fw-semibold" style="display:none;">
                                              <span class="operator-name"></span> - <span class="circle-name"></span>
                                          </div>
                                      </div>
                                      
                                      <div class="plan-search-container bg-white p-0 position-static mb-3" style="display: none;">
                                           <div class="input-group mb-3">
                                               <span class="input-group-text bg-light border-end-0"><i class="fas fa-search"></i></span>
                                               <input type="text" class="form-control bg-light border-start-0 shadow-none plan-search-input" placeholder="Search plans...">
                                           </div>
                                           
                                           <div class="filter-chips filter-scroll d-flex gap-2 overflow-auto pb-2">
                                                <div class="filter-chip" data-filter="28 Days">28 Days Validity</div>
                                                <div class="filter-chip" data-filter="2GB/Day">2GB/Day Data</div>
                                                <div class="filter-chip" data-filter="1.5GB/Day">1.5GB/Day Data</div>
                                                <div class="filter-chip" data-filter="84 Days">84 Days Validity</div>
                                                <div class="filter-chip" data-filter="Data">Data Booster</div>
                                           </div>
                                      </div>

                                      <div class="plan-tabs-container nav nav-pills mb-3 gap-2 overflow-auto flex-nowrap pb-2" style="scrollbar-width: none;">
                                           <!-- Tabs -->
                                      </div>
                                      
                                      <div class="plans-loader text-center py-5" style="display: none;">
                                          <div class="spinner-border text-primary" role="status">
                                              <span class="visually-hidden">Loading...</span>
                                          </div>
                                          <p class="mt-2 text-muted">Fetching Plans...</p>
                                      </div>

                                      <div class="plans-content-container" style="max-height: 600px; overflow-y: auto;">
                                           <div class="text-center text-muted py-5">
                                               Enter mobile number to view plans
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
                <div class="page-title">Mobile Recharge</div>
            </div>
            
            <div class="page-subtitle">
                Manage your internet with ease.
            </div>

            <div class="input-card position-relative">
                <div class="input-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <input type="tel" id="mobile_number" class="form-control-transparent mobile-input" placeholder="Enter Mobile Number" maxlength="10" pattern="\d{10}" inputmode="numeric">
                
                <!-- Loading Spinner -->
                <div id="loading_spinner" class="loading-spinner" style="display:none; margin-left: 10px;">
                    <i class="fas fa-spinner fa-spin text-white" style="font-size: 24px;"></i>
                </div>

                <!-- Operator Logo Container (Right side inside input) -->
                <div id="operator_logo_container" class="operator-logo-container" style="display:none; margin-left: 10px;">
                    <img id="operator_logo" class="operator-logo" src="" alt="Op" style="width: 32px; height: 32px; border-radius: 50%; object-fit: contain; background: white; padding: 2px;">
                </div>
            </div>
            
            <!-- Operator Info (Below input) -->
            <div id="operator_info" class="operator-info mt-3 px-2 text-white" style="display:none; transition: all 0.3s ease;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 id="operator_name" class="operator-name mb-0 fw-bold" style="font-size: 1.1rem;"></h6>
                        <small id="circle_name" class="circle-name opacity-75"></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body Content -->
        <div class="content-body" style="flex: 1; background: #f8f9fa;">
            
            <!-- Search & Filters -->
            <div class="plan-search-container" style="display: none;">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="plan_search" class="plan-search-input" placeholder="Search for a plan, eg 349 or 28...">
                </div>
                
                <div class="filter-scroll filter-chips" id="filter_chips">
                    <div class="filter-chip" data-filter="28 Days">28 Days Validity</div>
                    <div class="filter-chip" data-filter="2GB/Day">2GB/Day Data</div>
                    <div class="filter-chip" data-filter="1.5GB/Day">1.5GB/Day Data</div>
                    <div class="filter-chip" data-filter="84 Days">84 Days Validity</div>
                    <div class="filter-chip" data-filter="Data">Data Booster</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="plan-tabs plan-tabs-container" id="plan_tabs">
                <!-- Tabs will be injected here -->
                <div class="plan-tab active">Recommended Packs</div> <!-- Placeholder -->
            </div>

            <!-- Plan List -->
            <div class="plan-list" id="plan_list_container">
                
                <div id="plans_loader" class="plans-loader text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Fetching Plans...</p>
                </div>

                <div id="plans_content" class="plans-content-container">
                     <!-- Initial placeholder content or empty -->
                     <div class="text-center text-muted py-5">
                         Enter mobile number to view plans
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
            let activeFilter = null;

            // Check for mobile query parameter and trigger fetch if present
            const urlParams = new URLSearchParams(window.location.search);
            const mobileParam = urlParams.get('mobile');
            if (mobileParam && mobileParam.length === 10) {
                $('.mobile-input').val(mobileParam);
                // Trigger input event after a short delay to ensure listeners are ready
                setTimeout(() => {
                    $('.mobile-input').trigger('input');
                }, 100);
            }

            $('.mobile-input').on('input', function() {
                // Sync inputs
                const val = $(this).val().replace(/\D/g, '');
                $('.mobile-input').val(val);
                
                let number = val;
                
                // When exactly 10 digits
                if (number.length === 10) {
                    // Show loading state
                    $('.loading-spinner').show();
                    $('.operator-logo-container').hide();
                    $('.operator-info').hide();
                    
                    // Reset plans
                    $('.plans-content-container').html('<div class="text-center text-muted py-5">Enter mobile number to view plans</div>');
                    $('.plan-tabs-container').empty();
                    $('.plan-search-container').hide();
                    currentPlans = {};
                    allPlansFlat = [];
                    
                    $.ajax({
                        url: "{{ route('user.service.recharge.fetch-operator') }}",
                        method: 'POST',
                        data: {
                            mobile: number,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('.loading-spinner').hide();
                            console.log('Operator API Response:', response);
                            
                            // Handle both wrapped (response.data) and flat response structures
                            let data = response;
                            if (response.data) {
                                data = response.data;
                            }
                            
                            // Map API keys
                            const opName = data.company || data.operator_name || data.Operator || data.operator;
                            const circleName = data.circle || data.circle_name || data.Circle;
                            const logoUrl = data.operator_logo || data.Logo || data.logo; 
                            
                            const opcode = data.company_code || data.opcode || data.operator_code;
                            const circleCode = data.circle_code || data.circlecode;

                            if(opName) {
                                $('.operator-name').text(opName);
                                if (circleName) {
                                    $('.circle-name').text(circleName);
                                }
                                $('.operator-info').fadeIn();
                            }
                            
                            if(logoUrl) {
                                $('.operator-logo').attr('src', logoUrl);
                                $('.operator-logo-container').fadeIn();
                            } else {
                                $('.operator-logo-container').hide();
                            }

                            // Fetch plans if we have the codes
                            if (opcode && circleCode) {
                                fetchPlans(number, opcode, circleCode);
                            } else {
                                console.warn('Opcode or Circle Code missing, cannot fetch plans. Data:', data);
                            }
                        },
                        error: function(xhr) {
                            $('.loading-spinner').hide();
                            console.error('Error fetching operator:', xhr);
                        }
                    });
                } else {
                    // Hide info if number is changed and not 10 digits
                    $('.loading-spinner').hide();
                    $('.operator-info').fadeOut();
                    $('.operator-logo-container').fadeOut();
                    $('.plans-content-container').html('<div class="text-center text-muted py-5">Enter mobile number to view plans</div>');
                    $('.plan-tabs-container').empty();
                    $('.plan-search-container').hide();
                    currentPlans = {};
                    allPlansFlat = [];
                }
            });

            function fetchPlans(mobile, opcode, circle) {
                $('.plans-loader').show();
                $('.plans-content-container').empty();
                $('.plan-tabs-container').empty();
                $('.plan-search-container').hide();

                $.ajax({
                    url: "{{ route('user.service.recharge.fetch-plans') }}",
                    method: 'POST',
                    data: {
                        mobile: mobile,
                        opcode: opcode,
                        circle: circle,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('.plans-loader').hide();
                        console.log('Plans API Response:', response);
                        
                        let plansData = response;
                        if (response.data) plansData = response.data;
                        if (response.plans) plansData = response.plans;

                        allPlansFlat = [];
                        if (Array.isArray(plansData)) {
                            allPlansFlat = plansData;
                            // Group by type
                            currentPlans = plansData.reduce((acc, plan) => {
                                const type = plan.type || 'Others';
                                if (!acc[type]) acc[type] = [];
                                acc[type].push(plan);
                                return acc;
                            }, {});
                        } else if (typeof plansData === 'object') {
                            // If already grouped object
                            currentPlans = plansData;
                            // Flatten for search
                            Object.values(plansData).forEach(group => {
                                if (Array.isArray(group)) {
                                    allPlansFlat = allPlansFlat.concat(group);
                                }
                            });
                        } else {
                            currentPlans = {};
                        }

                        renderTabs(Object.keys(currentPlans));
                        
                        const firstType = Object.keys(currentPlans)[0];
                        if (firstType) {
                            currentActiveTab = firstType;
                            $('.plan-search-container').fadeIn();
                            renderFilteredPlans();
                        } else {
                            $('.plans-content-container').html('<div class="text-center p-3">No plans found.</div>');
                        }
                    },
                    error: function(xhr) {
                        $('.plans-loader').hide();
                        console.error('Error fetching plans:', xhr);
                        $('.plans-content-container').html('<div class="text-center p-3 text-danger">Failed to load plans.</div>');
                    }
                });
            }

            function renderTabs(types) {
                const tabsContainer = $('.plan-tabs-container');
                tabsContainer.empty();
                
                types.forEach((type, index) => {
                    const activeClass = index === 0 ? 'active' : '';
                    // For desktop we might want nav-link instead of plan-tab? 
                    // Let's use generic class and style accordingly via CSS or just use same class
                    const tabHtml = `<div class="plan-tab ${activeClass} nav-link" data-type="${type}" style="cursor: pointer;">${type}</div>`;
                    tabsContainer.append(tabHtml);
                });

                $('.plan-tab').click(function() {
                    $('.plan-tab').removeClass('active');
                    // Add active class to clicked element and its counterparts in other view? 
                    // No, just visual active state on all elements with same type
                    const type = $(this).data('type');
                    $(`.plan-tab[data-type="${type}"]`).addClass('active');
                    
                    currentActiveTab = type;
                    renderFilteredPlans();
                });
            }

            // Search & Filter Listeners
            $('.plan-search-input').on('input', function() {
                const val = $(this).val();
                $('.plan-search-input').val(val); // Sync
                renderFilteredPlans();
            });

            $('.filter-chip').click(function() {
                const filter = $(this).data('filter');
                
                if ($(this).hasClass('active')) {
                    // Remove active from all matching chips
                    $(`.filter-chip[data-filter="${filter}"]`).removeClass('active');
                    activeFilter = null;
                } else {
                    $('.filter-chip').removeClass('active');
                    $(`.filter-chip[data-filter="${filter}"]`).addClass('active');
                    activeFilter = filter;
                }
                renderFilteredPlans();
            });

            function renderFilteredPlans() {
                // Get value from any of the inputs
                const searchQuery = $('.plan-search-input').first().val().toLowerCase().trim();
                let plansToRender = [];

                // If search query exists, search across ALL plans
                if (searchQuery.length > 0) {
                    plansToRender = allPlansFlat.filter(plan => {
                        const price = (plan.price || plan.amount || plan.rs || '').toString();
                        const validity = (plan.validity || '').toString().toLowerCase();
                        const data = (plan.data || plan.benefit || '').toString().toLowerCase();
                        const desc = (plan.desc || plan.description || plan.details || '').toString().toLowerCase();
                        
                        return price.includes(searchQuery) || 
                               validity.includes(searchQuery) || 
                               data.includes(searchQuery) ||
                               desc.includes(searchQuery);
                    });
                } else {
                    // Otherwise use current tab
                    plansToRender = currentPlans[currentActiveTab] || [];
                }

                // Apply Chip Filter
                if (activeFilter) {
                    plansToRender = plansToRender.filter(plan => {
                        const validity = (plan.validity || '').toString().toLowerCase();
                        const data = (plan.data || plan.benefit || '').toString().toLowerCase();
                        const filterLower = activeFilter.toLowerCase();
                        
                        // Custom logic for specific filters if needed
                        if (activeFilter === 'Data') {
                             return data.includes('data') || (plan.type && plan.type.toLowerCase().includes('data'));
                        }
                        
                        return validity.includes(filterLower) || data.includes(filterLower);
                    });
                }

                renderPlansList(plansToRender);
            }

            function renderPlansList(plans) {
                const contentContainer = $('.plans-content-container');
                contentContainer.empty();
                
                if (plans.length === 0) {
                    contentContainer.html('<div class="text-center p-3">No matching plans found.</div>');
                    return;
                }

                plans.forEach(plan => {
                    const price = plan.price || plan.amount || plan.rs || '0';
                    const validity = plan.validity || 'NA';
                    const dataBenefit = plan.data || plan.benefit || 'NA';
                    const description = plan.desc || plan.description || plan.details || '';
                    
                    const html = `
                        <div class="plan-card">
                            <div class="plan-header">
                                <div class="plan-price">₹${price}</div>
                                <div class="plan-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Validity</span>
                                        <span class="meta-value">${validity}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Data</span>
                                        <span class="meta-value">${dataBenefit}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="plan-desc">
                                ${description}
                            </div>
                            
                            <div class="plan-footer">
                                <div class="plan-icons">
                                </div>
                                <button class="btn-details" data-plan='${JSON.stringify({
                                    price: price,
                                    validity: validity,
                                    data: dataBenefit,
                                    desc: description
                                }).replace(/'/g, "&#39;")}'>Details</button>
                            </div>
                        </div>
                    `;
                    contentContainer.append(html);
                });
            }

            // Event listener for details button
            $(document).on('click', '.btn-details', function() {
                const planData = $(this).data('plan');
                
                $('#modal_price').text(planData.price);
                $('#modal_desc').text(planData.desc);
                $('#modal_validity').text(planData.validity);
                $('#modal_data').text(planData.data);
                
                // Store data for navigation
                $('#btnNavigateToConfirm').data('details', planData);
                
                const modal = new bootstrap.Modal(document.getElementById('planDetailsModal'));
                modal.show();
            });
            
            $('#btnNavigateToConfirm').click(function() {
                const data = $(this).data('details');
                // Use .first() to get value from one of the inputs (they are synced)
                const mobile = $('.mobile-input').first().val();
                const operator = $('.operator-name').first().text();
                const circle = $('.circle-name').first().text();
                
                // Construct query parameters
                const params = new URLSearchParams({
                    mobile: mobile,
                    operator: operator,
                    circle: circle,
                    amount: data.price,
                    validity: data.validity,
                    plan_desc: data.desc
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
                        <p class="text-muted small" id="modal_desc"></p>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Validity</span>
                        <span class="fw-bold" id="modal_validity"></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Data</span>
                        <span class="fw-bold" id="modal_data"></span>
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
