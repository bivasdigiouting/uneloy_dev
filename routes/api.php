<?php

use App\Http\Controllers\Api\ECard\AuthController;
use App\Http\Controllers\Api\ECard\MpinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ECard\VerificationController;
use App\Http\Controllers\Api\ECard\ProfileController;
use App\Http\Controllers\Api\ECard\QrCodeController;
use App\Http\Controllers\Api\ECard\WalletController;
use App\Http\Controllers\Api\ECard\RegistrationController;

use App\Http\Controllers\Api\Vendor\VendorAuthApiController;
use App\Http\Controllers\Api\Vendor\VendorProfileApiController;
use App\Http\Controllers\Api\Vendor\VendorProductsApiController;
use App\Http\Controllers\Api\Vendor\VendorBillingApiController;
use App\Http\Controllers\Api\Vendor\VendorStaffApiController;
use App\Http\Controllers\Api\Vendor\VendorPayrollApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Vendor API Routes (for Vendor app)
Route::prefix('vendor')->group(function () {

    // Public: login + otp verify
    Route::post('/login', [VendorAuthApiController::class, 'login']);
    Route::post('/login/otp/verify', [VendorAuthApiController::class, 'verifyOtp']);

    // Logout (requires auth)
    Route::middleware('auth:sanctum')->post('/logout', [VendorAuthApiController::class, 'logout']);

    // Protected APIs
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [VendorProfileApiController::class, 'dashboard']);

        // Available stock summary for vendor portal
        Route::get('/inventory/summary', [\App\Http\Controllers\Api\Vendor\VendorInventoryApiController::class, 'summary']);

        // Inventory stock list (Vendor Portal -> Inventory tab)
        Route::get('/inventory/stock-list', [\App\Http\Controllers\Api\Vendor\VendorInventoryApiController::class, 'stockList']);


        // Vendor New Members (Today/Monthly/Yearly/Selected date)
        Route::get('/new-members/report', [\App\Http\Controllers\Api\Vendor\VendorNewMembersController::class, 'report']);
        Route::get('/new-members/summary', [\App\Http\Controllers\Api\Vendor\VendorNewMembersController::class, 'summary']);
        Route::get('/new-members/list', [\App\Http\Controllers\Api\Vendor\VendorNewMembersController::class, 'list']);

        Route::get('/profile', [VendorProfileApiController::class, 'profile']);

        Route::post('/profile', [VendorProfileApiController::class, 'updateProfile']);
        Route::post('/change-password', [VendorProfileApiController::class, 'changePassword']);

        Route::get('/products', [VendorProductsApiController::class, 'index']);
        Route::post('/products', [VendorProductsApiController::class, 'store']);
        Route::delete('/products/{id}', [VendorProductsApiController::class, 'destroy']);

        Route::post('/billing/pay', [VendorBillingApiController::class, 'pay']);

        // New bill tab (search customer + fetch purchased products to generate bill)
        Route::get('/billing/new-bill/customers/search', [\App\Http\Controllers\Api\Vendor\VendorNewBillTabApiController::class, 'searchCustomers']);
        Route::get('/billing/new-bill/purchased-products', [\App\Http\Controllers\Api\Vendor\VendorNewBillTabApiController::class, 'purchasedProducts']);

        // Transactions + statements

        Route::get('/transactions', [\App\Http\Controllers\Api\Vendor\VendorTransactionsApiController::class, 'list']);
        Route::get('/transactions/{id}', [\App\Http\Controllers\Api\Vendor\VendorTransactionsApiController::class, 'details']);
        Route::get('/statements', [\App\Http\Controllers\Api\Vendor\VendorTransactionsApiController::class, 'statements']);

        // Earnings trend + breakdown
        Route::get('/earnings/daily-trend', [\App\Http\Controllers\Api\Vendor\VendorEarningsTrendApiController::class, 'dailyTrend']);
        Route::get('/earnings/breakdown', [\App\Http\Controllers\Api\Vendor\VendorEarningsTrendApiController::class, 'breakdown']);

        // Staff
        Route::get('/staff', [VendorStaffApiController::class, 'index']);
        Route::post('/staff', [VendorStaffApiController::class, 'store']);
        Route::get('/staff/report', [\App\Http\Controllers\Api\Vendor\VendorStaffReportApiController::class, 'staffReport']);
        Route::get('/staff/sales', [\App\Http\Controllers\Api\Vendor\VendorStaffReportApiController::class, 'salesByStaff']);

        // Report Analytics Tab (Daily/Monthly/Yearly + Inventory)
        Route::get('/reports/analytics/daily', [\App\Http\Controllers\Api\Vendor\VendorReportAnalyticsApiController::class, 'daily']);
        Route::get('/reports/analytics/monthly', [\App\Http\Controllers\Api\Vendor\VendorReportAnalyticsApiController::class, 'monthly']);
        Route::get('/reports/analytics/yearly', [\App\Http\Controllers\Api\Vendor\VendorReportAnalyticsApiController::class, 'yearly']);
        Route::get('/reports/analytics/inventory', [\App\Http\Controllers\Api\Vendor\VendorReportAnalyticsApiController::class, 'inventory']);

        // Stock + alerts + charts
        Route::get('/stock/remaining', [\App\Http\Controllers\Api\Vendor\VendorStockAlertsApiController::class, 'remaining']);
        Route::get('/stock/reminders', [\App\Http\Controllers\Api\Vendor\VendorStockAlertsApiController::class, 'reminders']);
        Route::get('/stock/restock-alerts', [\App\Http\Controllers\Api\Vendor\VendorStockAlertsApiController::class, 'restockAlerts']);
        Route::get('/stock/quantity-breakdown', [\App\Http\Controllers\Api\Vendor\VendorStockAlertsApiController::class, 'quantityBreakdown']);


        // Vendor Sales dashboard (today/monthly/yearly/custom)
        Route::get('/sales/summary', [\App\Http\Controllers\Api\Vendor\VendorSalesApiController::class, 'summary']);
        Route::get('/sales/breakdown', [\App\Http\Controllers\Api\Vendor\VendorSalesApiController::class, 'breakdown']);
        Route::get('/sales/top-products', [\App\Http\Controllers\Api\Vendor\VendorSalesApiController::class, 'topProducts']);


        Route::get('/payroll', [VendorPayrollApiController::class, 'index']);
        Route::post('/payroll/process', [VendorPayrollApiController::class, 'process']);
    });
});


// ECard API Routes
Route::prefix('ecard')->group(function () {

    // CSRF mismatch fix: login endpoint is stateless and should not depend on cookie-based CSRF.
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['web']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/mpin/update', [MpinController::class, 'update']);
        Route::post('/mpin/verify', [MpinController::class, 'verify']);

        // Verification Routes
        Route::post('/verification/mobile/send', [VerificationController::class, 'sendMobileOtp']);
        Route::post('/verification/mobile/verify', [VerificationController::class, 'verifyMobileOtp']);
        Route::post('/verification/email/send', [VerificationController::class, 'sendEmailOtp']);
        Route::post('/verification/email/verify', [VerificationController::class, 'verifyEmailOtp']);

        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'getProfile']);
        Route::post('/profile/update', [ProfileController::class, 'updateProfile']);

        // Master Data Routes
        Route::get('/states', [ProfileController::class, 'getStates']);
        Route::get('/districts/{state_id}', [ProfileController::class, 'getDistricts']);
        Route::get('/cities/{district_id}', [ProfileController::class, 'getCities']);

        // QR Code Routes
        Route::get('/qrcode/generate', [QrCodeController::class, 'generate']);
        Route::get('/qrcode/view', [QrCodeController::class, 'view']);

        // Wallet Routes
        Route::get('/wallet/balance', [WalletController::class, 'getBalance']);
        Route::get('/wallet/transactions', [WalletController::class, 'getTransactions']);
        Route::post('/wallet/add-money', [WalletController::class, 'addMoney']);
        Route::any('/wallet/verify-payment', [WalletController::class, 'verifyPayment'])->name('api.ecard.wallet.verify');

        // Pay Tab (Transfers + History + Graph)
        Route::get('/pay/balance', [\App\Http\Controllers\Api\ECard\PayTabController::class, 'balance']);
        Route::post('/pay/qr', [\App\Http\Controllers\Api\ECard\PayTabController::class, 'payWithQr']);
        Route::post('/pay/transfer', [\App\Http\Controllers\Api\ECard\PayTabController::class, 'transfer']);
        Route::get('/pay/transactions', [\App\Http\Controllers\Api\ECard\PayTabController::class, 'transactions']);
        Route::get('/pay/outflow/monthly-breakdown', [\App\Http\Controllers\Api\ECard\PayTabController::class, 'monthlyOutflow']);

        // Profile details (documents + activity + device permissions)
        Route::get('/profile/documents', [\App\Http\Controllers\Api\ECard\ProfileDetailController::class, 'documents']);
        Route::get('/profile/activity', [\App\Http\Controllers\Api\ECard\ProfileDetailController::class, 'activity']);
        Route::get('/device-permission', [\App\Http\Controllers\Api\ECard\ProfileDetailController::class, 'devicePermission']);
        Route::post('/device-permission', [\App\Http\Controllers\Api\ECard\ProfileDetailController::class, 'setDevicePermission']);

        // Transactions settings (payment enable/disable + shares breakdown) - best-effort
        Route::get('/transactions/settings', [\App\Http\Controllers\Api\ECard\TransactionsSettingsController::class, 'settings']);
        Route::post('/transactions/settings', [\App\Http\Controllers\Api\ECard\TransactionsSettingsController::class, 'updateSettings']);
        Route::get('/transactions/payment-shares', [\App\Http\Controllers\Api\ECard\TransactionsSettingsController::class, 'paymentShares']);

        // E-Card Seva: Sell/Buy Points breakdown
        Route::get('/seva-points/sell-buy/breakdown', [\App\Http\Controllers\Api\ECard\EcardSevaSellBuyPointsController::class, 'breakdown']);
        Route::get('/seva-points/sell-buy/list', [\App\Http\Controllers\Api\ECard\EcardSevaSellBuyPointsController::class, 'list']);





        // E-Card Seva Users (list/verified/unverified verification)

        Route::get('/ecard-seva-users', [\App\Http\Controllers\Api\ECard\SevaUsersController::class, 'listUsers']);
        Route::get('/ecard-seva-users/verified-details', [\App\Http\Controllers\Api\ECard\SevaUsersController::class, 'verifiedDetails']);
        Route::post('/ecard-seva-users/complete-verification', [\App\Http\Controllers\Api\ECard\SevaUsersController::class, 'completeVerification']);


        // E-Card Seva Income (Income page)
        Route::get('/seva-income/summary', [\App\Http\Controllers\Api\ECard\SevaIncomeController::class, 'summary']);
        Route::get('/seva-income/graph', [\App\Http\Controllers\Api\ECard\SevaIncomeController::class, 'graph']);
        Route::get('/seva-income/list', [\App\Http\Controllers\Api\ECard\SevaIncomeController::class, 'list']);
        Route::get('/seva-income/sources', [\App\Http\Controllers\Api\ECard\SevaIncomeController::class, 'sources']);

        // E-Card Seva Members (Members page)
        Route::get('/seva-members/report', [\App\Http\Controllers\Api\ECard\SevaMembersController::class, 'report']);
        Route::get('/seva-members/summary', [\App\Http\Controllers\Api\ECard\SevaMembersController::class, 'summary']);
        Route::get('/seva-members/list', [\App\Http\Controllers\Api\ECard\SevaMembersController::class, 'list']);

        // Registration Route
        Route::get('/registration/list', [RegistrationController::class, 'index']);
        Route::post('/registration/create', [RegistrationController::class, 'store']);

        // Recharge APIs (Mobile/DTH/Fastag/BBPS)
        Route::prefix('recharge')->group(function () {
            // Mobile Recharge
            Route::get('/mobile/operators', [\App\Http\Controllers\Api\V1\MobileRechargeController::class, 'operators']);
            Route::post('/mobile/initiate', [\App\Http\Controllers\Api\V1\MobileRechargeController::class, 'initiate']);
            Route::get('/mobile/status/{transaction_id}', [\App\Http\Controllers\Api\V1\MobileRechargeController::class, 'status']);

            // DTH Recharge
            Route::get('/dth/operators', [\App\Http\Controllers\Api\V1\DthRechargeController::class, 'operators']);
            Route::post('/dth/initiate', [\App\Http\Controllers\Api\V1\DthRechargeController::class, 'initiate']);
            Route::get('/dth/status/{transaction_id}', [\App\Http\Controllers\Api\V1\DthRechargeController::class, 'status']);

            // FASTag Recharge
            Route::get('/fastag/operators', [\App\Http\Controllers\Api\V1\FastagRechargeController::class, 'operators']);
            Route::post('/fastag/initiate', [\App\Http\Controllers\Api\V1\FastagRechargeController::class, 'initiate']);
            Route::get('/fastag/status/{transaction_id}', [\App\Http\Controllers\Api\V1\FastagRechargeController::class, 'status']);

            // BBPS Recharge
            Route::get('/bbps/billers', [\App\Http\Controllers\Api\V1\BbpsController::class, 'billers']);
            Route::post('/bbps/pay', [\App\Http\Controllers\Api\V1\BbpsController::class, 'pay']);
            Route::get('/bbps/status/{transaction_id}', [\App\Http\Controllers\Api\V1\BbpsController::class, 'status']);
        });

        // Blood Support APIs (E-Card Seva)
        Route::prefix('blood-support')->group(function () {
            Route::get('/donors/search', [\App\Http\Controllers\Api\ECard\BloodSupportController::class, 'searchDonors']);
            Route::get('/donors/{id}', [\App\Http\Controllers\Api\ECard\BloodSupportController::class, 'getDonorDetails']);
            Route::post('/donors/{id}/request', [\App\Http\Controllers\Api\ECard\BloodSupportController::class, 'requestDonor']);
            Route::post('/donors/{id}/message', [\App\Http\Controllers\Api\ECard\BloodSupportController::class, 'sendDonorMessage']);

            // Urgent blood help request
            Route::post('/urgent', [\App\Http\Controllers\Api\ECard\BloodSupportController::class, 'requestUrgentBlood']);
        });

        Route::prefix('blood-emergency-support')->group(function () {
            Route::get('/requests', [\App\Http\Controllers\Api\ECard\BloodEmergencySupportController::class, 'listRequests']);
        });

    });
});





