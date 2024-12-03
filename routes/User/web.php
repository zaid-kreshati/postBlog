<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\web\AuthController;
use App\Http\Controllers\User\web\HomeController;
use App\Http\Controllers\User\web\ProfileController;
use App\Http\Controllers\User\web\PostController;
use App\Http\Controllers\User\web\TwoAuthController;
use App\Http\Controllers\User\web\CommentController;
use App\Http\Controllers\User\web\SearchController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'showLoginForm')->name('login.form');
    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegisterForm')->name('register.form');
});

// Home Routes
Route::controller(HomeController::class)->middleware('auth:web')->group(function () {
    Route::get('/home', 'home')->name('home');
    Route::get('/search', 'search')->name('searchview');
});

// Profile Routes
Route::controller(ProfileController::class)->middleware('auth:web')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/{id?}', 'index')->name('index');
    Route::put('/upload-profile-image', 'upload_profile_image')->name('upload-profile-image');
    Route::put('/upload-background-image', 'upload_background_image')->name('upload-background-image');
    Route::post('/description/add', 'addDescription')->name('add-description');
    Route::put('/description/update/{id}', 'updateDescription');
    Route::delete('/description/delete/{id}', 'deleteDescription');
    Route::post('/save-descriptions', 'saveDescriptions')->name('save-descriptions');
    Route::delete('/remove-profile-image', 'removeProfileImage')->name('remove-profile-image');
    Route::delete('/remove-cover-image', 'removeCoverImage')->name('remove-cover-image');
    Route::get('/show/{id}', 'show')->name('show');
    Route::post('/switch-privacy', 'switchPrivacy')->name('switch-privacy');
});

// Post Routes
Route::controller(PostController::class)->middleware('auth:web')->prefix('posts')->name('posts.')->group(function () {
    Route::post('/store', 'store')->name('store');
    Route::put('/{id}/update', 'update')->name('update');
    Route::patch('/{id}/archive', 'archive')->name('archive');
    Route::post('/filter', 'filterPosts')->name('filter');
    Route::delete('/media/{id}/delete', 'deleteMedia')->name('media.delete');
    Route::delete('/{id}/delete', 'deletePost')->name('delete');
    Route::post('/{id}/publish', 'publishPost')->name('publish');
    Route::get('/list', 'postList')->name('list');
    Route::get('/load-more', 'loadMorePosts')->name('load-more');
});

// Two Factor Routes
Route::controller(TwoAuthController::class)->prefix('two-factor')->name('verify.two.factor.')->group(function () {
    Route::post('/verify', 'initiateRegistration')->name('code');
    Route::get('/resend', 'resendTwoFactorCode')->name('resend');
    Route::post('/register/initiate', 'initiateRegistration')->name('register.initiate');
    Route::post('/register/verify', 'verifyRegistration')->name('register.verify');
});



// Comment Routes
Route::controller(CommentController::class)->prefix('comment')->name('comment.')->group(function () {
    Route::post('/store', 'store')->name('store');
    Route::get('/{postId}', 'index')->name('index');
    Route::delete('/{id}', 'destroy')->name('destroy');
    Route::put('/{id}', 'update')->name('update');
    Route::post('/store/nested', 'storeNested')->name('store.nested');
    Route::get('/get/nested', 'getNestedComments')->name('get.nested');
});

// Search Routes
Route::controller(SearchController::class)->prefix('search')->name('search.')->group(function () {
    Route::get('/all', 'searchAll')->name('all');
    Route::get('/posts/with/photo', 'searchPostswithphoto')->name('posts.with.photo');
    Route::get('/posts/with/video', 'searchPostswithvideo')->name('posts.with.video');
    Route::get('/all/posts', 'searchAllPosts')->name('all.posts');
    Route::get('/users', 'searchUsers')->name('users');
    Route::get('/load-more', 'loadMoreResults')->name('load-more');
    Route::get('/category', 'searchCategory')->name('category');
});



