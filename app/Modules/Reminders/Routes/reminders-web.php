<?php

use App\Modules\Reminders\Controllers\RemindersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('reservations/{reservationId}/reminders')->group(function () {
        Route::get('/', [RemindersController::class, 'index'])->name('reminders.index');
        Route::get('/create', [RemindersController::class, 'create'])->name('reminders.create');
        Route::post('/', [RemindersController::class, 'store'])->name('reminders.store');
        Route::get('/{id}/edit', [RemindersController::class, 'edit'])->name('reminders.edit');
        Route::put('/{id}', [RemindersController::class, 'update'])->name('reminders.update');
        Route::delete('/{id}', [RemindersController::class, 'destroy'])->name('reminders.destroy');
    });
}); 