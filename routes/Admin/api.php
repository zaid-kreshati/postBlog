<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\api\CategoryController;
use App\Http\Controllers\Admin\api\TwoAuthController;
use App\Http\Controllers\Admin\api\AuthController;

// Category Routes
Route::controller(CategoryController::class)->prefix('admin/categories')->middleware('auth:api','checkAdmin')->group(function () {
    Route::get('/index', 'index');
    Route::post('/store', 'store');
    Route::post('update/{id}', 'update');
    Route::delete('destroy/{id}', 'destroy');
    Route::get('nested/{id}', 'getNestedCategories');
    Route::post('search', 'search');

});

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth:api');
});

// Two Factor Routes
Route::controller(TwoAuthController::class)->prefix('two-factor')->name('verify.two.factor.')->group(function () {
    Route::post('/admin/register', 'initiateRegistration');
    Route::get('/resend_code', 'resendTwoFactorCode');
    Route::post('/register/verify', 'verifyRegistration');
});


