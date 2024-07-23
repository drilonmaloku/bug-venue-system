<?php

declare(strict_types=1);

use App\Modules\Venues\Controllers\VenuesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/venues', [VenuesController::class, 'index'])->name('venues.index');
    Route::get('/venues/export', [VenuesController::class, 'export'])->name('venues.export');
    Route::get('/venues/{id}/edit', [VenuesController::class, 'edit'])->name('venues.edit');
    Route::get('/venues/create', [VenuesController::class, 'create'])->name('venues.create');
    Route::put('/venues/{id}/update', [VenuesController::class, 'update'])->name('venues.update');
    Route::post('/venues-store', [VenuesController::class, 'store'])->name('venues.store');
    Route::get('/venues/{id}', [VenuesController::class, 'view'])->name('venues.view');
    Route::delete('/venues/{id}', [VenuesController::class, 'delete'])->name('venues.destroy');
});