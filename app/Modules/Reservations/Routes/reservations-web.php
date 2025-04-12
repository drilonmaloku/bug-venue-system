<?php

declare(strict_types=1);

use App\Modules\Reservations\Controllers\ReservationsController;
use App\Modules\Reservations\Models\ReservationCollaborator;
use App\Modules\Reservations\Services\ReservationCollaboratorServices;
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
    Route::get('/reservations/import/page', [ReservationsController::class, 'importPage'])->name('reservations.import.page');
    Route::post('/reservations/import', [ReservationsController::class, 'import'])->name('reservations.import');
    Route::put('/reservations/{id}/invoices-update/{invoiceId}', [ReservationsController::class, 'updateInvoice'])->name('reservations.invoice.update');
    Route::delete('/reservations/{id}/invoices-delete/{invoiceId}', [ReservationsController::class, 'deleteInvoice'])->name('reservations.invoice.destroy');

    //Discount

    Route::post('/reservations/{id}/discount', [ReservationsController::class, 'storeDiscount'])->name('reservations.discount.store');
    Route::get('/reservations/{id}/edit-discount/{discountId}', [ReservationsController::class, 'editDiscount'])->name('reservations.discount.edit');
    Route::put('/reservations/{id}/discount-update/{discountId}', [ReservationsController::class, 'updateDiscount'])->name('reservations.discount.update');
    Route::delete('/reservations/{id}/discount-delete/{discountId}', [ReservationsController::class, 'deleteDiscount'])->name('reservations.discount.destroy');


    Route::get('/reservations/{id}/edit', [ReservationsController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservations/{id}/update', [ReservationsController::class, 'update'])->name('reservations.update');
    Route::put('/reservations/{id}/update-status', [ReservationsController::class, 'updateStatus'])->name('reservations.updateStatus');
    Route::get('reservations/{id}/print-contract', [ReservationsController::class, 'printContract'])->name('reservations.printContract');


    Route::post('/reservations/{reservationId}/add-member', [ReservationsController::class, 'addMember'])->name('reservations.addMember');
    Route::delete('/reservations/staff/{id}', [ReservationsController::class, 'deleteStaff'])->name('reservations.staff.delete');

    Route::post('/reservations/add-collaborator/{reservationId}', [ReservationsController::class, 'addCollaborator'])->name('reservations.addCollaborator');
    Route::delete('reservations/{reservationId}/collaborators/{collaboratorId}', [ReservationsController::class, 'deleteCollaborator'])->name('reservations.delete-collaborator');

    Route::put('/reservations/update-planning/{id}', [ReservationsController::class, 'updatePlanning'])->name('reservations.updatePlanning');
    Route::get('/reservations/edit-notes/{id}', [ReservationsController::class, 'editNotes'])->name('reservations.editNotes');

    Route::put('/reservations/update-notes/{id}', [ReservationsController::class, 'updateNotes'])->name('reservations.updateNotes');



    Route::get('/reservations/manage-guests/{id}', [ReservationsController::class, 'listGuests'])->name('reservations.listGuests');
    Route::post('/reservations/manage-guests/{id}/add', [ReservationsController::class, 'addGuest'])->name('reservations.addGuest');
    Route::put('/reservations/manage-guests/{reservationId}/update/{guestId}', [ReservationsController::class, 'updateGuest'])->name('reservations.updateGuest');
    Route::delete('/reservations/manage-guests/{reservationId}/delete/{guestId}', [ReservationsController::class, 'deleteGuest'])->name('reservations.deleteGuest');
    Route::patch('/reservations/manage-guests/{reservationId}/update-status/{guestId}', [ReservationsController::class, 'updateGuestStatus'])->name('reservations.updateGuestStatus');
    Route::patch('/reservations/manage-guests/{reservationId}/update-checkin/{guestId}', [ReservationsController::class, 'updateGuestCheckin'])->name('reservations.updateGuestCheckin');

    Route::put('reservations/{id}/update-date', [ReservationsController::class, 'updateDate'])->name('reservations.updateDate');

});

