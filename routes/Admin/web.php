<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\web\AdminController;
use App\Http\Controllers\Admin\web\CategoryController;
use App\Http\Controllers\Admin\web\AnalyticsController;
use App\Http\Controllers\Admin\web\TwoAuthController;

// Admin Routes
Route::controller(AdminController::class)->name('DashBoard.')->group(function () {
    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::get('/', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegisterForm')->name('register.form')->middleware('checkAdminAuth');
    Route::post('/register', 'register')->middleware(['auth:api', 'checkAdminAuth'])->name('register');

});

// Two Factor Routes
Route::controller(TwoAuthController::class)->prefix('two-factor')->middleware('checkAdminAuth')->name('admin.verify.two.factor.')->group(function () {
    Route::post('/verify', 'initiateRegistration')->name('code');
    Route::get('/resend', 'resendTwoFactorCode')->name('resend');
    Route::post('/register/initiate', 'initiateRegistration')->name('register.initiate');
    Route::post('/register/verify', 'verifyRegistration')->name('register.verify');
});



// Category Routes
Route::controller(CategoryController::class)->middleware('checkAdminAuth')->prefix('/categories')->name('categories.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('store/', 'store')->name('store');
    Route::post('update/{id}', 'update')->name('update');
    Route::delete('delete/{id}', 'destroy')->name('destroy');
    Route::get('/{id}/nested', 'getNestedCategories')->name('nested');
    Route::post('/search', 'search')->name('search');
    Route::post('/paginate', 'paginate')->name('paginate');
});



// Analytics Routes
Route::controller(AnalyticsController::class)->middleware('auth:web','checkAdminAuth')->prefix('analytics')->name('DashBoard.')->group(function () {
    Route::get('/', 'home')->name('home');
});
