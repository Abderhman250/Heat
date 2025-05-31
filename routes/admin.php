<?php

use App\Http\Controllers\Admin\AdminContrtoller;
use App\Http\Controllers\Admin\AppointmentContrtoller;
use App\Http\Controllers\Admin\ClassContrtoller;
use App\Http\Controllers\Admin\CoachContrtoller;
use App\Http\Controllers\Admin\DashboardContrtoller;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\LoginContrtoller;
use App\Http\Controllers\Admin\PlansController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserContrtoller;
use App\Http\Controllers\Admin\OfficeManagerController;
use App\Http\Controllers\Admin\NotificationController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SectionPlanContrtoller;


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

Route::middleware(['role:admin|office_manager'])->group(function () {
    Route::post('/logout', [LoginContrtoller::class, 'logout'])->name('admin.logout');


    Route::get('dashboard', [DashboardContrtoller::class, 'index'])->name('admin.dashboard');

    Route::group(['prefix' => 'users'], function () {

        Route::get('/', [UserContrtoller::class, 'index'])->name('admin.users');
        Route::get('/not_active', [UserContrtoller::class, 'not_active'])->name('admin.users.not_active');
        Route::post('/activate', [UserContrtoller::class, 'activateUser']);
        Route::post('/deactivate', [UserContrtoller::class, 'deactivateUser']);
    });

    Route::group(['prefix' => 'coach'], function () {

        Route::get('/',       [CoachContrtoller::class, 'index'])->name('admin.coach.index');
        Route::get('/create', [CoachContrtoller::class, 'create'])->name('admin.coach.create');
        Route::post('/',      [CoachContrtoller::class, 'store'])->name('admin.coach.store');
        Route::get('/{id}/edit',    [CoachContrtoller::class, 'edit'])->name('admin.coach.edit');
            Route::put('/{id}/update',  [CoachContrtoller::class, 'update'])->name('admin.coach.update');
    });

    Route::group(['prefix' => 'admins'], function () {

        Route::get('/', [AdminContrtoller::class, 'index'])->name('admin.admins.index');
        Route::get('/{id}/edit', [AdminContrtoller::class, 'edit'])->name('admin.admins.edit');
        Route::put('/{id}', [AdminContrtoller::class, 'update'])->name('admin.admins.update');

    });


    Route::group(['prefix' => 'classes'], function () {

        Route::get('/',       [ClassContrtoller::class, 'index'])->name('admin.classes.index');
        Route::get('/create', [ClassContrtoller::class, 'create'])->name('admin.classes.create');
        Route::post('/',      [ClassContrtoller::class, 'store'])->name('admin.classes.store');
        Route::get('/{id}/edit', [ClassContrtoller::class, 'edit'])->name('admin.classes.edit');
        Route::put('/{id}', [ClassContrtoller::class, 'update'])->name('admin.classes.update');
    });

    Route::group(['prefix' => 'appointments'], function () {

        Route::get('/',       [AppointmentContrtoller::class, 'index'])->name('admin.appointments.index');
        Route::get('/create', [AppointmentContrtoller::class, 'create'])->name('admin.appointments.create');
        Route::post('/',      [AppointmentContrtoller::class, 'store'])->name('admin.appointments.store');
        Route::get('/{appointment}/edit', [AppointmentContrtoller::class, 'edit'])->name('admin.appointments.edit');
        Route::put('/{appointment}',      [AppointmentContrtoller::class, 'update'])->name('admin.appointments.update');
    });

    Route::group(['prefix' => 'booking'], function () {

        Route::get('/', [AppointmentContrtoller::class, 'booking'])->name('admin.booking.index');
    });


    Route::group(['prefix' => 'seats'], function () {

        Route::get('/', [AppointmentContrtoller::class, 'seats'])->name('admin.seats.index');
        Route::get('/{appointment_id}', [AppointmentContrtoller::class, 'show_seat'])->name('admin.seat.show');
    });


    Route::group(['prefix' => 'class_seat'], function () {

        Route::get('/', [AppointmentContrtoller::class, 'class_seat'])->name('admin.class_seat.index');
    });

    Route::get('/search-seats', [AppointmentContrtoller::class, 'searchSeatsByClass'])->name('searchSeats');

    Route::group(['prefix' => 'transaction'], function () {

        Route::get('/', [TransactionController::class, 'index'])->name('admin.transaction.index');
    });

    Route::group(['prefix' => 'plans'], function () {
        Route::get('/', [PlansController::class, 'index'])->name('admin.plans.index');
        Route::get('/create', [PlansController::class, 'create'])->name('admin.plans.create');
        Route::post('/',      [PlansController::class, 'store'])->name('admin.plans.store');
        Route::post('/deactivate',      [PlansController::class, 'deactivatePlan'])->name('admin.plans.deactivate');
        Route::post('/activate',      [PlansController::class, 'activatePlan'])->name('admin.plans.activate');


         

    });
    
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/',       [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::get('/create', [NotificationController::class, 'create'])->name('admin.notifications.create');
        Route::post('/push',  [NotificationController::class, 'push'])->name('admin.notifications.push');
        
    });

    Route::group(['prefix' => 'section_plans'], function () {
        Route::get('/',       [PlansController::class, 'section_plans'])->name('admin.section_plans.index');
        Route::get('/create', [SectionPlanContrtoller::class, 'create'])->name('admin.section_plans.create');
        Route::post('/',      [SectionPlanContrtoller::class, 'store'])->name('admin.section_plans.store');
        Route::get('/{id}/edit',[SectionPlanContrtoller::class, 'edit'])->name('admin.section_plans.edit'); // Show form to edit level
        Route::put('/{id}/update',[SectionPlanContrtoller::class, 'update'])->name('admin.section_plans.update'); // Show form to edit level

    });


    Route::group(['prefix' => 'level'], function () {
        Route::get('/',             [LevelController::class, 'index'])->name('admin.level.index'); // List levels
        Route::get('/create',       [LevelController::class, 'create'])->name('admin.level.create'); // Show form to create level
        Route::post('/store',       [LevelController::class, 'store'])->name('admin.level.store'); // Save level

        Route::get('/{id}/edit',    [LevelController::class, 'edit'])->name('admin.level.edit'); // Show form to edit level
        Route::put('/{id}/update',  [LevelController::class, 'update'])->name('admin.level.update'); // Update level
    });


    Route::group(['prefix' => 'office_manager'], function () {

        Route::get('/',       [OfficeManagerController::class, 'index'])->name('admin.office_manager.index');
        Route::get('/create', [OfficeManagerController::class, 'create'])->name('admin.office_manager.create');
        Route::post('/',      [OfficeManagerController::class, 'store'])->name('admin.office_manager.store');
        Route::get('/{id}/edit',    [OfficeManagerController::class, 'edit'])->name('admin.office_manager.edit');
            Route::put('/{id}/update',  [OfficeManagerController::class, 'update'])->name('admin.office_manager.update');
    });
});
