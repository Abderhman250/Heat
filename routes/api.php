<?php

use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\FollowerController;
use App\Http\Controllers\API\Group\GroupController;
use App\Http\Controllers\API\Group\GroupInviteController;

use App\Http\Controllers\API\User\RegisterController;
use App\Http\Controllers\API\User\LoginController;

use App\Http\Controllers\API\InterestsController as Interests;
use App\Http\Controllers\API\Chat\MessageController;
use App\Http\Controllers\API\Poll\CommentController;
use App\Http\Controllers\API\Poll\FollowPollController;
use App\Http\Controllers\API\Poll\LookupPollController;
use App\Http\Controllers\API\Poll\PollController;
use App\Http\Controllers\API\Poll\VoteController;
use App\Http\Controllers\API\User\BlockController;
use App\Http\Controllers\API\User\OtpController as OtpController;
use App\Http\Controllers\API\User\UserController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//home

Route::get('/test',  function (Request $request) {

    dd(bcrypt($request->password));
});

Route::group(['prefix' => 'user'], function () {
    Route::post('/forget-password', [UserController::class, 'forgetPassword'])->name('user.forgetPassword');
    Route::post('/check-otp', [UserController::class, 'checkOTP'])->name('user.checkOTP');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    
});
Route::prefix('auth')->group(function () {
    Route::post('/login',  [LoginController::class, 'login'])->name('auth.login');
    Route::post('/login/google',  [LoginController::class, 'loginByGoogle'])->name('auth.login.google');
    Route::post('/login/facebook',  [LoginController::class, 'loginByFacebook'])->name('auth.login.facebook');
    Route::post('/login/apple',  [LoginController::class, 'loginByApple'])->name('auth.login.apple');

  
    Route::post('/register',  [RegisterController::class, 'register'])->name('auth.register');
    Route::post('/verify-otp',  [OtpController::class, 'verifyOtp'])->name('verifyOtp.post');
    Route::post('/send-otp',  [OtpController::class, 'sendOtp'])->name('sendOtp.post');
});

Route::get('/country',  [CountryController::class, 'country'])->name('country.get');
Route::get('/city/{country_id}',  [CountryController::class, 'city'])->name('city.get');


Route::get('/interests',  [Interests::class, 'index'])->name('interests.get');


Route::middleware(['auth:api','check.completing_info'])->group(function () {
    Route::put('/change_password',  [UserController::class, 'changePassword'])->name('change.password');


    Route::get('invaite-follower', [FollowerController::class, 'invaite_follower'])->name('followers.invaite');

    // Route::get('test2', [FollowerController::class, 'test'])->name('followers');

    Route::get('followers', [FollowerController::class, 'followers'])->name('followers');
    Route::get('followings', [FollowerController::class, 'followings'])->name('followings');
    Route::post('follow/{id}', [FollowerController::class, 'follow'])->name('follow');
    Route::post('unfollow/{id}', [FollowerController::class, 'unfollow'])->name('unfollow');

    Route::group(['prefix' => 'user'], function () {
        Route::get('followers/by-user/{id}', [FollowerController::class, 'followersByUser'])->name('followers.by.user');
        Route::get('followings/by-user/{id}', [FollowerController::class, 'followingsByUser'])->name('following.by.user');
        Route::get('info-by-user/{user_id}', [UserController::class, 'infoByUser'])->name('info.by.user');
        
        // Route::get('invite/{id}', [GroupController::class, 'groups'])->name('groups');
        Route::get('/info', [UserController::class, 'info'])->name('user.info');
        Route::post('/profile', [UserController::class, 'updateInfo'])->name('user.edit.profile');

        Route::get('/mobile-token', [UserController::class, 'mobileToken'])->name('user.mobileToken');
        Route::post('/mobile-token', [UserController::class, 'setMobileToken'])->name('user.setMobileToken');        
        Route::post('/send-notification',[UserController::class, 'sendNotification'])->name('user.sendNotification');
        Route::get('/notification',[UserController::class, 'getNotification'])->name('user.getNotification');

 
 

 
 
 
    });
});
 
