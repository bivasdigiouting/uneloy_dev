<?php

use App\Http\Controllers\Vendor\VendorAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [VendorAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [VendorAuthController::class, 'login']);
        Route::get('/login/otp', [VendorAuthController::class, 'showOtpForm'])->name('login.otp');
        Route::post('/login/otp', [VendorAuthController::class, 'verifyOtp'])->name('login.otp.verify');
        Route::post('/login/otp/resend', [VendorAuthController::class, 'resendOtp'])->name('login.otp.resend');
    });

    Route::middleware('web')->group(function () {
        Route::get('/dashboard', [VendorAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [VendorAuthController::class, 'profile'])->name('profile');
        Route::post('/profile', [VendorAuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('/change-password', [VendorAuthController::class, 'changePassword'])->name('password.change');
        Route::post('/logout', [VendorAuthController::class, 'logout'])->name('logout');

        Route::get('/billing', [VendorAuthController::class, 'page'])->defaults('page', 'billing')->name('billing');
        Route::get('/products', [\App\Http\Controllers\Vendor\VendorProductController::class, 'index'])->name('products');
        Route::post('/products', [\App\Http\Controllers\Vendor\VendorProductController::class, 'store'])->name('products.store');
        Route::delete('/products/{id}', [\App\Http\Controllers\Vendor\VendorProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/inventory', [VendorAuthController::class, 'page'])->defaults('page', 'inventory')->name('inventory');
        Route::get('/payments', [VendorAuthController::class, 'page'])->defaults('page', 'payments')->name('payments');
        Route::get('/ads', [VendorAuthController::class, 'page'])->defaults('page', 'ads')->name('ads');
        Route::get('/camping', [VendorAuthController::class, 'page'])->defaults('page', 'camping')->name('camping');
        Route::get('/settlements', [VendorAuthController::class, 'page'])->defaults('page', 'settlements')->name('settlements');
        Route::get('/staff', [VendorAuthController::class, 'page'])->defaults('page', 'staff')->name('staff');
        Route::post('/staff', [VendorAuthController::class, 'storeStaff'])->name('staff.store');
        Route::get('/payroll', [VendorAuthController::class, 'page'])->defaults('page', 'payroll')->name('payroll');
        Route::post('/payroll/process', [VendorAuthController::class, 'processPayroll'])->name('payroll.process');
        Route::get('/reports', [VendorAuthController::class, 'page'])->defaults('page', 'reports')->name('reports');
        Route::get('/reports/export/{type}', [VendorAuthController::class, 'exportReport'])->name('reports.export');
        Route::get('/settings', [VendorAuthController::class, 'page'])->defaults('page', 'settings')->name('settings');
        Route::post('/settings', [VendorAuthController::class, 'updateSettings'])->name('settings.update');
    });
});
