<?php

use App\Modules\Clients\Controllers\ClientsController;
use App\Modules\Common\Controllers\DashboardController;
use App\Modules\Logs\Controllers\LogsController;
use App\Modules\Menus\Controllers\MenuController;
use App\Modules\Payments\Controllers\PaymentsController;
use App\Modules\Reports\Controllers\ReportsController;
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
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Modules\Common\Controllers\BackupController;
use App\Modules\Expenses\Controllers\ExpensesController;
use App\Modules\Users\Controllers\UsersController;

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');

	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('auth');
	Route::post('/login-v2', [LoginController::class, 'login'])->name('loginv2');

Route::group(['middleware' => 'auth'], function () {
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});


// Clients
Route::group(['middleware' => 'auth'], function () {
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/clients/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::get('/clients/{id}', [ClientsController::class, 'view'])->name('clients.view');
    Route::put('/clients/{id}/update', [ClientsController::class, 'update'])->name('clients.update');
});

// Expenses
Route::group(['middleware' => 'auth'], function () {
    Route::get('/expenses', [ExpensesController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpensesController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpensesController::class, 'store'])->name('expenses.store');

    Route::get('/expenses/{id}/edit', [ExpensesController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{id}/update', [ExpensesController::class, 'update'])->name('expenses.update');
    Route::get('/expenses/{id}', [ExpensesController::class, 'view'])->name('expenses.view');
    Route::delete('/expenses/{id}', [ExpensesController::class, 'destroy'])->name('expenses.destroy');

});


// reservation.edit
Route::group(['middleware' => 'auth'], function () {
    Route::get('/reservations', [ReservationsController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationsController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationsController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/json/{id}', [ReservationsController::class, 'viewJson'])->name('reservations.viewJson');
    Route::get('/reservations/{id}', [ReservationsController::class, 'view'])->name('reservations.view');
    Route::delete('/reservation/{id}', [ReservationsController::class, 'delete'])->name('reservation.destroy');
    Route::post('/reservation/check-availability', [ReservationsController::class, 'checkVenueAvailability'])->name('reservation.destroy');
    Route::post("/reservations/{id}/comment", [ReservationsController::class, "storeComment"])->name('reservations.comment.store');
    Route::delete('/reservations/comment/{id}', [ReservationsController::class, 'deleteComment'])->name('reservations.comment.delete');
    Route::post('/reservations/{id}/payments', [ReservationsController::class, 'storePayment'])->name('reservations.payment.store');
    Route::get('/reservations/{id}/edit', [ReservationsController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservations/{id}/update', [ReservationsController::class, 'update'])->name('reservations.update');




    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentsController::class, 'view'])->name('payments.view');
    Route::delete('/payments/{id}', [PaymentsController::class, 'delete'])->name('payments.destroy');



    Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
    Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
    Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// Venues
Route::group(['middleware' => 'auth'], function () {
    Route::get('/venues', [VenuesController::class, 'index'])->name('venues.index');
    Route::get('/venues/{id}/edit', [VenuesController::class, 'edit'])->name('venues.edit');
    Route::get('/venues/create', [VenuesController::class, 'create'])->name('venues.create');
    Route::put('/venues/{id}/update', [VenuesController::class, 'update'])->name('venues.update');
    Route::post('/venues-store', [VenuesController::class, 'store'])->name('venues.store');
    Route::get('/venues/{id}', [VenuesController::class, 'view'])->name('venues.view');
    Route::delete('/venues/{id}', [VenuesController::class, 'delete'])->name('venues.destroy');
});

// Users
Route::group(['middleware' => 'auth'], function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UsersController::class, 'view'])->name('users.view');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}/update', [UsersController::class, 'update'])->name('users.update');
    Route::put('/users/{id}/update-password-profile', [UsersController::class, 'updatePassword'])->name('users-password.update');
    Route::delete('//users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');


});

// Profile
Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UsersController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UsersController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [UsersController::class, 'editPassword'])->name('profile.password-update');

});

// Logs
Route::group(['middleware' => 'auth'], function () {
    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index');
    Route::get('/logs/{id}', [LogsController::class, 'view'])->name('logs.view');
});

// Menus
Route::group(['middleware' => 'auth'], function () {
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::get('/menus/{id}', [MenuController::class, 'view'])->name('menus.view');
    Route::put('/menus/{id}/update', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{id}', [MenuController::class, 'delete'])->name('menus.destroy');

});

// Reports
Route::group(['middleware' => 'auth'], function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports-generated', [ReportsController::class, 'generate'])->name('reports.generate');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UsersController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UsersController::class, 'updateProfile'])->name('profile.update');
});

Auth::routes();


// Backup 
Route::middleware(['middleware' => 'auth'])->group(function () {
    Route::get('/backup', [BackupController::class, 'index'])->name('common.backup');
    Route::get('/backup-db', [BackupController::class, 'createDatabaseBackup'])->name('common.db-backup');
    Route::get('/download-db-backup/{file}', [BackupController::class, 'downloadDatabaseBackup'])->name('common.db-backup-download');
    Route::get('/delete-db-backup/{file}', [BackupController::class, 'deleteDatabaseBackup'])->name('common.db-backup-delete');
});