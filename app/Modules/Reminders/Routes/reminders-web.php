<?php

use App\Modules\Reminders\Controllers\RemindersController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/reservations/{reservation}/reminders', [RemindersController::class, 'index'])->name('reminders.index');
    Route::get('/reservations/{reservation}/reminders/create', [RemindersController::class, 'create'])->name('reminders.create');
    Route::post('/reservations/{reservation}/reminders', [RemindersController::class, 'store'])->name('reminders.store');
    Route::get('/reservations/{reservation}/reminders/{reminder}/show', [RemindersController::class, 'show'])->name('reminders.show');
    Route::get('/reservations/{reservation}/reminders/{reminder}/edit', [RemindersController::class, 'edit'])->name('reminders.edit');
    Route::put('/reservations/{reservation}/reminders/{reminder}', [RemindersController::class, 'update'])->name('reminders.update');
    Route::delete('/reservations/{reservation}/reminders/{reminder}', [RemindersController::class, 'destroy'])->name('reminders.destroy');
});