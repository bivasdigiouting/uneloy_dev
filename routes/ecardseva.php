<?php

use App\Http\Controllers\ECardAuthController;
use App\Http\Controllers\ECardDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('ecard')->name('ecard.')->group(function () {
    Route::middleware('guest:ecard')->group(function () {
        Route::get('/login', [ECardAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [ECardAuthController::class, 'login']);
        Route::get('/login/otp', [ECardAuthController::class, 'showOtpForm'])->name('login.otp');
        Route::post('/login/otp', [ECardAuthController::class, 'verifyOtp'])->name('login.otp.verify');
        Route::post('/login/otp/resend', [ECardAuthController::class, 'resendOtp'])->name('login.otp.resend');
    });

    Route::any('/registration/payment/callback', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'handlePaymentCallback'])->name('registration.payment.callback');

    Route::middleware(['auth:ecard'])->group(function () {
        Route::get('/sales/{id}/qr-pay', [\App\Http\Controllers\ECard\SaleController::class, 'qrPay'])->name('sales.qr-pay');
        Route::post('/sales/{id}/qr-pay', [\App\Http\Controllers\ECard\SaleController::class, 'qrPayProcess'])->name('sales.qr-pay.process');
    });

    Route::middleware(['auth:ecard', 'ecard.permissions'])->group(function () {
        Route::get('/dashboard', [ECardDashboardController::class, 'index'])->name('dashboard');

        Route::post('/theme/save', [ECardDashboardController::class, 'saveTheme'])->name('theme.save');

        Route::get('/emergency-desk', [\App\Http\Controllers\ECard\EmergencyController::class, 'index'])->name('emergency.index');
        Route::get('/blood-support', [\App\Http\Controllers\ECard\BloodSupportController::class, 'index'])->name('blood.index');
        Route::get('/digital-wallet', [\App\Http\Controllers\ECard\DigitalWalletController::class, 'index'])->name('digital-wallet.index');

        // Recharge & Bills (E-Card Seva)
        Route::prefix('recharge')->name('recharge.')->group(function () {
            Route::get('/mobile', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'mobileIndex'])->name('mobile');
            Route::get('/dth', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'dthIndex'])->name('dth');
            Route::get('/fastag', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'fastagIndex'])->name('fastag');
            Route::get('/bbps', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'bbpsIndex'])->name('bbps');

            Route::match(['get','post'], '/confirm', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'rechargeConfirm'])->name('confirm');

            Route::post('/fetch-operator', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'fetchMobileOperator'])->name('fetch-operator');
            Route::post('/fetch-plans', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'fetchPlans'])->name('fetch-plans');
            Route::post('/fetch-dth-plans', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'fetchDthPlans'])->name('fetch-dth-plans');

            Route::post('/create-order', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'createOrder'])->name('create-order');
            Route::post('/process', [\App\Http\Controllers\ECard\ECardSevaRechargeController::class, 'processRecharge'])->name('process');
        });


        Route::get('/registration/create', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'create'])->name('registration.create');
        Route::post('/registration', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'store'])->name('registration.store');
        Route::post('/registration/verify-aadhaar', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'verifyAadhaar'])->name('registration.verify-aadhaar');
        Route::post('/registration/verify-aadhaar-document', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'verifyAadhaarDocument'])->name('registration.verify-aadhaar-document');

        Route::get('/registration/payment/{id}', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'paymentSelection'])->name('registration.payment');
        Route::post('/registration/payment/{id}/wallet', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'processWalletPayment'])->name('registration.payment.wallet');
Route::post('/registration/payment/{id}/gateway', [\App\Http\Controllers\ECardPortalRegistrationController::class, 'processGatewayPayment'])->name('registration.payment.gateway');

        Route::get('/users/my', [\App\Http\Controllers\ECardPortalUserController::class, 'index'])->name('users.my');
        Route::put('/users/my/{id}', [\App\Http\Controllers\ECardPortalUserController::class, 'update'])->name('users.my.update');
        Route::post('/users/my/{id}/status', [\App\Http\Controllers\ECardPortalUserController::class, 'updateStatus'])->name('users.my.status');

        Route::get('/kyc', [\App\Http\Controllers\ECardPortalKycController::class, 'index'])->name('kyc.index');
        Route::post('/kyc', [\App\Http\Controllers\ECardPortalKycController::class, 'store'])->name('kyc.store');
        Route::delete('/kyc/{field}', [\App\Http\Controllers\ECardPortalKycController::class, 'destroy'])->name('kyc.destroy');

        Route::get('/kyc/approve', [\App\Http\Controllers\ECardPortalApproveKycDocumentController::class, 'index'])->name('kyc.approve.index');
        Route::get('/kyc/approve/data', [\App\Http\Controllers\ECardPortalApproveKycDocumentController::class, 'data'])->name('kyc.approve.data');

        Route::get('/users/report', [\App\Http\Controllers\ECardPortalEcardReportController::class, 'index'])->name('users.report.index');
        Route::get('/users/report/data', [\App\Http\Controllers\ECardPortalEcardReportController::class, 'data'])->name('users.report.data');
        Route::get('/users/report/{id}/ecard', [\App\Http\Controllers\ECardPortalEcardReportController::class, 'printEcard'])->name('users.report.print');
        Route::get('/users/report/ecard/bulk', [\App\Http\Controllers\ECardPortalEcardReportController::class, 'printEcardBulk'])->name('users.report.print.bulk');

        Route::get('/locations/districts', [\App\Http\Controllers\ECardPortalEcardReportController::class, 'districts'])->name('locations.districts');
        Route::get('/locations/cities', [\App\Http\Controllers\ECardPortalEcardReportController::class, 'cities'])->name('locations.cities');

        Route::get('/upgrade', [\App\Http\Controllers\ECardPortalUpgradeController::class, 'index'])->name('upgrade.index');
        Route::post('/upgrade', [\App\Http\Controllers\ECardPortalUpgradeController::class, 'store'])->name('upgrade.store');
        Route::get('/upgrade/report', [\App\Http\Controllers\ECardPortalUpgradeController::class, 'reportIndex'])->name('upgrade.report.index');
        Route::get('/upgrade/report/data', [\App\Http\Controllers\ECardPortalUpgradeController::class, 'reportData'])->name('upgrade.report.data');

        Route::get('/wallet/request', [\App\Http\Controllers\ECardPortalWalletController::class, 'requestIndex'])->name('wallet.request.index');
        Route::post('/wallet/request', [\App\Http\Controllers\ECardPortalWalletController::class, 'requestStore'])->name('wallet.request.store');
        Route::get('/wallet/settlement', [\App\Http\Controllers\ECardPortalWalletController::class, 'settlementIndex'])->name('wallet.settlement.index');
        Route::post('/wallet/settlement', [\App\Http\Controllers\ECardPortalWalletController::class, 'settlementStore'])->name('wallet.settlement.store');
        Route::get('/wallet/transactions', [\App\Http\Controllers\ECardPortalWalletController::class, 'transactionsIndex'])->name('wallet.transactions.index');
        Route::get('/wallet/transactions/data', [\App\Http\Controllers\ECardPortalWalletController::class, 'transactionsData'])->name('wallet.transactions.data');

        Route::get('/product/stock-request', [\App\Http\Controllers\ECardPortalProductController::class, 'stockRequestIndex'])->name('product.stock.request.index');
        Route::post('/product/stock-request', [\App\Http\Controllers\ECardPortalProductController::class, 'stockRequestStore'])->name('product.stock.request.store');
        Route::get('/product/ar-stock-request', [\App\Http\Controllers\ECardPortalProductController::class, 'arStockRequestIndex'])->name('product.ar.stock.request.index');
        Route::post('/product/ar-stock-request', [\App\Http\Controllers\ECardPortalProductController::class, 'arStockRequestStore'])->name('product.ar.stock.request.store');
        Route::get('/product/ar-stock-report', [\App\Http\Controllers\ECardPortalProductController::class, 'arStockReportIndex'])->name('product.ar.stock.report.index');
        Route::get('/product/ar-stock-report/data', [\App\Http\Controllers\ECardPortalProductController::class, 'arStockReportData'])->name('product.ar.stock.report.data');
        Route::get('/product/stock-report', [\App\Http\Controllers\ECardPortalProductController::class, 'stockReportIndex'])->name('product.stock.report.index');
        Route::get('/product/stock-report/data', [\App\Http\Controllers\ECardPortalProductController::class, 'stockReportData'])->name('product.stock.report.data');

        // Products List
        Route::get('/product/list', [\App\Http\Controllers\ECard\ProductController::class, 'index'])->name('products.index');

        // Sales Module
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ECard\SaleController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\ECard\SaleController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\ECard\SaleController::class, 'store'])->name('store');
            Route::get('/{id}/payment', [\App\Http\Controllers\ECard\SaleController::class, 'paymentSelection'])->name('payment');
            Route::post('/{id}/payment/process', [\App\Http\Controllers\ECard\SaleController::class, 'processPayment'])->name('payment.process');
            Route::any('/{id}/payment/callback', [\App\Http\Controllers\ECard\SaleController::class, 'handlePaymentCallback'])->name('payment.callback');
            Route::get('/{id}', [\App\Http\Controllers\ECard\SaleController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\ECard\SaleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\ECard\SaleController::class, 'update'])->name('update');
            Route::get('/{id}/invoice', [\App\Http\Controllers\ECard\SaleController::class, 'invoice'])->name('invoice');
        });

        Route::get('/report/level-commission', [\App\Http\Controllers\ECardPortalReportController::class, 'levelCommissionIndex'])->name('report.level-commission.index');
        Route::get('/report/level-commission/data', [\App\Http\Controllers\ECardPortalReportController::class, 'levelCommissionData'])->name('report.level-commission.data');
        Route::get('/report/registration-commission', [\App\Http\Controllers\ECardPortalReportController::class, 'registrationCommissionIndex'])->name('report.registration-commission.index');
        Route::get('/report/registration-commission/data', [\App\Http\Controllers\ECardPortalReportController::class, 'registrationCommissionData'])->name('report.registration-commission.data');
        Route::get('/report/wallet-commission', [\App\Http\Controllers\ECardPortalReportController::class, 'walletCommissionIndex'])->name('report.wallet-commission.index');
        Route::get('/report/wallet-commission/data', [\App\Http\Controllers\ECardPortalReportController::class, 'walletCommissionData'])->name('report.wallet-commission.data');
        Route::get('/report/purchase-commission', [\App\Http\Controllers\ECardPortalReportController::class, 'purchaseCommissionIndex'])->name('report.purchase-commission.index');
        Route::get('/report/purchase-commission/data', [\App\Http\Controllers\ECardPortalReportController::class, 'purchaseCommissionData'])->name('report.purchase-commission.data');
        Route::get('/report/eps-commission', [\App\Http\Controllers\ECardPortalReportController::class, 'epsCommissionIndex'])->name('report.eps-commission.index');
        Route::get('/report/eps-commission/data', [\App\Http\Controllers\ECardPortalReportController::class, 'epsCommissionData'])->name('report.eps-commission.data');
        Route::get('/report/login-history', [\App\Http\Controllers\ECardPortalReportController::class, 'loginHistoryIndex'])->name('report.login-history.index');
        Route::get('/report/login-history/data', [\App\Http\Controllers\ECardPortalReportController::class, 'loginHistoryData'])->name('report.login-history.data');

        Route::get('/report/tds-report', [\App\Http\Controllers\ECardPortalReportController::class, 'tdsReportIndex'])->name('report.tds-report.index');
        Route::get('/report/tds-report/data', [\App\Http\Controllers\ECardPortalReportController::class, 'tdsReportData'])->name('report.tds-report.data');

        Route::prefix('advertisement')->name('advertisement.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ECardAdvertisementController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\ECardAdvertisementController::class, 'store'])->name('store');
            Route::get('/report', [\App\Http\Controllers\ECardAdvertisementController::class, 'reportIndex'])->name('report.index');
        });

        Route::prefix('benefit')->name('benefit.')->group(function () {
            Route::get('/scheme-fund/report', [\App\Http\Controllers\ECardBenefitController::class, 'schemeFundReport'])->name('schemefund.report');
            Route::get('/book-camp', [\App\Http\Controllers\ECardBenefitController::class, 'bookCampIndex'])->name('bookcamp.index');
            Route::post('/book-camp', [\App\Http\Controllers\ECardBenefitController::class, 'bookCampStore'])->name('bookcamp.store');
            Route::get('/book-camp/report', [\App\Http\Controllers\ECardBenefitController::class, 'bookCampReport'])->name('bookcamp.report');
            Route::get('/ecard-seva/request', [\App\Http\Controllers\ECardBenefitController::class, 'ecardSevaRequestIndex'])->name('ecardseva.request.index');
            Route::post('/ecard-seva/request', [\App\Http\Controllers\ECardBenefitController::class, 'ecardSevaRequestStore'])->name('ecardseva.request.store');
            Route::get('/ecs/self/report', [\App\Http\Controllers\ECardBenefitController::class, 'ecsSelfReport'])->name('ecs.self.report');
            Route::get('/ecs/other/details', [\App\Http\Controllers\ECardBenefitController::class, 'ecsOtherRequestDetails'])->name('ecs.other.details');
            Route::get('/blood-donate/request', [\App\Http\Controllers\ECardBenefitController::class, 'bloodDonateRequestIndex'])->name('blooddonate.request.index');
            Route::post('/blood-donate/request', [\App\Http\Controllers\ECardBenefitController::class, 'bloodDonateRequestStore'])->name('blooddonate.request.store');
            Route::get('/blood-donate/self/report', [\App\Http\Controllers\ECardBenefitController::class, 'bloodDonateSelfReport'])->name('bd.self.report');
            Route::get('/blood-donate/other/details', [\App\Http\Controllers\ECardBenefitController::class, 'bloodDonateOtherRequestDetails'])->name('bd.other.details');
            Route::get('/emergency/ecs/request', [\App\Http\Controllers\ECardBenefitController::class, 'emergencyEcsRequestIndex'])->name('emergency.ecs.request.index');
            Route::post('/emergency/ecs/request', [\App\Http\Controllers\ECardBenefitController::class, 'emergencyEcsRequestStore'])->name('emergency.ecs.request.store');
            Route::get('/emergency/ecs/report', [\App\Http\Controllers\ECardBenefitController::class, 'emergencyEcsRequestReport'])->name('emergency.ecs.report');
            Route::get('/emergency/eco/details', [\App\Http\Controllers\ECardBenefitController::class, 'emergencyEcoRequestDetails'])->name('emergency.eco.details');
        });

        Route::get('/profile', [\App\Http\Controllers\ECardProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/avatar', [\App\Http\Controllers\ECardProfileController::class, 'storeAvatar'])->name('profile.avatar.store');

        Route::post('/logout', [ECardAuthController::class, 'logout'])->name('logout');
    });
});

