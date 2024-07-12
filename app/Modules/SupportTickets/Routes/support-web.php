<?php

declare(strict_types=1);

use App\Modules\SupportTickets\Controllers\SupportTicketsController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {
    Route::get('/supports-tickets', [SupportTicketsController::class, 'index'])->name('support-tickets.index');

    Route::get('/supports-tickets/create', [SupportTicketsController::class, 'create'])->name('support-tickets.create');
    Route::post('/supports-tickets', [SupportTicketsController::class, 'store'])->name('support-tickets.store');
    Route::get('/supports-tickets/{id}', [SupportTicketsController::class, 'view'])->name('support-tickets.view');
    Route::post("/supports-tickets/{id}/comment", [SupportTicketsController::class, "storeComment"])->name('support-tickets.comment.store');
    Route::put('/support-tickets/{id}', [SupportTicketsController::class, 'updateStatus'])->name('ticket-status.update');
    Route::put('/support-tickets/{id}/open', [SupportTicketsController::class, 'updateStatusOpen'])->name('ticket-status-open.update');



    

});
