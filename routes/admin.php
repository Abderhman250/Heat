<?php

use App\Http\Controllers\Admin\AdminContrtoller;
use App\Http\Controllers\Admin\AppointmentContrtoller;
use App\Http\Controllers\Admin\ClassContrtoller;
use App\Http\Controllers\Admin\CoachContrtoller;
use App\Http\Controllers\Admin\DashboardContrtoller;
use App\Http\Controllers\Admin\LoginContrtoller;
use App\Http\Controllers\Admin\UserContrtoller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/ss', function () {
    return  view('admin.index');
});

Route::get('/login', function () {
    return  view('admin.auth.login');
});

Route::post('/login', [LoginContrtoller::class, "login"])->name('admin.login');

Route::middleware(['role:admin'])->group(function () {


    Route::get('dashboard', [DashboardContrtoller::class, 'index'])->name('admin.dashboard');

    Route::group(['prefix' => 'users'], function () {

        Route::get('/', [UserContrtoller::class, 'index'])->name('admin.users');
        Route::get('/not_active', [UserContrtoller::class, 'not_active'])->name('admin.users.not_active');
    });

    Route::group(['prefix' => 'coach'], function () {
        
        Route::get('/', [CoachContrtoller::class, 'index'])->name('admin.coach.index');
    });

    Route::group(['prefix' => 'admins'], function () {
        
        Route::get('/', [AdminContrtoller::class, 'index'])->name('admin.admins.index');
    });
 

    Route::group(['prefix' => 'classes'], function () {

        Route::get('/', [ClassContrtoller::class, 'index'])->name('admin.classes.index');
    });

    Route::group(['prefix' => 'appointments'], function () {

        Route::get('/', [AppointmentContrtoller::class, 'index'])->name('admin.appointments.index');
    });


    Route::group(['prefix' => 'booking'], function () {

        Route::get('/', [AppointmentContrtoller::class, 'booking'])->name('admin.booking.index');
    });

 
    Route::group(['prefix' => 'seats'], function () {

        Route::get('/', [AppointmentContrtoller::class, 'seats'])->name('admin.seats.index');
    });   
});
