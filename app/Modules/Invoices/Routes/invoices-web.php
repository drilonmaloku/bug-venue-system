<?php

declare(strict_types=1);

use App\Modules\Invoices\Controllers\InvoicesController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {
    Route::get('/invoices', [InvoicesController::class, 'index'])->name('reservations.index');
    Route::get('/invoices/create', [InvoicesController::class, 'create'])->name('reservations.create');
    Route::post('/invoices', [InvoicesController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/json/{id}', [ReservationsController::class, 'viewJson'])->name('reservations.viewJson');
    Route::get('/reservations/{id}', [ReservationsController::class, 'view'])->name('reservations.view');
    Route::delete('/reservation/{id}', [ReservationsController::class, 'delete'])->name('reservation.destroy');
    Route::post('/reservation/check-availability', [ReservationsController::class, 'checkVenueAvailability'])->name('reservation.destroy');
    Route::post("/reservations/{id}/comment", [ReservationsController::class, "storeComment"])->name('reservations.comment.store');
    Route::delete('/reservations/comment/{id}', [ReservationsController::class, 'deleteComment'])->name('reservations.comment.delete');
    Route::post('/reservations/{id}/payments', [ReservationsController::class, 'storePayment'])->name('reservations.payment.store');
    Route::get('/reservations/{id}/edit', [ReservationsController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservations/{id}/update', [ReservationsController::class, 'update'])->name('reservations.update');

});
