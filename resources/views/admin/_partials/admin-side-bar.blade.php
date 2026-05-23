<div class="sidebar" id="sidebar">
			<!-- Logo -->
			<div class="sidebar-logo">
				<a href="{{url('admin/dashboard')}}" class="logo logo-normal">
					<img src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/logo.png')}}" alt="Logo">
				</a>
				<a href="{{url('admin/dashboard')}}" class="logo-small">
					<img src="{{ $settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/logo.png')}}" alt="Logo">
				</a>
				<a href="{{url('admin/dashboard')}}" class="dark-logo">
					<img src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/logo.png')}}" alt="Logo">
				</a>
			</div>
			<!-- /Logo -->
			<div class="modern-profile p-3 pb-0">
				<div class="text-center rounded bg-light p-3 mb-4 user-profile">
					<div class="avatar avatar-lg online mb-3">
						<img src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Img" class="img-fluid rounded-circle">
					</div>
					<h6 class="fs-12 fw-normal mb-1">Adrian Herman</h6>
					<p class="fs-10">System Admin</p>
				</div>
				<div class="sidebar-nav mb-3">
					<ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
						<li class="nav-item"><a class="nav-link active border-0" href="#">Menu</a></li>
						<li class="nav-item"><a class="nav-link border-0" href="chat.html">Chats</a></li>
						<li class="nav-item"><a class="nav-link border-0" href="email.html">Inbox</a></li>
					</ul>
				</div>
			</div>
			<div class="sidebar-header p-3 pb-0 pt-2">
				<div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
					<div class="avatar avatar-md onlin">
						<img src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}" alt="Img" class="img-fluid rounded-circle">
					</div>
					<div class="text-start sidebar-profile-info ms-2">
						<h6 class="fs-12 fw-normal mb-1">Adrian Herman</h6>
						<p class="fs-10">System Admin</p>
					</div>
				</div>
				<div class="input-group input-group-flat d-inline-flex mb-4">
					<span class="input-icon-addon">
						<i class="ti ti-search"></i>
					</span>
					<input type="text" class="form-control" placeholder="Search in Uonly">
					<span class="input-group-text">
						<kbd>CTRL + / </kbd>
					</span>
				</div>
				<div class="d-flex align-items-center justify-content-between menu-item mb-3">
					<div class="me-3">
						<a href="calendar.html" class="btn btn-menubar">
							<i class="ti ti-layout-grid-remove"></i>
						</a>
					</div>
					<div class="me-3">
						<a href="#" class="btn btn-menubar position-relative">
							<i class="ti ti-brand-hipchat"></i>
							<span class="badge bg-info rounded-pill d-flex align-items-center justify-content-center header-badge">5</span>
						</a>
					</div>
					<div class="me-3 notification-item">
						<a href="#" class="btn btn-menubar position-relative me-1">
							<i class="ti ti-bell"></i>
							<span class="notification-status-dot"></span>
						</a>
					</div>
					<div class="me-0">
						<a href="#" class="btn btn-menubar">
							<i class="ti ti-message"></i>
						</a>
					</div>
				</div>
			</div>
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
					<ul>
						<li class="menu-title"><span>MAIN MENU</span></li>
						<li>
							<ul>
                                <li class="@navactive(['admin.dashboard','admin.dashboard.*'])">
									<a href="{{ route('admin.dashboard') }}" class="@navactive(['admin.dashboard','admin.dashboard.*'])">
										<i class="ti ti-smart-home"></i><span>Dashboard</span>
									</a>
								</li>
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="@navopen(['admin.departments.*','admin.designations.*','admin.staff.*','admin.payroll.*','admin.expenses.*','admin.expense-bills.*'])">
                                        <i class="ti ti-building"></i><span>Office Management</span>
                                        <span class="menu-arrow"></span>
                                    </a>
									<ul>
										<li><a href="{{ route('admin.departments.index') }}" class="@navactive('admin.departments.*')">Department Master</a></li>
										<li><a href="{{ route('admin.designations.index') }}" class="@navactive('admin.designations.*')">Designation Master</a></li>
										<li><a href="{{ route('admin.staff.index') }}" class="@navactive('admin.staff.*')">Staff Master</a></li>
										{{-- <li><a href="#">Set Department Permission</a></li>
										<li><a href="#">Staff Salary Credit</a></li>
										<li><a href="#">Staff Salary Credit Report</a></li> --}}
                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="@navopen('admin.payroll.*')">
                                                <i class="ti ti-cash"></i><span>Payroll Management</span>
                                                <span class="menu-arrow"></span>
                                            </a>
											<ul>
                                                <li><a href="{{ route('admin.payroll.structures.index') }}" class="@navactive('admin.payroll.structures.*')">Salary Structure</a></li>
                                                <li><a href="{{ route('admin.payroll.credits.create') }}" class="@navactive('admin.payroll.credits.create')">Monthly Salary Credit</a></li>
                                                <li><a href="{{ route('admin.payroll.credits.index') }}" class="@navactive('admin.payroll.credits.index')">Salary Credit Report</a></li>
											</ul>
										</li>
                                        <li><a href="{{ route('admin.expenses.index') }}" class="@navactive('admin.expenses.*')">Expense Master</a></li>
                                        <li><a href="{{ route('admin.expense-bills.create') }}" class="@navactive('admin.expense-bills.create')">Expense Bill</a></li>
                                        <li><a href="{{ route('admin.expense-bills.report') }}" class="@navactive('admin.expense-bills.report')">Expense Bill Report</a></li>
									</ul>
								</li>
								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.business-categories.*','admin.expenses.*','admin.states.*','admin.districts.*','admin.cities.*','admin.panchayats.*','admin.municipalities.*','admin.wards.*','admin.villages.*'])">
                                        <i class="ti ti-database"></i><span>Master Module</span>
										<span class="menu-arrow"></span>
									</a>
                                    <ul>
                                        <li><a href="{{route('admin.business-categories.index')}}" class="@navactive('admin.business-categories.*')">Business Category Master</a></li>
                                        {{-- <li><a href="{{ route('admin.expenses.index') }}" class="@navactive('admin.expenses.*')">Expense Master</a></li>
                                        <li><a href="{{ route('admin.states.index') }}" class="@navactive('admin.states.*')">State Master</a></li> --}}
                                        <li><a href="{{ route('admin.districts.index') }}" class="@navactive('admin.districts.*')">District Master</a></li>
                                        <li><a href="{{ route('admin.cities.index') }}" class="@navactive('admin.cities.*')">City Master</a></li>
                                        <li><a href="{{ route('admin.panchayats.index') }}" class="@navactive('admin.panchayats.*')">Panchayat Master</a></li>
                                        <li><a href="{{ route('admin.municipalities.index') }}" class="@navactive('admin.municipalities.*')">Municipality Master</a></li>
                                        <li><a href="{{ route('admin.wards.index') }}" class="@navactive('admin.wards.*')">Ward Master</a></li>
                                        <li><a href="{{ route('admin.villages.index') }}" class="@navactive('admin.villages.*')">Village/Town Master</a></li>
                                    </ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.affiliates.*','admin.affiliate-links.*','admin.affiliate-apis.*','admin.banners.*'])">
                                        <i class="ti ti-link"></i><span>Affiliate & Banner Module</span>
										<span class="menu-arrow"></span>
									</a>
                                    <ul>
                                        <li><a href="{{ route('admin.affiliates.index') }}" class="@navactive('admin.affiliates.*')">Affiliate Master</a></li>
                                        <li><a href="{{ route('admin.affiliate-links.index') }}" class="@navactive('admin.affiliate-links.*')">Affiliate Link Creation</a></li>
                                        <li><a href="{{ route('admin.affiliate-apis.index') }}" class="@navactive('admin.affiliate-apis.*')">Affiliate API</a></li>
                                        
										<li><a href="{{ route('admin.banners.index') }}" class="@navactive('admin.banners.*')">Banner Master</a></li>
                                    </ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.banks.*','admin.company-upis.*','admin.gst-taxes.*','admin.gst-tax-report.*'])">
                                        <i class="ti ti-building-bank"></i><span>Banking & Finance Module</span>
										<span class="menu-arrow"></span>
									</a>
                                    <ul>
                                        <li><a href="{{ route('admin.banks.index') }}" class="@navactive('admin.banks.*')">Bank Master</a></li>
                                        <li><a href="{{ route('admin.company-upis.index') }}" class="@navactive('admin.company-upis.*')">Company UPI Master</a></li>
                                        <li><a href="{{ route('admin.gst-taxes.index') }}" class="@navactive('admin.gst-taxes.*')">GST Tax Master</a></li>
										<li><a href="{{ route('admin.gst-tax-report.index') }}" class="@navactive('admin.gst-tax-report.*')">GST Tax Report</a></li>
                                    </ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.advertisements.*','admin.social-media.*','admin.leads.*','admin.reports.advertisements.approve-reject.*'])">
                                        <i class="ti ti-speakerphone"></i><span>Advertisement Module</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.advertisements.index') }}" class="@navactive('admin.advertisements.*')">Advertisement Master</a></li>
                                        <li><a href="{{ route('admin.social-media.index') }}" class="@navactive('admin.social-media.*')">Social Media Master</a></li>
                                        <li><a href="{{ route('admin.leads.index') }}" class="@navactive('admin.leads.*')">Lead Master</a></li>
                                        {{-- <li><a href="#">A/R Advertisement</a></li> --}}
                                        <li><a href="{{ route('admin.reports.advertisements.approve-reject.index') }}" class="@navactive('admin.reports.advertisements.approve-reject.*')">A/R Advertisement Report</a></li>
										
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.product-categories.*','admin.products.*','admin.level-wise-commissions.*','admin.vendor-products.*'])">
                                        <i class="ti ti-package"></i><span>Vendor Product Management</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.product-categories.index') }}" class="@navactive('admin.product-categories.*')">Product Category</a></li>										
										<li><a href="{{ route('admin.level-wise-commissions.index') }}" class="@navactive('admin.level-wise-commissions.*')">Product Category Comm.</a></li>
										<li><a href="{{ route('admin.vendor-products.index') }}" class="@navactive('admin.vendor-products.*')">Vendor Products Approval</a></li>
										{{-- <li><a href="#">Update Product Image</a></li> --}}
										
										
										
									</ul>
								</li>
								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.inhouse-product-categories.*', 'admin.inhouse-products.*', 'admin.ecard-seva-product-commissions.*', 'admin.product-stock-transactions.*','admin.products.*','admin.stock-transfers.*','admin.stock-ar-req.report'])">
                                        <i class="ti ti-stack"></i><span>In House Product Management</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.inhouse-product-categories.index') }}" class="@navactive('admin.inhouse-product-categories.*')">Product Category</a></li>										
										<li><a href="{{ route('admin.inhouse-products.index') }}" class="@navactive('admin.inhouse-products.*')">Product Master</a></li>
										<li><a href="{{ route('admin.ecard-seva-product-commissions.index') }}" class="@navactive('admin.ecard-seva-product-commissions.*')">E Card Seva Product Comm.</a></li>
										<li><a href="{{ route('admin.product-stock-transactions.index') }}" class="@navactive('admin.product-stock-transactions.*')">Add Product Stock</a></li>
                                        <li><a href="{{ route('admin.stock-transfers.index') }}" class="@navactive('admin.stock-transfers.*')">Stock Transfer</a></li>
										<li><a href="{{ route('admin.stock-transfers.report') }}" class="@navactive('admin.stock-transfers.report')">Stock Transfer Report</a></li>
                                        <li><a href="{{ route('admin.stock-ar-req.report') }}" class="@navactive('admin.stock-ar-req.report')">A & R Req. Stock Report</a></li>
										
										
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen('admin.points.*')">
                                        <i class="ti ti-coin"></i><span>Points Modules</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.points.admin-user-report.index') }}" class="@navactive('admin.points.admin-user-report.*')">Admin by User Point Report</a></li>
										<li><a href="{{ route('admin.points.vendor-user-report.index') }}" class="@navactive('admin.points.vendor-user-report.*')">Vendor by User Point Report</a></li>
										
										
									</ul>
								</li>

                                <li class="submenu">
                                    <a href="#" class="@navopen(['admin.reports.common-summary.*','admin.reports.voucher-details.*','admin.reports.commission-summary.*','admin.reports.cashback.*','admin.reports.user-upload-reward.*','admin.reports.user-reward.*','admin.reports.level-commission.*','admin.reports.user-id-upgrade.*','admin.reports.login-history.*','admin.reports.help-support.*'])">
<i class="ti ti-report-analytics"></i><span>Report Modules</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="{{ route('admin.reports.common-summary.index') }}" class="@navactive('admin.reports.common-summary.*')">Common Summary Report</a></li>                                        
                                        <li><a href="{{ route('admin.reports.voucher-details.index') }}" class="@navactive('admin.reports.voucher-details.*')">Voucher Details Report</a></li>
                                        <li><a href="{{ route('admin.reports.commission-summary.index') }}" class="@navactive('admin.reports.commission-summary.*')">Commission Summary Report</a></li>
                                        <li><a href="{{ route('admin.reports.cashback.index') }}" class="@navactive('admin.reports.cashback.*')">Cashback Report</a></li>
                                        <li><a href="{{ route('admin.reports.user-upload-reward.index') }}" class="@navactive('admin.reports.user-upload-reward.*')">User Upload Reward</a></li>
                                        <li><a href="{{ route('admin.reports.user-reward.index') }}" class="@navactive('admin.reports.user-reward.*')">User Reward Report</a></li>
                                        <li><a href="{{ route('admin.reports.level-commission.index') }}" class="@navactive('admin.reports.level-commission.*')">Level Commission Report</a></li>
                                        <li><a href="{{ route('admin.reports.user-id-upgrade.index') }}" class="@navactive('admin.reports.user-id-upgrade.*')">UserId Upgrade Report</a></li>
                                        <li><a href="{{ route('admin.reports.login-history.index') }}" class="@navactive('admin.reports.login-history.*')">Login History</a></li>
										<li><a href="#">UserId Upgrade Report</a></li>
										<li><a href="#">Login History</a></li>
                                        <li><a href="{{ route('admin.reports.help-support.index') }}" class="@navactive('admin.reports.help-support.*')">Help & Support Report</a></li>                                        
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen('admin.redeem-values.*')">
                                        <i class="ti ti-gift"></i><span>Redeem Modules</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.redeem-values.index') }}" class="@navactive('admin.redeem-values.index')">Add Redeem Value</a></li>
										<li><a href="{{ route('admin.redeem-values.history.index') }}" class="@navactive('admin.redeem-values.history.*')">Redeem Value History</a></li>
										<li><a href="{{ route('admin.redeem-values.user-redeem-report.index') }}" class="@navactive('admin.redeem-values.user-redeem-report.*')">User Redeem Report</a></li>
																			
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.benefits.*','admin.services.*','admin.helplines.*','admin.camps.*','admin.camp-details.*','admin.reports.camp-summary.*','admin.reports.book-camp.*','admin.reports.eligible.*'])">
                                        <i class="ti ti-heart-handshake"></i><span>Benefit Modules</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
                                        <li><a href="{{ route('admin.benefits.index') }}" class="@navactive('admin.benefits.*')">Benefits Master</a></li>
                                        <li><a href="{{ route('admin.services.index') }}" class="@navactive('admin.services.*')">Service Master</a></li>                                        
                                        <li><a href="{{ route('admin.helplines.index') }}" class="@navactive('admin.helplines.*')">Helpline Master</a></li>
                                        <li><a href="{{ route('admin.camps.index') }}" class="@navactive('admin.camps.*')">Camp Master</a></li>
                                        <li><a href="{{ route('admin.camp-details.index') }}" class="@navactive('admin.camp-details.*')">Add Camp details</a></li>
                                        <li><a href="{{ route('admin.reports.camp-summary.index') }}" class="@navactive('admin.reports.camp-summary.*')">Camp Summary Report</a></li>
                                        <li><a href="{{ route('admin.reports.book-camp.index') }}" class="@navactive('admin.reports.book-camp.*')">Book Camp Report</a></li>
                                        <li><a href="{{ route('admin.reports.eligible.index') }}" class="@navactive('admin.reports.eligible.*')">Eligible Report</a></li>
                                        <li><a href="{{ route('admin.benefits.gd-scheme-fund.index') }}" class="@navactive('admin.benefits.gd-scheme-fund.*')">G.D. Scheme User Fund</a></li>
                                        <li><a href="{{ route('admin.benefits.scheme-user-fund-report.index') }}" class="@navactive('admin.benefits.scheme-user-fund-report.*')">Scheme User Fund Report</a></li>
                                        <li><a href="{{ route('admin.benefits.points-master.index') }}" class="@navactive('admin.benefits.points-master.*')">Points Master</a></li>
                                        <li><a href="{{ route('admin.benefits.blood-donate-other-points-report.index') }}" class="@navactive('admin.benefits.blood-donate-other-points-report.*')">Blood Donate Other Points Report</a></li>
                                        <li><a href="{{ route('admin.benefits.ecard-seva-other-points-report.index') }}" class="@navactive('admin.benefits.ecard-seva-other-points-report.*')">ECS Other Points Report</a></li>
                                        <li><a href="{{ route('admin.benefits.emergency-ecard-seva-other-points-report.index') }}" class="@navactive('admin.benefits.emergency-ecard-seva-other-points-report.*')">Emergency Other Points Report</a></li>
																			
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.membership.eps-user-fund.*','admin.membership.eps-global-disburs-report.*'])">
                                        <i class="ti ti-id"></i><span>Membership E.P.S Modules</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
									<li><a href="{{ route('admin.membership.eps-user-fund.index') }}" class="@navactive('admin.membership.eps-user-fund.*')">E.P.S User Fund</a></li>
									<li><a href="#">E.P.S User Fund Report</a></li>
									<li><a href="{{ route('admin.membership.eps-global-disburs-report.index') }}" class="@navactive('admin.membership.eps-global-disburs-report.*')">Global Disburs. Level Fund Report</a></li>
								</ul>
								</li>
								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.eps-level-fund.*','admin.eps-level-fund-report.*'])">
                                        <i class="ti ti-credit-card"></i><span>E-Card Seva & E.P.S Module</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.eps-level-fund.index') }}" class="@navactive('admin.eps-level-fund.*')">Global Disburs. Level Fund</a></li>
										<li><a href="{{ route('admin.eps-level-fund-report.index') }}" class="@navactive('admin.eps-level-fund-report.*')">Global Disburs. Level Fund Report</a></li>									
										
									</ul>
								</li>

								<li class="submenu">
                                <a href="#" class="@navopen(['admin.vendor-global-fund.*','admin.vendor-global-fund-report.*'])">
                                    <i class="ti ti-cash-banknote"></i><span>Vendor Global Disburs. Fund</span>
									<span class="menu-arrow"></span>
								</a>
								<ul>
									<li><a href="{{ route('admin.vendor-global-fund.index') }}" class="@navactive('admin.vendor-global-fund.*')">Vendor Global Disburs. Fund</a></li>
									<li><a href="{{ route('admin.vendor-global-fund-report.index') }}" class="@navactive('admin.vendor-global-fund-report.*')">Vendor Global Disburs. Fund Report</a></li>
									
								</ul>
							</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.ecard-seva-approve-kyc-documents.*','admin.ecard-seva-ar-withdrawal-report.*','admin.retailer-employee-permissions.*'])">
                                        <i class="ti ti-credit-card"></i><span>E-Card Seva Modules</span>
										<span class="menu-arrow"></span>
									</a>
									<ul id="ecard-seva-modules" class="nav-content collapse" data-bs-parent="#sidebar-nav">
										<li><a href="{{ route('admin.ecard-seva-approve-kyc-documents.index') }}" class="@navactive('admin.ecard-seva-approve-kyc-documents.*')">Approve KYC Document</a></li>
										<li><a href="{{ route('admin.ecard-seva-ar-withdrawal-report.index') }}" class="@navactive('admin.ecard-seva-ar-withdrawal-report.*')"> A/R Withdrawal Report</a></li>
                                            
										<li><a href="{{ route('admin.retailer-employee-permissions.index') }}" class="@navactive('admin.retailer-employee-permissions.*')">See Retailer/Employee Permission</a></li>										
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.department-commissions.*','admin.first-recharge-plans.*','admin.commission-tds-charge-report.*'])">
                                        <i class="ti ti-settings"></i><span>System Modules</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.department-commissions.index') }}" class="@navactive('admin.department-commissions.*')">Department Module Comm. Master</a></li>
										<li><a href="{{ route('admin.first-recharge-plans.index') }}" class="@navactive('admin.first-recharge-plans.*')">First Recharge Plan Master</a></li>
										<li><a href="{{ route('admin.commission-tds-charge-report.index') }}" class="@navactive('admin.commission-tds-charge-report.*')">Commission TDS Charge Report</a></li>									 
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.recharge-services.*','admin.recharge-operators.*','admin.recharge-summary-report.*','admin.recharge-report.*','admin.recharge-commissions.*'])">
                                        <i class="ti ti-plug"></i><span>Recharge Modules</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.recharge-services.index') }}" class="@navactive('admin.recharge-services.*')">Recharge Service</a></li>
										<li><a href="{{ route('admin.recharge-operators.index') }}" class="@navactive('admin.recharge-operators.*')">Recharge Operator</a></li>
										<li><a href="{{ route('admin.recharge-summary-report.index') }}" class="@navactive('admin.recharge-summary-report.*')">Recharge Summary Report</a></li>
										<li><a href="{{ route('admin.recharge-report.index') }}" class="@navactive('admin.recharge-report.*')">Recharge Report</a></li>
										<li><a href="{{ route('admin.recharge-commissions.index') }}" class="@navactive('admin.recharge-commissions.*')">User Commission</a></li>
										{{-- <li><a href="#">Level Commission</a></li>	
										<li><a href="#">User Beneficiary</a></li>								 --}}
										
								</ul>
							</li>

							<li class="submenu">
								<a href="#" class="@navopen('admin.notifications.*')">
									<i class="ti ti-bell"></i><span>Notification Modules</span>
									<span class="menu-arrow"></span>
								</a>
								<ul>
									<li><a href="{{ route('admin.notifications.index') }}" class="@navactive('admin.notifications.*')">Send Notification</a></li>
								</ul>
							</li>

                                
							</ul>
						</li>

						<li class="menu-title"><span>ADMINISTRATION</span></li>
						<li>
							<ul>
								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.vendor-types.*','admin.vendors.*','admin.vendor.*'])">
                                        <i class="ti ti-building-store"></i><span>Vendor Management</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.vendor-types.index') }}" class="@navactive('admin.vendor-types.*')">Vendor Type</a></li>										
										<li><a href="{{ route('admin.vendors.index') }}" class="@navactive('admin.vendors.*')">Vendor Master</a></li>										
										<li><a href="{{ route('admin.vendor.wallet.management') }}" class="@navactive('admin.vendor.wallet.management')">Add/Remove Vendor Wallet</a></li>
										<li><a href="{{ route('admin.vendor.wallet.summary.index') }}" class="@navactive('admin.vendor.wallet.summary.*')">Vendor Wallet Summary</a></li>
										<li><a href="{{ route('admin.vendor.wallet.request-report.index') }}" class="@navactive('admin.vendor.wallet.request-report.*')">Wallet Request Report</a></li>
										<li><a href="{{ route('admin.vendor.notification-master.index') }}" class="@navactive('admin.vendor.notification-master.*')">Notification Master</a></li>
										<li><a href="{{ route('admin.vendor.utility-affiliate-links.index') }}" class="@navactive('admin.vendor.utility-affiliate-links.*')">Vendor Utility & Affiliate Link</a></li>
										<li><a href="{{ route('admin.vendor.view-orders.index') }}" class="@navactive('admin.vendor.view-orders.*')">View Order</a></li>
										<li><a href="{{ route('admin.vendor.approve-kyc.index') }}" class="@navactive('admin.vendor.approve-kyc.*')">Approve KYC</a></li>
										<li><a href="#">A/R Bank Settlement Req</a></li>
										
									</ul>
								</li>
								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.ecard-registrations.*','admin.ecard-seva-summary.*','admin.ecard-seva-wallet-request-report.*','admin.ecard-seva-user-utility-affiliate-links.*','admin.ecard-permissions.*','admin.ecard-seva-bank-settlement-requests.*','admin.registrations.*','admin.user-wallet-request.*'])">
                                        <i class="ti ti-credit-card"></i><span>E-Card Seva</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										    <!--<li><a href="{{ route('admin.registrations.index') }}">Registration</a></li>-->
                                            <li><a href="{{ route('admin.ecard-registrations.index') }}" class="@navactive('admin.ecard-registrations.*')"> E - Card Registration</a></li>
                                            <li><a href="{{ route('admin.ecard-seva-summary.index') }}" class="@navactive('admin.ecard-seva-summary.*')"> E-Card Seva Summary</a></li>
                                            <li><a href="{{ route('admin.user-wallet-request.index') }}" class="@navactive('admin.user-wallet-request.*')"> E-Card Request Report</a></li>
											<li><a href="{{ route('admin.ecard-seva-wallet-request-report.index') }}" class="@navactive('admin.ecard-seva-wallet-request-report.*')"> E-Card Seva Wallet Summary Report</a></li>
                                            
                                            <li><a href="{{ route('admin.ecard-seva-wallet-request-report.index') }}" class="@navactive('admin.ecard-seva-wallet-request-report.*')"> E-Card Seva Wallet Req. Report</a></li>
                                            <!--<li><a href="{{ route('admin.notification-master.index') }}"> Notification Master</a></li>-->
                                            <li><a href="{{ route('admin.ecard-seva-user-utility-affiliate-links.index') }}" class="@navactive('admin.ecard-seva-user-utility-affiliate-links.*')"> User Utility &amp; Affiliate Link</a></li>
                                            <li><a href="{{ route('admin.ecard-permissions.index') }}" class="@navactive('admin.ecard-permissions.*')"> Set Permission</a></li>
                                            <li><a href="{{ route('admin.ecard-seva-bank-settlement-requests.index') }}" class="@navactive('admin.ecard-seva-bank-settlement-requests.*')"> Bank Settlement Requests</a></li>
                                             <li><a href="{{ route('admin.registrations.index') }}" class="@navactive('admin.registrations.*')">Id Card List</a></li>
											 <li><a href="{{ route('admin.registrations.index') }}" class="@navactive('admin.registrations.*')">Certificate</a></li>
											 <li><a href="{{ route('admin.registrations.index') }}" class="@navactive('admin.registrations.*')">E Card Print</a></li>
											 <li><a href="{{ route('admin.registrations.index') }}" class="@navactive('admin.registrations.*')">E Card Print Report</a></li>
											 <li><a href="{{ route('admin.registrations.index') }}" class="@navactive('admin.registrations.*')">E Card Authorization Letter</a></li>
											 <li><a href="{{ route('admin.registrations.index') }}" class="@navactive('admin.registrations.*')">Accept Agreement Letter</a></li>
										
									</ul>
								</li>
								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.registrations.*','admin.wallet.*','admin.user-wallet-request.*','admin.membership.details.*','admin.wallet.request-report.*','admin.notification-master.*','admin.user-utility-affiliate-links.*','admin.view-orders.*','admin.user-ecard-report.*','admin.security-amount-master.*'])">
                                        <i class="ti ti-users"></i><span>User Management</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										    <li><a href="{{ route('admin.registrations.index') }}" class="@navactive('admin.registrations.*')">Registrations</a></li>
                                            <li><a href="{{ route('admin.wallet.management') }}" class="@navactive('admin.wallet.management')"> Add/Remove User Wallet</a></li>
												    <li><a href="{{ route('admin.membership.details.index') }}" class="@navactive('admin.membership.details.*')"> My Membership Details</a></li>
                                            
                                            <li><a href="{{ route('admin.wallet.summary.index') }}" class="@navactive('admin.wallet.summary.*')"> User Wallet Summary</a></li>
                                            <li><a href="{{ route('admin.user-wallet-request.index') }}" class="@navactive('admin.user-wallet-request.*')"> User Wallet Request</a></li>
									<li><a href="{{ route('admin.wallet.request-report.index') }}" class="@navactive('admin.wallet.request-report.*')"> Level Wallet Req. Report</a></li>
                                            <li><a href="{{ route('admin.notification-master.index') }}" class="@navactive('admin.notification-master.*')"> Notification Master</a></li>
                                            <li><a href="{{ route('admin.user-utility-affiliate-links.index') }}" class="@navactive('admin.user-utility-affiliate-links.*')"> User Utility &amp; Affiliate Link</a></li>
                                            <li><a href="{{ route('admin.view-orders.index') }}" class="@navactive('admin.view-orders.*')"> View Order</a></li>
                                            {{-- <li><a href="#"> A/R User Withdrawal Request</a></li> --}}
                                            <li><a href="{{ route('admin.user-ecard-report.index') }}" class="@navactive('admin.user-ecard-report.*')"> User e-card Report</a></li>
                                            <li><a href="#"> User e-Card Print Report</a></li>
                                            <li><a href="{{ route('admin.security-amount-master.index') }}" class="@navactive('admin.security-amount-master.*')">Security Amount Master</a></li>
										
									</ul>
								</li>

								<li class="submenu">
                                    <a href="javascript:void(0);" class="@navopen(['admin.users.*','admin.roles.*','admin.permissions.*','admin.profile.settings'])">
                                        <i class="ti ti-user-shield"></i><span>Admin User</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.users.index') }}" class="@navactive('admin.users.*')">Users</a></li>
										<li><a href="{{ route('admin.roles.index') }}" class="@navactive('admin.roles.*')">Roles</a></li>
										<li><a href="{{ route('admin.permissions.index') }}" class="@navactive('admin.permissions.*')">Permissions</a></li>
										<li><a href="{{ route('admin.profile.settings') }}" class="@navactive('admin.profile.settings')">Profile Settings</a></li>
									</ul>
								</li>

								<li class="submenu">
									<a href="javascript:void(0);" class="@navopen(['admin.settings.*','admin.payment-gateways.*','admin.gst-taxes.*'])">
										<i class="ti ti-settings"></i><span>Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
								<li class="submenu submenu-two">
									<a href="javascript:void(0);" class="@navopen('admin.settings.notification')">General Settings<span class="menu-arrow inside-submenu"></span></a>
									<ul>
										<li><a href="#">Profile</a></li>
										<li><a href="#">Security</a></li>
										<li><a href="{{ route('admin.settings.notification') }}" class="@navactive('admin.settings.notification')">Notification Settings</a></li>
										
									</ul>
								</li>
										<li class="submenu submenu-two">
											<a href="javascript:void(0);" class="@navopen('admin.settings.website')">Website Settings<span class="menu-arrow inside-submenu"></span></a>
											<ul>
												<li><a href="{{ route('admin.settings.website') }}" class="@navactive('admin.settings.website')">General Settings</a></li>
												<li><a href="#">SEO Settings</a></li>
												
											</ul>
										</li>
										{{-- <li class="submenu submenu-two">
											<a href="javascript:void(0);">App Settings<span class="menu-arrow inside-submenu"></span></a>
											<ul>
												<li><a href="#">Salary Settings</a></li>
												<li><a href="#">Approval Settings</a></li>
												<li><a href="#">Invoice Settings</a></li>
												
											</ul>
										</li> --}}
								<li class="submenu submenu-two">
									<a href="javascript:void(0);" class="@navopen(['admin.settings.third-party-api.*','admin.settings.recharge-api.*','admin.settings.maintenance.*'])">System Settings<span class="menu-arrow inside-submenu"></span></a>
									<ul>
										{{-- <li><a href="#">Email Settings</a></li>
										<li><a href="#">Email Templates</a></li>
										<li><a href="#">SMS Settings</a></li>
										<li><a href="#">SMS Templates</a></li> --}}
										<li><a href="{{ route('admin.settings.third-party-api.show') }}" class="@navactive('admin.settings.third-party-api.*')">Third Party Api Settings</a></li>
										<li><a href="{{ route('admin.settings.recharge-api.show') }}" class="@navactive('admin.settings.recharge-api.*')">Recharge API Settings</a></li>
										<li><a href="#">OTP</a></li>
										<li><a href="#">GDPR Cookies</a></li>
										<li><a href="{{ route('admin.settings.maintenance.show') }}" class="@navactive('admin.settings.maintenance.*')">Maintenance Mode</a></li>
									</ul>
								</li>
										<li class="submenu submenu-two">
											<a href="javascript:void(0);" class="@navopen(['admin.payment-gateways.*','admin.gst-taxes.*'])">Financial Settings<span class="menu-arrow inside-submenu"></span></a>
											<ul>
                                            <li><a href="{{ route('admin.payment-gateways.index') }}" class="@navactive('admin.payment-gateways.*')">Payment Gateways</a></li>
												<li><a href="{{ route('admin.gst-taxes.index') }}" class="@navactive('admin.gst-taxes.*')">Tax Rate</a></li>
												<li><a href="#">Currencies</a></li>
											</ul>
										</li>
										<li class="submenu submenu-two">
											<a href="javascript:void(0);">Other Settings<span class="menu-arrow inside-submenu"></span></a>
											<ul>
												<li><a href="#">Custom CSS</a></li>
												<li><a href="#">Custom JS</a></li>
												<li><a href="#">Cronjob</a></li>
												<li><a href="#">Storage</a></li>
												<li><a href="#">Ban IP Address</a></li>
												<li><a href="#">Backup</a></li>
												<li><a href="#">Clear Cache</a></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>

						<li class="menu-title"><span>FRONTEND CMS</span></li>
						<li>
							<ul>
								<li class="submenu">
                                    <a href="#" class="@navopen(['admin.menus.*','admin.home-sliders.*','admin.about-us.*','admin.government.*','admin.website-benefits.*','admin.special-features.*','admin.cms-pages.*','admin.news.*','admin.website-help-support.*','admin.galleries.*','admin.website-services.*','admin.website-e-store.*','admin.website-uonly-by-apps.*'])">
                                        <i class="ti ti-world"></i><span>Website Module</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="{{ route('admin.menus.index') }}" class="@navactive('admin.menus.*')">Main Menu</a></li>										
                                        <li><a href="{{ route('admin.home-sliders.index') }}" class="@navactive('admin.home-sliders.*')">Home Slider</a></li>
                                        <li class="submenu submenu-two">
                                            <a href="javascript:void(0);" class="@navopen('admin.about-us.*')">About Us<span class="menu-arrow inside-submenu"></span></a>
                                            <ul>
                                                <li><a href="{{ route('admin.about-us.edit') }}" class="@navactive('admin.about-us.edit')">General</a></li>
                                                <li><a href="{{ route('admin.about-us.organization-profile.edit') }}" class="@navactive('admin.about-us.organization-profile.*')">Organization Profile</a></li>
                                                <li><a href="{{ route('admin.about-us.business-focus.edit') }}" class="@navactive('admin.about-us.business-focus.*')">Business Focus</a></li>
                                                <li><a href="{{ route('admin.about-us.excellence.edit') }}" class="@navactive('admin.about-us.excellence.*')">Excellence</a></li>
                                                <li><a href="{{ route('admin.about-us.our-vision.edit') }}" class="@navactive('admin.about-us.our-vision.*')">Our Vision</a></li>
                                                <li><a href="{{ route('admin.our-team.index') }}" class="@navactive('admin.our-team.*')">Our Team</a></li>
                                                <li><a href="{{ route('admin.about-us.leadership-with-trust.edit') }}" class="@navactive('admin.about-us.leadership-with-trust.*')">Leadership With Trust</a></li>
                                                <li><a href="{{ route('admin.about-us.our-mission.edit') }}" class="@navactive('admin.about-us.our-mission.*')">Our Mission</a></li>
                                                <li><a href="{{ route('admin.about-us.legals.edit') }}" class="@navactive('admin.about-us.legals.*')">Legals</a></li>
                                                <li><a href="{{ route('admin.about-us.ecard-focus.edit') }}" class="@navactive('admin.about-us.ecard-focus.*')">e-Card Focus</a></li>
                                                <li><a href="{{ route('admin.about-us.faqs.index') }}" class="@navactive('admin.about-us.faqs.*')">FAQ's</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('admin.government.edit') }}" class="@navactive('admin.government.*')">Government</a></li>
                                        <li class="submenu submenu-two">
                                            <a href="javascript:void(0);" class="@navopen('admin.benefits.*')">Benefit<span class="menu-arrow inside-submenu"></span></a>
                                            <ul>
                                                <li><a href="{{ route('admin.benefits.book-camp.edit') }}" class="@navactive('admin.benefits.book-camp.edit')">Book Camp</a></li>
                                                <li><a href="{{ route('admin.benefits.blood-donate.edit') }}" class="@navactive('admin.benefits.blood-donate.edit')">Blood Donate</a></li>
                                            </ul>
                                        </li>
                                        <li class="submenu submenu-two">
                                            <a href="javascript:void(0);" class="@navopen('admin.website-services.*')">Services<span class="menu-arrow inside-submenu"></span></a>
                                            <ul>
                                                <li><a href="{{ route('admin.website-services.e-card.edit') }}" class="@navactive('admin.website-services.e-card.edit')">E-Card</a></li>
                                                <li><a href="{{ route('admin.website-services.on-demand-service.edit') }}" class="@navactive('admin.website-services.on-demand-service.edit')">On Demand Service</a></li>
                                                <li><a href="{{ route('admin.website-services.marketplace.edit') }}" class="@navactive('admin.website-services.marketplace.edit')">Marketplace</a></li>
                                                <li><a href="{{ route('admin.website-services.city-development.edit') }}" class="@navactive('admin.website-services.city-development.edit')">City Development</a></li>
                                                <li><a href="{{ route('admin.website-services.education.edit') }}" class="@navactive('admin.website-services.education.edit')">Education</a></li>
                                                <li><a href="{{ route('admin.website-services.real-estate-business.edit') }}" class="@navactive('admin.website-services.real-estate-business.edit')">Real Estate Business</a></li>
                                            </ul>
                                        </li>
                                        <li class="submenu submenu-two">
                                            <a href="javascript:void(0);" class="@navopen('admin.website-e-store.*')">E-Store<span class="menu-arrow inside-submenu"></span></a>
                                            <ul>
                                                <li><a href="{{ route('admin.website-e-store.hotels.edit') }}" class="@navactive('admin.website-e-store.hotels.edit')">Hotels</a></li>
                                                <li><a href="{{ route('admin.website-e-store.hospitals.edit') }}" class="@navactive('admin.website-e-store.hospitals.edit')">Hospitals</a></li>
                                                <li><a href="{{ route('admin.website-e-store.shoppings.edit') }}" class="@navactive('admin.website-e-store.shoppings.edit')">Shoppings</a></li>
                                            </ul>
                                        </li>
                                        <li class="submenu submenu-two">
                                            <a href="javascript:void(0);" class="@navopen('admin.website-uonly-by-apps.*')">Uonly By Apps<span class="menu-arrow inside-submenu"></span></a>
                                            <ul>
                                                <li><a href="{{ route('admin.website-uonly-by-apps.education.edit') }}" class="@navactive('admin.website-uonly-by-apps.education.edit')">Education</a></li>
                                                <li><a href="{{ route('admin.website-uonly-by-apps.u-mart.edit') }}" class="@navactive('admin.website-uonly-by-apps.u-mart.edit')">U-Mart</a></li>
                                                <li><a href="{{ route('admin.website-uonly-by-apps.u-admission.edit') }}" class="@navactive('admin.website-uonly-by-apps.u-admission.edit')">U-Admission</a></li>
                                            </ul>
                                        </li>
										<li><a href="{{ route('admin.news.index') }}" class="@navactive('admin.news.*')">News</a></li>
										<li><a href="{{ route('admin.website-help-support.edit') }}" class="@navactive('admin.website-help-support.*')">Help & Support</a></li>
										<li><a href="{{ route('admin.galleries.index') }}" class="@navactive('admin.galleries.*')">Gallery</a></li>
										<li><a href="{{ route('admin.cms-pages.index') }}" class="@navactive('admin.cms-pages.*')">Policy Page</a></li>
										<li><a href="{{ route('admin.website-benefits.index') }}" class="@navactive('admin.website-benefits.*')">Website Benefit List</a></li>
										<li><a href="{{ route('admin.special-features.index') }}" class="@navactive('admin.special-features.*')">Special Features</a></li>
										
									</ul>
								</li>
							</ul>
						</li>
						
						<li class="menu-title"><span>Extras</span></li>
						<li>
							<ul>
								<li>
									<a href="javascript:void(0);"><i class="ti ti-file-text"></i><span>Documentation</span></a>
								</li>
                                <li>
									<a href="{{url('/docs')}}" target="_blank"><i class="ti ti-file-text"></i><span>API Documentation</span></a>
								</li>
								<li>
									<a href="javascript:void(0);"><i class="ti ti-exchange"></i><span>Changelog</span><span class="badge bg-pink badge-xs text-white fs-10 ms-s">v4.0.2</span></a>
								</li>

                                
                                
                                <li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-headset"></i><span>Help & Supports</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="knowledgebase.html">Knowledge Base</a></li>
										<li><a href="activity.html">Activities</a></li>
									</ul>
								</li>
								
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
