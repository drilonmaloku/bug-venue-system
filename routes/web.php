<?php

use App\Modules\NotificationsPreference\Controllers\NotificationPreferenceController;
use App\Modules\Common\Controllers\DashboardController;
use App\Modules\GoogleCalendar\Controllers\GoogleCalendarController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Notifications\Controllers\NotificationsController;
use App\Modules\Events\Controllers\EventsController;
use App\Modules\Reminders\Controllers\RemindersController;

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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index')->middleware('auth');
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
    // Run migrations
    Artisan::call('migrate', [
        '--force' => true // This option is necessary to run migrations in a production environment
    ]);

    return response()->json(['message' => 'Migrations ran successfully']);
});


Route::get('/migrate-seed', function () {
    // Run migrations
    Artisan::call('migrate:fresh', [
        '--force' => true // This option is necessary to run migrations in a production environment
    ]);


    Artisan::call('db:seed', [
        '--class' => 'ProductionSeeder', // Replace with your specific seeder class name
        '--force' => true // Use '--force' to run the seeder in production
    ]);

    return response()->json(['message' => 'Migrations and seeding ran successfully']);
});

Route::get('/google/auth', [GoogleCalendarController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('/google/callback', [GoogleCalendarController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('/events/sync', [GoogleCalendarController::class, 'syncEventsToGoogle'])->name('google.sync');
Route::post('/events/sync-all', [GoogleCalendarController::class, 'syncAllEventsToGoogle'])->name('google.sync.all');


Route::get('/notifications', [NotificationsController::class, 'archive'])->name('notification');
Route::patch('/notifications/{notification}/mark-as-read', [NotificationsController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');
Route::patch('/notifications/{notification}/mark-as-unread', [NotificationsController::class, 'markNotificationAsUnread'])->name('notifications.markAsUnread');
Route::get('/notifications/unread', [NotificationsController::class, 'fetchUnread'])->name('notifications.fetchUnread');
Route::patch('/notifications/mark-all-as-read', [NotificationsController::class, 'markAllNotificationsAsRead'])->name('notifications.markAllAsRead');


Route::get('/notifications/preferences', [NotificationPreferenceController::class, 'edit'])->name('notifications.preferences.edit');
Route::patch('/notifications/preferences', [NotificationPreferenceController::class, 'update'])->name('notifications.preferences.update');


Route::get('files/{path}', [\App\Modules\Files\Controllers\AppFileController::class, 'getFile'])
    ->where('path', '.*')->name('files.getFile');

Route::put('/reservations/manage-guests/{reservationId}/update/{guestId}', 'ReservationsController@updateGuest')
    ->name('reservations.updateGuest');

// Reminders
Route::group(['middleware' => 'auth'], function () {
    Route::get('/reservations/{reservation}/reminders', [RemindersController::class, 'index'])->name('reminders.index');
    Route::get('/reservations/{reservation}/reminders/create', [RemindersController::class, 'create'])->name('reminders.create');
    Route::post('/reservations/{reservation}/reminders', [RemindersController::class, 'store'])->name('reminders.store');
    Route::get('/reservations/{reservation}/reminders/{reminder}/show', [RemindersController::class, 'show'])->name('reminders.show');
    Route::get('/reservations/{reservation}/reminders/{reminder}/edit', [RemindersController::class, 'edit'])->name('reminders.edit');
    Route::put('/reservations/{reservation}/reminders/{reminder}', [RemindersController::class, 'update'])->name('reminders.update');
    Route::delete('/reservations/{reservation}/reminders/{reminder}', [RemindersController::class, 'destroy'])->name('reminders.destroy');
});