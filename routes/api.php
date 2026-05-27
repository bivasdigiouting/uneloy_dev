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





