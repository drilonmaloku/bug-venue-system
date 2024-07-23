<?php

declare(strict_types=1);

use App\Modules\Clients\Controllers\ClientsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/clients/export', [ClientsController::class, 'export'])->name('clients.export');
    Route::get('/clients/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::get('/clients/{id}', [ClientsController::class, 'view'])->name('clients.view');
    Route::put('/clients/{id}/update', [ClientsController::class, 'update'])->name('clients.update');
});
