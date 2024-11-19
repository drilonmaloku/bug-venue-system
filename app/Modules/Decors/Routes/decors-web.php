<?php

declare(strict_types=1);

use App\Modules\Decors\Controllers\DecorController;
use Illuminate\Support\Facades\Route;


// Decors
Route::group(['middleware' => 'auth'], function () {
    Route::get('/decors', [DecorController::class, 'index'])->name('decors.index');
    Route::post('/decors', [DecorController::class, 'store'])->name('decors.store');
    Route::get('/decors/create', [DecorController::class, 'create'])->name('decors.create');
    Route::get('/decors/{id}/edit', [DecorController::class, 'edit'])->name('decors.edit');
    Route::get('/decors/{id}', [DecorController::class, 'view'])->name('decors.view');
    Route::put('/decors/{id}/update', [DecorController::class, 'update'])->name('decors.update');
    Route::delete('/decors/{id}', [DecorController::class, 'delete'])->name('decors.destroy');

});
