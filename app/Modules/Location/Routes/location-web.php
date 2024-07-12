<?php

declare(strict_types=1);

use App\Modules\Location\Controllers\LocationController;
use Illuminate\Support\Facades\Route;



// locations
Route::group(['middleware' => ['auth', 'role:system-admin']], function () {
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{id}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::get('/locations/{id}/deactive', [LocationController::class, 'deactive'])->name('locations.deactive');
    Route::put('/locations/{id}/update', [LocationController::class, 'update'])->name('locations.update');
    Route::get('/locations/{id}', [LocationController::class, 'view'])->name('locations.view');
    Route::delete('/locations/{id}', [LocationController::class, 'destroy'])->name('locations.destroy');
});