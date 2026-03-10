<?php

use App\Modules\Payments\Controllers\LatePaymentAlertsController;
use App\Modules\Payments\Controllers\PaymentSchedulesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Payment Schedules Routes
|--------------------------------------------------------------------------
*/

Route::prefix('payment-schedules')->group(function () {
    // Payment Schedule Routes
    Route::get('/', [PaymentSchedulesController::class, 'index']);
    Route::post('/from-template', [PaymentSchedulesController::class, 'storeFromTemplate']);
    Route::post('/custom', [PaymentSchedulesController::class, 'storeCustom']);
    Route::get('/templates', [PaymentSchedulesController::class, 'templates']);
    Route::get('/upcoming', [PaymentSchedulesController::class, 'upcoming']);
    Route::get('/dashboard', [PaymentSchedulesController::class, 'dashboard']);
    Route::get('/client/{clientId}/stats', [PaymentSchedulesController::class, 'clientStats']);
    Route::get('/reservation/{reservationId}', [PaymentSchedulesController::class, 'byReservation']);
    Route::get('/{id}', [PaymentSchedulesController::class, 'show']);
    Route::post('/{id}/activate', [PaymentSchedulesController::class, 'activate']);
    Route::post('/{id}/payment', [PaymentSchedulesController::class, 'recordPayment']);
    Route::delete('/{id}', [PaymentSchedulesController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Late Payment Alerts Routes
|--------------------------------------------------------------------------
*/

Route::prefix('late-payment-alerts')->group(function () {
    Route::get('/', [LatePaymentAlertsController::class, 'index']);
    Route::get('/active', [LatePaymentAlertsController::class, 'active']);
    Route::get('/high-priority', [LatePaymentAlertsController::class, 'highPriority']);
    Route::get('/statistics', [LatePaymentAlertsController::class, 'statistics']);
    Route::get('/summary', [LatePaymentAlertsController::class, 'summary']);
    Route::get('/run-check', [LatePaymentAlertsController::class, 'runCheck']);
    Route::get('/installment/{installmentId}', [LatePaymentAlertsController::class, 'byInstallment']);
    Route::get('/{id}', [LatePaymentAlertsController::class, 'show']);
    Route::post('/{id}/acknowledge', [LatePaymentAlertsController::class, 'acknowledge']);
    Route::post('/{id}/resolve', [LatePaymentAlertsController::class, 'resolve']);
    Route::post('/{id}/escalate', [LatePaymentAlertsController::class, 'escalate']);
});
