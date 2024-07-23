<?php

declare(strict_types=1);

use App\Modules\Reservations\Controllers\ReservationsController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {
    Route::get('/reservations', [ReservationsController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationsController::class, 'create'])->name('reservations.create');
    Route::get('/reservations/export', [ReservationsController::class, 'export'])->name('reservations.export');
    Route::post('/reservations', [ReservationsController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/json/{id}', [ReservationsController::class, 'viewJson'])->name('reservations.viewJson');
    Route::get('/reservations/{id}', [ReservationsController::class, 'view'])->name('reservations.view');
    Route::delete('/reservation/{id}', [ReservationsController::class, 'delete'])->name('reservation.destroy');
    Route::post('/reservation/check-availability', [ReservationsController::class, 'checkVenueAvailability'])->name('reservations.destroy');
    Route::post("/reservations/{id}/comment", [ReservationsController::class, "storeComment"])->name('reservations.comment.store');
    Route::delete('/reservations/comment/{id}', [ReservationsController::class, 'deleteComment'])->name('reservations.comment.delete');
    Route::post('/reservations/{id}/payments', [ReservationsController::class, 'storePayment'])->name('reservations.payment.store');
    Route::post('/reservations/{id}/invoices', [ReservationsController::class, 'storeInvoice'])->name('reservations.invoice.store');
    Route::get('/reservations/{id}/edit-invoice/{invoiceId}', [ReservationsController::class, 'editInvoice'])->name('reservations.invoice.edit');
    Route::get('/reservations/{id}/edit-payment/{paymentId}', [ReservationsController::class, 'editpayment'])->name('reservations.payment.edit');
    Route::put('/reservations/{id}/payment-update/{paymentId}', [ReservationsController::class, 'updatePayment'])->name('reservations.payment.update');

    Route::put('/reservations/{id}/invoices-update/{invoiceId}', [ReservationsController::class, 'updateInvoice'])->name('reservations.invoice.update');
    Route::delete('/reservations/{id}/invoices-delete/{invoiceId}', [ReservationsController::class, 'deleteInvoice'])->name('reservations.invoice.destroy');

    //Discount

    Route::post('/reservations/{id}/discount', [ReservationsController::class, 'storeDiscount'])->name('reservations.discount.store');
    Route::get('/reservations/{id}/edit-discount/{discountId}', [ReservationsController::class, 'editDiscount'])->name('reservations.discount.edit');
    Route::put('/reservations/{id}/discount-update/{discountId}', [ReservationsController::class, 'updateDiscount'])->name('reservations.discount.update');
    Route::delete('/reservations/{id}/discount-delete/{discountId}', [ReservationsController::class, 'deleteDiscount'])->name('reservations.discount.destroy');


    Route::get('/reservations/{id}/edit', [ReservationsController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservations/{id}/update', [ReservationsController::class, 'update'])->name('reservations.update');
    Route::get('reservations/{id}/print-contract', [ReservationsController::class, 'printContract'])->name('reservations.printContract');


    Route::post('/reservations/{reservationId}/add-member', [ReservationsController::class, 'addMember'])->name('reservations.addMember');
    Route::delete('/reservations/staff/{id}', [ReservationsController::class, 'deleteStaff'])->name('reservations.staff.delete');



});

