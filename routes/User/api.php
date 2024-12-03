<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\api\AuthController;
use App\Http\Controllers\User\api\HomeController;
use App\Http\Controllers\User\api\ProfileController;
use App\Http\Controllers\User\api\PostController;
use App\Http\Controllers\User\api\TwoAuthController;
use App\Http\Controllers\User\api\CategoryController;
use App\Http\Controllers\User\api\CommentController;
use App\Http\Controllers\User\api\SearchController;
use GuzzleHttp\Middleware;

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
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth:api');
});

// Two Factor Routes
Route::controller(TwoAuthController::class)->prefix('two-factor')->name('verify.two.factor.')->group(function () {
    Route::post('/admin/register', 'initiateRegistration');
    Route::get('/resend_code', 'resendTwoFactorCode');
    Route::post('/register/verify', 'verifyRegistration');
});



// Home Route
Route::get('/home', [HomeController::class, 'index'])->middleware('auth:api');

// Profile Routes
Route::controller(ProfileController::class)->middleware('auth:api')->prefix('profile')->group(function () {
    Route::get('/{id?}', 'index');
    Route::post('/upload-profile-image', 'upload_profile_image');
    Route::post('/upload-background-image', 'upload_background_image');
    Route::post('/description/add', 'addDescription');
    Route::put('/description/update/{id}', 'updateDescription');
    Route::delete('/description/delete/{id}', 'deleteDescription');
    Route::delete('/remove-profile-image', 'removeProfileImage');
    Route::delete('/remove-cover-image', 'removeCoverImage');
    Route::post('/switch-privacy', 'switchPrivacy');
});

// Post Routes
Route::controller(PostController::class)->middleware('auth:api')->prefix('posts')->group(function () {
    Route::post('/store', 'store');
    Route::post('/update/{id}', 'update');
    Route::patch('/archive/{id}', 'archive');
    Route::post('/filter', 'filterPosts');
    Route::delete('/media/delete/{id}', 'deleteMedia');
    Route::delete('/delete/{id}', 'deletePost');
    Route::post('/publish/{id}', 'publishPost');
    Route::post('/load-more', 'loadMorePosts');
});





// Comment Routes
Route::controller(CommentController::class)->middleware('auth:api')->prefix('comment')->group(function () {
    Route::post('/store', 'store');
    Route::get('commentsOnPost/{postId}', 'index');
    Route::delete('destroy/{id}', 'destroy');
    Route::post('storeNested', 'storeNested');
    Route::get('getNested/{parentId}', 'getNestedComments');
});

// Search Routes
Route::controller(SearchController::class)->prefix('search')->middleware('auth:api')->group(function () {
    Route::get('/all', 'searchAll');
    Route::get('/posts-with-photo', 'searchPostswithphoto');
    Route::get('/posts-with-video', 'searchPostswithvideo');
    Route::get('/posts', 'searchAllPosts');
    Route::get('/users', 'searchUsers');
    Route::post('/load-more', 'loadMoreResults');
});
