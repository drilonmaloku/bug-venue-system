<?php
use App\Modules\Common\Controllers\DashboardController;
use App\Modules\GoogleCalendar\Controllers\GoogleCalendarController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Notifications\Controllers\NotificationsController;


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


use App\Modules\Users\Controllers\UsersController;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('auth');
Route::get('/dashboard/events', [DashboardController::class, 'fetchEvents'])->name('dashboard.events');




// Profile
Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UsersController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UsersController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [UsersController::class, 'editPassword'])->name('profile.password-update');
});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);


Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});


Route::get('/migrate', function () {
    Artisan::call('migrate', [
        '--force' => true // This option is necessary to run migrations in a production environment
    ]);

    return response()->json(['message' => 'Migrations ran successfully']);
});


Route::get('/google/redirect', [GoogleCalendarController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('/google/callback', [GoogleCalendarController::class, 'handleGoogleCallback']);
Route::get('/events/sync', [GoogleCalendarController::class, 'syncEventsToGoogle']);


Route::get('/notifications', [NotificationsController::class, 'archive'])->name('notification');
Route::patch('/notifications/{notification}/mark-as-read', [NotificationsController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');
Route::patch('/notifications/{notification}/mark-as-unread', [NotificationsController::class, 'markNotificationAsUnread'])->name('notifications.markAsUnread');
Route::get('/notifications/unread', [NotificationsController::class, 'fetchUnread'])->name('notifications.fetchUnread');
