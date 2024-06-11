<?php

declare(strict_types=1);

use App\Modules\Reservations\Controllers\ReservationsController;
use Illuminate\Support\Facades\Route;


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
    Route::post('/reservations/{id}/invoices', [ReservationsController::class, 'storeInvoice'])->name('reservations.invoice.store');
    Route::get('/reservations/{id}/edit-invoice', [ReservationsController::class, 'editInvoice'])->name('reservations.invoice.edit');
    Route::put('/reservations/{id}/invoices-update', [ReservationsController::class, 'updateInvoice'])->name('reservations.invoice.update');
    Route::delete('/reservations/{id}/invoices-delete', [ReservationsController::class, 'deleteInvoice'])->name('reservations.invoice.destroy');


    Route::get('/reservations/{id}/edit', [ReservationsController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservations/{id}/update', [ReservationsController::class, 'update'])->name('reservations.update');

});

