<?php

use App\Modules\Clients\Controllers\ClientsController;
use App\Modules\Logs\Controllers\LogsController;
use App\Modules\Payments\Controllers\PaymentsController;
use App\Modules\Reservations\Controllers\ReservationsController;
use App\Modules\Venues\Controllers\VenuesController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
            

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 

	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/clients/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::get('/clients/{id}', [ClientsController::class, 'view'])->name('clients.view');
    Route::put('/clients/{id}/update', [ClientsController::class, 'update'])->name('clients.update');


    Route::get('/venues', [VenuesController::class, 'index'])->name('venues.index');
    Route::get('/venues/{id}/edit', [VenuesController::class, 'edit'])->name('venues.edit');
    Route::get('/venues/create', [VenuesController::class, 'create'])->name('venues.create');
    Route::put('/venues/{id}/update', [VenuesController::class, 'update'])->name('venues.update');
    Route::post('/venues-store', [VenuesController::class, 'store'])->name('venues.store');
    Route::get('/venues/{id}', [VenuesController::class, 'view'])->name('venues.view');
    Route::delete('/venues/{id}', [VenuesController::class, 'delete'])->name('venues.destroy');

    Route::get('/reservations', [ReservationsController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationsController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationsController::class, 'store'])->name('reservations.store');

    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');

    Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
    Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
    Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});





Route::group(['middleware' => 'auth'], function () {
    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index');
    Route::get('/logs/{id}', [LogsController::class, 'view'])->name('logs.view');
});