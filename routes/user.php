<?php

use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserDeviceController;
use App\Http\Controllers\User\UserTransactionController;
use App\Http\Controllers\User\WalletPaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserAuthController::class, 'login']);
    });

    Route::middleware('web')->group(function () {
        Route::get('/dashboard', [UserAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [UserAuthController::class, 'profile'])->name('profile');
        Route::get('/manage-profile', [UserAuthController::class, 'manageProfile'])->name('profile.manage');
        Route::post('/manage-profile/update', [UserAuthController::class, 'updateProfile'])->name('profile.update');
        Route::get('/edit-profile', [UserAuthController::class, 'editProfile'])->name('profile.edit');

        Route::get('/my-qr', [UserAuthController::class, 'showMyQr'])->name('qr.show');

        Route::get('/my-wallet', [UserAuthController::class, 'showMyWallet'])->name('wallet.show');
        Route::post('/wallet/initiate', [WalletPaymentController::class, 'initiatePayment'])->name('wallet.initiate');
        Route::match(['GET', 'POST'], '/wallet/callback', [WalletPaymentController::class, 'handleCallback'])->name('wallet.callback');
        Route::post('/wallet/webhook', [WalletPaymentController::class, 'webhook'])->name('wallet.webhook');

        Route::get('/transactions', [UserTransactionController::class, 'index'])->name('manage.transactions');

        Route::get('/device-permission', [UserDeviceController::class, 'index'])->name('manage.device-permission');
        Route::post('/device-permission/update', [UserDeviceController::class, 'update'])->name('manage.device-permission.update');

        Route::get('/manage-login-history', [UserAuthController::class, 'loginHistory'])->name('manage.login-history');

        Route::get('/change-password', [UserAuthController::class, 'showChangePassword'])->name('password.show');
        Route::post('/change-password', [UserAuthController::class, 'changePassword'])->name('password.update');
        Route::get('/security-settings', [UserAuthController::class, 'showSecuritySettings'])->name('security.settings');
        Route::get('/refer-and-earn', [UserAuthController::class, 'referAndEarn'])->name('refer.earn');
        Route::get('/manage-payments', [UserAuthController::class, 'managePayments'])->name('manage.payments');
        Route::get('/login-history', [UserAuthController::class, 'loginHistory'])->name('login.history');
        Route::get('/change-pin-password', [UserAuthController::class, 'changePinPassword'])->name('pin.change.show');
        Route::post('/change-pin-password', [UserAuthController::class, 'changePinPassword'])->name('pin.change');

        Route::get('/language', [UserAuthController::class, 'showLanguageSettings'])->name('language.show');
        Route::post('/language', [UserAuthController::class, 'updateLanguage'])->name('language.update');

        Route::get('/upgrade-id', [UserAuthController::class, 'showUpgradeId'])->name('upgrade.show');
        Route::post('/upgrade-id', [UserAuthController::class, 'submitUpgradeId'])->name('upgrade.submit');

        Route::get('/kyc', [UserAuthController::class, 'showUploadKyc'])->name('kyc.show');
        Route::post('/kyc', [UserAuthController::class, 'uploadKyc'])->name('kyc.upload');

        Route::get('/ecard', [UserAuthController::class, 'showECard'])->name('ecard.show');
        Route::get('/ecard/details', [UserAuthController::class, 'showECardDetails'])->name('ecard.details');
        Route::post('/ecard/otp/send', [UserAuthController::class, 'sendEcardOtp'])->name('ecard.otp.send');
        Route::post('/ecard/otp/verify', [UserAuthController::class, 'verifyEcardOtp'])->name('ecard.otp.verify');
        Route::post('/ecard/update', [UserAuthController::class, 'updateEcardDetails'])->name('ecard.update');

        Route::get('/links', [UserAuthController::class, 'showLinks'])->name('links');

        Route::get('/login-history', [UserAuthController::class, 'loginHistory'])->name('login_history');

        Route::get('/wallet/request', [UserAuthController::class, 'showWalletRequest'])->name('wallet.request.show');
        Route::post('/wallet/request', [UserAuthController::class, 'submitWalletRequest'])->name('wallet.request.submit');
        Route::get('/wallet/transactions', [UserAuthController::class, 'showWalletTransactions'])->name('wallet.transactions');
        Route::get('/wallet/settlement', [UserAuthController::class, 'showBankSettlementRequest'])->name('wallet.settlement.show');
        Route::post('/wallet/settlement', [UserAuthController::class, 'submitBankSettlementRequest'])->name('wallet.settlement.submit');
        Route::get('/wallet/transfer/qr-to-qr', [UserAuthController::class, 'showQrToQrTransfer'])->name('wallet.transfer.qr.show');
        Route::post('/wallet/transfer/qr-to-qr', [UserAuthController::class, 'submitQrToQrTransfer'])->name('wallet.transfer.qr.submit');
        Route::get('/wallet/transfer/user-to-user', [UserAuthController::class, 'showUserToUserTransfer'])->name('wallet.transfer.user.show');
        Route::post('/wallet/transfer/user-to-user', [UserAuthController::class, 'submitUserToUserTransfer'])->name('wallet.transfer.user.submit');

        Route::get('/advertisement', [UserAuthController::class, 'showAdvertisement'])->name('advertisement.show');
        Route::get('/advertisement/report', [UserAuthController::class, 'showAdvertisementReport'])->name('advertisement.report');

        Route::get('/benefit/book-camp', [UserAuthController::class, 'showBookCamp'])->name('benefit.bookcamp.show');
        Route::post('/benefit/book-camp', [UserAuthController::class, 'submitBookCamp'])->name('benefit.bookcamp.submit');
        Route::get('/benefit/book-camp-report', [UserAuthController::class, 'showBookCampReport'])->name('benefit.bookcamp.report');

        Route::get('/benefit/eligible-report', [UserAuthController::class, 'showEligibleReport'])->name('benefit.eligible.report');
        Route::get('/benefit/card/{benefit}', [UserAuthController::class, 'showBenefitCard'])
            ->whereIn('benefit', ['sfd-e-card', 'esewa-e-card', 'epf-e-card', 'benefits-eps', 'benefits-02', 'benefits-01'])
            ->name('benefit.card.show');
        Route::get('/benefit/scheme-fund-report', [UserAuthController::class, 'showSchemeFundReport'])->name('benefit.schemefund.report');

        Route::get('/benefit/e-card-seva-request', [UserAuthController::class, 'showEcardSevaRequest'])->name('benefit.ecard.seva.request.show');
        Route::post('/benefit/e-card-seva-request', [UserAuthController::class, 'submitEcardSevaRequest'])->name('benefit.ecard.seva.request.submit');
        Route::get('/benefit/e-card-seva-self-report', [UserAuthController::class, 'showEcardSevaSelfReport'])->name('benefit.ecard.seva.self.report');
        Route::get('/benefit/e-card-seva-other-details', [UserAuthController::class, 'showEcardSevaOtherDetails'])->name('benefit.ecard.seva.other.details');

        Route::get('/benefit/blood-seva', [UserAuthController::class, 'showBloodSevaDashboard'])->name('benefit.blood.dashboard');
        Route::get('/benefit/blood-my-requests', [UserAuthController::class, 'showBloodMyRequests'])->name('benefit.blood.my.requests');
        Route::post('/benefit/blood-my-requests/{id}/delete', [UserAuthController::class, 'deleteBloodMyRequest'])->name('benefit.blood.my.requests.delete');
        Route::get('/benefit/blood-other-requests', [UserAuthController::class, 'showBloodOtherRequests'])->name('benefit.blood.other.requests');
        Route::post('/benefit/blood-other-requests/{id}/accept', [UserAuthController::class, 'acceptBloodOtherRequest'])->name('benefit.blood.other.requests.accept');
        Route::get('/benefit/blood-my-details', [UserAuthController::class, 'showBloodMyDonateDetails'])->name('benefit.blood.my.details');
        Route::get('/benefit/blood-donate-request', [UserAuthController::class, 'showBloodDonateRequest'])->name('benefit.blood.request.show');
        Route::post('/benefit/blood-donate-request', [UserAuthController::class, 'submitBloodDonateRequest'])->name('benefit.blood.request.submit');
        Route::get('/benefit/blood-donate-self-report', [UserAuthController::class, 'showBloodDonateSelfReport'])->name('benefit.blood.self.report');
        Route::get('/benefit/blood-donate-other-details', [UserAuthController::class, 'showBloodDonateOtherDetails'])->name('benefit.blood.other.details');

        Route::get('/benefit/emergency-seva', [UserAuthController::class, 'showEmergencySevaDashboard'])->name('benefit.emergency.dashboard');
        Route::get('/benefit/emergency-my-requests', [UserAuthController::class, 'showEmergencyMyRequests'])->name('benefit.emergency.my.requests');
        Route::post('/benefit/emergency-my-requests/{id}/delete', [UserAuthController::class, 'deleteEmergencyMyRequest'])->name('benefit.emergency.my.requests.delete');
        Route::get('/benefit/emergency-e-card-seva-request', [UserAuthController::class, 'showEmergencyEcardSevaRequest'])->name('benefit.emergency.ecard.request.show');
        Route::post('/benefit/emergency-e-card-seva-request', [UserAuthController::class, 'submitEmergencyEcardSevaRequest'])->name('benefit.emergency.ecard.request.submit');
        Route::get('/benefit/emergency-e-card-self-report', [UserAuthController::class, 'showEmergencyEcardSelfReport'])->name('benefit.emergency.ecard.self.report');
        Route::get('/benefit/emergency-e-card-other-details', [UserAuthController::class, 'showEmergencyEcardOtherDetails'])->name('benefit.emergency.ecard.other.details');
        Route::post('/benefit/emergency-e-card-other-details', [UserAuthController::class, 'submitEmergencyContactDetails'])->name('benefit.emergency.ecard.other.details.submit');

        Route::get('/benefit/emergency-family-contacts', [UserAuthController::class, 'showEmergencyFamilyContacts'])->name('benefit.emergency.family.contacts');
        Route::post('/benefit/emergency-family-contacts', [UserAuthController::class, 'submitEmergencyFamilyContact'])->name('benefit.emergency.family.contacts.submit');

        Route::get('/service/orders/view', [UserAuthController::class, 'serviceOrdersView'])->name('service.orders.view');

        Route::get('/estore/categories', [UserAuthController::class, 'estoreCategories'])->name('estore.categories');
        Route::get('/estore/categories/{category}', [UserAuthController::class, 'estoreCategoryVendors'])->name('estore.category.vendors');

        Route::get('/service/report/admin-points', [UserAuthController::class, 'serviceReportAdminPoints'])->name('service.report.admin.points');
        Route::get('/service/report/vendor-points', [UserAuthController::class, 'serviceReportVendorPoints'])->name('service.report.vendor.points');
        Route::get('/service/report/coupon-summary', [UserAuthController::class, 'serviceReportCouponSummary'])->name('service.report.coupon.summary');
        Route::get('/service/report/voucher-detail', [UserAuthController::class, 'serviceReportVoucherDetail'])->name('service.report.voucher.detail');
        Route::get('/service/report/global-disbursement-fund', [UserAuthController::class, 'serviceReportGlobalDisbursementFund'])->name('service.report.global.disbursement.fund');
        Route::get('/service/report/physically-challenged-fund', [UserAuthController::class, 'serviceReportPhysicallyChallengedFund'])->name('service.report.physically.challenged.fund');
        Route::get('/service/report/month-wise-user-redeem', [UserAuthController::class, 'serviceReportMonthWiseUserRedeem'])->name('service.report.monthwise.user.redeem');
        Route::get('/service/report/reward', [UserAuthController::class, 'serviceReportReward'])->name('service.report.reward');
        Route::get('/service/report/reward/{reward}', [UserAuthController::class, 'serviceReportRewardShow'])->name('service.report.reward.show');
        Route::post('/service/report/reward/{reward}/redeem', [UserAuthController::class, 'serviceReportRewardRedeem'])->name('service.report.reward.redeem');

        Route::get('/service/recharge/report', [UserAuthController::class, 'serviceRechargeReport'])->name('service.recharge.report');
        Route::get('/service/recharge/utility-link', [UserAuthController::class, 'serviceRechargeUtilityLink'])->name('service.recharge.utility.link');
        Route::get('/service/recharge/dth', [UserAuthController::class, 'serviceRechargeDth'])->name('service.recharge.dth');
        Route::get('/service/recharge/fastag', [UserAuthController::class, 'serviceRechargeFastag'])->name('service.recharge.fastag');
        Route::get('/service/recharge/bbps', [UserAuthController::class, 'serviceRechargeBbps'])->name('service.recharge.bbps');
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

        Route::get('/service/recharge/mobile', [UserAuthController::class, 'serviceRechargeMobile'])->name('service.recharge.mobile');
        Route::post('/service/recharge/fetch-operator', [UserAuthController::class, 'fetchMobileOperator'])->name('service.recharge.fetch-operator');
        Route::post('/service/recharge/fetch-plans', [UserAuthController::class, 'fetchPlans'])->name('service.recharge.fetch-plans');
        Route::post('/service/recharge/fetch-dth-plans', [UserAuthController::class, 'fetchDthPlans'])->name('service.recharge.fetch-dth-plans');
        Route::match(['get', 'post'], '/service/recharge/confirm', [UserAuthController::class, 'rechargeConfirm'])->name('service.recharge.confirm');

        Route::post('/service/recharge/create-order', [\App\Http\Controllers\User\UserRechargeController::class, 'createOrder'])->name('service.recharge.create-order');
        Route::post('/service/recharge/process', [\App\Http\Controllers\User\UserRechargeController::class, 'processRecharge'])->name('service.recharge.process');
    });
});

