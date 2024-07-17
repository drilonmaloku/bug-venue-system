<?php

declare(strict_types=1);

use App\Modules\Logs\Controllers\LogsController;
use Illuminate\Support\Facades\Route;


// Logs
Route::group(['middleware' =>['auth', 'role:super-admin|system-admin']], function () {
    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index');
    Route::get('/logs/{id}', [LogsController::class, 'view'])->name('logs.view');
});