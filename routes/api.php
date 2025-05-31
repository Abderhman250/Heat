<?php

use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\FollowerController;
use App\Http\Controllers\API\Group\GroupController;
use App\Http\Controllers\API\Group\GroupInviteController;

use App\Http\Controllers\API\User\RegisterController;
use App\Http\Controllers\API\User\LoginController;

use App\Http\Controllers\API\InterestsController as Interests;
use App\Http\Controllers\API\Chat\MessageController;
use App\Http\Controllers\API\CoachController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\PlanController;
use App\Http\Controllers\API\Poll\CommentController;
use App\Http\Controllers\API\Poll\FollowPollController;
use App\Http\Controllers\API\Poll\LookupPollController;
use App\Http\Controllers\API\Poll\PollController;
use App\Http\Controllers\API\Poll\VoteController;
use App\Http\Controllers\API\User\BlockController;
use App\Http\Controllers\API\User\OtpController as OtpController;
use App\Http\Controllers\API\User\UserController;
use App\Models\User;
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
 
 
 
Route::middleware(['auth:api'])->group(function () {
    
    Route::put('/change_password',      [UserController::class, 'changePassword'])->name('change.password');
   
    Route::get('/home', [HomeController::class, 'homePageData'])->name('home.page'); 

    Route::group(['prefix' => 'user'], function () {

        Route::post('/update-notification',  [UserController::class, 'updateNotification'])->name('auth.updateNotification');

        Route::get('info-by-user/{user_id}', [UserController::class, 'infoByUser'])->name('info.by.user');
        
        Route::get('/info', [UserController::class, 'info'])->name('user.info');
      
        Route::post('/profile', [UserController::class, 'updateInfo'])->name('user.edit.profile');

        Route::get('/mobile-token', [UserController::class, 'mobileToken'])->name('user.mobileToken');
      
        Route::post('/mobile-token', [UserController::class, 'setMobileToken'])->name('user.setMobileToken');        
      
        Route::post('/send-notification',[UserController::class, 'sendNotification'])->name('user.sendNotification');
      
        Route::get('/notification',[UserController::class, 'getNotification'])->name('user.getNotification');
       
    });


    Route::group(['prefix' => 'appointment'], function () {

        Route::get('/', [AppointmentController::class, 'index'])->name('appointment.index');


        Route::get('/{appointment_id}', [AppointmentController::class, 'show'])->name('appointment.show');
       
        Route::get('/{appointment_id}/is-reserve', [AppointmentController::class, 'isReserve'])->name('appointments.is_reserve');
       
        Route::get('/{appointment_id}/seat-point', [AppointmentController::class, 'seatPoint'])->name('appointment.seat.point');
       
        Route::post('/reserve', [AppointmentController::class, 'reserve'])->name('appointment.reserve');
       
 
    });

    Route::get('/appointment-coach/{coach_id}', [AppointmentController::class, 'coach'])->name('appointment.coach');
    Route::get('/appointment-filters', [AppointmentController::class, 'filters'])->name('appointment.filters');
    Route::get('/date', [AppointmentController::class, 'date'])->name('appointment.date');


    Route::group(['prefix' => 'booking'], function () {

 
        Route::get('/', [BookingController::class, 'index'])->name('booking.index');

    });
    

    Route::group(['prefix' => 'coach'], function () {

        Route::get('/', [CoachController::class, 'index'])->name('coach.index');

        Route::get('/{coach_id}', [CoachController::class, 'show'])->name('coach.show');

    });

    Route::prefix('plans')->group(function () {
        
        Route::get('/', [PlanController::class, 'index'])->name('plan.index');
        Route::get('/{plan_id}', [PlanController::class, 'show'])->name('plan.show');

    });

    Route::get('/user/history-plans', [PlanController::class, 'historyPlans'])->name('plan.histor');

    Route::post('/user/checkout', [PlanController::class, 'checkout'])->name('plan.checkout');

     
});
 
