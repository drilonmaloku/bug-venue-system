<?php

use App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group
| assigned the "api" middleware group. All routes use URL versioning.
|
*/

// Public Routes (No Authentication)
Route::prefix('v1')->group(function () {
    
    // Health Check
    Route::get('/health', fn() => response()->json(['status' => 'ok', 'version' => '1.0.0']));
    
    // Guest Public Routes (for public guest list access)
    Route::prefix('public')->name('api.v1.public.')->group(function () {
        Route::get('/reservations/{uuid}', [V1\PublicReservationsController::class, 'show']);
        Route::get('/reservations/{uuid}/guests', [V1\PublicReservationsController::class, 'listGuests']);
        Route::post('/reservations/{uuid}/guests', [V1\PublicReservationsController::class, 'addGuest']);
        Route::put('/reservations/{uuid}/guests/{guestId}', [V1\PublicReservationsController::class, 'updateGuest']);
        Route::delete('/reservations/{uuid}/guests/{guestId}', [V1\PublicReservationsController::class, 'deleteGuest']);
        Route::patch('/reservations/{uuid}/guests/{guestId}/status', [V1\PublicReservationsController::class, 'updateGuestStatus']);
    });
});

// Protected Routes (Authentication Required)
Route::prefix('v1')->middleware(['auth:sanctum'])->name('api.v1.')->group(function () {
    
    //---------------------------------------------------------------
    // AUTHENTICATION
    //---------------------------------------------------------------
    Route::prefix('auth')->group(function () {
        Route::get('/me', [V1\AuthController::class, 'me'])->name('auth.me');
        Route::post('/logout', [V1\AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/refresh', [V1\AuthController::class, 'refresh'])->name('auth.refresh');
    });
    
    //---------------------------------------------------------------
    // RESERVATIONS (Complete CRUD + Extended Operations)
    //---------------------------------------------------------------
    Route::apiResource('reservations', V1\ReservationsController::class);
    
    // Reservation Extended Operations
    Route::prefix('reservations')->name('reservations.')->group(function () {
        // Availability
        Route::post('/check-availability', [V1\ReservationsController::class, 'checkAvailability'])->name('check-availability');
        
        // Financial Operations
        Route::post('/{reservation}/payments', [V1\ReservationsController::class, 'storePayment'])->name('payments.store');
        Route::get('/{reservation}/payments', [V1\ReservationsController::class, 'listPayments'])->name('payments.index');
        Route::post('/{reservation}/invoices', [V1\ReservationsController::class, 'storeInvoice'])->name('invoices.store');
        Route::get('/{reservation}/invoices', [V1\ReservationsController::class, 'listInvoices'])->name('invoices.index');
        Route::post('/{reservation}/discounts', [V1\ReservationsController::class, 'storeDiscount'])->name('discounts.store');
        Route::get('/{reservation}/discounts', [V1\ReservationsController::class, 'listDiscounts'])->name('discounts.index');
        
        // Comments
        Route::post('/{reservation}/comments', [V1\ReservationsController::class, 'storeComment'])->name('comments.store');
        Route::get('/{reservation}/comments', [V1\ReservationsController::class, 'listComments'])->name('comments.index');
        Route::delete('/{reservation}/comments/{commentId}', [V1\ReservationsController::class, 'deleteComment'])->name('comments.destroy');
        
        // Staff Management
        Route::post('/{reservation}/staff', [V1\ReservationsController::class, 'addStaff'])->name('staff.store');
        Route::get('/{reservation}/staff', [V1\ReservationsController::class, 'listStaff'])->name('staff.index');
        Route::delete('/{reservation}/staff/{userId}', [V1\ReservationsController::class, 'deleteStaff'])->name('staff.destroy');
        
        // Guest Management
        Route::post('/{reservation}/guests', [V1\ReservationsController::class, 'addGuest'])->name('guests.store');
        Route::get('/{reservation}/guests', [V1\ReservationsController::class, 'listGuests'])->name('guests.index');
        Route::put('/{reservation}/guests/{guestId}', [V1\ReservationsController::class, 'updateGuest'])->name('guests.update');
        Route::delete('/{reservation}/guests/{guestId}', [V1\ReservationsController::class, 'deleteGuest'])->name('guests.destroy');
        Route::patch('/{reservation}/guests/{guestId}/status', [V1\ReservationsController::class, 'updateGuestStatus'])->name('guests.status');
        Route::patch('/{reservation}/guests/{guestId}/checkin', [V1\ReservationsController::class, 'updateGuestCheckin'])->name('guests.checkin');
        
        // Documents
        Route::get('/{reservation}/documents', [V1\ReservationsController::class, 'listDocuments'])->name('documents.index');
        
        // Contract
        Route::post('/{reservation}/contract', [V1\ReservationsController::class, 'generateContract'])->name('contract.generate');
        Route::get('/{reservation}/contract/download', [V1\ReservationsController::class, 'downloadContract'])->name('contract.download');
        
        // Status Operations
        Route::patch('/{reservation}/status', [V1\ReservationsController::class, 'updateStatus'])->name('status.update');
        Route::post('/{reservation}/confirm', [V1\ReservationsController::class, 'confirm'])->name('confirm');
        Route::post('/{reservation}/cancel', [V1\ReservationsController::class, 'cancel'])->name('cancel');
    });
    
    //---------------------------------------------------------------
    // CLIENTS
    //---------------------------------------------------------------
    Route::apiResource('clients', V1\ClientsController::class);
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/search/query', [V1\ClientsController::class, 'search'])->name('search');
        Route::get('/{client}/reservations', [V1\ClientsController::class, 'reservations'])->name('reservations');
        Route::get('/{client}/payments', [V1\ClientsController::class, 'payments'])->name('payments');
    });
    
    //---------------------------------------------------------------
    // USERS
    //---------------------------------------------------------------
    Route::apiResource('users', V1\UsersController::class);
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/me/profile', [V1\UsersController::class, 'profile'])->name('profile');
        Route::put('/me/profile', [V1\UsersController::class, 'updateProfile'])->name('profile.update');
        Route::put('/me/password', [V1\UsersController::class, 'changePassword'])->name('password.change');
        Route::get('/{user}/reservations', [V1\UsersController::class, 'reservations'])->name('reservations');
        Route::get('/{user}/permissions', [V1\UsersController::class, 'permissions'])->name('permissions');
    });
    
    //---------------------------------------------------------------
    // PAYMENTS
    //---------------------------------------------------------------
    Route::apiResource('payments', V1\PaymentsController::class);
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/stats/summary', [V1\PaymentsController::class, 'summary'])->name('summary');
        Route::get('/overdue/list', [V1\PaymentsController::class, 'overdue'])->name('overdue');
    });
    
    //---------------------------------------------------------------
    // VENUES
    //---------------------------------------------------------------
    Route::apiResource('venues', V1\VenuesController::class);
    Route::prefix('venues')->name('venues.')->group(function () {
        Route::get('/{venue}/availability', [V1\VenuesController::class, 'availability'])->name('availability');
        Route::get('/{venue}/reservations', [V1\VenuesController::class, 'reservations'])->name('reservations');
        Route::get('/{venue}/calendar', [V1\VenuesController::class, 'calendar'])->name('calendar');
    });
    
    //---------------------------------------------------------------
    // MENUS
    //---------------------------------------------------------------
    Route::apiResource('menus', V1\MenusController::class);
    
    //---------------------------------------------------------------
    // DECORS
    //---------------------------------------------------------------
    Route::apiResource('decors', V1\DecorsController::class);
    Route::prefix('decors')->name('decors.')->group(function () {
        Route::get('/category/{category}', [V1\DecorsController::class, 'byCategory'])->name('by-category');
    });
    
    //---------------------------------------------------------------
    // EXPENSES
    //---------------------------------------------------------------
    Route::apiResource('expenses', V1\ExpensesController::class);
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/category/{category}', [V1\ExpensesController::class, 'byCategory'])->name('by-category');
        Route::get('/summary/monthly', [V1\ExpensesController::class, 'monthlySummary'])->name('monthly-summary');
    });
    
    //---------------------------------------------------------------
    // DASHBOARD & ANALYTICS
    //---------------------------------------------------------------
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/stats', [V1\DashboardController::class, 'stats'])->name('stats');
        Route::get('/kpi', [V1\DashboardController::class, 'kpi'])->name('kpi');
        Route::get('/events', [V1\DashboardController::class, 'events'])->name('events');
        Route::get('/kitchen', [V1\DashboardController::class, 'kitchen'])->name('kitchen');
        Route::get('/upcoming', [V1\DashboardController::class, 'events'])->name('upcoming');
    });
    
    //---------------------------------------------------------------
    // REPORTS
    //---------------------------------------------------------------
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/types', [V1\ReportsController::class, 'types'])->name('types');
        Route::post('/generate', [V1\ReportsController::class, 'generate'])->name('generate');
        Route::get('/reservations/summary', [V1\ReportsController::class, 'reservationsSummary'])->name('reservations.summary');
        Route::get('/financial/summary', [V1\ReportsController::class, 'financialSummary'])->name('financial.summary');
        Route::get('/clients/summary', [V1\ReportsController::class, 'clientsSummary'])->name('clients.summary');
    });
    
    //---------------------------------------------------------------
    // NOTIFICATIONS
    //---------------------------------------------------------------
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [V1\NotificationsController::class, 'index'])->name('index');
        Route::get('/unread', [V1\NotificationsController::class, 'unread'])->name('unread');
        Route::get('/unread-count', [V1\NotificationsController::class, 'unreadCount'])->name('unread-count');
        Route::patch('/{id}/read', [V1\NotificationsController::class, 'markAsRead'])->name('read');
        Route::patch('/{id}/unread', [V1\NotificationsController::class, 'markAsUnread'])->name('unread');
        Route::post('/mark-all-read', [V1\NotificationsController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [V1\NotificationsController::class, 'destroy'])->name('destroy');
    });
});
