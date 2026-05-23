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
    Route::post('/login', [AuthController::class, 'login']);

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

        // Registration Route
        Route::get('/registration/list', [RegistrationController::class, 'index']);
        Route::post('/registration/create', [RegistrationController::class, 'store']);
    });
});
