<?php
use App\Modules\Common\Controllers\DashboardController;
use App\Modules\Logs\Controllers\LogsController;
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

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');
Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('auth');
Route::get('/dashboard/events', [DashboardController::class, 'fetchEvents'])->name('dashboard.events');

Route::post('/login-v2', [LoginController::class, 'login'])->name('loginv2');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});







// reservation.edit
Route::group(['middleware' => 'auth'], function () {



    Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
    Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
    Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
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
