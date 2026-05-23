<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\RedeemValueHistoryController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Admin Routes |-------------------------------------------------------------------------- | | Here is where you can register admin routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "admin" middleware group. Now create something great! | */

Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

// Protected Admin Routes (with admin middleware)
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [AdminDashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/dashboard/user-chart', [AdminDashboardController::class, 'getUserRegistrationChart'])->name('dashboard.user-chart');

    // Role & Permission Management
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
    Route::post('permissions/bulk-action', [\App\Http\Controllers\Admin\PermissionController::class, 'bulkAction'])->name('permissions.bulk-action');

    // Profile Settings
    Route::get('/profile/settings', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.settings');
    Route::put('/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/image', [\App\Http\Controllers\Admin\ProfileController::class, 'removeImage'])->name('profile.image.remove');

    // Website Settings Routes
    Route::get('/settings/website', [\App\Http\Controllers\Admin\SettingsController::class, 'show'])->name('settings.website');
    Route::put('/settings/website', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.website.update');
    Route::delete('/settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'removeLogo'])->name('settings.logo.remove');

    // CMS Pages Management
    Route::resource('cms-pages', \App\Http\Controllers\Admin\CmsPageController::class);
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::resource('galleries', \App\Http\Controllers\Admin\GalleryController::class);
    Route::delete('/galleries/images/{id}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroyImage'])->name('galleries.images.destroy');
    Route::get('/website/help-support', [\App\Http\Controllers\Admin\HelpSupportPageController::class, 'edit'])->name('website-help-support.edit');
    Route::put('/website/help-support', [\App\Http\Controllers\Admin\HelpSupportPageController::class, 'update'])->name('website-help-support.update');

    // Notification Settings (Firebase)
    Route::get('/settings/notification', [\App\Http\Controllers\Admin\SettingsController::class, 'showNotification'])->name('settings.notification');
    Route::put('/settings/notification', [\App\Http\Controllers\Admin\SettingsController::class, 'updateNotification'])->name('settings.notification.update');
    Route::delete('/settings/favicon', [\App\Http\Controllers\Admin\SettingsController::class, 'removeFavicon'])->name('settings.favicon.remove');

    // Third Party API Settings
    Route::get('/settings/third-party-api', [\App\Http\Controllers\Admin\SettingsController::class, 'showThirdPartyApi'])->name('settings.third-party-api.show');
    Route::put('/settings/third-party-api', [\App\Http\Controllers\Admin\SettingsController::class, 'updateThirdPartyApi'])->name('settings.third-party-api.update');

    // Recharge API Settings
    Route::get('/settings/recharge-api', [\App\Http\Controllers\Admin\SettingsController::class, 'showRechargeApi'])->name('settings.recharge-api.show');
    Route::put('/settings/recharge-api', [\App\Http\Controllers\Admin\SettingsController::class, 'updateRechargeApi'])->name('settings.recharge-api.update');

    // Maintenance Mode Settings
    Route::get('/settings/maintenance', [\App\Http\Controllers\Admin\SettingsController::class, 'showMaintenance'])->name('settings.maintenance.show');
    Route::put('/settings/maintenance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateMaintenance'])->name('settings.maintenance.update');

    // User Management Routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::delete('/users/{user}/image', [\App\Http\Controllers\Admin\UserController::class, 'removeImage'])->name('users.image.remove');

    // Security Amount Master Routes
    Route::resource('security-amount-master', \App\Http\Controllers\Admin\SecurityAmountMasterController::class);

    // Business Category Management Routes
    Route::resource('business-categories', \App\Http\Controllers\Admin\BusinessCategoryController::class);
    Route::post('/business-categories/{id}/toggle-status', [\App\Http\Controllers\Admin\BusinessCategoryController::class, 'toggleStatus'])->name('business-categories.toggle-status');

    // State Management Routes
    Route::resource('states', \App\Http\Controllers\Admin\StateController::class);
    Route::post('/states/{id}/toggle-status', [\App\Http\Controllers\Admin\StateController::class, 'toggleStatus'])->name('states.toggle-status');

    // District Management Routes
    Route::resource('districts', \App\Http\Controllers\Admin\DistrictController::class);
    Route::post('/districts/{id}/toggle-status', [\App\Http\Controllers\Admin\DistrictController::class, 'toggleStatus'])->name('districts.toggle-status');
    Route::get('/districts/by-state/{state_id}', [\App\Http\Controllers\Admin\DistrictController::class, 'getDistrictsByState'])->name('districts.by-state');

    // City Management Routes
    Route::resource('cities', \App\Http\Controllers\Admin\CityController::class);
    Route::post('/cities/{id}/toggle-status', [\App\Http\Controllers\Admin\CityController::class, 'toggleStatus'])->name('cities.toggle-status');
    Route::get('/cities/get-districts-by-state', [\App\Http\Controllers\Admin\CityController::class, 'getDistrictsByState'])->name('cities.get-districts-by-state');
    Route::get('/cities/by-district/{district_id}', [\App\Http\Controllers\Admin\CityController::class, 'getCitiesByDistrict'])->name('cities.by-district');

    // Village/Town Management Routes
    Route::resource('villages', \App\Http\Controllers\Admin\VillageController::class);
    Route::post('/villages/{id}/toggle-status', [\App\Http\Controllers\Admin\VillageController::class, 'toggleStatus'])->name('villages.toggle-status');
    Route::get('/villages/get-districts-by-state', [\App\Http\Controllers\Admin\VillageController::class, 'getDistrictsByState'])->name('villages.get-districts-by-state');
    Route::get('/villages/get-cities-by-district', [\App\Http\Controllers\Admin\VillageController::class, 'getCitiesByDistrict'])->name('villages.get-cities-by-district');
    Route::get('/villages/by-city/{city_id}', [\App\Http\Controllers\Admin\VillageController::class, 'getVillagesByCity'])->name('villages.by-city');
    Route::post('/villages/bulk', [\App\Http\Controllers\Admin\VillageController::class, 'bulkStore'])->name('villages.bulk-store');
    Route::post('/villages/import-csv', [\App\Http\Controllers\Admin\VillageController::class, 'importCsv'])->name('villages.import-csv');

    // Panchayat/Municipality/Ward Management Routes
    Route::resource('panchayats', \App\Http\Controllers\Admin\PanchayatController::class);
    Route::post('/panchayats/{id}/toggle-status', [\App\Http\Controllers\Admin\PanchayatController::class, 'toggleStatus'])->name('panchayats.toggle-status');
    Route::get('/panchayats/by-city/{city_id}', [\App\Http\Controllers\Admin\PanchayatController::class, 'getPanchayatsByCity'])->name('panchayats.by-city');

    Route::resource('municipalities', \App\Http\Controllers\Admin\MunicipalityController::class);
    Route::post('/municipalities/{id}/toggle-status', [\App\Http\Controllers\Admin\MunicipalityController::class, 'toggleStatus'])->name('municipalities.toggle-status');
    Route::get('/municipalities/by-city/{city_id}', [\App\Http\Controllers\Admin\MunicipalityController::class, 'getMunicipalitiesByCity'])->name('municipalities.by-city');

    Route::resource('wards', \App\Http\Controllers\Admin\WardController::class);
    Route::post('/wards/{id}/toggle-status', [\App\Http\Controllers\Admin\WardController::class, 'toggleStatus'])->name('wards.toggle-status');
    Route::get('/wards/by-municipality/{municipality_id}', [\App\Http\Controllers\Admin\WardController::class, 'getWardsByMunicipality'])->name('wards.by-municipality');

    // Bank Management Routes
    Route::resource('banks', \App\Http\Controllers\Admin\BankController::class);
    Route::post('/banks/{id}/toggle-status', [\App\Http\Controllers\Admin\BankController::class, 'toggleStatus'])->name('banks.toggle-status');
    Route::get('/banks/data', [\App\Http\Controllers\Admin\BankController::class, 'getData'])->name('banks.data');

    // Company UPI Management Routes
    Route::resource('company-upis', \App\Http\Controllers\Admin\CompanyUpiController::class);
    Route::post('/company-upis/{id}/toggle-status', [\App\Http\Controllers\Admin\CompanyUpiController::class, 'toggleStatus'])->name('company-upis.toggle-status');

    // Affiliate Management Routes
    Route::resource('affiliates', \App\Http\Controllers\Admin\AffiliateController::class);
    Route::post('/affiliates/{id}/toggle-status', [\App\Http\Controllers\Admin\AffiliateController::class, 'toggleStatus'])->name('affiliates.toggle-status');

    // Affiliate Link Routes
    Route::get('/affiliate-links', [\App\Http\Controllers\Admin\AffiliateLinkController::class, 'index'])->name('affiliate-links.index');
    Route::get('/affiliate-links/data', [\App\Http\Controllers\Admin\AffiliateLinkController::class, 'data'])->name('affiliate-links.data');
    Route::post('/affiliate-links', [\App\Http\Controllers\Admin\AffiliateLinkController::class, 'store'])->name('affiliate-links.store');
    Route::delete('/affiliate-links/{id}', [\App\Http\Controllers\Admin\AffiliateLinkController::class, 'destroy'])->name('affiliate-links.destroy');

    // Affiliate API Routes
    Route::get('/affiliate-apis', [\App\Http\Controllers\Admin\AffiliateApiController::class, 'index'])->name('affiliate-apis.index');
    Route::get('/affiliate-apis/data', [\App\Http\Controllers\Admin\AffiliateApiController::class, 'data'])->name('affiliate-apis.data');
    Route::post('/affiliate-apis', [\App\Http\Controllers\Admin\AffiliateApiController::class, 'store'])->name('affiliate-apis.store');
    Route::delete('/affiliate-apis/{id}', [\App\Http\Controllers\Admin\AffiliateApiController::class, 'destroy'])->name('affiliate-apis.destroy');

    // Company Bank Management Routes
    Route::resource('company-banks', \App\Http\Controllers\Admin\CompanyBankController::class);
    Route::post('/company-banks/{id}/toggle-status', [\App\Http\Controllers\Admin\CompanyBankController::class, 'toggleStatus'])->name('company-banks.toggle-status');

    // Banner Management Routes
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::post('/banners/{id}/toggle-status', [\App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('banners.toggle-status');

    // Vendor Management Routes
    Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);
    Route::post('/vendors/{id}/toggle-status', [\App\Http\Controllers\Admin\VendorController::class, 'toggleStatus'])->name('vendors.toggle-status');

    // Vendor Wallet Management
    Route::get('/vendor-wallet-management', [\App\Http\Controllers\Admin\VendorWalletController::class, 'index'])->name('vendor.wallet.management');
    Route::post('/vendor-wallet-management/search', [\App\Http\Controllers\Admin\VendorWalletController::class, 'searchVendor'])->name('vendor.wallet.search');
    Route::post('/vendor-wallet-management/transaction', [\App\Http\Controllers\Admin\VendorWalletController::class, 'processTransaction'])->name('vendor.wallet.transaction');

    // Vendor Wallet Summary
    Route::get('/vendor-wallet-summary', [\App\Http\Controllers\Admin\VendorWalletSummaryController::class, 'index'])->name('vendor.wallet.summary.index');
    Route::get('/vendor-wallet-summary/data', [\App\Http\Controllers\Admin\VendorWalletSummaryController::class, 'data'])->name('vendor.wallet.summary.data');
    Route::get('/vendor-wallet-summary/transactions/{vendorId}', [\App\Http\Controllers\Admin\VendorWalletSummaryController::class, 'transactions'])->name('vendor.wallet.summary.transactions');

    // Vendor Wallet Request Report
    Route::get('/vendor-wallet-request-report', [\App\Http\Controllers\Admin\VendorWalletRequestReportController::class, 'index'])->name('vendor.wallet.request-report.index');
    Route::get('/vendor-wallet-request-report/data', [\App\Http\Controllers\Admin\VendorWalletRequestReportController::class, 'data'])->name('vendor.wallet.request-report.data');

    // Vendor Type Management Routes
    Route::resource('vendor-types', \App\Http\Controllers\Admin\VendorTypeController::class);
    Route::post('/vendor-types/{id}/toggle-status', [\App\Http\Controllers\Admin\VendorTypeController::class, 'toggleStatus'])->name('vendor-types.toggle-status');
    Route::get('/vendor-types/active/list', [\App\Http\Controllers\Admin\VendorTypeController::class, 'getActiveVendorTypes'])->name('vendor-types.active');

    // Product Category Management Routes
    Route::resource('product-categories', \App\Http\Controllers\Admin\ProductCategoryController::class);
    Route::post('/product-categories/{id}/toggle-status', [\App\Http\Controllers\Admin\ProductCategoryController::class, 'toggleStatus'])->name('product-categories.toggle-status');
    Route::get('/product-categories/active/list', [\App\Http\Controllers\Admin\ProductCategoryController::class, 'getActiveProductCategories'])->name('product-categories.active');

    // Product Management Routes
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('/products/data', [\App\Http\Controllers\Admin\ProductController::class, 'data'])->name('products.data');
    Route::post('/products/{id}/toggle-status', [\App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle-status');

    // Vendor Product Approval Management
    Route::get('/vendor-products', [\App\Http\Controllers\Admin\AdminVendorProductController::class, 'index'])->name('vendor-products.index');
    Route::post('/vendor-products/{id}/approve', [\App\Http\Controllers\Admin\AdminVendorProductController::class, 'approve'])->name('vendor-products.approve');
    Route::post('/vendor-products/{id}/reject', [\App\Http\Controllers\Admin\AdminVendorProductController::class, 'reject'])->name('vendor-products.reject');

    // Level Wise Product Commission Routes
    Route::resource('level-wise-commissions', \App\Http\Controllers\Admin\LevelWiseProductCommissionController::class);
    Route::post('/level-wise-commissions/show-details', [\App\Http\Controllers\Admin\LevelWiseProductCommissionController::class, 'showDetails'])->name('level-wise-commissions.show-details');
    Route::post('/level-wise-commissions/{id}/toggle-status', [\App\Http\Controllers\Admin\LevelWiseProductCommissionController::class, 'toggleStatus'])->name('level-wise-commissions.toggle-status');

    // Menu Management Routes
    Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
    Route::post('/menus/{id}/toggle-status', [\App\Http\Controllers\Admin\MenuController::class, 'toggleStatus'])->name('menus.toggle-status');
    Route::post('/menus/update-order', [\App\Http\Controllers\Admin\MenuController::class, 'updateOrder'])->name('menus.update-order');
    Route::post('/menus/{id}/duplicate', [\App\Http\Controllers\Admin\MenuController::class, 'duplicate'])->name('menus.duplicate');
    Route::get('/menus/hierarchical/{type}', [\App\Http\Controllers\Admin\MenuController::class, 'getHierarchical'])->name('menus.hierarchical');

    // E Card Seva Product Commission Routes
    Route::post('ecard-seva-product-commissions/show-details', [\App\Http\Controllers\Admin\EcardSevaProductCommissionController::class, 'showDetails'])->name('ecard-seva-product-commissions.show-details');
    Route::post('ecard-seva-product-commissions/{id}/toggle-status', [\App\Http\Controllers\Admin\EcardSevaProductCommissionController::class, 'toggleStatus'])->name('ecard-seva-product-commissions.toggle-status');
    Route::resource('ecard-seva-product-commissions', \App\Http\Controllers\Admin\EcardSevaProductCommissionController::class);

    // Home Slider Management Routes
    Route::resource('home-sliders', \App\Http\Controllers\Admin\HomeSliderController::class);
    Route::post('/home-sliders/{id}/toggle-status', [\App\Http\Controllers\Admin\HomeSliderController::class, 'toggleStatus'])->name('home-sliders.toggle-status');

    // Department Management Routes
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
    Route::post('/departments/{id}/toggle-status', [\App\Http\Controllers\Admin\DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
    Route::get('/departments/export/excel', [\App\Http\Controllers\Admin\DepartmentController::class, 'export'])->name('departments.export');
    Route::get('/departments/export/pdf', [\App\Http\Controllers\Admin\DepartmentController::class, 'exportPdf'])->name('departments.export.pdf');
    Route::post('/departments/import', [\App\Http\Controllers\Admin\DepartmentController::class, 'import'])->name('departments.import');

    // Designation Management Routes
    Route::resource('designations', \App\Http\Controllers\Admin\DesignationController::class);
    Route::post('/designations/{id}/toggle-status', [\App\Http\Controllers\Admin\DesignationController::class, 'toggleStatus'])->name('designations.toggle-status');
    Route::get('/designations/export/excel', [\App\Http\Controllers\Admin\DesignationController::class, 'export'])->name('designations.export');
    Route::get('/designations/export/pdf', [\App\Http\Controllers\Admin\DesignationController::class, 'exportPdf'])->name('designations.export.pdf');
    Route::post('/designations/import', [\App\Http\Controllers\Admin\DesignationController::class, 'import'])->name('designations.import');

    // Expense Management Routes
    Route::resource('expenses', \App\Http\Controllers\Admin\ExpenseController::class);
    Route::post('/expenses/{id}/toggle-status', [\App\Http\Controllers\Admin\ExpenseController::class, 'toggleStatus'])->name('expenses.toggle-status');
    Route::get('/expenses/export/excel', [\App\Http\Controllers\Admin\ExpenseController::class, 'export'])->name('expenses.export');
    Route::get('/expenses/export/pdf', [\App\Http\Controllers\Admin\ExpenseController::class, 'exportPdf'])->name('expenses.export-pdf');
    Route::post('/expenses/import', [\App\Http\Controllers\Admin\ExpenseController::class, 'import'])->name('expenses.import');

    // Expense Bill Management Routes
    Route::get('/expense-bills/create', [\App\Http\Controllers\Admin\ExpenseBillController::class, 'create'])->name('expense-bills.create');
    Route::post('/expense-bills', [\App\Http\Controllers\Admin\ExpenseBillController::class, 'store'])->name('expense-bills.store');

    // Staff Management Routes
    Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class);
    Route::post('/staff/{id}/toggle-status', [\App\Http\Controllers\Admin\StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
    Route::get('/staff/export/excel', [\App\Http\Controllers\Admin\StaffController::class, 'export'])->name('staff.export');
    Route::get('/staff/export/pdf', [\App\Http\Controllers\Admin\StaffController::class, 'exportPdf'])->name('staff.export.pdf');
    Route::post('/staff/import', [\App\Http\Controllers\Admin\StaffController::class, 'import'])->name('staff.import');

    // Registration Management Routes
    Route::resource('registrations', \App\Http\Controllers\Admin\RegistrationController::class);
    Route::post('/registrations/{id}/update-status', [\App\Http\Controllers\Admin\RegistrationController::class, 'updateStatus'])->name('registrations.update-status');

    // E-Card Registration Management Routes
    Route::resource('ecard-registrations', \App\Http\Controllers\Admin\ECardRegistrationController::class);
    Route::post('/ecard-registrations/{id}/update-status', [\App\Http\Controllers\Admin\ECardRegistrationController::class, 'updateStatus'])->name('ecard-registrations.update-status');
    Route::post('/ecard-registrations/{id}/generate-credentials', [\App\Http\Controllers\Admin\ECardRegistrationController::class, 'generateCredentials'])->name('ecard-registrations.generate-credentials');
    Route::post('/ecard-registrations/{id}/update-kyc-status', [\App\Http\Controllers\Admin\ECardRegistrationController::class, 'updateKycStatus'])->name('ecard-registrations.update-kyc-status');

    // E-Card Seva Summary Routes
    Route::get('/ecard-seva-summary', [\App\Http\Controllers\Admin\ECardSevaSummaryController::class, 'index'])->name('ecard-seva-summary.index');
    Route::get('/ecard-seva-summary/data', [\App\Http\Controllers\Admin\ECardSevaSummaryController::class, 'data'])->name('ecard-seva-summary.data');

    // E-Card Approve KYC Documents Routes
    Route::get('/ecard-seva-approve-kyc-documents', [\App\Http\Controllers\Admin\ECardApproveKycDocumentController::class, 'index'])->name('ecard-seva-approve-kyc-documents.index');
    Route::get('/ecard-seva-approve-kyc-documents/data', [\App\Http\Controllers\Admin\ECardApproveKycDocumentController::class, 'data'])->name('ecard-seva-approve-kyc-documents.data');
    Route::get('/ecard-seva-approve-kyc-documents/export', [\App\Http\Controllers\Admin\ECardApproveKycDocumentController::class, 'export'])->name('ecard-seva-approve-kyc-documents.export');

    // E-Card Seva Wallet Req. Report Routes
    Route::get('/ecard-seva-wallet-request-report', [\App\Http\Controllers\Admin\ECardSevaWalletRequestReportController::class, 'index'])->name('ecard-seva-wallet-request-report.index');
    Route::get('/ecard-seva-wallet-request-report/data', [\App\Http\Controllers\Admin\ECardSevaWalletRequestReportController::class, 'data'])->name('ecard-seva-wallet-request-report.data');

    // E-Card Seva User Utility & Affiliate Link Routes
    Route::get('/ecard-seva-user-utility-affiliate-links', [\App\Http\Controllers\Admin\ECardSevaUserUtilityAffiliateLinkController::class, 'index'])->name('ecard-seva-user-utility-affiliate-links.index');
    Route::get('/ecard-seva-user-utility-affiliate-links/data', [\App\Http\Controllers\Admin\ECardSevaUserUtilityAffiliateLinkController::class, 'data'])->name('ecard-seva-user-utility-affiliate-links.data');
    Route::post('/ecard-seva-user-utility-affiliate-links', [\App\Http\Controllers\Admin\ECardSevaUserUtilityAffiliateLinkController::class, 'store'])->name('ecard-seva-user-utility-affiliate-links.store');
    Route::delete('/ecard-seva-user-utility-affiliate-links/{id}', [\App\Http\Controllers\Admin\ECardSevaUserUtilityAffiliateLinkController::class, 'destroy'])->name('ecard-seva-user-utility-affiliate-links.destroy');

    // User E-Card Report Routes
    Route::get('/user-ecard-report', [\App\Http\Controllers\Admin\UserECardReportController::class, 'index'])->name('user-ecard-report.index');
    Route::get('/user-ecard-report/data', [\App\Http\Controllers\Admin\UserECardReportController::class, 'data'])->name('user-ecard-report.data');
    Route::get('/user-ecard-report/districts', [\App\Http\Controllers\Admin\UserECardReportController::class, 'districts'])->name('user-ecard-report.districts');
    Route::get('/user-ecard-report/cities', [\App\Http\Controllers\Admin\UserECardReportController::class, 'cities'])->name('user-ecard-report.cities');
    Route::get('/user-ecard-report/print/{id}', [\App\Http\Controllers\Admin\UserECardReportController::class, 'print'])->name('user-ecard-report.print');

    // Profile Update Routes
    Route::get('/profile-update', [\App\Http\Controllers\Admin\RegistrationController::class, 'profileUpdate'])->name('profile.update');
    Route::post('/profile-update/search', [\App\Http\Controllers\Admin\RegistrationController::class, 'searchUser'])->name('profile.search');
    Route::post('/profile-update/{id}', [\App\Http\Controllers\Admin\RegistrationController::class, 'updateProfile'])->name('profile.update.save');

    // Wallet Management
    Route::get('/wallet-management', [\App\Http\Controllers\Admin\WalletController::class, 'index'])->name('wallet.management');
    Route::post('/wallet-management/search', [\App\Http\Controllers\Admin\WalletController::class, 'searchUser'])->name('wallet.search');
    Route::post('/wallet-management/transaction', [\App\Http\Controllers\Admin\WalletController::class, 'processTransaction'])->name('wallet.transaction');

    // User Wallet Summary
    Route::get('/wallet-summary', [\App\Http\Controllers\Admin\WalletSummaryController::class, 'index'])->name('wallet.summary.index');
    Route::get('/wallet-summary/data', [\App\Http\Controllers\Admin\WalletSummaryController::class, 'data'])->name('wallet.summary.data');

    // Wallet Request Report
    // User Wallet Request
    Route::get('/user-wallet-request', [\App\Http\Controllers\Admin\WalletRequestReportController::class, 'indexUser'])->name('user-wallet-request.index');
    Route::get('/user-wallet-request/data', [\App\Http\Controllers\Admin\WalletRequestReportController::class, 'data'])->name('user-wallet-request.data');
    Route::get('/user-wallet-request/{source}/{id}', [\App\Http\Controllers\Admin\WalletRequestReportController::class, 'show'])->name('user-wallet-request.show');
    Route::post('/user-wallet-request/{source}/{id}/approve', [\App\Http\Controllers\Admin\WalletRequestReportController::class, 'approve'])->name('user-wallet-request.approve');
    Route::post('/user-wallet-request/{source}/{id}/reject', [\App\Http\Controllers\Admin\WalletRequestReportController::class, 'reject'])->name('user-wallet-request.reject');

    // Level Wallet Req. Report
    Route::get('/wallet-request-report', [\App\Http\Controllers\Admin\WalletRequestReportController::class, 'index'])->name('wallet.request-report.index');
    Route::get('/wallet-request-report/data', [\App\Http\Controllers\Admin\WalletRequestReportController::class, 'data'])->name('wallet.request-report.data');

    // My Membership Details
    Route::get('/membership/details', [\App\Http\Controllers\Admin\MembershipDetailsController::class, 'index'])->name('membership.details.index');
    Route::get('/membership/details/data', [\App\Http\Controllers\Admin\MembershipDetailsController::class, 'data'])->name('membership.details.data');

    // Membership E.P.S User Fund
    Route::get('/membership/eps-user-fund', [\App\Http\Controllers\Admin\EpsUserFundController::class, 'index'])->name('membership.eps-user-fund.index');
    Route::get('/membership/eps-user-fund/data', [\App\Http\Controllers\Admin\EpsUserFundController::class, 'data'])->name('membership.eps-user-fund.data');
    Route::get('/membership/eps-user-fund/history', [\App\Http\Controllers\Admin\EpsUserFundController::class, 'history'])->name('membership.eps-user-fund.history');
    Route::post('/membership/eps-user-fund', [\App\Http\Controllers\Admin\EpsUserFundController::class, 'store'])->name('membership.eps-user-fund.store');

    // Membership E.P.S - Global Disburs. Level Fund Report
    Route::get('/membership/eps-global-disburs-report', [\App\Http\Controllers\Admin\EpsGlobalDisbursReportController::class, 'index'])->name('membership.eps-global-disburs-report.index');
    Route::get('/membership/eps-global-disburs-report/data', [\App\Http\Controllers\Admin\EpsGlobalDisbursReportController::class, 'data'])->name('membership.eps-global-disburs-report.data');

    // E-Card Seva & E.P.S Module - Global Disburs. Level Fund
    Route::get('/eps-level-fund', [\App\Http\Controllers\Admin\EpsLevelFundController::class, 'index'])->name('eps-level-fund.index');
    Route::get('/eps-level-fund/data', [\App\Http\Controllers\Admin\EpsLevelFundController::class, 'data'])->name('eps-level-fund.data');
    Route::post('/eps-level-fund/distribute', [\App\Http\Controllers\Admin\EpsLevelFundController::class, 'distribute'])->name('eps-level-fund.distribute');

    // E-Card Seva & E.P.S Module - Global Disburs. Level Fund Report
    Route::get('/eps-level-fund/report', [\App\Http\Controllers\Admin\EpsLevelFundReportController::class, 'index'])->name('eps-level-fund-report.index');
    Route::get('/eps-level-fund/report/data', [\App\Http\Controllers\Admin\EpsLevelFundReportController::class, 'data'])->name('eps-level-fund-report.data');

    // Vendor Global Disburs. Fund - Global Fund
    Route::get('/vendor-global-fund', [\App\Http\Controllers\Admin\VendorGlobalFundController::class, 'index'])->name('vendor-global-fund.index');
    Route::get('/vendor-global-fund/data', [\App\Http\Controllers\Admin\VendorGlobalFundController::class, 'data'])->name('vendor-global-fund.data');
    Route::post('/vendor-global-fund/distribute', [\App\Http\Controllers\Admin\VendorGlobalFundController::class, 'distribute'])->name('vendor-global-fund.distribute');

    // Vendor Global Disburs. Fund Report
    Route::get('/vendor-global-fund/report', [\App\Http\Controllers\Admin\VendorGlobalFundReportController::class, 'index'])->name('vendor-global-fund-report.index');
    Route::get('/vendor-global-fund/report/data', [\App\Http\Controllers\Admin\VendorGlobalFundReportController::class, 'data'])->name('vendor-global-fund-report.data');

    // About Us Routes
    Route::get('/about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'edit'])->name('about-us.edit');
    Route::put('/about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'update'])->name('about-us.update');
    // Organization Profile (under About Us)
    Route::get('/about-us/organization-profile', [\App\Http\Controllers\Admin\AboutUsController::class, 'organizationProfileEdit'])->name('about-us.organization-profile.edit');
    Route::put('/about-us/organization-profile', [\App\Http\Controllers\Admin\AboutUsController::class, 'organizationProfileUpdate'])->name('about-us.organization-profile.update');

    // Business Focus (under About Us)
    Route::get('/about-us/business-focus', [\App\Http\Controllers\Admin\BusinessFocusController::class, 'edit'])->name('about-us.business-focus.edit');
    Route::put('/about-us/business-focus', [\App\Http\Controllers\Admin\BusinessFocusController::class, 'update'])->name('about-us.business-focus.update');

    // Excellence (under About Us)
    Route::get('/about-us/excellence', [\App\Http\Controllers\Admin\ExcellenceController::class, 'edit'])->name('about-us.excellence.edit');
    Route::put('/about-us/excellence', [\App\Http\Controllers\Admin\ExcellenceController::class, 'update'])->name('about-us.excellence.update');

    // Our Vision (under About Us)
    Route::get('/about-us/our-vision', [\App\Http\Controllers\Admin\OurVisionController::class, 'edit'])->name('about-us.our-vision.edit');
    Route::put('/about-us/our-vision', [\App\Http\Controllers\Admin\OurVisionController::class, 'update'])->name('about-us.our-vision.update');

    // Our Team (under About Us)
    Route::resource('/about-us/our-team', \App\Http\Controllers\Admin\TeamMemberController::class)->except(['show']);
    Route::patch('/about-us/our-team/{id}/toggle-status', [\App\Http\Controllers\Admin\TeamMemberController::class, 'toggleStatus'])->name('our-team.toggle-status');

    // Leadership With Trust (under About Us)
    Route::get('/about-us/leadership-with-trust', [\App\Http\Controllers\Admin\LeadershipWithTrustController::class, 'edit'])->name('about-us.leadership-with-trust.edit');
    Route::put('/about-us/leadership-with-trust', [\App\Http\Controllers\Admin\LeadershipWithTrustController::class, 'update'])->name('about-us.leadership-with-trust.update');

    // Our Mission (under About Us)
    Route::get('/about-us/our-mission', [\App\Http\Controllers\Admin\OurMissionController::class, 'edit'])->name('about-us.our-mission.edit');
    Route::put('/about-us/our-mission', [\App\Http\Controllers\Admin\OurMissionController::class, 'update'])->name('about-us.our-mission.update');

    // Legals (under About Us)
    Route::get('/about-us/legals', [\App\Http\Controllers\Admin\LegalController::class, 'edit'])->name('about-us.legals.edit');
    Route::put('/about-us/legals', [\App\Http\Controllers\Admin\LegalController::class, 'update'])->name('about-us.legals.update');

    // e-Card Focus (under About Us)
    Route::get('/about-us/ecard-focus', [\App\Http\Controllers\Admin\ECardFocusController::class, 'edit'])->name('about-us.ecard-focus.edit');
    Route::put('/about-us/ecard-focus', [\App\Http\Controllers\Admin\ECardFocusController::class, 'update'])->name('about-us.ecard-focus.update');

    // FAQ's (under About Us)
    Route::resource('/about-us/faqs', \App\Http\Controllers\Admin\FaqController::class, ['as' => 'about-us'])->except(['show']);
    Route::post('/about-us/faqs/{id}/toggle-status', [\App\Http\Controllers\Admin\FaqController::class, 'toggleStatus'])->name('about-us.faqs.toggle-status');

    // Government Routes
    Route::get('/government', [\App\Http\Controllers\Admin\GovernmentController::class, 'edit'])->name('government.edit');
    Route::put('/government', [\App\Http\Controllers\Admin\GovernmentController::class, 'update'])->name('government.update');

    // Benefit -> Book Camp Routes
    Route::get('/benefits/book-camp', [\App\Http\Controllers\Admin\BookCampController::class, 'edit'])->name('benefits.book-camp.edit');
    Route::put('/benefits/book-camp', [\App\Http\Controllers\Admin\BookCampController::class, 'update'])->name('benefits.book-camp.update');

    // Benefit -> Blood Donate Routes
    Route::get('/benefits/blood-donate', [\App\Http\Controllers\Admin\BloodDonateController::class, 'edit'])->name('benefits.blood-donate.edit');
    Route::put('/benefits/blood-donate', [\App\Http\Controllers\Admin\BloodDonateController::class, 'update'])->name('benefits.blood-donate.update');

    // Service -> On Demand Service Routes
    Route::get('/services/on-demand-service', [\App\Http\Controllers\Admin\OnDemandServiceController::class, 'edit'])->name('website-services.on-demand-service.edit');
    Route::put('/services/on-demand-service', [\App\Http\Controllers\Admin\OnDemandServiceController::class, 'update'])->name('website-services.on-demand-service.update');

    // Service -> E-Card Routes
    Route::get('/services/e-card', [\App\Http\Controllers\Admin\ECardController::class, 'edit'])->name('website-services.e-card.edit');
    Route::put('/services/e-card', [\App\Http\Controllers\Admin\ECardController::class, 'update'])->name('website-services.e-card.update');

    // Service -> Marketplace Routes
    Route::get('/services/marketplace', [\App\Http\Controllers\Admin\MarketplaceController::class, 'edit'])->name('website-services.marketplace.edit');
    Route::put('/services/marketplace', [\App\Http\Controllers\Admin\MarketplaceController::class, 'update'])->name('website-services.marketplace.update');

    // Service -> City Development Routes
    Route::get('/services/city-development', [\App\Http\Controllers\Admin\CityDevelopmentController::class, 'edit'])->name('website-services.city-development.edit');
    Route::put('/services/city-development', [\App\Http\Controllers\Admin\CityDevelopmentController::class, 'update'])->name('website-services.city-development.update');

    // Service -> Education Routes
    Route::get('/services/education', [\App\Http\Controllers\Admin\EducationController::class, 'edit'])->name('website-services.education.edit');
    Route::put('/services/education', [\App\Http\Controllers\Admin\EducationController::class, 'update'])->name('website-services.education.update');

    // Service -> Real Estate Business Routes
    Route::get('/services/real-estate-business', [\App\Http\Controllers\Admin\RealEstateBusinessController::class, 'edit'])->name('website-services.real-estate-business.edit');
    Route::put('/services/real-estate-business', [\App\Http\Controllers\Admin\RealEstateBusinessController::class, 'update'])->name('website-services.real-estate-business.update');

    // E-Store -> Hotel Routes
    Route::get('/e-store/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'edit'])->name('website-e-store.hotels.edit');
    Route::put('/e-store/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'update'])->name('website-e-store.hotels.update');

    // E-Store -> Hospital Routes
    Route::get('/e-store/hospitals', [\App\Http\Controllers\Admin\HospitalController::class, 'edit'])->name('website-e-store.hospitals.edit');
    Route::put('/e-store/hospitals', [\App\Http\Controllers\Admin\HospitalController::class, 'update'])->name('website-e-store.hospitals.update');

    // E-Store -> Shopping Routes
    Route::get('/e-store/shoppings', [\App\Http\Controllers\Admin\ShoppingController::class, 'edit'])->name('website-e-store.shoppings.edit');
    Route::put('/e-store/shoppings', [\App\Http\Controllers\Admin\ShoppingController::class, 'update'])->name('website-e-store.shoppings.update');

    // Uonly By Apps -> Education Routes
    Route::get('/uonly-by-apps/education', [\App\Http\Controllers\Admin\UonlyByAppsEducationController::class, 'edit'])->name('website-uonly-by-apps.education.edit');
    Route::put('/uonly-by-apps/education', [\App\Http\Controllers\Admin\UonlyByAppsEducationController::class, 'update'])->name('website-uonly-by-apps.education.update');

    // Uonly By Apps -> U-Mart Routes
    Route::get('/uonly-by-apps/u-mart', [\App\Http\Controllers\Admin\UonlyByAppsUMartController::class, 'edit'])->name('website-uonly-by-apps.u-mart.edit');
    Route::put('/uonly-by-apps/u-mart', [\App\Http\Controllers\Admin\UonlyByAppsUMartController::class, 'update'])->name('website-uonly-by-apps.u-mart.update');

    // Uonly By Apps -> U-Admission Routes
    Route::get('/uonly-by-apps/u-admission', [\App\Http\Controllers\Admin\UonlyByAppsUAdmissionController::class, 'edit'])->name('website-uonly-by-apps.u-admission.edit');
    Route::put('/uonly-by-apps/u-admission', [\App\Http\Controllers\Admin\UonlyByAppsUAdmissionController::class, 'update'])->name('website-uonly-by-apps.u-admission.update');

    // Payroll Management Routes
    Route::prefix('payroll')->name('payroll.')->group(function () {
        // Structures
        Route::get('/structures', [\App\Http\Controllers\Admin\PayrollStructureController::class, 'index'])->name('structures.index');
        Route::get('/structures/create', [\App\Http\Controllers\Admin\PayrollStructureController::class, 'create'])->name('structures.create');
        Route::post('/structures', [\App\Http\Controllers\Admin\PayrollStructureController::class, 'store'])->name('structures.store');
        Route::get('/structures/{structure}/edit', [\App\Http\Controllers\Admin\PayrollStructureController::class, 'edit'])->name('structures.edit');
        Route::put('/structures/{structure}', [\App\Http\Controllers\Admin\PayrollStructureController::class, 'update'])->name('structures.update');

        // Monthly Salary Credit
        Route::get('/credits', [\App\Http\Controllers\Admin\SalaryCreditController::class, 'index'])->name('credits.index');
        Route::get('/credits/create', [\App\Http\Controllers\Admin\SalaryCreditController::class, 'create'])->name('credits.create');
        Route::post('/credits', [\App\Http\Controllers\Admin\SalaryCreditController::class, 'store'])->name('credits.store');
    }
    );

    // Website Benefit Management Routes
    Route::resource('website-benefits', \App\Http\Controllers\Admin\WebsiteBenefitController::class);
    Route::post('/website-benefits/{id}/toggle-status', [\App\Http\Controllers\Admin\WebsiteBenefitController::class, 'toggleStatus'])->name('website-benefits.toggle-status');

    // G.D. Scheme User Fund Routes (place BEFORE benefits resource to avoid conflict)
    Route::get('/benefits/gd-scheme-fund', [\App\Http\Controllers\Admin\GDSchemeUserFundController::class, 'index'])->name('benefits.gd-scheme-fund.index');
    Route::get('/benefits/gd-scheme-fund/data', [\App\Http\Controllers\Admin\GDSchemeUserFundController::class, 'data'])->name('benefits.gd-scheme-fund.data');
    Route::post('/benefits/gd-scheme-fund/add', [\App\Http\Controllers\Admin\GDSchemeUserFundController::class, 'store'])->name('benefits.gd-scheme-fund.store');

    // Scheme User Fund Report Routes (also BEFORE benefits resource)
    Route::get('/benefits/scheme-user-fund-report', [\App\Http\Controllers\Admin\GDSchemeUserFundReportController::class, 'index'])->name('benefits.scheme-user-fund-report.index');
    Route::get('/benefits/scheme-user-fund-report/data', [\App\Http\Controllers\Admin\GDSchemeUserFundReportController::class, 'data'])->name('benefits.scheme-user-fund-report.data');

    // Blood Donate Other Points Report Routes (BEFORE benefits resource)
    Route::get('/benefits/blood-donate-other-points-report', [\App\Http\Controllers\Admin\BloodDonateOtherPointsReportController::class, 'index'])->name('benefits.blood-donate-other-points-report.index');
    Route::get('/benefits/blood-donate-other-points-report/data', [\App\Http\Controllers\Admin\BloodDonateOtherPointsReportController::class, 'data'])->name('benefits.blood-donate-other-points-report.data');

    // E-Card Seva Other Points Report Routes (BEFORE benefits resource)
    Route::get('/benefits/ecard-seva-other-points-report', [\App\Http\Controllers\Admin\ECardSevaOtherPointsReportController::class, 'index'])->name('benefits.ecard-seva-other-points-report.index');
    Route::get('/benefits/ecard-seva-other-points-report/data', [\App\Http\Controllers\Admin\ECardSevaOtherPointsReportController::class, 'data'])->name('benefits.ecard-seva-other-points-report.data');

    // Emergency E-Card Seva Other Points Report Routes (BEFORE benefits resource)
    Route::get('/benefits/emergency-ecard-seva-other-points-report', [\App\Http\Controllers\Admin\ECardSevaEmergencyOtherPointsReportController::class, 'index'])->name('benefits.emergency-ecard-seva-other-points-report.index');
    Route::get('/benefits/emergency-ecard-seva-other-points-report/data', [\App\Http\Controllers\Admin\ECardSevaEmergencyOtherPointsReportController::class, 'data'])->name('benefits.emergency-ecard-seva-other-points-report.data');

    // Point Master Routes (BEFORE benefits resource)
    Route::get('/benefits/points-master', [\App\Http\Controllers\Admin\PointMasterController::class, 'index'])->name('benefits.points-master.index');
    Route::get('/benefits/points-master/data', [\App\Http\Controllers\Admin\PointMasterController::class, 'data'])->name('benefits.points-master.data');
    Route::post('/benefits/points-master/add', [\App\Http\Controllers\Admin\PointMasterController::class, 'store'])->name('benefits.points-master.store');
    Route::delete('/benefits/points-master/{id}', [\App\Http\Controllers\Admin\PointMasterController::class, 'destroy'])->name('benefits.points-master.destroy');

    // Benefit Master Routes
    Route::resource('benefits', \App\Http\Controllers\Admin\BenefitController::class);

    // Service Master Routes
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);

    // Helpline Master Routes
    Route::resource('helplines', \App\Http\Controllers\Admin\HelplineController::class);

    // Camp Master Routes
    Route::resource('camps', \App\Http\Controllers\Admin\CampController::class);
    Route::post('/camps/{id}/toggle-status', [\App\Http\Controllers\Admin\CampController::class, 'toggleStatus'])->name('camps.toggle-status');

    // Advertisement Master Routes
    Route::get('/advertisements/data', [\App\Http\Controllers\Admin\AdvertisementController::class, 'data'])->name('advertisements.data');
    Route::resource('advertisements', \App\Http\Controllers\Admin\AdvertisementController::class);
    Route::post('/advertisements/{id}/toggle-status', [\App\Http\Controllers\Admin\AdvertisementController::class, 'toggleStatus'])->name('advertisements.toggle-status');

    // Social Media Master Routes
    Route::get('/social-media/data', [\App\Http\Controllers\Admin\SocialMediaController::class, 'data'])->name('social-media.data');
    Route::resource('social-media', \App\Http\Controllers\Admin\SocialMediaController::class);
    Route::post('/social-media/{id}/toggle-status', [\App\Http\Controllers\Admin\SocialMediaController::class, 'toggleStatus'])->name('social-media.toggle-status');

    // GST Tax Master Routes
    Route::get('/gst-taxes/data', [\App\Http\Controllers\Admin\GstTaxController::class, 'data'])->name('gst-taxes.data');
    Route::resource('gst-taxes', \App\Http\Controllers\Admin\GstTaxController::class);
    Route::post('/gst-taxes/{id}/toggle-status', [\App\Http\Controllers\Admin\GstTaxController::class, 'toggleStatus'])->name('gst-taxes.toggle-status');

    // GST Tax Report Routes
    Route::get('/gst-tax-report', [\App\Http\Controllers\Admin\GstTaxReportController::class, 'index'])->name('gst-tax-report.index');
    Route::get('/gst-tax-report/export/excel', [\App\Http\Controllers\Admin\GstTaxReportController::class, 'export'])->name('gst-tax-report.export');
    Route::get('/gst-tax-report/export/pdf', [\App\Http\Controllers\Admin\GstTaxReportController::class, 'exportPdf'])->name('gst-tax-report.export.pdf');

    // A/R Advertisement Report Routes
    Route::get('/reports/advertisements/approve-reject/data', [\App\Http\Controllers\Admin\ApproveRejectAdvertisementReportController::class, 'data'])->name('reports.advertisements.approve-reject.data');
    Route::get('/reports/advertisements/approve-reject', [\App\Http\Controllers\Admin\ApproveRejectAdvertisementReportController::class, 'index'])->name('reports.advertisements.approve-reject.index');

    // Camp Details Routes
    Route::resource('camp-details', \App\Http\Controllers\Admin\CampDetailController::class);

    // Camp Summary Report Routes
    Route::get('/reports/camp-summary', [\App\Http\Controllers\Admin\CampSummaryReportController::class, 'index'])->name('reports.camp-summary.index');
    Route::get('/reports/camp-summary/cities', [\App\Http\Controllers\Admin\CampSummaryReportController::class, 'getCitiesByCamp'])->name('reports.camp-summary.cities');

    // Book Camp Report Routes
    Route::get('/reports/book-camp', [\App\Http\Controllers\Admin\BookCampReportController::class, 'index'])->name('reports.book-camp.index');

    // Expense Bill Report
    Route::get('/expense-bills/report', [\App\Http\Controllers\Admin\ExpenseBillReportController::class, 'index'])->name('expense-bills.report');

    // Eligible Report Routes
    Route::get('/reports/eligible', [\App\Http\Controllers\Admin\EligibleReportController::class, 'index'])->name('reports.eligible.index');
    Route::get('/reports/eligible/data', [\App\Http\Controllers\Admin\EligibleReportController::class, 'data'])->name('reports.eligible.data');

    // Common Summary Report Routes
    Route::get('/reports/common-summary', [\App\Http\Controllers\Admin\CommonSummaryReportController::class, 'index'])->name('reports.common-summary.index');
    Route::get('/reports/common-summary/data', [\App\Http\Controllers\Admin\CommonSummaryReportController::class, 'data'])->name('reports.common-summary.data');

    // Voucher Details Report Routes
    Route::get('/reports/voucher-details', [\App\Http\Controllers\Admin\VoucherDetailsReportController::class, 'index'])->name('reports.voucher-details.index');
    Route::get('/reports/voucher-details/data', [\App\Http\Controllers\Admin\VoucherDetailsReportController::class, 'data'])->name('reports.voucher-details.data');

    // Commission Summary Report Routes
    Route::get('/reports/commission-summary', [\App\Http\Controllers\Admin\CommissionSummaryReportController::class, 'index'])->name('reports.commission-summary.index');
    Route::get('/reports/commission-summary/data', [\App\Http\Controllers\Admin\CommissionSummaryReportController::class, 'data'])->name('reports.commission-summary.data');
    Route::get('/reports/cashback', [\App\Http\Controllers\Admin\CashBackReportController::class, 'index'])->name('reports.cashback.index');
    Route::get('/reports/cashback/data', [\App\Http\Controllers\Admin\CashBackReportController::class, 'data'])->name('reports.cashback.data');
    Route::get('/reports/user-id-upgrade', [\App\Http\Controllers\Admin\UserIdUpgradeReportController::class, 'index'])->name('reports.user-id-upgrade.index');
    Route::get('/reports/user-id-upgrade/data', [\App\Http\Controllers\Admin\UserIdUpgradeReportController::class, 'data'])->name('reports.user-id-upgrade.data');
    Route::get('/reports/level-commission', [\App\Http\Controllers\Admin\LevelCommissionReportController::class, 'index'])->name('reports.level-commission.index');
    Route::get('/reports/level-commission/data', [\App\Http\Controllers\Admin\LevelCommissionReportController::class, 'data'])->name('reports.level-commission.data');
    // Login History Report
    Route::get('/reports/login-history', [\App\Http\Controllers\Admin\LoginHistoryReportController::class, 'index'])->name('reports.login-history.index');
    Route::get('/reports/login-history/data', [\App\Http\Controllers\Admin\LoginHistoryReportController::class, 'data'])->name('reports.login-history.data');
    // Help & Support Report Routes
    Route::get('/reports/help-support', [\App\Http\Controllers\Admin\HelpSupportReportController::class, 'index'])->name('reports.help-support.index');
    Route::get('/reports/help-support/data', [\App\Http\Controllers\Admin\HelpSupportReportController::class, 'data'])->name('reports.help-support.data');

    // User Upload Reward Report Routes
    Route::get('/reports/user-upload-reward', [\App\Http\Controllers\Admin\UserUploadRewardReportController::class, 'index'])->name('reports.user-upload-reward.index');
    Route::get('/reports/user-upload-reward/data', [\App\Http\Controllers\Admin\UserUploadRewardReportController::class, 'data'])->name('reports.user-upload-reward.data');

    // User Reward Report
    Route::get('/reports/user-reward', [\App\Http\Controllers\Admin\UserRewardReportController::class, 'index'])->name('reports.user-reward.index');
    Route::get('/reports/user-reward/data', [\App\Http\Controllers\Admin\UserRewardReportController::class, 'data'])->name('reports.user-reward.data');

    // Special Features Management Routes
    Route::resource('special-features', \App\Http\Controllers\Admin\SpecialFeaturesController::class);
    Route::post('/special-features/{id}/toggle-status', [\App\Http\Controllers\Admin\SpecialFeaturesController::class, 'toggleStatus'])->name('special-features.toggle-status');

    // Notification Master Routes
    Route::get('/notification-master', [\App\Http\Controllers\Admin\NotificationMasterController::class, 'index'])->name('notification-master.index');
    Route::get('/notification-master/data', [\App\Http\Controllers\Admin\NotificationMasterController::class, 'data'])->name('notification-master.data');
    Route::post('/notification-master', [\App\Http\Controllers\Admin\NotificationMasterController::class, 'store'])->name('notification-master.store');
    Route::delete('/notification-master/{id}', [\App\Http\Controllers\Admin\NotificationMasterController::class, 'destroy'])->name('notification-master.destroy');

    // Vendor Notification Master Routes
    Route::get('/vendor-notification-master', [\App\Http\Controllers\Admin\VendorNotificationMasterController::class, 'index'])->name('vendor.notification-master.index');
    Route::get('/vendor-notification-master/data', [\App\Http\Controllers\Admin\VendorNotificationMasterController::class, 'data'])->name('vendor.notification-master.data');
    Route::post('/vendor-notification-master', [\App\Http\Controllers\Admin\VendorNotificationMasterController::class, 'store'])->name('vendor.notification-master.store');
    Route::delete('/vendor-notification-master/{id}', [\App\Http\Controllers\Admin\VendorNotificationMasterController::class, 'destroy'])->name('vendor.notification-master.destroy');

    // moved into admin group

    // User Utility & Affiliate Link Routes
    Route::get('/user-utility-affiliate-links', [\App\Http\Controllers\Admin\UserUtilityAffiliateLinkController::class, 'index'])->name('user-utility-affiliate-links.index');
    Route::get('/user-utility-affiliate-links/data', [\App\Http\Controllers\Admin\UserUtilityAffiliateLinkController::class, 'data'])->name('user-utility-affiliate-links.data');
    Route::post('/user-utility-affiliate-links', [\App\Http\Controllers\Admin\UserUtilityAffiliateLinkController::class, 'store'])->name('user-utility-affiliate-links.store');
    Route::delete('/user-utility-affiliate-links/{id}', [\App\Http\Controllers\Admin\UserUtilityAffiliateLinkController::class, 'destroy'])->name('user-utility-affiliate-links.destroy');

    // Vendor Utility & Affiliate Link Routes
    Route::get('/vendor-utility-affiliate-links', [\App\Http\Controllers\Admin\VendorUtilityAffiliateLinkController::class, 'index'])->name('vendor.utility-affiliate-links.index');
    Route::get('/vendor-utility-affiliate-links/data', [\App\Http\Controllers\Admin\VendorUtilityAffiliateLinkController::class, 'data'])->name('vendor.utility-affiliate-links.data');
    Route::post('/vendor-utility-affiliate-links', [\App\Http\Controllers\Admin\VendorUtilityAffiliateLinkController::class, 'store'])->name('vendor.utility-affiliate-links.store');
    Route::delete('/vendor-utility-affiliate-links/{id}', [\App\Http\Controllers\Admin\VendorUtilityAffiliateLinkController::class, 'destroy'])->name('vendor.utility-affiliate-links.destroy');

    // View Order Routes
    Route::get('/view-orders', [\App\Http\Controllers\Admin\ViewOrderController::class, 'index'])->name('view-orders.index');
    Route::get('/view-orders/data', [\App\Http\Controllers\Admin\ViewOrderController::class, 'data'])->name('view-orders.data');

    // Vendor View Order Routes
    Route::get('/vendor-view-orders', [\App\Http\Controllers\Admin\VendorViewOrderController::class, 'index'])->name('vendor.view-orders.index');
    Route::get('/vendor-view-orders/data', [\App\Http\Controllers\Admin\VendorViewOrderController::class, 'data'])->name('vendor.view-orders.data');

    // Lead Master Routes
    Route::get('/leads', [\App\Http\Controllers\Admin\LeadController::class, 'index'])->name('leads.index');

    // Payment Gateway Settings (Financial Settings)
    Route::get('/settings/payment-gateways', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::post('/settings/payment-gateways', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
    Route::get('/leads/data', [\App\Http\Controllers\Admin\LeadController::class, 'data'])->name('leads.data');
    Route::get('/leads/create', [\App\Http\Controllers\Admin\LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads', [\App\Http\Controllers\Admin\LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads/{id}/edit', [\App\Http\Controllers\Admin\LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{id}', [\App\Http\Controllers\Admin\LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{id}', [\App\Http\Controllers\Admin\LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/{id}/toggle-status', [\App\Http\Controllers\Admin\LeadController::class, 'toggleStatus'])->name('leads.toggle-status');

    // Vendor Approve KYC Routes
    Route::get('/vendor-approve-kyc', [\App\Http\Controllers\Admin\VendorKycApprovalController::class, 'index'])->name('vendor.approve-kyc.index');
    Route::get('/vendor-approve-kyc/data', [\App\Http\Controllers\Admin\VendorKycApprovalController::class, 'data'])->name('vendor.approve-kyc.data');
    Route::post('/vendor-approve-kyc/{vendor}/approve', [\App\Http\Controllers\Admin\VendorKycApprovalController::class, 'approve'])->name('vendor.approve-kyc.approve');

    // Inhouse Product Category Management Routes
    Route::resource('inhouse-product-categories', \App\Http\Controllers\Admin\InhouseProductCategoryController::class);
    Route::post('/inhouse-product-categories/{id}/toggle-status', [\App\Http\Controllers\Admin\InhouseProductCategoryController::class, 'toggleStatus'])->name('inhouse-product-categories.toggle-status');

    // Inhouse Product Management Routes
    Route::resource('inhouse-products', \App\Http\Controllers\Admin\InhouseProductController::class);
    Route::post('/inhouse-products/{id}/toggle-status', [\App\Http\Controllers\Admin\InhouseProductController::class, 'toggleStatus'])->name('inhouse-products.toggle-status');

    // Stock Management Routes
    Route::get('/product-stock-transactions', [\App\Http\Controllers\Admin\ProductStockController::class, 'index'])->name('product-stock-transactions.index');
    Route::post('/product-stock-transactions', [\App\Http\Controllers\Admin\ProductStockController::class, 'store'])->name('product-stock-transactions.store');
    Route::get('/products/by-category/{categoryId}', [\App\Http\Controllers\Admin\ProductStockController::class, 'productsByCategory'])->name('products.by-category');

    // Stock Transfer Routes
    Route::get('/stock-transfers', [\App\Http\Controllers\Admin\ProductStockTransferController::class, 'index'])->name('stock-transfers.index');
    Route::post('/stock-transfers', [\App\Http\Controllers\Admin\ProductStockTransferController::class, 'store'])->name('stock-transfers.store');

    // Stock Transfer Report Routes
    Route::get('/stock-transfers/report', [\App\Http\Controllers\Admin\ProductStockTransferController::class, 'report'])->name('stock-transfers.report');
    Route::get('/stock-transfers/report/data', [\App\Http\Controllers\Admin\ProductStockTransferController::class, 'reportData'])->name('stock-transfers.report.data');

    // A & R Req. Stock Report
    Route::get('/stock-ar-req/report', [\App\Http\Controllers\Admin\ARStockRequestReportController::class, 'index'])->name('stock-ar-req.report');
    Route::get('/stock-ar-req/report/data', [\App\Http\Controllers\Admin\ARStockRequestReportController::class, 'data'])->name('stock-ar-req.report.data');

    // Points Modules - Admin By User Point Report
    Route::get('/points/admin-user-report', [\App\Http\Controllers\Admin\AdminUserPointsReportController::class, 'index'])->name('points.admin-user-report.index');
    Route::get('/points/admin-user-report/data', [\App\Http\Controllers\Admin\AdminUserPointsReportController::class, 'data'])->name('points.admin-user-report.data');

    // Points Modules - Vendor By User Point Report
    Route::get('/points/vendor-user-report', [\App\Http\Controllers\Admin\VendorUserPointsReportController::class, 'index'])->name('points.vendor-user-report.index');
    Route::get('/points/vendor-user-report/data', [\App\Http\Controllers\Admin\VendorUserPointsReportController::class, 'data'])->name('points.vendor-user-report.data');

    // Redeem Modules - Add Redeem Value
    Route::get('/redeem-values', [\App\Http\Controllers\Admin\RedeemValueController::class, 'index'])->name('redeem-values.index');
    Route::post('/redeem-values', [\App\Http\Controllers\Admin\RedeemValueController::class, 'update'])->name('redeem-values.update');
    Route::get('/redeem-values/history', [RedeemValueHistoryController::class, 'index'])->name('redeem-values.history.index');

    // Redeem Modules - User Redeem Report
    Route::get('/redeem-values/user-redeem-report', [\App\Http\Controllers\Admin\UserRedeemReportController::class, 'index'])->name('redeem-values.user-redeem-report.index');
    Route::get('/redeem-values/user-redeem-report/data', [\App\Http\Controllers\Admin\UserRedeemReportController::class, 'data'])->name('redeem-values.user-redeem-report.data');

    // E-Card Seva Bank Settlement Request Routes
    Route::get('/ecard-seva-bank-settlement-requests', [\App\Http\Controllers\Admin\ECardSevaBankSettlementRequestController::class, 'index'])->name('ecard-seva-bank-settlement-requests.index');
    Route::get('/ecard-seva-bank-settlement-requests/data', [\App\Http\Controllers\Admin\ECardSevaBankSettlementRequestController::class, 'data'])->name('ecard-seva-bank-settlement-requests.data');

    // E-Card Seva A/R Withdrawal Report Routes
    Route::get('/ecard-seva-ar-withdrawal-report', [\App\Http\Controllers\Admin\RetailerWithdrawalRequestController::class, 'index'])->name('ecard-seva-ar-withdrawal-report.index');
    Route::get('/ecard-seva-ar-withdrawal-report/data', [\App\Http\Controllers\Admin\RetailerWithdrawalRequestController::class, 'data'])->name('ecard-seva-ar-withdrawal-report.data');

    Route::get('/ecard-seva-set-permissions', [\App\Http\Controllers\Admin\ECardPermissionController::class, 'index'])->name('ecard-permissions.index');
    Route::post('/ecard-seva-set-permissions/save', [\App\Http\Controllers\Admin\ECardPermissionController::class, 'save'])->name('ecard-permissions.save');
    Route::post('/ecard-seva-set-permissions/sync-modules', [\App\Http\Controllers\Admin\ECardPermissionController::class, 'syncModules'])->name('ecard-permissions.sync');
    Route::get('/ecard-registrations/{id}/permissions', [\App\Http\Controllers\Admin\ECardPermissionController::class, 'userPermissions'])->name('ecard-permissions.user');
    Route::post('/ecard-registrations/{id}/permissions', [\App\Http\Controllers\Admin\ECardPermissionController::class, 'saveUserPermissions'])->name('ecard-permissions.user.save');

    // Retailer/Employee Permission Routes
    Route::get('/retailer-employee-permissions', [\App\Http\Controllers\Admin\RetailerEmployeePermissionController::class, 'index'])->name('retailer-employee-permissions.index');
    Route::get('/retailer-employee-permissions/{role}/permissions', [\App\Http\Controllers\Admin\RetailerEmployeePermissionController::class, 'rolePermissions'])->name('retailer-employee-permissions.role-permissions');
    Route::post('/retailer-employee-permissions/{role}/permissions', [\App\Http\Controllers\Admin\RetailerEmployeePermissionController::class, 'assignPermissions'])->name('retailer-employee-permissions.assign');

    // System Modules - Department Module Comm. Master
    Route::get('/department-commissions', [\App\Http\Controllers\Admin\DepartmentCommissionController::class, 'index'])->name('department-commissions.index');
    Route::post('/department-commissions/{department}', [\App\Http\Controllers\Admin\DepartmentCommissionController::class, 'update'])->name('department-commissions.update');

    // System Modules - First Recharge Plan Master
    Route::resource('first-recharge-plans', \App\Http\Controllers\Admin\FirstRechargePlanController::class)->except(['show']);
    Route::post('/first-recharge-plans/{id}/toggle-status', [\App\Http\Controllers\Admin\FirstRechargePlanController::class, 'toggleStatus'])->name('first-recharge-plans.toggle-status');
    Route::get('/first-recharge-plans/{first_recharge_plan}/commissions', [\App\Http\Controllers\Admin\FirstRechargePlanController::class, 'commissions'])->name('first-recharge-plans.commissions');
    Route::post('/first-recharge-plans/{first_recharge_plan}/commissions', [\App\Http\Controllers\Admin\FirstRechargePlanController::class, 'updateCommissions'])->name('first-recharge-plans.commissions.update');

    // System Modules - Commission TDS Charge Report
    Route::get('/commission-tds-charge-report', [\App\Http\Controllers\Admin\CommissionTdsChargeReportController::class, 'index'])->name('commission-tds-charge-report.index');
    Route::get('/commission-tds-charge-report/export/excel', [\App\Http\Controllers\Admin\CommissionTdsChargeReportController::class, 'export'])->name('commission-tds-charge-report.export');
    Route::get('/commission-tds-charge-report/export/pdf', [\App\Http\Controllers\Admin\CommissionTdsChargeReportController::class, 'exportPdf'])->name('commission-tds-charge-report.export.pdf');

    // Recharge Modules - Recharge Service
    Route::resource('recharge-services', \App\Http\Controllers\Admin\RechargeServiceController::class);
    Route::post('/recharge-services/{id}/toggle-status', [\App\Http\Controllers\Admin\RechargeServiceController::class, 'toggleStatus'])->name('recharge-services.toggle-status');

    // Recharge Modules - Recharge Operator
    Route::resource('recharge-operators', \App\Http\Controllers\Admin\RechargeOperatorController::class);
    Route::post('/recharge-operators/{id}/toggle-status', [\App\Http\Controllers\Admin\RechargeOperatorController::class, 'toggleStatus'])->name('recharge-operators.toggle-status');

    // Recharge Modules - User Commission Rules
    Route::resource('recharge-commissions', \App\Http\Controllers\Admin\RechargeCommissionController::class);
    Route::post('/recharge-commissions/{id}/toggle-status', [\App\Http\Controllers\Admin\RechargeCommissionController::class, 'toggleStatus'])->name('recharge-commissions.toggle-status');

    // Recharge Modules - Recharge Summary Report
    Route::get('/recharge-summary-report', [\App\Http\Controllers\Admin\RechargeSummaryReportController::class, 'index'])->name('recharge-summary-report.index');

    // Recharge Modules - Recharge Report
    Route::get('/recharge-report', [\App\Http\Controllers\Admin\RechargeReportController::class, 'index'])->name('recharge-report.index');

    // Notification Modules - Send Notification CRUD
    Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class);
    Route::post('/notifications/{notification}/send', [\App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');

    // Add more admin routes here
});
